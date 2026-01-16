import { useAuthStore } from "~/stores/auth"

export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()

  // Ensure auth is initialized
  if (!authStore.isAuthenticated) {
    authStore.initAuth()
  }

  // If still not authenticated after init, redirect to login
  if (!authStore.isAuthenticated) {
    return navigateTo('/auth/login')
  }
})
