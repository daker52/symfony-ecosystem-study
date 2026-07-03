# Den 4 — Symfony Console

**Datum:** 4. 7. 2026  
**Status:** hotovo

`bin/console` = Symfony ekvivalent `php artisan`. Dnes vlastní příkazy s argumenty, options a DI.

---

## Základy

```powershell
php bin/console list
php bin/console list app
php bin/console app:hello --help
```

Command = třída v `src/Command/`, atribut `#[AsCommand(name: '...')]`.

Autoconfigure + `_defaults` v `services.yaml` → command se registruje sám (jako u controllerů).

---

## Co jsem přidal

| Příkaz | Co dělá |
|--------|---------|
| `app:hello [name]` | pozdrav přes DI (`GreetingGeneratorInterface`) |
| `app:hello --shout` | option — velká písmena |
| `app:study:list` | výpis topics z DB |
| `app:study:list --day=3` | filtr |
| `app:study:list --json` | JSON výstup |
| `app:study:seed` | seed DB (už z den 3) |

---

## Argumenty vs options

```php
->addArgument('name', InputArgument::OPTIONAL, '...', 'studující')
->addOption('shout', null, InputOption::VALUE_NONE)
->addOption('day', 'd', InputOption::VALUE_REQUIRED)
```

| | Argument | Option |
|---|----------|--------|
| Příklad | `app:hello daker52` | `--shout`, `--day=3` |
| Laravel | argumenty v signature | `{--shout}` flags |

---

## DI v commandu

```php
public function __construct(
    private GreetingGeneratorInterface $greetingGenerator,
) {
    parent::__construct();  // důležité!
}
```

Stejný princip jako u controlleru — služby z containeru.

---

## SymfonyStyle

`SymfonyStyle` (`$io`) = hezčí výstup v terminálu:

```php
$io->success('...');
$io->warning('...');
$io->table(['Den', 'Slug'], $rows);
$io->error('...');
```

---

## Laravel vs Symfony

| Laravel | Symfony |
|---------|---------|
| `php artisan` | `php bin/console` |
| `make:command` | `make:command` (MakerBundle) nebo ručně |
| `$this->info()` | `$io->writeln()` / `$io->success()` |
| `$this->argument('name')` | `$input->getArgument('name')` |

---

## Zdroje co jsem studoval

- [SymfonyCasts — Console Commands](https://symfonycasts.com/screencast/symfony/console-command)
- [Symfony Docs — Console](https://symfony.com/doc/current/console.html)
- [Refactoring Guru — Command pattern](https://refactoring.guru/design-patterns/command)

→ víc v [zdroje.md](zdroje.md)

---

## Zítra (den 5)

Design patterns v praxi — Strategy, Factory, Decorator, Observer, Repository.

---

## Poznámky pro sebe

- V commandu vždy `parent::__construct()` pokud mám vlastní konstruktor.
- `Command::SUCCESS` / `Command::FAILURE` jako exit code.
