# Den 1 — Symfony základy

**Datum:** 30. 6. 2026  
**Čas:** ~3–4 h  
**Status:** hotovo

Přicházím z Laravelu. Dnes jsem si hlavně mapoval, kde je co a jak teče request.

---

## Setup (Windows)

Nainstaloval jsem PHP 8.3 přes winget, Composer do `~/bin` a Symfony CLI taky do `~/bin`.

```powershell
winget install PHP.PHP.8.3
```

U PHP jsem musel v `php.ini` nastavit `extension_dir` — winget to nedá defaultně správně a bez openssl/curl nejde Composer.

Projekt:

```powershell
symfony new demo --webapp --version="7.2.*" --no-git
```

Spuštění:

```powershell
cd demo
php -S 127.0.0.1:8000 -t public
# nebo: symfony server:start  (po restartu terminálu)
```

---

## Struktura — Laravel vs Symfony

Nejvíc mi pomohlo si to napsat bok po boku:

| Laravel | Symfony (`demo/`) |
|---------|-------------------|
| `app/Http/Controllers` | `src/Controller` |
| `routes/web.php` | `config/routes.yaml` + `#[Route]` |
| `resources/views` | `templates/` |
| `artisan` | `bin/console` |
| `AppServiceProvider` | `config/services.yaml` (řeším zítra) |

Symfony působí víc „na rovinu“ — míň magie, víc explicitních souborů.

---

## Routing

V `config/routes.yaml`:

```yaml
controllers:
    resource:
        path: ../src/Controller/
        namespace: App\Controller
    type: attribute
```

Symfony projde `src/Controller/` a routy bere z PHP atributů na metodách.

Statická route:

```php
#[Route('/', name: 'app_home')]
public function index(): Response
```

Dynamická:

```php
#[Route('/study/{topic}', name: 'app_study_topic')]
public function topic(string $topic): Response
```

Debug:

```powershell
php bin/console debug:router
php bin/console router:match /about
```

**Aha moment:** V Laravelu mám zvyk všechno cpát do `web.php`. Tady můžu mít route přímo u metody — líbí se mi to, je to přehlednější u větších controllerů.

---

## Controllery

Dědí z `AbstractController`.

- HTML: `$this->render('home/index.html.twig', ['title' => '...'])`
- JSON: `$this->json(['status' => 'ok'])`

Dnes jsem udělal:

| Route | Controller | Poznámka |
|-------|------------|----------|
| `/` | `HomeController::index` | homepage |
| `/about` | `HomeController::about` | procvičení Twig |
| `/laravel-map` | `HomeController::laravelMap` | tabulka pro mě |
| `/api/version` | `HomeController::version` | JSON s verzí PHP/Symfony |
| `/study/{topic}` | `LessonController::topic` | dynamický parametr |
| `/api/ping` | `LessonController::ping` | jednoduchý JSON |

---

## Twig

Blade znám, Twig je podobný:

```twig
{% extends 'base.html.twig' %}

{% block body %}
    {% for topic in topics %}
        <li>{{ topic }}</li>
    {% endfor %}

    <a href="{{ path('app_home') }}">Home</a>
{% endblock %}
```

| Blade | Twig |
|-------|------|
| `{{ $x }}` | `{{ x }}` |
| `@foreach` | `{% for %}` |
| `route('name')` | `path('name')` |

---

## Co jsem si zapsal (request flow)

```
prohlížeč → public/index.php → Kernel → router → controller → twig / json → response
```

V Laravelu stejná idea, jen jiné názvy složek.

---

## Zdroje co jsem použil

- [SymfonyCasts — Symfony](https://symfonycasts.com/screencast/symfony)
- [Symfony Setup](https://symfony.com/doc/current/setup.html)
- [Routing](https://symfony.com/doc/current/routing.html)
- [Templates](https://symfony.com/doc/current/templates.html)

---

## Zítra (den 2)

- `config/services.yaml`
- autowiring / DI
- jak se služby dostanou do controlleru

---

## Poznámky pro sebe

- Terminál musel restart — jinak nešel `symfony` příkaz (PATH).
- Na začátku jsem úplně nechápal rozdíl mezi routou a controllerem — pomohlo si to projít URL po URL v prohlížeči a podívat se do odpovídající metody.
- JSON endpointy dávají smysl hned — `return response()->json()` v Laravelu, tady `$this->json()`.
