![symfony-ecosystem-study](docs/assets/github-social-preview.png)

# symfony-ecosystem-study

Osobní poznámky a cvičení — přechod z Laravelu na Symfony stack.  
Cíl: za ~14 dní pochopit, jak tenhle ekosystém funguje v praxi (ne memorovat docs).

Laravel a PHP znám, tady si mapuju, co je co jinde.

---

## Co mě zajímá / co potřebuju umět

| Oblast | Co to je |
|--------|----------|
| Symfony | framework místo Laravelu |
| Doctrine | ORM (Eloquent ekvivalent) |
| DDD | organizace kódu podle domény |
| CQRS | oddělení zápisu a čtení |
| Messenger + RabbitMQ | fronty / async (jako Laravel queues) |
| Vue + TS + Vite | frontend |
| JWT | API auth |
| PHPStan, Mago | statická analýza |
| DI, Console | service container, vlastní příkazy |

Hlavní zdroje:
- https://symfonycasts.com/
- https://symfony.com/doc/current/index.html
- https://refactoring.guru/design-patterns
- https://martinfowler.com/bliki/
- https://mago.carthage.software/latest/en/

Kompletní seznam co studuju: [docs/zdroje.md](docs/zdroje.md)

---

## Laravel → Symfony (pro mě)

| Laravel | Symfony |
|---------|---------|
| Eloquent | Doctrine |
| Artisan | Console (`bin/console`) |
| Service container | DI / `services.yaml` |
| `routes/web.php` | routes (attributes / yaml) |
| Blade | Twig |
| Middleware | event listeners |
| Queues | Messenger + RabbitMQ |
| Vite | Vite / Webpack Encore |
| Sanctum | JWT + Security |

---

## Plán — fáze 1: 14 dní (hotovo) · fáze 2: dny 15–21

Prvních 14 dní = pochopit stack a mít Pulse demo.  
Další týden = dorovnat slabá místa z checklistu (Rabbit napevno, Mago, LESS, testy…).

### Týden 1

**Den 1 — Symfony základy**
- [x] nainstalovat Symfony CLI, vytvořit webapp
- [x] porovnat strukturu s Laravel (`src/` vs `app/`)
- [x] routing, controller, Twig

Odkazy:
- https://symfonycasts.com/screencast/symfony
- https://symfony.com/doc/current/setup.html

**Den 2 — konfigurace, services**
- [x] services, autowiring, `.env`
- [x] bundles, config

- https://symfony.com/doc/current/configuration.html

**Den 3 — Doctrine ORM**
- [x] entity, repository, migrace
- [x] vztah OneToMany / ManyToOne
- [x] `persist()` + `flush()`

- https://symfonycasts.com/screencast/symfony/doctrine
- https://symfony.com/doc/current/doctrine.html

**Den 3 (původně DI)** — pokryto v [den 2](docs/den-02.md) + [compiler pass](docs/den-02-dodatek-compiler-pass.md)

**Den 4 — Console**
- [x] vlastní command `app:hello`
- [x] argumenty, options, DI v commandu
- [x] `app:study:list` — DB dotaz z CLI

- https://symfonycasts.com/screencast/symfony/console-command
- https://refactoring.guru/design-patterns/command

**Den 5 — design patterns**
- [x] Strategy, Factory, Decorator, Repository, Observer, Command
- [x] ukázka v demo — `/patterns-demo`

- https://refactoring.guru/design-patterns
- https://refactoring.guru/design-patterns/php

**Den 6 — DDD + CQRS**
- [x] Entity, Value Object, Aggregate (teorie)
- [x] Command vs Query
- [x] Messenger jako transport
- [x] `CreateStudyTopicCommand` + `GetStudyTopicQuery`

- https://martinfowler.com/bliki/CQRS.html
- https://symfonycasts.com/screencast/messenger
- https://symfony.com/doc/current/messenger.html

**Den 7 — Fixtures + async**
- [x] DoctrineFixturesBundle
- [x] `StudyDataFixtures` + `doctrine:fixtures:load`
- [x] async `StudyTopicCreatedMessage` + worker

