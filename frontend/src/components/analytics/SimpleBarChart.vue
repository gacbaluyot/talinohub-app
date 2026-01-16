<template>
  <div class="bg-surface-0 dark:bg-surface-900 border border-surface-200 dark:border-surface-700 rounded-lg shadow-sm p-4">
    <h3 class="text-base font-semibold text-surface-900 dark:text-surface-0 mb-3">{{ title }}</h3>
    <div class="space-y-2.5">
      <div v-for="(item, index) in data" :key="index" class="flex items-center">
        <div class="w-24 text-xs text-surface-600 dark:text-surface-400 truncate">{{ item.label }}</div>
        <div class="flex-1 mx-3">
          <div class="bg-surface-200 dark:bg-surface-700 rounded-full h-3 overflow-hidden">
            <div
              :class="[barColor, 'h-full rounded-full transition-all duration-500']"
              :style="{ width: `${(item.value / maxValue) * 100}%` }"
            />
          </div>
        </div>
        <div class="w-12 text-xs font-medium text-surface-900 dark:text-surface-0 text-right">{{ item.value }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface BarData {
  label: string
  value: number
}

interface Props {
  title: string
  data: BarData[]
  barColor?: string
}

const props = withDefaults(defineProps<Props>(), {
  barColor: 'bg-indigo-500'
})

const maxValue = computed(() => Math.max(...props.data.map(d => d.value)))
</script>
