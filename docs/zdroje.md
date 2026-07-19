# Zdroje — co studuju

Seznam materiálů, které používám k učení. Není to exhaustivní — spíš co mi reálně pomohlo po dnech.

---

## Průběžně (celý stack)

| Zdroj | Proč |
|-------|------|
| [SymfonyCasts](https://symfonycasts.com/) | hlavní video průvodce, krok za krokem |
| [Symfony Documentation](https://symfony.com/doc/current/index.html) | referenční docs, když potřebuju přesné chování |
| [Symfony Best Practices](https://symfony.com/doc/current/best_practices.html) | struktura projektu, konvence |
| [Refactoring Guru — Design Patterns](https://refactoring.guru/design-patterns) | patterns v kontextu architektury |
| [Martin Fowler — bliki](https://martinfowler.com/bliki/) | DDD, CQRS, repository (teorie) |

---

## Den 1 — routing, controller, Twig

**SymfonyCasts (prošel jsem):**
- [Installing Symfony](https://symfonycasts.com/screencast/symfony/install)
- [Routing](https://symfonycasts.com/screencast/symfony/routing)
- [Controller](https://symfonycasts.com/screencast/symfony/controller)
- [Twig](https://symfonycasts.com/screencast/symfony/twig)

**Docs:**
- [The Architecture](https://symfony.com/doc/current/quick_tour/the_architecture.html) — request flow, Kernel
- [Routing](https://symfony.com/doc/current/routing.html) — attributes vs YAML
- [Creating Pages](https://symfony.com/doc/current/page_creation.html)
- [Twig templates](https://symfony.com/doc/current/templates.html)

**Články / srovnání:**
- [Symfony Blog — best practices for routes](https://symfony.com/blog/new-in-symfony-6-2-ux-improvements) *(prošel jsem i starší routing články na blogu)*
- [Laravel vs Symfony — community srovnání](https://kinsta.com/blog/laravel-vs-symfony/) — hrubý přehled rozdílů filozofie (ne jako bible, spíš orientace)

**Poznámka:** SymfonyCasts epizoda o routingu mi konečně sjednotila attributes s tím, co jsem znal z Laravel route listu.

---

## Den 2 — DI, services, compiler pass

**SymfonyCasts:**
- [Service Container](https://symfonycasts.com/screencast/symfony/services)
- [Creating Services](https://symfonycasts.com/screencast/symfony/creating-services)
- [DI in Symfony](https://symfonycasts.com/screencast/symfony/dependency-injection)

**Docs:**
- [Service Container](https://symfony.com/doc/current/service_container.html)
- [Autowiring](https://symfony.com/doc/current/service_container/autowiring.html)
- [Tags](https://symfony.com/doc/current/service_container/tags.html)
- [Compiler Passes](https://symfony.com/doc/current/service_container/compiler_passes.html)
- [Configuration](https://symfony.com/doc/current/configuration.html)

**Teorie DI:**
- [Martin Fowler — Inversion of Control](https://martinfowler.com/bliki/InversionOfControl.html)
- [Martin Fowler — Dependency Injection](https://martinfowler.com/articles/injection.html) — klasika, vysvětluje *proč* ne jen *jak*
- [Refactoring Guru — Dependency Injection](https://refactoring.guru/design-patterns/dependency-injection)

**Laravel srovnání (znám z praxe, občas refresh):**
- [Laravel — Service Container](https://laravel.com/docs/11.x/container) — bind, singleton, tagged — mapuju na Symfony YAML

**Články:**
- [Symfony Blog — autowiring](https://symfony.com/blog/new-in-symfony-3-3-dependency-injection-improvements) — historie, ale pořád dobré vysvětlení autowiringu

---

## Den 3 — Doctrine ORM

**SymfonyCasts:**
- [Doctrine](https://symfonycasts.com/screencast/symfony/doctrine) — celá série
- [Doctrine Relations](https://symfonycasts.com/screencast/symfony/doctrine-relations)
- [Migrations](https://symfonycasts.com/screencast/symfony/migrations)

**Symfony Docs:**
- [Databases and Doctrine](https://symfony.com/doc/current/doctrine.html)
- [Relationships](https://symfony.com/doc/current/doctrine/associations.html)
- [Migrations](https://symfony.com/doc/current/doctrine/migrations.html)
- [Repository](https://symfony.com/doc/current/doctrine/repository.html)

**Doctrine (oficiální):**
- [Doctrine ORM — Introduction](https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/introduction.html)
- [Association Mapping](https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/association-mapping.html)
- [Basic Mapping](https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/basic-mapping.html)

**Patterns:**
- [Refactoring Guru — Repository](https://refactoring.guru/design-patterns/repository)
- [Martin Fowler — Repository (EAA)](https://martinfowler.com/eaaCatalog/repository.html)

**Srovnání s Eloquentem:**
- [Doctrine vs Eloquent — discussion / přehledy](https://stackoverflow.com/questions/tagged/doctrine+eloquent) — četl jsem vybrané odpovědi k persist/flush vs save
- [Symfony Blog — Doctrine](https://symfony.com/blog/category/doctrine) — občasné tipy k best practices

---

## Den 4 — Console

**SymfonyCasts:**
- [Console Commands](https://symfonycasts.com/screencast/symfony/console-command)

**Docs:**
- [Console](https://symfony.com/doc/current/console.html)
- [Console Input](https://symfony.com/doc/current/console/input.html)

**Patterns:**
- [Command pattern](https://refactoring.guru/design-patterns/command)

---

## Den 5 — Design patterns

**Refactoring Guru:**
- [Design Patterns catalog](https://refactoring.guru/design-patterns)
- [Design Patterns in PHP](https://refactoring.guru/design-patterns/php)
- [Strategy](https://refactoring.guru/design-patterns/strategy)
- [Factory Method](https://refactoring.guru/design-patterns/factory-method)
- [Decorator](https://refactoring.guru/design-patterns/decorator)
- [Observer](https://refactoring.guru/design-patterns/observer)
- [Repository](https://refactoring.guru/design-patterns/repository)

**Symfony:**
- [Doctrine Events / Listeners](https://symfony.com/doc/current/doctrine/events.html)

---

## Den 6 — DDD + CQRS + Messenger

**SymfonyCasts:**
- [Messenger](https://symfonycasts.com/screencast/messenger)

**Docs:**
- [Messenger](https://symfony.com/doc/current/messenger.html)
- [Multiple buses](https://symfony.com/doc/current/messenger.html#multiple-buses)

**Teorie:**
- [CQRS — Martin Fowler](https://martinfowler.com/bliki/CQRS.html)
- [DDD Aggregate — Martin Fowler](https://martinfowler.com/bliki/DDD_Aggregate.html)
- [Value Object — Martin Fowler](https://martinfowler.com/bliki/ValueObject.html)

---

## Den 7 — Fixtures + async

**SymfonyCasts:**
- [Persisting Fixtures](https://symfonycasts.com/screencast/symfony-doctrine/persisting-fixtures)

**Docs:**
- [DoctrineFixturesBundle](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html)
- [Messenger — consuming](https://symfony.com/doc/current/messenger.html#consuming-messages-running-the-worker)

**Další (plánuju):**
- [Zenstruck Foundry](https://github.com/zenstruck/foundry) — factory nad fixtures

---

## Den 8 — Vue + TypeScript

- [Vue guide](https://vuejs.org/guide/introduction.html)
- [Composition API](https://vuejs.org/guide/extras/composition-api-faq.html)
- [TypeScript handbook](https://www.typescriptlang.org/docs/handbook/intro.html)

---

## Den 9 — Vite

- [Vite guide](https://vite.dev/guide/)
- [Symfony frontend](https://symfony.com/doc/current/frontend.html)

---

## Den 10 — RabbitMQ

- [RabbitMQ tutorials](https://www.rabbitmq.com/tutorials)
- [AMQP transport](https://symfony.com/doc/current/messenger.html#amqp-transport)

---

## Den 11 — JWT

- [Symfony Security](https://symfony.com/doc/current/security.html)
- [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle)

---

## Den 12 — PHPStan / Mago / CI

- [PHPStan](https://phpstan.org/user-guide/getting-started)
- [Mago](https://mago.carthage.software/latest/en/)

---

## Den 13 — SASS

- [Sass docs](https://sass-lang.com/documentation/)
- [Less](https://lesscss.org/)

---

## Den 14 — Pulse

- vlastní `demo/` + [den-14.md](den-14.md)
- [Messenger](https://symfony.com/doc/current/messenger.html)
- [Lexik JWT](https://github.com/lexik/LexikJWTAuthenticationBundle)

---

## Týden 3 — plánované zdroje (dny 15–21)

Detail: [plan-tyden-3.md](plan-tyden-3.md)

| Den | Téma | Zdroje |
|-----|------|--------|
| 15 | RabbitMQ napevno | [tutorials](https://www.rabbitmq.com/tutorials), [AMQP transport](https://symfony.com/doc/current/messenger.html#amqp-transport), [tutorial PHP](https://www.rabbitmq.com/tutorials/tutorial-one-php) |
| 16 | Mago + CI | [Mago docs](https://mago.carthage.software/latest/en/), [install](https://mago.carthage.software/latest/en/getting-started/installation.html), [analyzer](https://mago.carthage.software/latest/en/tools/analyzer.html) |
| 17 | LESS | [lesscss.org](https://lesscss.org/), [features](https://lesscss.org/features/), [Vite CSS](https://vite.dev/guide/features.html#css-pre-processors) |
| 18 | JWT RS256 | [Lexik bundle](https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html), [jwt.io](https://jwt.io/introduction) |
| 19 | PHPUnit | [Symfony testing](https://symfony.com/doc/current/testing.html), [PHPUnit](https://docs.phpunit.de/en/11.5/), [SymfonyCasts PHPUnit](https://symfonycasts.com/screencast/phpunit) |
| 20 | Forms + Validation | [Forms](https://symfony.com/doc/current/forms.html), [Validation](https://symfony.com/doc/current/validation.html), [SymfonyCasts Forms](https://symfonycasts.com/screencast/symfony-forms) |
| 21 | Foundry + Encore | [Foundry bundle](https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html), [Foundry GitHub](https://github.com/zenstruck/foundry), [Encore](https://symfony.com/doc/current/frontend/encore/installation.html) |

---

*Aktualizuju podle toho, co zrovna řeším v `demo/`.*