- https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html
- https://symfonycasts.com/screencast/symfony-doctrine/persisting-fixtures

### Týden 2

**Den 8 — Vue + TypeScript**
- [x] Composition API (`ref`, `computed`)
- [x] komponenty, props
- [x] typy u API response (`StudyTopicDto`)

- https://vuejs.org/guide/introduction.html
- https://www.typescriptlang.org/docs/handbook/intro.html

**Den 9 — Vite / Webpack v Symfony**
- [x] build pipeline `assets/` → `public/build/`
- [x] Vite vedle AssetMapper + `vite_js()` helper

- https://vite.dev/guide/
- https://symfony.com/doc/current/frontend.html

**Den 10 — RabbitMQ**
- [x] broker, queue, exchange (teorie)
- [x] Message → Handler → transport (Doctrine ↔ AMQP DSN)
- [x] `docker-compose.rabbitmq.yml`

- https://www.rabbitmq.com/tutorials
- https://symfony.com/doc/current/messenger.html#amqp-transport

**Den 11 — JWT + Security**
- [x] firewall, json_login, jwt
- [x] login → token → `/api/me`

- https://symfony.com/doc/current/security.html
- https://github.com/lexik/LexikJWTAuthenticationBundle

**Den 12 — PHPStan, Mago, CI**
- [x] PHPStan level 5 (0 errors)
- [x] `mago.toml` připravený
- [x] GitHub Actions CI (PHPStan + Vite build)

- https://phpstan.org/user-guide/getting-started
- https://mago.carthage.software/latest/en/

**Den 13 — SASS / LESS**
- [x] proměnné, nesting, mixiny
- [x] napojeno do Vite (`study.scss`)

- https://sass-lang.com/documentation/

**Den 14 — demo**
- [x] Pulse: API + Doctrine + JWT
- [x] CQRS přes Messenger (create + async stages)
- [x] Vue frontend (live board)
- [x] console command `app:pulse:report`
- [x] PHPStan v CI (green)

### Týden 3 — dorovnání mezer (~2–3 h/den)

**Den 15 — RabbitMQ napevno**
- [ ] `docker compose -f docker-compose.rabbitmq.yml up -d`
- [ ] `symfony/amqp-messenger` + AMQP DSN
- [ ] Pulse přes Rabbit (stejné handlery, jiný transport)
- [ ] UI management: queues / messages

- https://www.rabbitmq.com/tutorials/tutorial-one-php
- https://symfony.com/doc/current/messenger.html#amqp-transport
- https://github.com/php-amqp/php-amqp

**Den 16 — Mago + CI**
- [ ] nainstalovat Mago, `mago analyze` na `demo/src`
- [ ] opravit / zapsat nálezy
- [ ] přidat Mago job do GitHub Actions (vedle PHPStan)

- https://mago.carthage.software/latest/en/
- https://mago.carthage.software/latest/en/getting-started/installation.html
- https://mago.carthage.software/latest/en/tools/analyzer.html

**Den 17 — LESS (+ srovnání se SASS)**
- [ ] jeden `.less` (proměnné, nesting, mixiny)
- [ ] napojit do Vite vedle SASS
- [ ] zapsat rozdíly LESS vs SASS (pro pohovor)

- https://lesscss.org/
- https://lesscss.org/features/
- https://sass-lang.com/documentation/syntax

**Den 18 — JWT RS256**
- [ ] PEM klíče (private/public)
- [ ] přepnout Lexik z HS256 na RS256
- [ ] ověřit `/api/login` + `/api/me` + Pulse

- https://github.com/lexik/LexikJWTAuthenticationBundle/blob/3.x/Resources/doc/index.md
- https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html
- https://jwt.io/introduction

**Den 19 — PHPUnit testy (Pulse / JWT)**
- [ ] 2–3 testy: login → token, create order (sync část), list orders
- [ ] `phpunit` lokálně + job v CI

