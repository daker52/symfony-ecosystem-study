# Den 9 — Vite build pipeline

**Datum:** 14. 7. 2026  
**Status:** hotovo

AssetMapper zůstává (Stimulus), Vite běží **vedle** — bundluje Vue/TS/SASS do `public/build/`.

---

## Pipeline

```
assets/vite-entry.ts
  → Vite (plugin-vue, sass)
  → public/build/assets/*.js + *.css
  → manifest: public/build/.vite/manifest.json
```

Twig helpers: `vite_js()`, `vite_css()`, `vite_dev()` — `App\Service\ViteAssetMapper`.

```powershell
cd demo
npm install
npm run build
# HMR: VITE_DEV_SERVER=1 v .env + npm run dev
```

---

## Vite vs Webpack Encore

| | Vite | Webpack Encore |
|---|------|----------------|
| Rychlost HMR | velmi rychlé | pomalejší |
| Symfony historie | novější default směrem | klasika ve starších projektech |
| Config | `vite.config.ts` | `webpack.config.js` + Encore API |

Pro nový projekt bych šel do Vite. Encore potkám spíš v legacy Symfony.

---

## Zdroje

- https://vite.dev/guide/
- https://symfony.com/doc/current/frontend.html
