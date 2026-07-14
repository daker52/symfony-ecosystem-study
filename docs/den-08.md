# Den 8 — Vue + TypeScript

**Datum:** 14. 7. 2026  
**Status:** hotovo

Composition API ve Symfony projektu — Vue není „samostatný frontend“, ale komponenta napojená na API.

---

## Co jsem udělal

| Soubor | Účel |
|--------|------|
| `assets/vue/StudyTopicsApp.vue` | `ref`, `computed`, `onMounted`, typed props |
| `assets/vue/TopicCard.vue` | child komponenta + props |
| `assets/vue/types.ts` | `StudyTopicDto` — typy nad API response |
| `/vue-demo` | Twig mount point `#vue-study-app` |
| `GET /api/study/topics` | JSON pro Vue |

---

## Composition API (stručně)

```ts
const topics = ref<StudyTopicDto[]>([]);
const filtered = computed(() => /* filtr podle search */);
onMounted(() => void load());
```

Laravel analogie: Inertia/Vue stejně — tady jen bez Inertia, čistý fetch na Symfony JSON.

---

## Aha

Typy u API response mi zachránily hádání tvaru JSON. Dokud API vrátí something jiného, TS to chytí ve Vue layer — ne až v runtime UI.

---

## Zdroje

- https://vuejs.org/guide/introduction.html
- https://www.typescriptlang.org/docs/handbook/intro.html
