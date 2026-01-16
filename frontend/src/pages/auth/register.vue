<template>
  <div class="min-h-screen flex">
    <!-- Left side - Form -->
    <div class="flex-1 flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-20 xl:px-24 bg-white">
      <div class="mx-auto w-full max-w-sm lg:w-96">
        <div>
          <h2 class="text-3xl font-bold tracking-tight text-surface-900 dark:text-surface-0">
            Create your account
          </h2>
          <p class="mt-2 text-sm text-surface-600 dark:text-surface-400">
            Already have an account?
            <NuxtLink to="/auth/login" class="font-semibold text-indigo-600 hover:text-indigo-500">
              Sign in
            </NuxtLink>
          </p>
        </div>

        <div class="mt-10">
          <form @submit.prevent="handleRegister" class="space-y-6">
            <!-- Name Input -->
            <div>
              <label for="name" class="block text-sm font-medium text-surface-900 dark:text-surface-0">
                Full Name
              </label>
              <div class="mt-2">
                <input
                  v-model="name"
                  id="name"
                  name="name"
                  type="text"
                  required
                  class="block w-full rounded-md border-0 py-2 px-3 text-surface-900 dark:text-surface-0 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-surface-400 dark:text-surface-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                  placeholder="John Doe"
                />
              </div>
            </div>

            <!-- Email Input -->
            <div>
              <label for="email" class="block text-sm font-medium text-surface-900 dark:text-surface-0">
                Email address
              </label>
              <div class="mt-2">
                <input
                  v-model="email"
                  id="email"
                  name="email"
                  type="email"
                  autocomplete="email"
                  required
                  class="block w-full rounded-md border-0 py-2 px-3 text-surface-900 dark:text-surface-0 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-surface-400 dark:text-surface-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                  placeholder="you@example.com"
                />
              </div>
            </div>

            <!-- Address Input -->
            <div>
              <label for="address" class="block text-sm font-medium text-surface-900 dark:text-surface-0">
                Address
              </label>
              <div class="mt-2">
                <input
                  v-model="address"
                  id="address"
                  name="address"
                  type="text"
                  required
                  class="block w-full rounded-md border-0 py-2 px-3 text-surface-900 dark:text-surface-0 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-surface-400 dark:text-surface-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                  placeholder="123 Main St, City, State"
                />
              </div>
            </div>

            <!-- Password Input -->
            <div>
              <label for="password" class="block text-sm font-medium text-surface-900 dark:text-surface-0">
                Password
              </label>
              <div class="mt-2">
                <input
                  v-model="password"
                  id="password"
                  name="password"
                  type="password"
                  autocomplete="new-password"
                  required
                  class="block w-full rounded-md border-0 py-2 px-3 text-surface-900 dark:text-surface-0 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-surface-400 dark:text-surface-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                  placeholder="••••••••"
                />
              </div>
            </div>

            <!-- Password Confirmation Input -->
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-surface-900 dark:text-surface-0">
                Confirm Password
              </label>
              <div class="mt-2">
                <input
                  v-model="passwordConfirmation"
                  id="password_confirmation"
                  name="password_confirmation"
                  type="password"
                  autocomplete="new-password"
                  required
                  class="block w-full rounded-md border-0 py-2 px-3 text-surface-900 dark:text-surface-0 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-surface-400 dark:text-surface-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                  placeholder="••••••••"
                />
              </div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="rounded-md bg-red-50 p-4">
              <div class="flex">
                <div class="ml-3">
                  <p class="text-sm font-medium text-red-800">{{ error }}</p>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div>
              <Button
                type="submit"
                :disabled="loading"
                class="w-full"
              >
                {{ loading ? 'Creating account...' : 'Create account' }}
              </Button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Right side - Image -->
    <div class="relative hidden w-0 flex-1 lg:block">
      <img
        class="absolute inset-0 h-full w-full object-cover"
        src="https://images.unsplash.com/photo-1496917756835-20cb06e75b4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1908&q=80"
        alt="Office workspace"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useAuthStore } from '~/stores/auth'
import type { RegisterRequest } from '~/types/auth'
import Button from '~/components/ui/Button.vue'

definePageMeta({
  middleware: 'guest',
  layout: false
})

const authStore = useAuthStore()

// Reactive state
const name = ref<string>('')
const email = ref<string>('')
const address = ref<string>('')
const password = ref<string>('')
const passwordConfirmation = ref<string>('')

// Computed from store
const loading = computed(() => authStore.loading)
const error = computed(() => authStore.error)

// Register handler
const handleRegister = async (): Promise<void> => {
  authStore.clearError()
  
  // Validate password match
  if (password.value !== passwordConfirmation.value) {
    authStore.error = 'Passwords do not match'
    return
  }
  
  try {
    const credentials: RegisterRequest = {
      name: name.value,
      email: email.value,
      address: address.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value
    }

    await authStore.register(credentials)
    
    // Redirect based on user role (new users get 'student' role by default)
    const primaryRole = authStore.primaryRole
    
    let redirectPath = '/student' // Default for new registrations
    
    if (primaryRole === 'admin') {
      redirectPath = '/admin'
    } else if (primaryRole === 'educator') {
      redirectPath = '/educator'
    } else if (primaryRole === 'student') {
      redirectPath = '/student'
    }
    
    // Redirect to appropriate dashboard (replace to prevent back)
    await navigateTo(redirectPath, { replace: true })
    
  } catch (err) {
    // Error is already handled in the store
    console.error('Registration error:', err)
  }
}
</script>