<template>
  <aside
    :class="[
      expanded ? 'w-64' : 'w-20',
      'bg-background border-r border-border min-h-screen sticky top-16 hidden md:block transition-all duration-300'
    ]"
  >
    <nav class="px-3 py-6 space-y-1">
      <a
        v-for="item in items"
        :key="item.id"
        href="#"
        @click.prevent="$emit('navigate', item.id)"
        :class="[
          currentView === item.id
            ? 'bg-primary/10 text-primary'
            : 'text-foreground hover:bg-accent hover:text-accent-foreground',
          'group flex flex-col items-center py-4 text-xs font-medium rounded-lg transition-colors',
          expanded && 'flex-row px-3 py-3 text-sm'
        ]"
      >
        <component :is="item.icon" :class="[expanded ? 'mr-3 h-5 w-5' : 'h-6 w-6 mb-1']" />
        <span :class="{ 'text-center': !expanded }">{{ item.name }}</span>
      </a>
    </nav>
  </aside>
</template>

<script setup lang="ts">
defineProps<{
  expanded: boolean
  currentView: string
  items: Array<{ id: string; name: string; icon: any }>
}>()

defineEmits<{
  'navigate': [view: string]
}>()
</script>
