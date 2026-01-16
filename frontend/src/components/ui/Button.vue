<template>
  <button :class="cn(buttonVariants({ variant, size }), props.class)" v-bind="$attrs">
    <slot />
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { cva, type VariantProps } from 'class-variance-authority'
import { cn } from '~/lib/utils'

const buttonVariants = cva(
  'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
  {
    variants: {
      variant: {
        default: 'bg-indigo-600 text-white shadow hover:bg-indigo-700 focus-visible:ring-indigo-600',
        destructive: 'bg-red-600 text-white shadow-sm hover:bg-red-700 focus-visible:ring-red-600',
        outline: 'border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-900 text-surface-900 dark:text-surface-0 shadow-sm hover:bg-surface-50 dark:hover:bg-surface-800 focus-visible:ring-indigo-600',
        secondary: 'bg-surface-100 dark:bg-surface-800 text-surface-900 dark:text-surface-0 shadow-sm hover:bg-surface-200 dark:hover:bg-surface-700 focus-visible:ring-indigo-600',
        ghost: 'text-surface-900 dark:text-surface-0 hover:bg-surface-100 dark:hover:bg-surface-800 focus-visible:ring-indigo-600',
        link: 'text-indigo-600 dark:text-indigo-400 underline-offset-4 hover:underline',
      },
      size: {
        default: 'h-10 px-4 py-2',
        sm: 'h-8 rounded-md px-3 text-xs',
        lg: 'h-12 rounded-lg px-8',
        icon: 'h-10 w-10',
      },
    },
    defaultVariants: {
      variant: 'default',
      size: 'default',
    },
  }
)

type ButtonVariants = VariantProps<typeof buttonVariants>

interface Props {
  variant?: ButtonVariants['variant']
  size?: ButtonVariants['size']
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
  size: 'default',
})
</script>
