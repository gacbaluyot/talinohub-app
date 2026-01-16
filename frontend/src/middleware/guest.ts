import { useAuthStore } from "~/stores/auth"

export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()

  // Ensure auth is initialized
  authStore.initAuth()

  // If already authenticated, redirect based on role
  if (authStore.isAuthenticated) {
    const primaryRole = authStore.primaryRole
    
    if (primaryRole === 'admin') {
      return navigateTo('/admin')
    } else if (primaryRole === 'educator') {
      return navigateTo('/educator')
    } else if (primaryRole === 'student') {
      return navigateTo('/student')
    }
    
    // Fallback to dashboard
    return navigateTo('/dashboard')
  }
})
