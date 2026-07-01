# Den 2 — Services, DI, konfigurace

**Datum:** 30. 6. 2026  
**Status:** hotovo

Dneska DI — jak Symfony „vstrčí“ služby do tříd. V Laravelu jsem na to zvyklý přes service container a `AppServiceProvider`, tady je to víc v YAML.

---

## Co jsem pochopil

**Service container** = centrální místo, kde Symfony drží objekty a ví, jak je složit dohromady.

**Autowiring** = Symfony se podívá na typ v konstruktoru a sám najde správnou službu.

```php
public function __construct(
    private GreetingGeneratorInterface $greetingGenerator,
    private StudyInfoProvider $studyInfo,
) {}
```

Nemusím psát `new FormalGreetingGenerator()` — container to udělá za mě.

---

## `config/services.yaml`

Základ:

```yaml
_defaults:
    autowire: true
    autoconfigure: true

App\:
    resource: '../src/'
```

= všechny třídy v `src/` (kromě exclude) jsou automaticky služby.

**Parametry** (jako config v Laravelu):

```yaml
parameters:
    app.study.title: 'symfony-ecosystem-study'
    app.study.day: 2
```

Použití: `%app.study.day%` nebo `%kernel.environment%`

**Interface → implementace** (jako `bind()` v AppServiceProvider):

```yaml
App\Contract\GreetingGeneratorInterface: '@App\Service\FormalGreetingGenerator'
```

Když někdo chce interface, dostane `FormalGreetingGenerator`.

**Explicitní argumenty:**

```yaml
App\Service\StudyInfoProvider:
    arguments:
        $studyTitle: '%app.study.title%'
        $studyDay: '%app.study.day%'
        $appEnv: '%kernel.environment%'
```

---

## Co jsem přidal do `demo/`

| Soubor | Účel |
|--------|------|
| `Contract/GreetingGeneratorInterface` | interface |
| `Service/FormalGreetingGenerator` | implementace (default) |
| `Service/CasualGreetingGenerator` | alternativa (zatím nepoužitá) |
| `Service/StudyInfoProvider` | čte parametry z configu |
| `Controller/ServiceDemoController` | DI v praxi |

**Routy:**
- `/services-demo` — HTML ukázka
- `/api/greeting/{name}` — JSON s greetingem

---

## Laravel vs Symfony

| Laravel | Symfony |
|---------|---------|
| `AppServiceProvider::register()` | `config/services.yaml` |
| `$this->app->bind(Interface::class, Impl::class)` | `Interface: '@Impl'` |
| `config('app.name')` | `%parameter%` v YAML |
| `app(SomeClass::class)` | autowiring v konstruktoru |

---

## Debug příkazy

```powershell
php bin/console debug:container
php bin/console debug:container GreetingGeneratorInterface
php bin/console debug:autowiring
php bin/console lint:container
```

`debug:container` je gold — vidím všechny služby a co se kam injectuje.

---

## `.env` a `config/packages/`

- `.env` — lokální hodnoty (`APP_ENV=dev`)
- `config/packages/*.yaml` — konfigurace jednotlivých bundlů (Doctrine, Twig, …)
- `bundles.php` — které bundly jsou zapnuté (jako service providers)

`%kernel.environment%` bere hodnotu z `APP_ENV`.

---

## Aha momenty

1. Controller **může** mít konstruktor s DI — Symfony ho zavolá samo.
2. Interface binding je čistší než záviset na konkrétní třídě.
3. YAML vypadá zpočátku suchě, ale je to přehledné — vidím celou mapu služeb na jednom místě.

---

## Zdroje co jsem studoval

**SymfonyCasts:**
- [Service Container](https://symfonycasts.com/screencast/symfony/services)
- [Creating Services](https://symfonycasts.com/screencast/symfony/creating-services)
- [Dependency Injection](https://symfonycasts.com/screencast/symfony/dependency-injection)

**Docs:**
- [Service Container](https://symfony.com/doc/current/service_container.html)
- [Autowiring](https://symfony.com/doc/current/service_container/autowiring.html)
- [Configuration](https://symfony.com/doc/current/configuration.html)

**Teorie (proč DI existuje):**
- [Martin Fowler — Dependency Injection](https://martinfowler.com/articles/injection.html)
- [Martin Fowler — Inversion of Control](https://martinfowler.com/bliki/InversionOfControl.html)

**Srovnání s Laravelem:**
- [Laravel Service Container docs](https://laravel.com/docs/11.x/container) — mapuju `bind()` na YAML binding

→ compiler pass: [dodatek](den-02-dodatek-compiler-pass.md) · víc v [zdroje.md](zdroje.md)

---

## Zítra (den 3)

V plánu mám ještě víc DI (tagy) — ale základy mám. Den 3 můžu buď prohloubit DI, nebo skočit na Doctrine podle tempa.

---

## Poznámky pro sebe

- `CasualGreetingGenerator` existuje schválně — ukázka že můžu přepnout binding na jinou implementaci bez změny controlleru.
- Když `lint:container` projde, container je OK.

**Dodatek:** [Compiler Pass](den-02-dodatek-compiler-pass.md) — tagy, `GreetingGeneratorPass`, `GreetingRegistry`
