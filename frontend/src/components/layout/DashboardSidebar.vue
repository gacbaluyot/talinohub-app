<template>
  <aside
    :class="[
      'bg-surface-0 dark:bg-surface-900 border-r border-surface-200 dark:border-surface-700 min-h-screen sticky top-16 transition-all duration-300 ease-in-out',
      isCollapsed ? 'w-20' : 'w-64'
    ]"
  >
    <!-- Navigation Menu -->
    <nav class="p-4">
      <div class="space-y-1">
        <template v-for="(item, index) in menuItems" :key="index">
          <!-- Separator -->
          <div v-if="item.separator" class="my-3 border-t border-surface-200 dark:border-surface-700"></div>

          <!-- Menu Item -->
          <a
            v-else
            href="#"
            @click.prevent="item.command"
            :class="[
              'flex items-center cursor-pointer rounded-lg transition-all duration-200',
              item.active
                ? 'bg-indigo-600 text-white font-semibold'
                : 'text-surface-700 dark:text-surface-200 hover:bg-surface-100 dark:hover:bg-surface-800',
              isCollapsed ? 'justify-center px-2 py-3' : 'px-4 py-3 gap-3'
            ]"
            :title="isCollapsed ? item.label : undefined"
          >
            <!-- Icons -->
            <svg v-if="item.icon === 'home'" xmlns="http://www.w3.org/2000/svg" :width="isCollapsed ? 20 : 18" :height="isCollapsed ? 20 : 18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
              <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <svg v-else-if="item.icon === 'book'" xmlns="http://www.w3.org/2000/svg" :width="isCollapsed ? 20 : 18" :height="isCollapsed ? 20 : 18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
              <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
            <svg v-else-if="item.icon === 'users'" xmlns="http://www.w3.org/2000/svg" :width="isCollapsed ? 20 : 18" :height="isCollapsed ? 20 : 18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <svg v-else-if="item.icon === 'chart-bar'" xmlns="http://www.w3.org/2000/svg" :width="isCollapsed ? 20 : 18" :height="isCollapsed ? 20 : 18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="20" x2="12" y2="10"></line>
              <line x1="18" y1="20" x2="18" y2="4"></line>
              <line x1="6" y1="20" x2="6" y2="16"></line>
            </svg>
            <svg v-else-if="item.icon === 'comments'" xmlns="http://www.w3.org/2000/svg" :width="isCollapsed ? 20 : 18" :height="isCollapsed ? 20 : 18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <svg v-else-if="item.icon === 'cog'" xmlns="http://www.w3.org/2000/svg" :width="isCollapsed ? 20 : 18" :height="isCollapsed ? 20 : 18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>

            <span
              v-if="!isCollapsed"
              class="flex-1"
            >
              {{ item.label }}
            </span>
            <Badge
              v-if="item.badge && !isCollapsed"
              :variant="item.active ? 'default' : 'secondary'"
              class="ml-auto"
            >
              {{ item.badge }}
            </Badge>
          </a>
        </template>
      </div>
    </nav>

    <!-- Quick Actions (only when expanded) -->
    <transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 transform scale-95"
      enter-to-class="opacity-100 transform scale-100"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 transform scale-100"
      leave-to-class="opacity-0 transform scale-95"
    >
      <div v-if="!isCollapsed" class="px-4 py-4 border-t border-surface-200 dark:border-surface-700">
        <h3 class="px-2 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-3">
          Quick Actions
        </h3>
        <div class="space-y-2">
          <Button
            @click="emit('create-course')"
            class="w-full justify-center"
            variant="outline"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            New Course
          </Button>
        </div>
      </div>
    </transition>

    <!-- Stats Card (only when expanded) -->
    <transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 transform scale-95"
      enter-to-class="opacity-100 transform scale-100"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 transform scale-100"
      leave-to-class="opacity-0 transform scale-95"
    >
      <div v-if="!isCollapsed" class="px-4 py-4">
        <Card class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white border-0 shadow-lg">
          <CardContent class="p-6">
            <div class="text-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 opacity-90">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
              </svg>
              <h4 class="text-lg font-semibold mb-1">Your Progress</h4>
              <p class="text-sm opacity-90 mb-4">Track your teaching journey</p>
              <Button
                variant="outline"
                size="sm"
                class="w-full border-white text-white hover:bg-white/10"
                @click="navigateTo('/educator/analytics')"
              >
                View Analytics
              </Button>
            </div>
          </CardContent>
        </Card>
      </div>
    </transition>

    <!-- Compact Stats (only when collapsed) -->
    <transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="isCollapsed" class="px-2 py-4 border-t border-surface-200 dark:border-surface-700 space-y-2">
        <Button
          variant="outline"
          size="icon"
          class="w-full"
          @click="emit('create-course')"
          :title="'New Course'"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
        </Button>
        <Button
          variant="ghost"
          size="icon"
          class="w-full"
          @click="navigateTo('/educator/analytics')"
          :title="'Analytics'"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
          </svg>
        </Button>
      </div>
    </transition>
  </aside>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Button from '~/components/ui/Button.vue'
import Badge from '~/components/ui/Badge.vue'
import Card from '~/components/ui/Card.vue'
import CardContent from '~/components/ui/CardContent.vue'

const props = defineProps<{
  isCollapsed?: boolean
}>()

const emit = defineEmits(['create-course', 'update:isCollapsed'])
const route = useRoute()

const menuItems = computed(() => [
  {
    label: 'Dashboard',
    shortLabel: 'Home',
    icon: 'home',
    active: route.path === '/educator',
    command: () => navigateTo('/educator')
  },
  {
    label: 'My Courses',
    shortLabel: 'Courses',
    icon: 'book',
    active: route.path.startsWith('/educator/courses'),
    badge: '12',
    command: () => navigateTo('/educator/courses')
  },
  {
    separator: true
  },
  {
    label: 'Students',
    icon: 'users',
    active: route.path === '/educator/students',
    badge: '248',
    command: () => navigateTo('/educator/students')
  },
  {
    label: 'Analytics',
    icon: 'chart-bar',
    active: route.path === '/educator/analytics',
    command: () => navigateTo('/educator/analytics')
  },
  {
    label: 'Messages',
    icon: 'comments',
    active: route.path === '/educator/messages',
    badge: '3',
    command: () => navigateTo('/educator/messages')
  },
  {
    separator: true
  },
  {
    label: 'Settings',
    icon: 'cog',
    active: route.path === '/educator/settings',
    command: () => navigateTo('/educator/settings')
  }
])

const toggleSidebar = () => {
  emit('update:isCollapsed', !props.isCollapsed)
}
</script>
