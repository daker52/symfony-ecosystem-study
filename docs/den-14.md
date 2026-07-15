# Den 14 — Pulse (finální demo)

**Datum:** 15. 7. 2026  
**Status:** hotovo

Jedna obrazovka, která spojuje celý stack: **JWT + CQRS + Messenger pipeline + Doctrine + Vue/TS + Console + PHPStan**.

---

## Co to je

**Pulse** — live async work-order board.

1. Login JWT (`study@example.com` / `study123`)
2. `POST /api/pulse/orders` → `CreateWorkOrderCommand` (sync, vrátí ID)
3. Handler hodí `AdvanceWorkOrderMessage` do async fronty
4. Worker bere stage po stage: `validate → process → notify → complete`
5. Vue polluje seznam + timeline

URL: `/pulse`

---

## Stack mapping

| Požadavek | Pulse |
|-----------|-------|
| CQRS | `CreateWorkOrderCommand` / `ListWorkOrdersQuery` |
| Messenger | `AdvanceWorkOrderMessage` → `async` |
| JWT | `/api/pulse/*` vyžaduje Bearer |
| Doctrine | `WorkOrder` + `WorkOrderEvent` |
| Vue + TS | `PulseApp.vue`, typed DTO |
| Console | `app:pulse:report` |
| CI / PHPStan | level 5 green |

---

## Jak spustit

```powershell
cd demo
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction

# terminál 1
php bin/console messenger:consume async -vv

# terminál 2 — web
symfony server:start
# nebo: php -S 127.0.0.1:8000 -t public
```

Pak otevři `/pulse`, login, vytvoř work order — timeline se plní, když běží worker.

```powershell
php bin/console app:pulse:report
```

---

## Soubory

- `src/Entity/WorkOrder.php`, `WorkOrderEvent.php`
- `src/Message/Command/CreateWorkOrderCommand.php`
- `src/Message/Event/AdvanceWorkOrderMessage.php`
- `src/Controller/PulseController.php`
- `assets/vue/PulseApp.vue`
- `src/Command/PulseReportCommand.php`

---

## Wow věta pro zadavatele

> Postavil jsem Pulse — malý ops dashboard: JWT autentizace, CQRS create/list, Messenger stage pipeline (validate→process→notify→done) a Vue board, který to live sleduje. Console report ukáže stav fronty. Je to stejný pattern jako v produkčním Symfony stacku, jen zmenšený.

---

## Zdroje

- [Messenger](https://symfony.com/doc/current/messenger.html)
- [Security / JWT](https://symfony.com/doc/current/security.html)
- předchozí dny 6–13 v tomhle deníku
