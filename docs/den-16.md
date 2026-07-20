# Den 16 — Mago + CI

**Datum:** 21. 7. 2026  
**Status:** hotovo

Mago = další statická analýza vedle PHPStan (Rust binary, rychlý).  
V CI běží oboje: PHPStan musí být clean, Mago běží s baseline.

---

## Co jsem udělal

1. `composer require --dev carthage-software/mago`
2. `mago.toml` — `src` + `includes = ["vendor"]` (jinak nevidí Symfony/Doctrine typy)
3. Baseline `mago-baseline.toml` — zavedení nástroje bez okamžitého refaktoru všeho
4. GitHub Actions job `mago` vedle `phpstan`
5. Composer script: `composer mago`

---

## Příkazy

```powershell
cd demo
composer phpstan
composer mago
# Windows (když vendor/bin/mago padá na SSL):
tools\mago\mago.exe analyze --baseline mago-baseline.toml
```

---

## PHPStan vs Mago (pro pohovor)

| | PHPStan | Mago |
|---|---------|------|
| Jazyk | PHP | Rust binary |
| U mě | level 5, 0 errors | analyze + baseline |
| CI | ano | ano |
| Smysl | typy / Doctrine / Symfony | rychlá druhá síť + lint/fmt ekosystém |

Baseline ≠ „ignoruju chyby navždy“ — je to startovní čára; nové issues CI chytí.

---

## Aha

- Bez `includes = ["vendor"]` Mago hlásí stovky „unknown Symfony/Doctrine type“.
- Na Windows Composer wrapper narazil na SSL při downloadu binary → ruční zip z GitHub releases do `tools/mago/`.

---

## Zdroje

- https://mago.carthage.software/
- https://mago.carthage.software/1.25.2/en/guide/installation/
- https://github.com/carthage-software/mago
