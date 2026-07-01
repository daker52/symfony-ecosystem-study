# Den 2 — dodatek: Compiler Pass

Doplňující téma k [den-02.md](den-02.md).  
**Compiler pass** = kód, který běží při **kompilaci** service containeru a upraví jeho definice dřív, než se z nich složí finální aplikace.

---

## Problém, který řeší

V `services.yaml` ručně napíšu:

```yaml
App\Contract\GreetingGeneratorInterface: '@App\Service\FormalGreetingGenerator'
```

To funguje pro **jednu** implementaci interface.

Ale co když mám **víc implementací** stejného interface a chci je všechny najednou použít v jedné službě (registry, strategy collection, plugin systém)?

Ručně do YAML:

```yaml
App\Service\GreetingRegistry:
    arguments:
        $generators:
            - '@App\Service\FormalGreetingGenerator'
            - '@App\Service\CasualGreetingGenerator'
            # ... a přidáváš pořád další?
```

Špatně škáluje. Přidáš novou třídu → musíš měnit YAML.

**Compiler pass** řekne: „Najdi všechny služby s tagem `app.greeting_generator` a automaticky je vlož do `GreetingRegistry`.“

---

## Kdy se container „skládá“

```
services.yaml + config/packages/*.yaml
        ↓
   načtení definic (ContainerBuilder)
        ↓
   compiler passes (úpravy definic)   ← TADY
        ↓
   zkompilovaný container (cache v var/cache/)
        ↓
   runtime — requesty, inject do controllerů
```

- **Kompilace** = při deployi, `cache:clear`, první request po změně configu (v dev).
- **Runtime** = běžná práce aplikace. Compiler pass už neběží.

Proto compiler pass **nesmí** dělat věci závislé na requestu nebo DB — pracuje jen s definicemi služeb.

---

## Co je Compiler Pass

Třída implementující `CompilerPassInterface`:

```php
public function process(ContainerBuilder $container): void
{
    // čtení / úprava definic služeb
}
```

Symfony projde všechny passy v pořadí a každý může:
- najít služby podle **tagu** (`findTaggedServiceIds`)
- změnit **argumenty** (`setArgument`)
- přidat **method calls**, **decoration**, aliasy…

---

## Příklad z mého `demo/`

### 1. Tag na službách (`services.yaml`)

```yaml
App\Service\FormalGreetingGenerator:
    tags: ['app.greeting_generator']

App\Service\CasualGreetingGenerator:
    tags: ['app.greeting_generator']
```

Tag = štítek „tato služba patří do skupiny X“. Sám o sobě nic nedělá — čeká na pass nebo jiný kód, který tag zpracuje.

### 2. Cílová služba (`GreetingRegistry`)

```php
public function __construct(private iterable $generators) {}
```

Na začátku nemá vyplněné `$generators` — doplní compiler pass.

### 3. Compiler pass

```php
foreach ($container->findTaggedServiceIds('app.greeting_generator') as $id => $tags) {
    $references[] = new Reference($id);
}

$container->getDefinition(GreetingRegistry::class)
    ->setArgument('$generators', $references);
```

= „Všechny otagované služby dej jako argument do registry.“

### 4. Registrace passu (`src/Kernel.php`)

```php
protected function build(ContainerBuilder $container): void
{
    $container->addCompilerPass(new GreetingGeneratorPass());
}
```

Alternativa: v `Bundle::build()` vlastního bundle — u vlastní knihovny / pluginu.

---

## Proč to dělám / k čemu je to dobré

| Důvod | Vysvětlení |
|-------|------------|
| **Automatická registrace** | Nová implementace + tag → pass ji sám přidá, YAML neměním |
| **Plugin architektura** | Bundly přidají služby s tagem, jádro je posbírá |
| **Oddělení konfigurace** | YAML říká „co existuje“, pass říká „jak to složit dohromady“ |
| **Stejný pattern jako Symfony** | Routing, security voters, form types — pod kapotou tagy + passes |

---

## Compiler pass vs. ruční YAML

| | Ruční YAML | Compiler pass |
|---|-----------|---------------|
| Pár služeb | OK | zbytečný overkill |
| Desítky pluginů | nepřehledné | ideální |
| Dynamika při buildi | ne | ano |
| Čitelnost pro začátečníka | vyšší | nižší |

**Pravidlo:** dokud stačí `bind` / jeden argument v YAML, nepřidávám pass. Pass až když seznam služeb roste nebo přibývá z různých bundlů.

---

## Tagy vs. interface binding

- **Interface binding** — „když někdo chce `GreetingGeneratorInterface`, dej mu FormalGreetingGenerator“ (jedna implementace).
- **Tag + pass** — „všechny služby s tímto tagem použij tady“ (víc implementací najednou).

V demo používám obojí zároveň — ukazuje rozdíl.

---

## Kde to Symfony používá (reálně)

- **Event subscribers** — `kernel.event_subscriber` tag
- **Console commands** — `console.command`
- **Security voters** — `security.voter`
- **Twig extensions** — `twig.extension`

Autoconfigure v `_defaults` vlastně tagy přidává automaticky. Compiler passes je pak zpracují.

---

## Jak debugovat

```powershell
# zkompilovat container znovu
php bin/console cache:clear

# ověřit že container sedí
php bin/console lint:container

# co je uvnitř
php bin/console debug:container GreetingRegistry
php bin/console debug:container --tag=app.greeting_generator
```

Na `/services-demo` vidím oba generátory v seznamu — pass fungoval.

---

## Typické chyby

1. **Pass nezaregistrovaný** v `Kernel::build()` — nic se nestane.
2. **Zapomenutý tag** na službě — pass ji nenajde.
3. **Logika závislá na runtime** v passu — špatně, patří do služby / event listeneru.
4. **Priorita passů** — `addCompilerPass($pass, PassConfig::TYPE_BEFORE_OPTIMIZATION, 10)` když záleží na pořadí (pokročilé).

---

## Laravel analogie (hrubě)

| Symfony | Laravel |
|---------|---------|
| Compiler pass | `register()` v service provideru, kde po `tag()` sbíráš služby |
| Tag | `$this->app->tag(Impl::class, 'my.tag')` |
| `findTaggedServiceIds` | `foreach ($this->app->tagged('my.tag') as ...)` |

V Laravel 11+ podobná idea přes `ServiceProvider` a tagged bindings.

---

## Soubory v demo

| Soubor | Role |
|--------|------|
| `DependencyInjection/Compiler/GreetingGeneratorPass.php` | pass |
| `Service/GreetingRegistry.php` | cíl — sbírka generátorů |
| `Kernel.php` | registrace passu |
| `config/services.yaml` | tagy na generátorech |

---

## Shrnutí jednou větou

**Compiler pass = skript při buildu containeru, který podle pravidel (tagů, jmen, …) automaticky upraví, jak se služby propojí — abych nemusel ručně udržovat dlouhé seznamy v YAML.**

---

## Odkazy

- https://symfony.com/doc/current/service_container/compiler_passes.html
- https://symfony.com/doc/current/service_container/tags.html
- https://symfonycasts.com/screencast/symfony/services
