# Den 13 — SASS (+ LESS poznámka)

**Datum:** 14. 7. 2026  
**Status:** hotovo

SASS napojený přes Vite do Vue demo stránky.

---

## Co je v `assets/styles/study.scss`

- **proměnné** `$study-accent`, `$study-bg`…
- **nesting** `.vue-study { &__header { … } }`
- **mixin** `@mixin card-surface` → `.topic-card`

Build: `npm run build` → CSS chunk v `public/build/assets/`.

---

## LESS vs SASS

Obojí: variables, nesting, mixins.  
V tomhle stacku (Vite + Vue) je SASS častější default. LESS potkám spíš ve starších FE.

---

## Zdroje

- https://sass-lang.com/documentation/
- https://lesscss.org/
