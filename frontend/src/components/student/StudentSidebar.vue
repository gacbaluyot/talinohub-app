<template>
  <aside
    :class="[
      'bg-surface-0 dark:bg-surface-900 border-r border-surface-200 dark:border-surface-700 min-h-screen sticky top-16 transition-all duration-300 ease-in-out',
      expanded ? 'w-64' : 'w-20'
    ]"
  >
    <nav class="p-4">
      <div class="space-y-2">
        <a
          v-for="item in items"
          :key="item.id"
          href="#"
          @click.prevent="$emit('navigate', item.id)"
          :class="[
            'flex items-center rounded-lg transition-all duration-200 cursor-pointer',
            currentView === item.id
              ? 'bg-primary text-primary-contrast font-semibold shadow-sm'
              : 'text-surface-700 dark:text-surface-200 hover:bg-surface-100 dark:hover:bg-surface-800',
            expanded ? 'px-4 py-3 gap-3' : 'flex-col px-2 py-4 gap-1.5'
          ]"
        >
          <component
            :is="item.icon"
            :class="[
              'flex-shrink-0',
              expanded ? 'w-5 h-5' : 'w-6 h-6'
            ]"
          />
          <span
            :class="[
              'transition-all duration-200',
              expanded ? 'text-sm' : 'text-xs text-center',
              !expanded && 'leading-tight'
            ]"
          >
            {{ item.name }}
          </span>
        </a>
      </div>

      <!-- Toggle Button -->
      <div class="mt-6 pt-6 border-t border-surface-200 dark:border-surface-700">
        <Button
          @click="$emit('update:expanded', !expanded)"
          variant="ghost"
          :class="[
            'w-full transition-all',
            !expanded && 'justify-center'
          ]"
        >
          <svg v-if="expanded" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
          </svg>
          <span v-if="expanded" class="ml-2">Collapse</span>
        </Button>
      </div>

      <!-- User Stats Card (only when expanded) -->
      <transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 transform scale-95"
        enter-to-class="opacity-100 transform scale-100"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 transform scale-100"
        leave-to-class="opacity-0 transform scale-95"
      >
        <Card v-if="expanded" class="mt-6 bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-950 dark:to-primary-900 border-0">
          <CardContent class="space-y-3 p-4">
            <div class="flex items-center justify-between">
              <span class="text-xs font-medium text-surface-600 dark:text-surface-400">Learning Streak</span>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-amber-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
              </svg>
            </div>
            <div class="flex items-baseline gap-1">
              <span class="text-2xl font-bold text-surface-900 dark:text-surface-0">7</span>
              <span class="text-sm text-surface-600 dark:text-surface-400">days</span>
            </div>
            <div class="h-1.5 w-full bg-surface-200 dark:bg-surface-700 rounded-full overflow-hidden">
              <div class="h-full bg-indigo-600 rounded-full" style="width: 70%"></div>
            </div>
            <p class="text-xs text-surface-600 dark:text-surface-400">Keep it up!</p>
          </CardContent>
        </Card>
      </transition>
    </nav>
  </aside>
</template>

<script setup lang="ts">
import Button from '~/components/ui/Button.vue'
import Card from '~/components/ui/Card.vue'
import CardContent from '~/components/ui/CardContent.vue'

defineProps<{
  expanded: boolean
  currentView: string
  items: Array<{ id: string; name: string; icon: any }>
}>()

defineEmits<{
  'navigate': [view: string]
  'update:expanded': [value: boolean]
}>()
</script>
