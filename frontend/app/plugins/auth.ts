import { useAuthStore } from '~/stores/auth'

export default defineNuxtPlugin((nuxtApp) => {
  // Wait for Pinia to be ready
  nuxtApp.hook('app:created', () => {
    const authStore = useAuthStore()
    
    // Initialize auth from cookies before any navigation happens
    authStore.initAuth()
  })
})
