# Den 6 — DDD + CQRS + Messenger

**Datum:** 5. 7. 2026  
**Status:** hotovo

Oddělení zápisu a čtení přes Symfony Messenger. V Laravelu bych to řešil Jobs + separátní query služby — tady je to first-class.

---

## DDD v mém demo

| Koncept | Kde | Poznámka |
|---------|-----|----------|
| **Entity** | `StudyTopic`, `StudyDay` | Doctrine entity = persistence model |
| **Value Object** | `TopicSlug` | validace v konstrukci, immutable |
| **Aggregate** | `StudyDay` + topics | zatím jednoduché — den drží topics |

`TopicSlug::fromString()` hází výjimku na neplatný slug — pravidla domény nejsou v controlleru.

---

## CQRS

| Typ | Třída | Handler | Bus |
|-----|-------|---------|-----|
| Command | `CreateStudyTopicCommand` | `CreateStudyTopicHandler` | `command.bus` |
| Query | `GetStudyTopicQuery` | `GetStudyTopicHandler` | `query.bus` |

Controller nevolá repository přímo — posílá message na bus:

```php
$this->commandBus->dispatch(new CreateStudyTopicCommand(...));
$this->queryBus->dispatch(new GetStudyTopicQuery($slug));
```

Výsledek query přes `HandledStamp`:

```php
$handled = $envelope->last(HandledStamp::class);
$result = $handled?->getResult();
```

---

## Messenger

Konfigurace: `config/packages/messenger.yaml`

- `command.bus` — default bus pro zápis
- `query.bus` — sync čtení
- `CreateStudyTopicCommand` → transport `async` (Doctrine DB fronta)
- Worker: `php bin/console messenger:consume async -vv`

API POST `/cqrs-demo/api/topics` vrací **202 Accepted** — command je ve frontě, dokud neběží worker.

Laravel analogie:

| Laravel | Symfony |
|---------|---------|
| `dispatch(new Job())` | `$bus->dispatch(new Message())` |
| `php artisan queue:work` | `messenger:consume async` |
| Redis/DB queue | Doctrine transport (zatím, RabbitMQ den 10) |

---

## Stránka v demo

`/cqrs-demo` — formulář pro create + query, odkazy na worker.

---

## Aha moment

Dva `MessageBusInterface` v konstruktoru — Symfony neví který je který. Musím explicitně bindnout v `services.yaml`:

```yaml
bind:
    MessageBusInterface $commandBus: '@command.bus'
    MessageBusInterface $queryBus: '@query.bus'
```

V Laravelu by stačilo type-hint `CommandBus` / `QueryBus` interface — princip stejný.

---

## Zdroje

- [CQRS — Martin Fowler](https://martinfowler.com/bliki/CQRS.html)
- [Symfony Messenger](https://symfony.com/doc/current/messenger.html)
- [SymfonyCasts — Messenger](https://symfonycasts.com/screencast/messenger)

Další: [den-07.md](den-07.md) — Fixtures + async side-effects
