import { createApp } from 'vue';
import StudyTopicsApp from './vue/StudyTopicsApp.vue';
import './styles/study.scss';

const el = document.getElementById('vue-study-app');

if (el) {
  createApp(StudyTopicsApp, {
    apiUrl: el.dataset.apiUrl ?? '/api/study/topics',
  }).mount(el);
}

console.log('[vite] Vue + TS entry načten');
