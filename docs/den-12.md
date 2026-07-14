# Den 12 — PHPStan, Mago, CI

**Datum:** 14. 7. 2026  
**Status:** hotovo

---

## PHPStan

```powershell
cd demo
vendor/bin/phpstan analyse --memory-limit=512M
```

Config: `phpstan.neon` (level 5 + symfony + doctrine extension).  
Po dni 11–13: **0 errors**.

---

## Mago

`mago.toml` připravený. Instalace toolu: https://mago.carthage.software/latest/en/  
V CI primárně PHPStan — Mago jako další analyzer týmu.

```powershell
mago analyze
```

---

## CI

`.github/workflows/ci.yml`:

1. PHP 8.3 → `composer install` → PHPStan  
2. Node 22 → `npm ci` → `npm run build`

Push na `main` / PR spustí checky.

---

## Zdroje

- https://phpstan.org/user-guide/getting-started
- https://mago.carthage.software/latest/en/
