<template>
  <div v-if="show" :class="alertClasses">
    <div class="flex">
      <div class="shrink-0">
        <component :is="alertIcon" :class="iconClasses" />
      </div>
      <div class="ml-3 flex-1">
        <h3 v-if="title" :class="titleClasses">{{ title }}</h3>
        <div :class="messageClasses">
          <slot>{{ message }}</slot>
        </div>
      </div>
      <div v-if="dismissible" class="ml-auto pl-3">
        <button
          type="button"
          @click="$emit('close')"
          :class="closeButtonClasses"
        >
          <span class="sr-only">Dismiss</span>
          <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { 
  CheckCircleIcon, 
  ExclamationTriangleIcon, 
  InformationCircleIcon, 
  XCircleIcon 
} from '@heroicons/vue/24/outline'

const props = withDefaults(defineProps<{
  show?: boolean
  variant?: 'success' | 'warning' | 'error' | 'info'
  title?: string
  message?: string
  dismissible?: boolean
}>(), {
  show: true,
  variant: 'info',
  dismissible: false
})

defineEmits<{
  close: []
}>()

const alertClasses = computed(() => {
  const base = 'rounded-md p-4'
  
  const variants = {
    success: 'bg-green-50',
    warning: 'bg-yellow-50',
    error: 'bg-red-50',
    info: 'bg-blue-50'
  }
  
  return `${base} ${variants[props.variant]}`
})

const iconClasses = computed(() => {
  const base = 'h-5 w-5'
  
  const variants = {
    success: 'text-green-400',
    warning: 'text-yellow-400',
    error: 'text-red-400',
    info: 'text-blue-400'
  }
  
  return `${base} ${variants[props.variant]}`
})

const titleClasses = computed(() => {
  const base = 'text-sm font-medium'
  
  const variants = {
    success: 'text-green-800',
    warning: 'text-yellow-800',
    error: 'text-red-800',
    info: 'text-blue-800'
  }
  
  return `${base} ${variants[props.variant]}`
})

const messageClasses = computed(() => {
  const base = 'text-sm'
  const margin = props.title ? 'mt-2' : ''
  
  const variants = {
    success: 'text-green-700',
    warning: 'text-yellow-700',
    error: 'text-red-700',
    info: 'text-blue-700'
  }
  
  return `${base} ${margin} ${variants[props.variant]}`
})

const closeButtonClasses = computed(() => {
  const base = 'inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2'
  
  const variants = {
    success: 'bg-green-50 text-green-500 hover:bg-green-100 focus:ring-green-600 focus:ring-offset-green-50',
    warning: 'bg-yellow-50 text-yellow-500 hover:bg-yellow-100 focus:ring-yellow-600 focus:ring-offset-yellow-50',
    error: 'bg-red-50 text-red-500 hover:bg-red-100 focus:ring-red-600 focus:ring-offset-red-50',
    info: 'bg-blue-50 text-blue-500 hover:bg-blue-100 focus:ring-blue-600 focus:ring-offset-blue-50'
  }
  
  return `${base} ${variants[props.variant]}`
})

const alertIcon = computed(() => {
  const icons = {
    success: CheckCircleIcon,
    warning: ExclamationTriangleIcon,
    error: XCircleIcon,
    info: InformationCircleIcon
  }
  
  return icons[props.variant]
})
</script>
