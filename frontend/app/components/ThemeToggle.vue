<template>
  <button
    @click="toggleColorMode"
    class="p-2 rounded-lg hover:bg-accent transition-colors"
    type="button"
  >
    <SunIcon class="h-5 w-5 rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0 text-foreground" />
    <MoonIcon class="absolute h-5 w-5 rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100 text-foreground" />
    <span class="sr-only">Toggle theme</span>
  </button>
</template>

<script setup lang="ts">
import { SunIcon, MoonIcon } from '@heroicons/vue/24/outline'

</script>
