<template>
  <header class="bg-surface-0 dark:bg-surface-900 sticky top-0 z-40 border-b border-surface-200 dark:border-surface-700">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Left: Logo and Title -->
        <div class="flex items-center gap-4">
          <!-- Sidebar Toggle -->
          <Button
            @click="$emit('toggle-sidebar')"
            variant="ghost"
            size="icon"
            aria-label="Toggle Sidebar"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </Button>

          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
              </svg>
            </div>
            <div class="hidden sm:block">
              <h1 class="text-xl font-bold bg-gradient-to-r from-primary-600 to-primary-500 bg-clip-text text-transparent">
                TalinoHub
              </h1>
              <p class="text-xs text-surface-500 dark:text-surface-400">Educator Dashboard</p>
            </div>
          </div>
        </div>

        <!-- Center: Search -->
        <div class="hidden md:flex flex-1 max-w-md mx-8">
          <div class="relative w-full">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-surface-400">
              <circle cx="11" cy="11" r="8"></circle>
              <path d="m21 21-4.35-4.35"></path>
            </svg>
            <Input
              v-model="searchQuery"
              placeholder="Search courses, students..."
              class="pl-9 w-full"
            />
          </div>
        </div>

        <!-- Right: Actions and User menu -->
        <div class="flex items-center gap-3">
          <!-- Theme Toggle -->
          <ThemeToggle />

          <!-- Notifications -->
          <div class="relative">
            <Button
              variant="ghost"
              size="icon"
              @click="toggleNotifications"
              class="relative"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
              </svg>
              <span class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-600 text-white text-xs flex items-center justify-center font-semibold">3</span>
            </Button>

            <!-- Notification Menu -->
            <Menu as="div" class="absolute right-0 mt-2 w-80 origin-top-right z-50" v-show="showNotifications">
              <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
              >
                <MenuItems class="rounded-lg bg-surface-0 dark:bg-surface-900 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden border border-surface-200 dark:border-surface-700">
                  <div class="px-4 py-3 border-b border-surface-200 dark:border-surface-700">
                    <h3 class="text-sm font-semibold text-surface-900 dark:text-surface-0">Notifications</h3>
                  </div>
                  <div class="max-h-96 overflow-y-auto">
                    <MenuItem v-for="(item, index) in notificationItems" :key="index" v-slot="{ active }">
                      <div :class="[active ? 'bg-surface-50 dark:bg-surface-800' : '', 'flex items-start gap-3 px-4 py-3 border-b border-surface-100 dark:border-surface-800 last:border-0']">
                        <div :class="['w-8 h-8 rounded-full flex items-center justify-center text-white text-sm']" :style="{ backgroundColor: item.color }">
                          <svg v-if="item.icon === 'user-plus'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <line x1="19" y1="8" x2="19" y2="14"></line>
                            <line x1="22" y1="11" x2="16" y2="11"></line>
                          </svg>
                          <svg v-else-if="item.icon === 'star'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                          </svg>
                          <svg v-else-if="item.icon === 'dollar'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                          </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                          <p class="text-sm font-medium text-surface-900 dark:text-surface-0">{{ item.label }}</p>
                          <p class="text-xs text-surface-500">{{ item.time }}</p>
                        </div>
                      </div>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>
          </div>

          <!-- User Menu -->
          <Menu as="div" class="relative">
            <MenuButton as="template">
              <Button
                variant="ghost"
                class="flex items-center gap-2 px-3"
              >
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-semibold">
                  {{ userInitials }}
                </div>
                <div class="hidden lg:block text-left">
                  <p class="text-sm font-semibold text-surface-900 dark:text-surface-0">{{ userName }}</p>
                  <p class="text-xs text-surface-500">{{ userRole }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-surface-500">
                  <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
              </Button>
            </MenuButton>

            <transition
              enter-active-class="transition duration-100 ease-out"
              enter-from-class="transform scale-95 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-75 ease-in"
              leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0"
            >
              <MenuItems class="absolute right-0 mt-2 w-72 origin-top-right rounded-lg bg-surface-0 dark:bg-surface-900 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden border border-surface-200 dark:border-surface-700 z-50">
                <!-- User Info -->
                <div class="px-4 py-3 border-b border-surface-200 dark:border-surface-700">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-semibold">
                      {{ userInitials }}
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="font-semibold text-surface-900 dark:text-surface-0 truncate">{{ userName }}</p>
                      <p class="text-sm text-surface-500 truncate">{{ authStore.user?.email }}</p>
                      <Badge variant="info" class="mt-1">{{ userRole }}</Badge>
                    </div>
                  </div>
                </div>

                <!-- Menu Items -->
                <div class="py-1">
                  <MenuItem v-for="(item, index) in userMenuItems" :key="index" v-slot="{ active }">
                    <button
                      :class="[
                        active ? 'bg-surface-100 dark:bg-surface-800' : '',
                        'flex items-center gap-3 w-full px-4 py-2 text-sm text-surface-900 dark:text-surface-0 transition-colors'
                      ]"
                      @click="item.command"
                    >
                      <svg v-if="item.icon === 'user'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-surface-600 dark:text-surface-400">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                      </svg>
                      <svg v-else-if="item.icon === 'cog'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-surface-600 dark:text-surface-400">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                      <svg v-else-if="item.icon === 'book'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-surface-600 dark:text-surface-400">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                      </svg>
                      <svg v-else-if="item.icon === 'question-circle'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-surface-600 dark:text-surface-400">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                      </svg>
                      <span>{{ item.label }}</span>
                    </button>
                  </MenuItem>
                </div>

                <!-- Sign Out -->
                <div class="border-t border-surface-200 dark:border-surface-700">
                  <MenuItem v-slot="{ active }">
                    <button
                      :class="[
                        active ? 'bg-red-50 dark:bg-red-900/20' : '',
                        'flex items-center gap-3 w-full px-4 py-3 text-sm text-red-600 transition-colors font-medium'
                      ]"
                      @click="handleLogout"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                      </svg>
                      <span>Sign Out</span>
                    </button>
                  </MenuItem>
                </div>
              </MenuItems>
            </transition>
          </Menu>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { useAuthStore } from '~/stores/auth'
