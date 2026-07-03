# Den 5 — Design patterns v Symfony

**Datum:** 4. 7. 2026  
**Status:** hotovo

Ne memorovat — najít kde se patterns v projektu reálně používají.

---

## Přehled v mém demo

| Pattern | Kde | Laravel analogie |
|---------|-----|------------------|
| **Strategy** | `FormalGreetingGenerator` / `CasualGreetingGenerator` | různé strategie za interface |
| **Factory** | `GreetingGeneratorFactory::create()` | Factory / `app()->make()` |
| **Decorator** | `LoggingGreetingGeneratorDecorator` | middleware, wrapper |
| **Repository** | `StudyTopicRepository` | Eloquent + query scopes |
| **Observer** | `StudyTopicSubscriber` (postPersist) | Model events |
| **Command** | `app:hello`, `app:greet` | Artisan |

Stránka: `/patterns-demo`

---

## Strategy

Více implementací stejného interface — výměna chování bez změny volajícího kódu.

```php
interface GreetingGeneratorInterface { public function greet(string $name): string; }
// FormalGreetingGenerator vs CasualGreetingGenerator
```

Už jsem měl z den 2 — dnes propojeno s Factory.

---

## Factory

`GreetingGeneratorFactory` vytvoří správnou strategii:

```php
$generator = $this->factory->create('casual', withLogging: true);
```

Volání z `app:greet --style=casual`.

---

## Decorator

`LoggingGreetingGeneratorDecorator` obalí jakýkoliv generator a přidá logování — bez úpravy původní třídy.

```php
return new LoggingGreetingGeneratorDecorator($inner, $this->logger);
```

Log jde do `var/log/dev.log`.

---

## Repository

Doctrine repository = oddělení dotazů od entity. Už z den 3.

```php
$this->topicRepository->findBySlug($slug);
$this->topicRepository->findLatest(20);
```

Refactoring Guru: [Repository pattern](https://refactoring.guru/design-patterns/repository)

---

## Observer

`StudyTopicSubscriber` poslouchá Doctrine `postPersist` — když se uloží nový `StudyTopic`, zapíše log.

Laravel: `Model::creating()`, `created` events.

---

## Command pattern

Symfony Console commands **jsou** Command pattern v praxi — zapouzdřená akce s `execute()`.

---

## Příkazy k vyzkoušení

```powershell
php bin/console app:greet daker52 --style=formal
php bin/console app:greet daker52 --style=casual
php bin/console app:greet daker52 --no-log
```

---

## Zdroje co jsem studoval

- [Refactoring Guru — katalog](https://refactoring.guru/design-patterns)
- [Refactoring Guru — PHP](https://refactoring.guru/design-patterns/php)
- [Strategy](https://refactoring.guru/design-patterns/strategy)
- [Factory Method](https://refactoring.guru/design-patterns/factory-method)
- [Decorator](https://refactoring.guru/design-patterns/decorator)
- [Observer](https://refactoring.guru/design-patterns/observer)

→ víc v [zdroje.md](zdroje.md)

---

## Zítra (den 6–7)

DDD + CQRS + Messenger.

---

## Poznámky pro sebe

- Patterns nejsou magie — většinu už používám, jen jsem je nepojmenoval.
- Decorator ≠ DI — decorator mění chování objektu, DI ho dodá.
