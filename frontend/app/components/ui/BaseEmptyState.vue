<template>
  <div :class="containerClasses">
    <component :is="icon" v-if="icon" :class="iconClasses" />
    <slot name="icon" v-else>
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
      </svg>
    </slot>
    
    <h3 v-if="title" class="mt-4 text-lg font-semibold text-gray-900">{{ title }}</h3>
    <p v-if="description" class="mt-2 text-sm text-gray-500">{{ description }}</p>
    
    <div v-if="$slots.action" class="mt-6">
      <slot name="action" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { Component } from 'vue'

const props = withDefaults(defineProps<{
  title?: string
  description?: string
  icon?: Component
  compact?: boolean
}>(), {
  compact: false
})

const containerClasses = computed(() => {
  const base = 'text-center bg-white rounded-lg shadow'
  const padding = props.compact ? 'py-8' : 'py-12 px-4'
  return `${base} ${padding}`
})

const iconClasses = computed(() => {
  return 'mx-auto h-12 w-12 text-gray-400'
})
</script>
