import {fileURLToPath, URL} from 'node:url';
import {defineConfig} from 'vite';
import symfonyPlugin from 'vite-plugin-symfony';
import vue from '@vitejs/plugin-vue';

// https://vitejs.dev/config/
// noinspection JSUnusedGlobalSymbols
export default defineConfig({
  build: {
    rollupOptions: {
      input: {
        app: './src/vue/main.ts',
      },
    },
  },
  plugins: [
    symfonyPlugin(),
    vue(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src/vue', import.meta.url)),
    },
  },
  test: {
    environment: 'jsdom',
  },
});
