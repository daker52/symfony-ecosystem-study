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

## Plán — 14 dní (~3–4 h/den)

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
- [ ] Composition API (`ref`, `computed`)
- [ ] komponenty, props
- [ ] typy u API response

- https://vuejs.org/guide/introduction.html
- https://www.typescriptlang.org/docs/handbook/intro.html

**Den 9 — Vite / Webpack v Symfony**
- [ ] build pipeline `assets/` → `public/build/`
- [ ] rozdíl Vite vs Webpack Encore

- https://vite.dev/guide/
- https://symfony.com/doc/current/frontend.html

**Den 10 — RabbitMQ**
- [ ] co je broker, queue, exchange
- [ ] Message → Handler → transport
- [ ] analogie k Laravel Jobs

- https://www.rabbitmq.com/tutorials
- https://symfony.com/doc/current/messenger.html#amqp-transport

**Den 11 — JWT + Security**
- [ ] firewall, authenticator
- [ ] login → token → API request

- https://symfony.com/doc/current/security.html
- https://github.com/lexik/LexikJWTAuthenticationBundle

**Den 12 — PHPStan, Mago, CI**
- [ ] spustit PHPStan, opravit typy
- [ ] `mago init`, `mago analyze`
- [ ] základní CI pipeline

- https://phpstan.org/user-guide/getting-started
- https://mago.carthage.software/latest/en/

**Den 13 — SASS / LESS**
- [ ] proměnné, nesting, mixiny
- [ ] napojit do Vite

- https://sass-lang.com/documentation/

**Den 14 — demo**
- [ ] malá appka: API + Doctrine + JWT
- [ ] CQRS přes Messenger
- [ ] Vue frontend
- [ ] jeden console command
- [ ] PHPStan/Mago v CI

---

## Priorita (když nestíhám)

1. Symfony + DI + Doctrine
2. DDD/CQRS základy
3. Vue + TS + Vite
4. RabbitMQ / Messenger
5. PHPStan, Mago, CI
6. zbytek

---

## Struktura repa

```
demo/        — Symfony aplikace
docs/        — studijní deník (den 1, den 2, …)
private/     — jen lokálně, ne na git (.gitignore)
```

Studijní poznámky: [docs/README.md](docs/README.md)

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
| 8 | Vue + TS | | |
| 9 | Vite | | |
| 10 | RabbitMQ | | |
| 11 | JWT | | |
| 12 | PHPStan + Mago | | |
| 13 | SASS | | |
| 14 | demo | | |

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

**Nástroje**
- https://phpstan.org/
- https://mago.carthage.software/latest/en/
- https://www.rabbitmq.com/tutorials
