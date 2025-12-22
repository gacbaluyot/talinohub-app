<template>
  <aside class="w-64 bg-white border-r border-gray-200 min-h-screen sticky top-16">
    <nav class="px-3 py-6 space-y-1">
      <a
        v-for="item in navigation"
        :key="item.name"
        :href="item.href"
        @click.prevent="handleNavigate(item.href)"
        :class="[
          item.current
            ? 'bg-indigo-50 text-indigo-600'
            : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600',
          'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors'
        ]"
      >
        <component
          :is="item.icon"
          :class="[
            item.current ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600',
            'mr-3 flex-shrink-0 h-5 w-5'
          ]"
        />
        {{ item.name }}
        <span
          v-if="item.badge"
          :class="[
            item.current ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600',
            'ml-auto inline-block py-0.5 px-2 text-xs rounded-full'
          ]"
        >
          {{ item.badge }}
        </span>
      </a>
    </nav>

    <!-- Quick Actions -->
    <div class="px-3 py-6 border-t border-gray-200">
      <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
        Quick Actions
      </h3>
      <div class="space-y-2">
        <button
          @click="emit('create-course')"
          class="w-full flex items-center px-3 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors"
        >
          <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          New Course
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { ref, h } from 'vue'

const emit = defineEmits(['create-course'])

// Navigation items
const navigation = ref([
  {
    name: 'Dashboard',
    href: '/educator',
    current: true,
    icon: h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' })
    ])
  },
  {
    name: 'My Courses',
    href: '/educator/courses',
    current: false,
    icon: h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' })
    ])
  },
  {
    name: 'Students',
    href: '#students',
    current: false,
    icon: h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' })
    ])
  },
  {
    name: 'Analytics',
    href: '#analytics',
    current: false,
    icon: h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' })
    ])
  },
  {
    name: 'Settings',
    href: '#settings',
    current: false,
    icon: h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' }),
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z' })
    ])
  }
])

const handleNavigate = (href: string) => {
  navigation.value.forEach(item => {
    item.current = item.href === href
  })
  navigateTo(href)
}
</script>