import ThemeToggle from '~/components/ThemeToggle.vue'
import Button from '~/components/ui/Button.vue'
import Badge from '~/components/ui/Badge.vue'
import Input from '~/components/ui/Input.vue'

defineEmits(['toggle-sidebar'])

const authStore = useAuthStore()

const searchQuery = ref('')
const showNotifications = ref(false)

const userName = computed(() => authStore.user?.name || 'User')
const userRole = computed(() => {
  const role = authStore.primaryRole
  return role ? role.charAt(0).toUpperCase() + role.slice(1) : 'User'
})
const userInitials = computed(() => {
  const name = authStore.user?.name || 'U'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const notificationItems = ref([
  {
    label: 'New student enrolled',
    icon: 'user-plus',
    color: '#22c55e',
    time: '5 minutes ago'
  },
  {
    label: 'Course review received',
    icon: 'star',
    color: '#f59e0b',
    time: '1 hour ago'
  },
  {
    label: 'Payment received',
    icon: 'dollar',
    color: '#3b82f6',
    time: '3 hours ago'
  }
])

const userMenuItems = ref([
  {
    label: 'Your Profile',
    icon: 'user',
    command: () => navigateTo('/educator/profile')
  },
  {
    label: 'Account Settings',
    icon: 'cog',
    command: () => navigateTo('/educator/settings')
  },
  {
    label: 'My Courses',
    icon: 'book',
    command: () => navigateTo('/educator/courses')
  },
  {
    label: 'Help & Support',
    icon: 'question-circle',
    command: () => navigateTo('/help')
  }
])

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value
}

const handleLogout = async () => {
  await authStore.logout()
  await navigateTo('/auth/login', { replace: true })
}
</script>
