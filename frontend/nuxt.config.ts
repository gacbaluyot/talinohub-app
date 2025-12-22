import tailwindcss from "@tailwindcss/vite";

export default defineNuxtConfig({
  compatibilityDate: "2025-07-15",
  devtools: { enabled: true },
  ssr: false,
  css: ['~/assets/css/main.css'],
  modules: ['@pinia/nuxt'],
  runtimeConfig: {
    public: {
      apiUrl: 'http://127.0.0.1:8000/api'
    }
  },
  
  vite: {
    plugins: [
      tailwindcss(),
    ],
  },
});