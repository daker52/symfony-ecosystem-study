<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import type { StudyTopicDto, StudyTopicsResponse } from './types';
import TopicCard from './TopicCard.vue';

const props = defineProps<{
  apiUrl: string;
}>();

const topics = ref<StudyTopicDto[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);
const filter = ref('');

const filtered = computed(() => {
  const q = filter.value.trim().toLowerCase();
  if (!q) {
    return topics.value;
  }

  return topics.value.filter(
    (t) => t.title.toLowerCase().includes(q) || t.slug.includes(q),
  );
});

async function load(): Promise<void> {
  loading.value = true;
  error.value = null;

  try {
    const res = await fetch(props.apiUrl);
    if (!res.ok) {
      throw new Error(`HTTP ${res.status}`);
    }
    const data = (await res.json()) as StudyTopicsResponse;
    topics.value = data.topics;
  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Neznámá chyba';
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  void load();
});
</script>

<template>
  <section class="vue-study">
    <header class="vue-study__header">
      <h2>Study topics (Vue + TS)</h2>
      <p>Composition API — <code>ref</code>, <code>computed</code>, typed props</p>
    </header>

    <label class="vue-study__filter">
      Filtr
      <input v-model="filter" type="search" placeholder="routing, doctrine…" />
    </label>

    <p v-if="loading">Načítám…</p>
    <p v-else-if="error" class="vue-study__error">{{ error }}</p>
    <p v-else-if="filtered.length === 0">Nic nenalezeno.</p>
    <ul v-else class="vue-study__list">
      <TopicCard v-for="t in filtered" :key="t.slug" :topic="t" />
    </ul>
  </section>
</template>
