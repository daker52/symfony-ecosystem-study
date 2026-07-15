import { createApp } from 'vue';
import StudyTopicsApp from './vue/StudyTopicsApp.vue';
import PulseApp from './vue/PulseApp.vue';
import './styles/study.scss';
import './styles/pulse.scss';

const studyEl = document.getElementById('vue-study-app');
if (studyEl) {
  createApp(StudyTopicsApp, {
    apiUrl: studyEl.dataset.apiUrl ?? '/api/study/topics',
  }).mount(studyEl);
}

const pulseEl = document.getElementById('vue-pulse-app');
if (pulseEl) {
  createApp(PulseApp, {
    loginUrl: pulseEl.dataset.loginUrl ?? '/api/login',
    ordersUrl: pulseEl.dataset.ordersUrl ?? '/api/pulse/orders',
  }).mount(pulseEl);
}

console.log('[vite] Vue entries ready');
