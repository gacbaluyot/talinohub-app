<template>
  <div :class="cn(badgeVariants({ variant }), props.class)">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { cva, type VariantProps } from 'class-variance-authority'
import { cn } from '~/lib/utils'

const badgeVariants = cva(
  'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2',
  {
    variants: {
      variant: {
        default: 'border-transparent bg-indigo-600 text-white shadow hover:bg-indigo-700',
        secondary: 'border-transparent bg-surface-100 dark:bg-surface-800 text-surface-900 dark:text-surface-0 hover:bg-surface-200 dark:hover:bg-surface-700',
        destructive: 'border-transparent bg-red-600 text-white shadow hover:bg-red-700',
        outline: 'border-surface-200 dark:border-surface-700 text-surface-900 dark:text-surface-0',
        success: 'border-green-600 dark:border-green-800 bg-green-600 dark:bg-green-900/30 text-white dark:text-green-400',
        warning: 'border-yellow-600 dark:border-yellow-800 bg-yellow-500 dark:bg-yellow-900/30 text-white dark:text-yellow-400',
        info: 'border-blue-600 dark:border-blue-800 bg-blue-600 dark:bg-blue-900/30 text-white dark:text-blue-400',
      },
    },
    defaultVariants: {
      variant: 'default',
    },
  }
)

type BadgeVariants = VariantProps<typeof badgeVariants>

interface Props {
  variant?: BadgeVariants['variant']
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
})
</script>
