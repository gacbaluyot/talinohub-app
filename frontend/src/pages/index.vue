<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="text-center">
      <h1 class="text-4xl font-bold text-surface-900 dark:text-surface-0 mb-4">Welcome to TalinoHub</h1>
      <div class="space-x-4">
        <NuxtLink 
          to="/auth/login" 
          class="inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700"
        >
          Login
        </NuxtLink>
        <NuxtLink 
          to="/auth/register" 
          class="inline-block px-6 py-3 bg-gray-200 text-surface-900 dark:text-surface-0 font-medium rounded-md hover:bg-gray-300"
        >
          Sign Up
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
</script>
