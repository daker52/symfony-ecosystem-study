import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'node:path';

export default defineConfig({
  plugins: [vue()],
  root: resolve(__dirname, 'assets'),
  base: '/build/',
  build: {
    outDir: resolve(__dirname, 'public/build'),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        app: resolve(__dirname, 'assets/vite-entry.ts'),
      },
    },
  },
  server: {
    origin: 'http://localhost:5173',
  },
});
