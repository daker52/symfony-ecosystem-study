<script setup lang="ts">
import type { StudyTopicDto } from './types';

defineProps<{
  topic: StudyTopicDto;
}>();
</script>

<template>
  <li class="topic-card">
    <strong>{{ topic.title }}</strong>
    <span class="topic-card__meta">den {{ topic.day }} · {{ topic.slug }}</span>
    <p>{{ topic.body }}</p>
  </li>
</template>
