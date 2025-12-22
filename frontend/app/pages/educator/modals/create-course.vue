<template>
  <TransitionRoot :show="show" as="template">
    <Dialog as="div" class="relative z-50" @close="handleClose">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
              <div>
                <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 mb-4">
                  Create New Course
                </DialogTitle>

                <form @submit.prevent="handleSubmit" class="space-y-4">
                  <!-- Title -->
                  <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Course Title *</label>
                    <input
                      v-model="formData.title"
                      type="text"
                      id="title"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="e.g., Introduction to Web Development"
                    />
                  </div>

                  <!-- Short Description -->
                  <div>
                    <label for="short_description" class="block text-sm font-medium text-gray-700">Short Description</label>
                    <input
                      v-model="formData.short_description"
                      type="text"
                      id="short_description"
                      maxlength="500"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="Brief overview of your course"
                    />
                  </div>

                  <!-- Description -->
                  <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Full Description</label>
                    <textarea
                      v-model="formData.description"
                      id="description"
                      rows="4"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="Detailed course description..."
                    />
                  </div>

                  <!-- Category & Level -->
                  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                      <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                      <input
                        v-model="formData.category"
                        type="text"
                        id="category"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="e.g., Programming, Design"
                      />
                    </div>

                    <div>
                      <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                      <select
                        v-model="formData.level"
                        id="level"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      >
                        <option value="">Select level</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                      </select>
                    </div>
                  </div>

                  <!-- Language -->
                  <div>
                    <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                    <input
                      v-model="formData.language"
                      type="text"
                      id="language"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="e.g., English"
                    />
                  </div>

                  <!-- Price -->
                  <div>
                    <div class="flex items-center mb-2">
                      <input
                        v-model="formData.is_free"
                        type="checkbox"
                        id="is_free"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                      />
                      <label for="is_free" class="ml-2 block text-sm text-gray-700">This is a free course</label>
                    </div>
                    
                    <div v-if="!formData.is_free">
                      <label for="price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                      <input
                        v-model.number="formData.price"
                        type="number"
                        id="price"
                        step="0.01"
                        min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="0.00"
                      />
                    </div>
                  </div>

                  <!-- Thumbnail -->
                  <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700">Course Thumbnail</label>
                    <input
                      type="file"
                      id="thumbnail"
                      accept="image/*"
                      @change="handleThumbnailChange"
                      class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    />
                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                  </div>

                  <!-- Published -->
                  <div class="flex items-center">
                    <input
                      v-model="formData.is_published"
                      type="checkbox"
                      id="is_published"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    />
                    <label for="is_published" class="ml-2 block text-sm text-gray-700">Publish immediately</label>
                  </div>

                  <!-- Error Message -->
                  <div v-if="error" class="rounded-md bg-red-50 p-4">
                    <p class="text-sm text-red-800">{{ error }}</p>
                  </div>

                  <!-- Actions -->
                  <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                    <button
                      type="submit"
                      :disabled="loading"
                      class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <span v-if="!loading">Create Course</span>
                      <span v-else class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Creating...
                      </span>
                    </button>
                    <button
                      type="button"
                      @click="handleClose"
                      :disabled="loading"
                      class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      Cancel
                    </button>
                  </div>
                </form>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { useCoursesStore } from '~/stores/courses'
import { useAuthStore } from '~/stores/auth'
import type { CreateCourseRequest } from '~/types/courses'

const props = defineProps<{
  show: boolean
}>()

const emit = defineEmits<{
  close: []
  created: []
}>()

const coursesStore = useCoursesStore()
const authStore = useAuthStore()

const loading = ref(false)
const error = ref<string | null>(null)

const formData = ref<CreateCourseRequest>({
  user_id: authStore.user?.id || 0,
  title: '',
  short_description: '',
  description: '',
  is_free: true,
  price: 0,
  category: '',
  level: undefined,
  language: '',
  is_published: false
})

const handleThumbnailChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    formData.value.thumbnail = target.files[0]
  }
}

const handleSubmit = async () => {
  loading.value = true
  error.value = null

  try {
    formData.value.user_id = authStore.user?.id || 0
    await coursesStore.createCourse(formData.value)
    emit('created')
    resetForm()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to create course'
  } finally {
    loading.value = false
  }
}

const handleClose = () => {
  if (!loading.value) {
    resetForm()
    emit('close')
  }
}

const resetForm = () => {
  formData.value = {
    user_id: authStore.user?.id || 0,
    title: '',
    short_description: '',
    description: '',
    is_free: true,
    price: 0,
    category: '',
    level: undefined,
    language: '',
    is_published: false
  }
  error.value = null
}

watch(() => props.show, (newVal) => {
  if (newVal) {
    error.value = null
  }
})
</script>
