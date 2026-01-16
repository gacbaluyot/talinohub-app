<template>
  <div :class="cn('rounded-xl border shadow-sm card-bg border-default', props.class)">
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
