<template>
  <input
    type="checkbox"
    :class="cn('h-4 w-4 rounded border-surface-300 dark:border-surface-600 text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-surface-900', props.class)"
    v-bind="$attrs"
  />
</template>

<script setup lang="ts">
import { cn } from '~/lib/utils'

interface Props {
  class?: string
}

const props = defineProps<Props>()
</script>
