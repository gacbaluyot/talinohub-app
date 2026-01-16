import tailwindcss from "@tailwindcss/vite";
import path from 'path';

export default defineNuxtConfig({
  compatibilityDate: "2025-07-15",
  devtools: { enabled: true },
  ssr: false,
  srcDir: 'src/',
  css: ['~/assets/css/main.css'],
  modules: [
    '@pinia/nuxt',
    'shadcn-nuxt',
  ],
  runtimeConfig: {
    public: {
      apiUrl: 'http://127.0.0.1:8000/api'
    }
  },
  shadcn: {
    prefix: '',
    componentDir: './src/components/ui'
  },
  vite: {
    plugins: [
      tailwindcss(),
    ],
    resolve: {
      alias: {
        '@': path.resolve(__dirname, './src')
      }
    }
  },
});