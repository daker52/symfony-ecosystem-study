# Den 7 — Doctrine Fixtures + async zpracování

**Datum:** 5. 7. 2026  
**Status:** hotovo

Doplnil jsem to, na co se mě ptal zadavatel — fixtures. A propojil async Messenger s reálným side-effectem.

---

## Doctrine Fixtures

Předtím jsem seedoval přes vlastní `app:study:seed`. Fixtures jsou standardní Symfony cesta.

**Instalace:**
```powershell
composer require --dev doctrine/doctrine-fixtures-bundle
```

**Třída:** `src/DataFixtures/StudyDataFixtures.php`

**Spuštění:**
```powershell
php bin/console doctrine:fixtures:load --no-interaction
```

⚠️ Smaže existující data a naplní znovu (jako `migrate:fresh --seed` v Laravelu).

| | `app:study:seed` | `doctrine:fixtures:load` |
|---|------------------|--------------------------|
| Kdy | quick hack, den 3 | standardní Symfony |
| Skupiny | ne | ano (`--group`) |
| Závislosti | ručně | `DependentFixtureInterface` |
| Dev vs prod | command | typicky jen `--dev` bundle |

**Zenstruck Foundry** — chci zkusit později jako nadstavbu (factory + fake data). Zatím klasické fixtures stačí.

---

## Async side-effect

Po vytvoření topicu handler pošle další message:

```
CreateStudyTopicHandler
  → persist + flush
  → dispatch(StudyTopicCreatedMessage)  // async
       → StudyTopicCreatedHandler       // log „notifikace“
```

Routing v `messenger.yaml`:
```yaml
App\Message\Event\StudyTopicCreatedMessage: async
```

V logu (`var/log/dev.log`) vidím:
```
Async: study topic vytvořen { slug, title, day }
```

Laravel analogie: po `UserCreated` eventu dispatchnu `SendWelcomeEmail` job do fronty.

---

## Workflow pro test

```powershell
# 1. DB + data
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction

# 2. Worker (druhý terminál)
php bin/console messenger:consume async -vv

# 3. Vytvoř topic přes /cqrs-demo nebo API
# 4. Ve worker terminálu uvidím zpracování commandu + StudyTopicCreatedMessage
```

---

## Co je v fixtures

Dny 1–7 + topics pro routing, twig, DI, doctrine, console, patterns, CQRS, fixtures samotné.

---

## Zdroje

- [DoctrineFixturesBundle](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html)
- [SymfonyCasts — Persisting Fixtures](https://symfonycasts.com/screencast/symfony-doctrine/persisting-fixtures)
- [Messenger — consuming messages](https://symfony.com/doc/current/messenger.html#consuming-messages-running-the-worker)
