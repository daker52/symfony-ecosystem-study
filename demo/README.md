# demo/ — Symfony appka (studium)

Tady je živá Symfony 7 aplikace, na které jsem si procházel stack přechodem z Laravelu.  
Není to produkční produkt — je to **cvičné demo**, ať mám v ruce kód, ne jen poznámky.

Repo root (plán, deník): [../README.md](../README.md) · deník: [../docs/](../docs/)

---

## Co to umí / co tím ukazuju

Po 14 dnech tohle demo skládá dohromady věci, které jsem se učil jednotlivě:

| Oblast | Kde to uvidíš |
|--------|----------------|
| Routing, controllers, Twig | `/`, `/about`, starší stránky |
| DI / services / compiler pass | `/services-demo` |
| Doctrine ORM + fixtures | `/topics`, `doctrine:fixtures:load` |
| Console (CLI) | `app:hello`, `app:study:list`, `app:pulse:report` |
| Design patterns | `/patterns-demo` |
| DDD + CQRS + Messenger | `/cqrs-demo`, hlavně **Pulse** |
| Vue + TypeScript + Vite + SASS | `/vue-demo`, `/pulse` |
| JWT + Security | `/jwt-demo`, login na Pulse |
| RabbitMQ myšlení | Messenger DSN (Doctrine teď, AMQP připravený) |
| PHPStan + CI | `phpstan.neon`, `.github/workflows/ci.yml` |

**Hlavní wow obrazovka = [Pulse](#pulse--hlavní-demo)** (`/pulse`).

---

## Poznámky z vývoje (co mi sedělo / bolelo)

- Z Laravelu to není „jiné PHP“, spíš **jiná struktura a explicitnější DI**. Container + `services.yaml` mi dává větší jistotu než magie v providerech.
- Doctrine vs Eloquent: `persist()` + `flush()` mi ze začátku přišlo otravné, pak mi to začalo dávat smysl (jednotka práce / transakce).
- Messenger mě překvapil víc než jsem čekal — **message + handler + transport**. Vyměním DSN a handlery zůstanou. To je silnější než „jen Redis queue driver“.
- CQRS u mě není dogma — v Pulse je create/list přes busy, pipeline stage přes async zprávy. Controller netahá business do sebe.
- JWT: na Windows jsem narazil na generování RSA klíčů → studijně **HS256**, produkčně bych šel do RS256. Důležitější bylo pochopit firewall (`json_login` → Bearer).
- Frontend: AssetMapper zůstává na Stimulus; **Vite** jsem dal vedle kvůli Vue/TS/SASS. Manifest ve Vite 6 je v `public/build/.vite/`.
- Chyby, na kterých jsem se spálil: [aha-poznatky](../docs/aha-poznatky.md) (SQLite driver + restart serveru, atd.).

---

## Pulse — hlavní demo

Mini „ops“ board:

1. Přihlášení JWT  
2. Vytvoříš Work Order (CQRS command)  
3. Messenger ho žere ve workeru stage po stage: `validate → process → notify → complete`  
4. Vue timeline to live ukazuje  
5. CLI report: `app:pulse:report`

**Co tím říkám:** umím request → auth → command → fronta → worker → DB stav → UI — ne jen CRUD formulář.

Demo user (fixtures): `study@example.com` / `study123`

---

## Jak to ovládat

### 1) Příprava

```powershell
cd C:\Users\Hak\Projects\symfony-ecosystem-study\demo
composer install
npm install
npm run build
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

### 2) Web

```powershell
symfony server:start
# nebo: php -S 127.0.0.1:8000 -t public
```

Otevři třeba:
- `/pulse` — finální demo
- `/vue-demo` — Vue list topics
- `/jwt-demo` — login → token → `/api/me`
- `/cqrs-demo` — create/query study topic
- `/patterns-demo`, `/services-demo`, `/topics`

### 3) Worker (nutný pro Pulse / async)

**Musíš být ve složce `demo/`**, ne v `C:\Users\Hak`.

```powershell
cd C:\Users\Hak\Projects\symfony-ecosystem-study\demo
php bin/console messenger:consume async -vv
```

Tip: vytvoř order na `/pulse` *bez* workeru → zůstane `queued`. Pak spusť worker a sleduj timeline. To je ten moment „aha, fronta“.

### 4) Užitečné příkazy

```powershell
php bin/console list app
php bin/console app:pulse:report
php bin/console app:hello daker52
php bin/console app:study:list --day=3
php bin/console debug:messenger
php bin/console debug:router
vendor\bin\phpstan analyse --memory-limit=512M
```

### 5) Frontend HMR (volitelně)

V `.env`: `VITE_DEV_SERVER=1`, pak `npm run dev` + web server.

### 6) RabbitMQ (volitelně, den 10)

```powershell
docker compose -f docker-compose.rabbitmq.yml up -d
```

V `.env` přepni `MESSENGER_TRANSPORT_DSN` na AMQP (viz komentář v souboru). Default je Doctrine transport — na Windows/studium stačí.

---

## Struktura (co kde hledat)

```
demo/
  src/Controller/     — stránky + API
  src/Entity/         — Doctrine (Study*, User, WorkOrder…)
  src/Message/        — CQRS commands/queries + async events
  src/MessageHandler/ — handlery
  src/Command/        — console
  src/DataFixtures/   — seed dat
  assets/vue/         — Vue + TS
  assets/styles/      — SASS
  templates/          — Twig
  config/packages/    — security, messenger, …
  public/build/       — Vite build výstup
```

Studijní deník po dnech je v `../docs/` — tady v `demo/` je hlavně kód.

---

## Co záměrně není „hotový produkt“

- žádný fancy design systém
- JWT HS256 je studijní zkratka
- Pulse stage jsou simulované (usleep + log), ne napojení na reálný cluster
- RabbitMQ je připravený přes DSN/compose, default běží Doctrine fronta

Cíl byl **pochopit a ukázat architekturu**, ne shipnout SaaS.

Další plán (Rabbit napevno, Mago, LESS, RS256, testy…): [../docs/plan-tyden-3.md](../docs/plan-tyden-3.md)
