<template>
  <div :class="cn('px-6 pb-6 pt-4 flex items-center justify-end gap-3 border-t border-surface-200 dark:border-surface-700', props.class)">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { cn } from '~/lib/utils'

interface Props {
  class?: string
}

const props = defineProps<Props>()
</script>
