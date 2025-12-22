<template>
  <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ title }}</h3>
    <div class="space-y-3">
      <div v-for="(item, index) in data" :key="index" class="flex items-center">
        <div class="w-24 text-sm text-gray-600 truncate">{{ item.label }}</div>
        <div class="flex-1 mx-3">
          <div class="bg-gray-200 rounded-full h-4 overflow-hidden">
            <div
              :class="[barColor, 'h-full rounded-full transition-all duration-500']"
              :style="{ width: `${(item.value / maxValue) * 100}%` }"
            />
          </div>
        </div>
        <div class="w-16 text-sm font-medium text-gray-900 text-right">{{ item.value }}</div>
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
