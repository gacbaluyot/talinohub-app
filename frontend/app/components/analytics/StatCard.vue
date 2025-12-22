<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
      <div class="flex-1">
        <p class="text-sm font-medium text-gray-600">{{ title }}</p>
        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ value }}</p>
        <div class="mt-2 flex items-center text-sm">
          <span
            :class="[
              trend === 'up' ? 'text-green-600' : trend === 'down' ? 'text-red-600' : 'text-gray-600',
              'flex items-center font-medium'
            ]"
          >
            <svg
              v-if="trend === 'up'"
              class="w-4 h-4 mr-1"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            <svg
              v-else-if="trend === 'down'"
              class="w-4 h-4 mr-1"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
            </svg>
            {{ changeText }}
          </span>
          <span class="ml-2 text-gray-500">{{ period }}</span>
        </div>
      </div>
      <div
        :class="[
          iconBgColor,
          'flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center'
        ]"
      >
        <component :is="icon" class="w-6 h-6 text-white" />
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