- https://symfony.com/doc/current/testing.html
- https://symfony.com/doc/current/testing.html#functional-tests
- https://docs.phpunit.de/en/11.5/

**Den 20 — Forms + Validation**
- [ ] Symfony Form + constraints
- [ ] napojit na jednu stránku / API validaci (Work Order nebo topic)

- https://symfony.com/doc/current/forms.html
- https://symfony.com/doc/current/validation.html
- https://symfonycasts.com/screencast/symfony-forms

**Den 21 — Foundry + (volitelně) Encore přehled**
- [ ] Zenstruck Foundry místo/vedle klasických fixtures
- [ ] krátká poznámka Vite vs Webpack Encore (bez nutnosti plného Encore setupu)

- https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html
- https://github.com/zenstruck/foundry
- https://symfony.com/doc/current/frontend/encore/installation.html
- https://symfony.com/doc/current/frontend.html#webpack-encore

Detailnější rozpis + checklist: [docs/plan-tyden-3.md](docs/plan-tyden-3.md)

---

## Priorita (když nestíhám)

**Fáze 1 (hotovo):** Symfony + DI + Doctrine → DDD/CQRS → Vue/Vite → Messenger → PHPStan/CI  

**Fáze 2 (teď):**
1. RabbitMQ reálně
2. Mago v CI
3. LESS + JWT RS256
4. PHPUnit
5. Forms / Foundry

---

## Struktura repa

```
demo/        — Symfony aplikace (+ vlastní README jak to spustit)
docs/        — studijní deník (den 1 …) + plán týden 3
private/     — jen lokálně, ne na git (.gitignore)
```

Demo app — jak spustit / co ukazuje: [demo/README.md](demo/README.md)  
Studijní poznámky: [docs/README.md](docs/README.md)  
Plán týden 3: [docs/plan-tyden-3.md](docs/plan-tyden-3.md)

---

## Progress

| den | téma | hotovo | poznámka |
|-----|------|--------|----------|
| 1 | Symfony základy | x | [den-01](docs/den-01.md) |
| 2 | konfigurace, DI | x | [den-02](docs/den-02.md) |
| 3 | Doctrine ORM | x | [den-03](docs/den-03.md) |
| 4 | Console | x | [den-04](docs/den-04.md) |
| 5 | design patterns | x | [den-05](docs/den-05.md) |
| 6 | DDD + CQRS | x | [den-06](docs/den-06.md) |
| 7 | Fixtures + async | x | [den-07](docs/den-07.md) |
| 8 | Vue + TS | x | [den-08](docs/den-08.md) |
| 9 | Vite | x | [den-09](docs/den-09.md) |
| 10 | RabbitMQ (teorie + compose) | x | [den-10](docs/den-10.md) |
| 11 | JWT | x | [den-11](docs/den-11.md) |
| 12 | PHPStan + Mago (config) | x | [den-12](docs/den-12.md) |
| 13 | SASS | x | [den-13](docs/den-13.md) |
| 14 | Pulse demo | x | [den-14](docs/den-14.md) |
| 15 | RabbitMQ napevno | | |
| 16 | Mago + CI | | |
| 17 | LESS | | |
| 18 | JWT RS256 | | |
| 19 | PHPUnit | | |
| 20 | Forms + Validation | | |
| 21 | Foundry (+ Encore přehled) | | |

---

## Užitečné odkazy (sbírka)

**Symfony**
- https://symfonycasts.com/
- https://symfony.com/doc/current/index.html
- https://symfony.com/doc/current/best_practices.html

**Architektura**
- https://refactoring.guru/design-patterns
- https://martinfowler.com/bliki/CQRS.html

**Frontend**
- https://vuejs.org/
- https://vite.dev/
- https://lesscss.org/
- https://sass-lang.com/documentation/

**Nástroje**
- https://phpstan.org/
- https://mago.carthage.software/latest/en/
- https://www.rabbitmq.com/tutorials
- https://docs.phpunit.de/
- https://github.com/zenstruck/foundry

Kompletní seznam po dnech: [docs/zdroje.md](docs/zdroje.md)
