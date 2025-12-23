<template>
  <header class="bg-background sticky top-0 z-30">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-14">
        <!-- Left: Logo and Title -->
        <div class="flex items-center">
          <h1 class="text-xl font-bold text-primary">TalinoHub</h1>
        </div>

        <!-- Right: User menu -->
        <div class="flex items-center space-x-4">

          <!-- Notifications -->
          <button class="p-2 text-muted-foreground hover:text-foreground rounded-lg hover:bg-accent transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
          </button>

          <!-- User dropdown -->
          <Menu as="div" class="relative">
            <MenuButton class="flex items-center space-x-2 focus:outline-none hover:bg-accent rounded-lg px-2 py-1.5 transition-colors">
              <div class="flex items-center space-x-2">
                <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center">
                  <span class="text-xs font-medium text-primary-foreground">{{ userInitials }}</span>
                </div>
                <div class="hidden md:block text-left">
                  <p class="text-sm font-medium text-foreground">{{ userName }}</p>
                  <p class="text-xs text-muted-foreground">{{ userRole }}</p>
                </div>
              </div>
              <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </MenuButton>

            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <MenuItems class="absolute right-0 mt-2 w-48 origin-top-right rounded-lg bg-popover p-1 shadow-lg ring-1 ring-border focus:outline-none">
                <MenuItem v-slot="{ active }">
                  <a
                    href="#"
                    @click.prevent="navigateTo('/educator/profile')"
                    :class="[active ? 'bg-accent' : '', 'block px-3 py-2 text-sm text-popover-foreground rounded-md']"
                  >
                    Your Profile
                  </a>
                </MenuItem>
                <MenuItem v-slot="{ active }">
                  <a href="#" :class="[active ? 'bg-accent' : '', 'block px-3 py-2 text-sm text-popover-foreground rounded-md']">
                    Settings
                  </a>
                </MenuItem>
                <MenuItem v-slot="{ active }">
                  <button
                    @click="handleLogout"
                    :class="[active ? 'bg-accent' : '', 'block w-full text-left px-3 py-2 text-sm text-popover-foreground rounded-md']"
                  >
                    Sign out
                  </button>
                </MenuItem>
              </MenuItems>
            </transition>
          </Menu>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { useAuthStore } from '~/stores/auth'
import ThemeToggle from '~/components/ThemeToggle.vue'

const authStore = useAuthStore()

const userName = computed(() => authStore.user?.name || 'User')
const userRole = computed(() => {
  const role = authStore.primaryRole
  return role ? role.charAt(0).toUpperCase() + role.slice(1) : 'User'
})
const userInitials = computed(() => {
  const name = authStore.user?.name || 'U'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const handleLogout = async () => {
  await authStore.logout()
  await navigateTo('/auth/login', { replace: true })
}
</script>
