# Den 17 — LESS (+ srovnání se SASS)

**Datum:** 21. 7. 2026  
**Status:** hotovo

SASS už mám (`study.scss`, `pulse.scss`). Dnes doplněný **LESS**, ať checklist zadavatele sedí.

---

## Co je v demu

- `assets/styles/less-demo.less` — proměnné (`@x`), nesting, mixin `.card()`
- Vite + balíček `less`
- stránka `/less-demo`

---

## LESS vs SASS (co říct)

| | LESS | SASS (u mě default) |
|---|------|---------------------|
| Proměnná | `@color` | `$color` |
| Mixin | `.name() { }` | `@mixin name { }` / `@include` |
| Nesting | `&__title` | stejně |
| V Symfony FE | méně časté | častější s Vite |

Obojí řeší totéž: DRY styly před CSS. V tomhle projektu zůstává SASS hlavní; LESS je vědomá ukázka.

---

## Spuštění

```powershell
cd demo
npm install
npm run build
# otevři /less-demo
```

---

## Zdroje

- https://lesscss.org/
- https://lesscss.org/features/
- https://vite.dev/guide/features.html#css-pre-processors
- https://sass-lang.com/documentation/syntax
