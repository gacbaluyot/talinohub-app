<template>
  <p :class="cn('text-sm text-surface-600 dark:text-surface-400', props.class)">
    <slot />
  </p>
</template>

<script setup lang="ts">
import { cn } from '~/lib/utils'

interface Props {
  class?: string
}

const props = defineProps<Props>()
</script>
