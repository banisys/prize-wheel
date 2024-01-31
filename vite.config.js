import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  define: {
    __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false,
  },
  plugins: [
    laravel({
      input: [],
      refresh: true,
    }),
    vue()
  ],
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js',
    }
  }
});
