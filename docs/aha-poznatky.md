# Aha poznatky — co jsem pokazil / zapomněl

Sbírka chyb a momentů, kdy mi to konečně došlo. Ať to nedělám podruhé.

---

## Den 3 — `could not find driver` (SQLite)

**Co se stalo:**  
Na `/topics` spadla chyba `Doctrine\DBAL\Exception\DriverException` → `could not find driver`.

**Co jsem myslel:**  
Že jsem špatně nastavil Doctrine nebo `DATABASE_URL`.

**Co bylo špatně:**  
V `php.ini` jsem sice odkomentoval `extension=pdo_sqlite` a `extension=sqlite3`, ale **nerestartoval jsem web server**. `symfony server` běží na pozadí s `php-cgi.exe` — ten se načte jednou a drží starou konfiguraci.

**CLI vs web:**

- `php bin/console` → nový proces → nové php.ini → fungovalo
- `symfony server` → starý proces → pořád bez driveru → 500 na webu

**Oprava:**

```powershell
symfony server:stop
symfony server:start
```

**Aha moment:**  
Po změně `php.ini` vždy restartovat dev server. Ne jen zavřít terminál — explicitně `server:stop` / `server:start`.

**Kontrola do budoucna:**

```powershell
powershell -File check-php.ps1
symfony php -m | findstr sqlite
```

---

## Den 1 — `symfony` příkaz neexistuje

**Co se stalo:**  
`symfony : The term 'symfony' is not recognized`

**Oprava:**  
Restart terminálu (PATH se aktualizoval až po instalaci do `~/bin`), nebo plná cesta `C:\Users\Hak\bin\symfony.exe`.

**Aha moment:**  
Po instalaci nástroje do PATH často pomůže nové okno PowerShellu.

---

## Den 11 — OpenSSL klíče pro Lexik JWT na Windows

**Co se stalo:**  
`openssl` není v PATH. PHP `openssl_pkey_new()` skončil chybou `system library::No such process`.

**Co jsem udělal:**  
Pro studium Lexik nastaven na **HS256** (shared secret v `.env`). RS256 + PEM znám jako produkční standard a mám to zapsané v den-11.

**Aha:**  
Auth setup má víc cest — důležitější je pochopit firewall flow (json_login → token → Bearer) než ulpnout na generování RSA klíčů den jedna.

---

## Den 9 — Vite manifest path

**Co se stalo:**  
Vite 6 dává manifest do `public/build/.vite/manifest.json`, ne rovnou `manifest.json`.

**Oprava:**  
`ViteAssetMapper` zkouší obě cesty.

---

