<template>
  <div
    class="rounded-lg p-4 stat-card
           bg-surface-0 dark:bg-surface-900
           border border-surface-200 dark:border-surface-700
           shadow-sm"
  >
    <div class="flex items-center justify-between">
      <div class="flex-1">
        <p class="text-xs font-medium text-surface-600 dark:text-surface-400 stat-label">
          {{ title }}
        </p>

        <p class="mt-1 text-2xl font-semibold text-surface-900 dark:text-surface-0 stat-value">
          {{ value }}
        </p>

        <div class="mt-1 flex items-center text-xs">
          <span
            :class="[
              trend === 'up'
                ? 'text-green-600 dark:text-green-500'
                : trend === 'down'
                ? 'text-red-600 dark:text-red-500'
                : 'text-surface-600 dark:text-surface-400',
              'flex items-center font-medium'
            ]"
          >
            <svg
              v-if="trend === 'up'"
              class="w-3 h-3 mr-1"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
              />
            </svg>

            <svg
              v-else-if="trend === 'down'"
              class="w-3 h-3 mr-1"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"
              />
            </svg>

            {{ changeText }}
          </span>

          <span class="ml-2 text-surface-500 dark:text-surface-400">
            {{ period }}
          </span>
        </div>
      </div>

      <div
        :class="[
          iconBgColor,
          'flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center'
        ]"
      >
        <component :is="icon" class="w-5 h-5 text-white" />
      </div>
    </div>
  </div>
</template>


<script setup lang="ts">
import { h } from 'vue'

interface Props {
  title: string
  value: string | number
  changeText?: string
  period?: string
  trend?: 'up' | 'down' | 'neutral'
  icon: any
  iconBgColor?: string
}

withDefaults(defineProps<Props>(), {
  changeText: '',
  period: '',
  trend: 'neutral',
  iconBgColor: 'bg-indigo-500'
})
</script>
