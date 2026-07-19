# Plán — týden 3 (dny 15–21)

Po Pulse demu (den 14) dorovnávám mezery z checklistu zadavatele.  
Cíl: umět věci **provozovat**, ne jen „mám to v poznámkách“.

~2–3 h/den. Po každém dni: krátký zápis `docs/den-XX.md` + checkbox v root README.

---

## Přehled

| Den | Téma | Proč |
|-----|------|------|
| 15 | RabbitMQ napevno | checklist — zatím jen Doctrine transport |
| 16 | Mago + CI | checklist — zatím jen `mago.toml` |
| 17 | LESS | checklist — v kódu jen SASS |
| 18 | JWT RS256 | produkční standard oproti HS256 |
| 19 | PHPUnit | CI bez testů je slabší |
| 20 | Forms + Validation | denní chleba v Symfony |
| 21 | Foundry (+ Encore přehled) | moderní fixtures + Vite vs Encore |

---

## Den 15 — RabbitMQ napevno

**Status:** hotovo — Broker Passport + `amqpsim` / `amqplib`, viz [den-15.md](den-15.md)

**Úkoly**
1. [x] Topology twin `amqpsim://` (bez Dockeru)
2. [x] Pure PHP `amqplib://` na živý Rabbit
3. [x] Passport na Pulse timeline
4. [x] `app:pulse:broker --export` → definitions.json
5. [x] docker-compose připravený

**Zdroje**
- https://www.rabbitmq.com/tutorials
- https://www.rabbitmq.com/tutorials/tutorial-one-php
- https://symfony.com/doc/current/messenger.html#amqp-transport
- https://www.rabbitmq.com/docs/management
- https://www.rabbitmq.com/docs/definitions
- https://github.com/php-amqplib/php-amqplib

**Hotovo když:** Pulse stage messages jdou vidět s badge broker hop (amqpsim nebo rabbitmq).

---

## Den 16 — Mago + CI

**Úkoly**
1. Nainstalovat Mago podle docs
2. `mago analyze` na `demo/src`
3. Opravit / zdůvodnit nálezy
4. Přidat step do `.github/workflows/ci.yml`

**Zdroje**
- https://mago.carthage.software/latest/en/
- https://mago.carthage.software/latest/en/getting-started/installation.html
- https://mago.carthage.software/latest/en/tools/analyzer.html

**Hotovo když:** Mago běží lokálně i v CI (nebo aspoň lokálně + dokumentovaný postup).

---

## Den 17 — LESS

**Úkoly**
1. Vytvořit např. `assets/styles/less-demo.less` (variables, nesting, mixin)
2. Napojit přes Vite (less plugin / preprocess)
3. Malá stránka nebo třída v Pulse/Vue demo
4. Do deníku: LESS vs SASS (co je stejné, co jiné)

**Zdroje**
- https://lesscss.org/
- https://lesscss.org/features/
- https://vite.dev/guide/features.html#css-pre-processors
- https://sass-lang.com/documentation/syntax

**Hotovo když:** v buildu je LESS výstup a umím to vysvětlit na pohovoru.

---

## Den 18 — JWT RS256

**Úkoly**
1. Vygenerovat `config/jwt/private.pem` + `public.pem`
2. Přepnout Lexik na RS256
3. Ověřit login + `/api/me` + Pulse
4. Zapsat: HS256 (shared secret) vs RS256 (pár klíčů)

**Zdroje**
- https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html
- https://github.com/lexik/LexikJWTAuthenticationBundle/blob/3.x/Resources/doc/index.md
- https://jwt.io/introduction

**Hotovo když:** token vzniká RS256 a API ho přijme.

---

## Den 19 — PHPUnit (Pulse / JWT)

**Úkoly**
1. Functional test: `POST /api/login` → 200 + token
2. Test: create work order (auth) → 202
3. Test: list orders (auth)
4. CI job `phpunit`

**Zdroje**
- https://symfony.com/doc/current/testing.html
- https://symfony.com/doc/current/testing.html#functional-tests
- https://docs.phpunit.de/en/11.5/
- https://symfonycasts.com/screencast/phpunit

**Hotovo když:** `php bin/phpunit` green + CI.

---

## Den 20 — Forms + Validation

**Úkoly**
1. Form type pro vytvoření Work Order (nebo Study Topic)
2. Constraints (`NotBlank`, `Length`…)
3. Twig render nebo API validation errors

**Zdroje**
- https://symfony.com/doc/current/forms.html
- https://symfony.com/doc/current/validation.html
- https://symfonycasts.com/screencast/symfony-forms

**Hotovo když:** neplatný vstup vrátí chyby, platný projde.

---

## Den 21 — Foundry + Encore přehled

**Úkoly**
1. Zenstruck Foundry — factory pro `User` nebo `WorkOrder`
2. Porovnat s `DoctrineFixturesBundle`
3. Krátká poznámka: Vite (co používám) vs Webpack Encore (co potkám v legacy)

**Zdroje**
- https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html
- https://github.com/zenstruck/foundry
- https://symfony.com/doc/current/frontend.html
- https://symfony.com/doc/current/frontend/encore/installation.html

**Hotovo když:** Foundry seed funguje; Encore umím vysvětlit bez nutnosti plného setupu.

---

## Po týdnu 3 — co říct zadavateli

> Dorovnal jsem mezery: Pulse teď umí jet i přes RabbitMQ, Mago vedle PHPStanu, LESS vedle SASS, JWT na RS256, a mám základní PHPUnit + Forms. Foundry jsem zkusil jako modernější fixtures.
