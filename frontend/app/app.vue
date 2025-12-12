<template>
  <div>
    <!-- This renders your pages -->
    <NuxtPage />
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()

// Initialize auth from localStorage on app load
onMounted(() => {
  authStore.initAuth()

  if (authStore.isAuthenticated) {
    // Set axios default header
    navigateTo('/dashboard')
  } else {
    navigateTo('/auth/login')
  }
})
</script>