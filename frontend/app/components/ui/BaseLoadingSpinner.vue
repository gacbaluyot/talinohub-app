<template>
  <div :class="containerClasses">
    <div :class="spinnerClasses"></div>
    <p v-if="text" :class="textClasses">{{ text }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(defineProps<{
  size?: 'sm' | 'md' | 'lg'
  text?: string
  centered?: boolean
}>(), {
  size: 'md',
  centered: false
})

const containerClasses = computed(() => {
  const base = props.centered ? 'text-center' : ''
  return base
})

const spinnerClasses = computed(() => {
  const base = 'inline-block animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent'
  
  const sizes = {
    sm: 'h-6 w-6',
    md: 'h-8 w-8',
    lg: 'h-12 w-12'
  }
  
  return `${base} ${sizes[props.size]}`
})

const textClasses = computed(() => {
  const base = 'text-sm text-gray-600'
  const margin = props.size === 'sm' ? 'mt-1' : props.size === 'md' ? 'mt-2' : 'mt-3'
  return `${base} ${margin}`
})
</script>
