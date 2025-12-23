<template>
  <div v-if="show" :class="cn(alertVariants({ variant }), $attrs.class as string)">
    <component :is="alertIcon" class="h-5 w-5" />
    <div class="ml-3 flex-1">
      <h3 v-if="title" class="text-sm font-medium">{{ title }}</h3>
      <div :class="cn('text-sm', title && 'mt-2')">
        <slot>{{ message }}</slot>
      </div>
    </div>
    <button
      v-if="dismissible"
      type="button"
      @click="$emit('close')"
      class="ml-auto shrink-0 rounded-md p-1.5 inline-flex focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 opacity-70 hover:opacity-100 transition-opacity"
    >
      <span class="sr-only">Dismiss</span>
      <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
      </svg>
    </button>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'
import { cva, type VariantProps } from 'class-variance-authority'
import {
  CheckCircleIcon,
  ExclamationTriangleIcon,
  InformationCircleIcon,
  XCircleIcon
} from '@heroicons/vue/24/outline'

const alertVariants = cva(
  'relative w-full rounded-lg border p-4 flex items-start',
  {
    variants: {
      variant: {
        default: 'bg-background text-foreground',
        destructive: 'border-destructive/50 text-destructive dark:border-destructive [&>svg]:text-destructive bg-destructive/10',
        // Legacy variants for backward compatibility
        success: 'bg-green-50 border-green-200 text-green-800 [&>svg]:text-green-400',
        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800 [&>svg]:text-yellow-400',
        error: 'bg-red-50 border-red-200 text-red-800 [&>svg]:text-red-400',
        info: 'bg-blue-50 border-blue-200 text-blue-800 [&>svg]:text-blue-400',
      },
    },
    defaultVariants: {
      variant: 'default',
    },
  },
)

const props = withDefaults(defineProps<{
  show?: boolean
  variant?: VariantProps<typeof alertVariants>['variant']
  title?: string
  message?: string
  dismissible?: boolean
}>(), {
  show: true,
  variant: 'default',
  dismissible: false
})

defineEmits<{
  close: []
}>()

const alertIcon = computed(() => {
  const icons = {
    default: InformationCircleIcon,
    destructive: XCircleIcon,
    success: CheckCircleIcon,
    warning: ExclamationTriangleIcon,
    error: XCircleIcon,
    info: InformationCircleIcon
  }

  return icons[props.variant || 'default']
})
</script>
