<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import type { PulseListResponse, WorkOrderDto } from './pulse-types';

const props = defineProps<{
  loginUrl: string;
  ordersUrl: string;
}>();

const token = ref<string | null>(localStorage.getItem('pulse_jwt'));
const email = ref('study@example.com');
const password = ref('study123');
const authError = ref<string | null>(null);

const title = ref('');
const type = ref('cache-rebuild');
const createHint = ref<string | null>(null);

const data = ref<PulseListResponse | null>(null);
const loadError = ref<string | null>(null);
const selectedId = ref<number | null>(null);

let timer: ReturnType<typeof setInterval> | null = null;

const selected = computed((): WorkOrderDto | null => {
  if (!data.value || selectedId.value === null) {
    return null;
  }

  return data.value.orders.find((o) => o.id === selectedId.value) ?? null;
});

async function login(): Promise<void> {
  authError.value = null;
  const res = await fetch(props.loginUrl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email: email.value, password: password.value }),
  });
  const json = (await res.json()) as { token?: string; message?: string };
  if (!res.ok || !json.token) {
    authError.value = json.message ?? `Login failed (${res.status})`;
    return;
  }
  token.value = json.token;
  localStorage.setItem('pulse_jwt', json.token);
  await refresh();
  startPolling();
}

function logout(): void {
  token.value = null;
  localStorage.removeItem('pulse_jwt');
  data.value = null;
  stopPolling();
}

async function refresh(): Promise<void> {
  if (!token.value) {
    return;
  }
  loadError.value = null;
  const res = await fetch(props.ordersUrl, {
    headers: { Authorization: `Bearer ${token.value}` },
  });
  if (res.status === 401) {
    logout();
    loadError.value = 'Token vypršel — přihlas se znovu.';
    return;
  }
  if (!res.ok) {
    loadError.value = `HTTP ${res.status}`;
    return;
  }
  data.value = (await res.json()) as PulseListResponse;
  if (selectedId.value === null && data.value.orders.length > 0) {
    selectedId.value = data.value.orders[0].id;
  }
}

async function createOrder(): Promise<void> {
  if (!token.value || title.value.trim() === '') {
    return;
  }
  createHint.value = null;
  const res = await fetch(props.ordersUrl, {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${token.value}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ title: title.value.trim(), type: type.value }),
  });
  const json = (await res.json()) as { id?: number; hint?: string; error?: string };
  if (!res.ok) {
    createHint.value = json.error ?? `Create failed (${res.status})`;
    return;
  }
  createHint.value = json.hint ?? 'Accepted';
  if (json.id) {
    selectedId.value = json.id;
  }
  title.value = '';
  await refresh();
}

function startPolling(): void {
  stopPolling();
  timer = setInterval(() => {
    void refresh();
  }, 1500);
}

function stopPolling(): void {
  if (timer) {
    clearInterval(timer);
    timer = null;
  }
}

onMounted(() => {
  if (token.value) {
    void refresh().then(() => startPolling());
  }
});

onUnmounted(() => stopPolling());
</script>

<template>
  <div class="pulse">
    <header class="pulse__hero">
      <p class="pulse__brand">Pulse</p>
      <h1>Live async pipeline</h1>
      <p class="pulse__lead">
        JWT → CQRS command → Messenger fronta → stage timeline. Worker musí běžet.
      </p>
    </header>

    <section v-if="!token" class="pulse__panel">
      <h2>Login</h2>
      <div class="pulse__form">
        <input v-model="email" type="email" placeholder="email" />
        <input v-model="password" type="password" placeholder="password" />
        <button type="button" @click="login">Získat JWT</button>
      </div>
      <p v-if="authError" class="pulse__error">{{ authError }}</p>
      <p class="pulse__muted">Fixtures: study@example.com / study123</p>
    </section>

    <template v-else>
      <div class="pulse__toolbar">
        <button type="button" class="pulse__ghost" @click="logout">Odhlásit</button>
        <button type="button" class="pulse__ghost" @click="refresh">Refresh</button>
      </div>

      <section v-if="data" class="pulse__stats">
        <div><strong>{{ data.stats.queued }}</strong><span>queued</span></div>
        <div><strong>{{ data.stats.running }}</strong><span>running</span></div>
        <div><strong>{{ data.stats.done }}</strong><span>done</span></div>
        <div><strong>{{ data.stats.failed }}</strong><span>failed</span></div>
      </section>

      <section class="pulse__panel">
        <h2>Nový work order</h2>
        <div class="pulse__form pulse__form--row">
          <input v-model="title" placeholder="např. Rebuild study cache" />
          <select v-model="type">
            <option value="cache-rebuild">cache-rebuild</option>
            <option value="digest">digest</option>
            <option value="sync-topics">sync-topics</option>
            <option value="generic">generic</option>
          </select>
          <button type="button" @click="createOrder">Dispatch command</button>
        </div>
        <p v-if="createHint" class="pulse__muted">{{ createHint }}</p>
      </section>

      <p v-if="loadError" class="pulse__error">{{ loadError }}</p>

      <div class="pulse__grid">
        <section class="pulse__panel">
          <h2>Orders</h2>
          <ul class="pulse__list">
            <li
              v-for="order in data?.orders ?? []"
              :key="order.id"
              :class="{ 'is-active': order.id === selectedId }"
              @click="selectedId = order.id"
            >
              <strong>#{{ order.id }} {{ order.title }}</strong>
              <span :class="'st st--' + order.status">{{ order.status }}</span>
              <em>{{ order.currentStage ?? '—' }}</em>
            </li>
          </ul>
          <p v-if="(data?.orders.length ?? 0) === 0" class="pulse__muted">Zatím prázdné.</p>
        </section>

        <section class="pulse__panel">
          <h2>Timeline</h2>
          <template v-if="selected">
            <p>
              <span :class="'st st--' + selected.status">{{ selected.status }}</span>
              · {{ selected.type }}
            </p>
            <ol class="pulse__timeline">
              <li v-for="(ev, i) in selected.events" :key="i">
                <code>{{ ev.stage }}</code>
                <span>{{ ev.message }}</span>
                <time>{{ ev.at }}</time>
              </li>
            </ol>
          </template>
          <p v-else class="pulse__muted">Vyber order vlevo.</p>
        </section>
      </div>

      <pre class="pulse__cli">php bin/console messenger:consume async -vv
php bin/console app:pulse:report</pre>
    </template>
  </div>
</template>
