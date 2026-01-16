<template>
  <header class="bg-surface-0 dark:bg-surface-900 sticky top-0 z-50 border-b border-surface-200 dark:border-surface-700">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Left Section: Menu Toggle, Logo, and Search -->
        <div class="flex items-center flex-1 max-w-3xl gap-4">
          <!-- Hamburger Menu -->
          <Button
            @click="$emit('toggle-sidebar')"
            variant="ghost"
            size="icon"
            aria-label="Toggle Sidebar"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
          </Button>

          <!-- Logo -->
          <NuxtLink to="/student" class="flex items-center gap-2 min-w-fit">
            <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
              </svg>
            </div>
            <span class="text-xl font-bold bg-gradient-to-r from-primary-500 to-primary-600 bg-clip-text text-transparent hidden sm:block">
              TalinoHub
            </span>
          </NuxtLink>

          <!-- Search Bar -->
          <div class="flex-1 max-w-xl relative">
            <div class="relative">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-surface-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
              <Input
                :model-value="searchQuery"
                @update:model-value="$emit('update:searchQuery', $event)"
                placeholder="Search courses..."
                class="pl-9 w-full"
              />
            </div>
          </div>
        </div>

        <!-- Right Section: Theme Toggle, Notifications, User Menu -->
        <div class="flex items-center gap-2">
          <!-- Theme Toggle -->
          <ThemeToggle />

          <!-- Notifications -->
          <div class="relative">
            <Button
              variant="ghost"
              size="icon"
              aria-label="Notifications"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
              </svg>
              <span class="absolute top-0 right-0 flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
              </span>
            </Button>
          </div>

          <!-- User Menu -->
          <Menu as="div" class="relative">
            <MenuButton>
              <Button
                variant="ghost"
                size="icon"
                aria-label="User Menu"
                class="!rounded-full"
              >
                <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-semibold">
                  {{ userInitials }}
                </div>
              </Button>
            </MenuButton>

            <Transition
              enter-active-class="transition duration-100 ease-out"
              enter-from-class="transform scale-95 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-75 ease-in"
              leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0"
            >
              <MenuItems class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-surface-200 dark:divide-surface-700 rounded-lg bg-surface-0 dark:bg-surface-900 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none border border-surface-200 dark:border-surface-700">
                <div class="px-4 py-3">
                  <p class="text-sm font-semibold text-surface-900 dark:text-surface-0">
                    {{ authStore.user?.name || 'User' }}
                  </p>
                  <p class="text-xs text-surface-600 dark:text-surface-400 mt-0.5 truncate">
                    {{ authStore.user?.email || '' }}
                  </p>
                </div>

                <div class="py-1">
                  <MenuItem v-slot="{ active }">
                    <NuxtLink
                      to="/student/profile"
                      :class="[
                        active ? 'bg-surface-100 dark:bg-surface-800' : '',
                        'flex items-center px-4 py-2 text-sm text-surface-900 dark:text-surface-0 transition-colors'
                      ]"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-3 text-surface-600 dark:text-surface-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                      </svg>
                      Your Profile
                    </NuxtLink>
                  </MenuItem>

                  <MenuItem v-slot="{ active }">
                    <NuxtLink
                      to="/student"
                      :class="[
                        active ? 'bg-surface-100 dark:bg-surface-800' : '',
                        'flex items-center px-4 py-2 text-sm text-surface-900 dark:text-surface-0 transition-colors'
                      ]"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-3 text-surface-600 dark:text-surface-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                      </svg>
                      My Learning
                    </NuxtLink>
                  </MenuItem>

                  <MenuItem v-slot="{ active }">
                    <button
                      :class="[
                        active ? 'bg-surface-100 dark:bg-surface-800' : '',
                        'flex w-full items-center px-4 py-2 text-sm text-surface-900 dark:text-surface-0 transition-colors'
                      ]"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-3 text-surface-600 dark:text-surface-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                      </svg>
                      Settings
                    </button>
                  </MenuItem>
                </div>

                <div class="py-1">
                  <MenuItem v-slot="{ active }">
                    <button
                      @click="$emit('logout')"
                      :class="[
                        active ? 'bg-surface-100 dark:bg-surface-800' : '',
                        'flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 transition-colors'
                      ]"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                      </svg>
                      Sign out
                    </button>
                  </MenuItem>
                </div>
              </MenuItems>
            </Transition>
          </Menu>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { useAuthStore } from '~/stores/auth'
import ThemeToggle from '~/components/ThemeToggle.vue'
import Button from '~/components/ui/Button.vue'
import Input from '~/components/ui/Input.vue'

defineProps<{
  searchQuery: string
}>()

defineEmits<{
  'update:searchQuery': [value: string]
  'toggle-sidebar': []
  'navigate': [view: string]
  'logout': []
}>()

const authStore = useAuthStore()

const userInitials = computed(() => {
  const name = authStore.user?.name || 'U'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})
</script>
