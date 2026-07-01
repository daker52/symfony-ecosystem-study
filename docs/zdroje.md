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

## Plánované (den 4+)

| Téma | Zdroj |
|------|-------|
| Console | [SymfonyCasts — Console](https://symfonycasts.com/screencast/symfony/console-command) |
| Messenger / CQRS | [SymfonyCasts — Messenger](https://symfonycasts.com/screencast/messenger) |
| DDD | [Martin Fowler — DDD Aggregate](https://martinfowler.com/bliki/DDD_Aggregate.html) |
| Mago | [mago.carthage.software](https://mago.carthage.software/latest/en/) |
| Vue + TS | [vuejs.org guide](https://vuejs.org/guide/introduction.html) |

---

*Aktualizuju podle toho, co zrovna řeším v `demo/`.*
