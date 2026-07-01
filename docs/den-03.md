# Den 3 — Doctrine ORM

**Datum:** 1. 7. 2026  
**Status:** hotovo

Eloquent znám — Doctrine je jiný mindset. Dnes entity, repository, migrace, vztah OneToMany.

---

## Eloquent vs Doctrine (pro mě)

| Eloquent | Doctrine |
|----------|----------|
| `User::create([...])` | `new User()` + `persist()` + `flush()` |
| Active Record (model dělá vše) | Entity + EntityManager + Repository |
| `$user->save()` | `$em->persist($user); $em->flush();` |
| migrace `php artisan migrate` | `php bin/console doctrine:migrations:migrate` |
| `User::where(...)` | `$repo->findBy()` / DQL |

**Aha moment:** Doctrine není magic — musím explicitně říct „chci uložit“ (`persist`) a „pošli do DB“ (`flush`).

---

## Databáze

Přepnul jsem na **SQLite** (bez instalace PostgreSQL):

```env
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data_%kernel.environment%.db"
```

Soubor DB: `var/data_dev.db` — v `.gitignore`, nepushuje se.

Potřebné PHP ext: `pdo_sqlite`, `sqlite3`.

---

## Entity

`src/Entity/` — mapování tabulek přes PHP attributes:

```php
#[ORM\Entity(repositoryClass: StudyTopicRepository::class)]
class StudyTopic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'topics')]
    private StudyDay $studyDay;
}
```

- `StudyDay` — den studia (číslo + název)
- `StudyTopic` — poznámka k tématu
- vztah **OneToMany** / **ManyToOne** (jeden den → víc topics)

---

## Repository

`src/Repository/StudyTopicRepository.php` — dědí `ServiceEntityRepository`.

```php
public function findBySlug(string $slug): ?StudyTopic
public function findLatest(int $limit = 20): array  // DQL query builder
```

DI do controlleru automaticky — autowire.

---

## Ukládání dat

```php
$topic = new StudyTopic($title, $slug, $body, $day);
$this->entityManager->persist($topic);
$this->entityManager->flush();
```

- `persist()` = připrav entitu ke uložení (ještě není v DB)
- `flush()` = odešli změny do DB

V Laravelu by to bylo jedno `save()`.

---

## Migrace

```powershell
php bin/console doctrine:migrations:diff      # vygeneruj z entity
php bin/console doctrine:migrations:migrate     # spusť
php bin/console doctrine:schema:validate
```

Soubor: `migrations/Version20260701171413.php` — verzované změny schématu jako v Laravel migracích.

---

## Seed dat

```powershell
php bin/console app:study:seed
```

Console command `StudySeedCommand` — naplní ukázkové topics pro den 1–3.

---

## Routy v demo

| URL | Co dělá |
|-----|---------|
| `/topics` | seznam topics z DB |
| `/topics/{slug}` | detail |
| `/topics/new` | formulář — nový topic |

Controller injectuje `EntityManagerInterface` + repositories.

---

## Debug příkazy

```powershell
php bin/console doctrine:mapping:info
php bin/console doctrine:query:sql "SELECT * FROM study_topic"
php bin/console debug:autowiring EntityManagerInterface
```

---

## Co mi dává smysl / co je jiné

1. **Explicitní persist/flush** — víc kontroly, méně magie
2. **Entity jako čisté PHP objekty** — ne dědí z Model třídy
3. **Repository pattern** — dotazy oddělené od entity
4. **DQL** — SQL-like, ale pro entity (ne raw tabulky)

---

## Zdroje

- https://symfonycasts.com/screencast/symfony/doctrine
- https://symfony.com/doc/current/doctrine.html
- https://symfony.com/doc/current/doctrine/associations.html
- https://refactoring.guru/design-patterns/repository

---

## Zítra (den 4)

- Symfony Console — vlastní příkazy (mám už `app:study:seed`, zítra rozšířím)
- nebo hlubší Doctrine dotazy

---

## Poznámky pro sebe

- Když `could not find driver` → chybí `pdo_sqlite` v php.ini.
- OneToMany: vlastník vztahu je **ManyToOne** strana (`StudyTopic.studyDay` má foreign key).
