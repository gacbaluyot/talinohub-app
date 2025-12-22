<template>
  <span :class="badgeClasses">
    <slot />
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(defineProps<{
  variant?: 'primary' | 'success' | 'warning' | 'danger' | 'info' | 'gray'
  size?: 'sm' | 'md' | 'lg'
}>(), {
  variant: 'gray',
  size: 'md'
})

const badgeClasses = computed(() => {
  const base = 'inline-flex items-center rounded-full font-medium'
  
  const variants = {
    primary: 'bg-indigo-100 text-indigo-700 ring-1 ring-inset ring-indigo-600/20',
    success: 'bg-green-100 text-green-700 ring-1 ring-inset ring-green-600/20',
    warning: 'bg-yellow-100 text-yellow-800 ring-1 ring-inset ring-yellow-600/20',
    danger: 'bg-red-100 text-red-700 ring-1 ring-inset ring-red-600/20',
    info: 'bg-blue-100 text-blue-700 ring-1 ring-inset ring-blue-600/20',
    gray: 'bg-gray-100 text-gray-700 ring-1 ring-inset ring-gray-600/20'
  }
  
  const sizes = {
    sm: 'px-2 py-0.5 text-xs',
    md: 'px-2.5 py-0.5 text-xs',
    lg: 'px-3 py-1 text-sm'
  }
  
  return `${base} ${variants[props.variant]} ${sizes[props.size]}`
})
</script>
