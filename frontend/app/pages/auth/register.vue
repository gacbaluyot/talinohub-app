<template>
  <div class="min-h-screen flex">
    <!-- Left side - Form -->
    <div class="flex-1 flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-20 xl:px-24 bg-white">
      <div class="mx-auto w-full max-w-sm lg:w-96">
        <div>
          <h2 class="text-3xl font-bold tracking-tight text-gray-900">
            Create your account
          </h2>
          <p class="mt-2 text-sm text-gray-600">
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
              <label for="name" class="block text-sm font-medium text-gray-900">
                Full Name
              </label>
              <div class="mt-2">
                <input
                  v-model="name"
                  id="name"
                  name="name"
                  type="text"
                  required
                  class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                  placeholder="John Doe"
                />
              </div>
            </div>

            <!-- Email Input -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-900">
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
                  class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                  placeholder="you@example.com"
                />
              </div>
            </div>

            <!-- Address Input -->
            <div>
              <label for="address" class="block text-sm font-medium text-gray-900">
                Address
              </label>
              <div class="mt-2">
                <input
                  v-model="address"
                  id="address"
                  name="address"
                  type="text"
                  required
                  class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                  placeholder="123 Main St, City, State"
                />
              </div>
            </div>

            <!-- Password Input -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-900">
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
                  class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                  placeholder="••••••••"
                />
              </div>
            </div>

            <!-- Password Confirmation Input -->
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-900">
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
                  class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
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
              <button
                type="submit"
                :disabled="loading"
                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ loading ? 'Creating account...' : 'Create account' }}
              </button>
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
import type { RegisterRequest } from '~/models/auth'

definePageMeta({
  middleware: 'guest'
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
    
    // Redirect to dashboard on success (replace to prevent back)
    await navigateTo('/dashboard', { replace: true })
    
  } catch (err) {
    // Error is already handled in the store
    console.error('Registration error:', err)
  }
}
</script>