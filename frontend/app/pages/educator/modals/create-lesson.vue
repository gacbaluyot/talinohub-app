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
                  Create New Lesson
                </DialogTitle>

                <form @submit.prevent="handleSubmit" class="space-y-4">
                  <!-- Title -->
                  <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Lesson Title *</label>
                    <input
                      v-model="formData.title"
                      type="text"
                      id="title"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="e.g., Introduction to HTML"
                    />
                  </div>

                  <!-- Type -->
                  <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Lesson Type *</label>
                    <select
                      v-model="formData.type"
                      id="type"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                      <option value="">Select type</option>
                      <option value="video">Video</option>
                      <option value="document">Document</option>
                      <option value="text">Text</option>
                    </select>
                  </div>

                  <!-- Video URL (if type is video) -->
                  <div v-if="formData.type === 'video'">
                    <label for="video_url" class="block text-sm font-medium text-gray-700">Video URL</label>
                    <input
                      v-model="formData.video_url"
                      type="url"
                      id="video_url"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="https://youtube.com/watch?v=..."
                    />
                  </div>

                  <!-- Document (if type is document) -->
                  <div v-if="formData.type === 'document'">
                    <label for="document" class="block text-sm font-medium text-gray-700">Document File</label>
                    <input
                      type="file"
                      id="document"
                      accept=".pdf,.doc,.docx,.ppt,.pptx"
                      @change="handleDocumentChange"
                      class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    />
                    <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX, PPT, PPTX up to 10MB</p>
                  </div>

                  <!-- Position & Duration -->
                  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                      <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                      <input
                        v-model.number="formData.position"
                        type="number"
                        id="position"
                        min="1"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Auto"
                      />
                      <p class="mt-1 text-xs text-gray-500">Leave empty for auto-positioning</p>
                    </div>

                    <div>
                      <label for="duration_seconds" class="block text-sm font-medium text-gray-700">Duration (seconds)</label>
                      <input
                        v-model.number="formData.duration_seconds"
                        type="number"
                        id="duration_seconds"
                        min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="0"
                      />
                    </div>
                  </div>

                  <!-- Is Preview -->
                  <div class="flex items-center">
                    <input
                      v-model="formData.is_preview"
                      type="checkbox"
                      id="is_preview"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    />
                    <label for="is_preview" class="ml-2 block text-sm text-gray-700">
                      Allow preview (visible to non-enrolled students)
                    </label>
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
                      <span v-if="!loading">Create Lesson</span>
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
import type { CreateLessonRequest } from '~/types/courses'

const props = defineProps<{
  show: boolean
  courseId: number | null
}>()

const emit = defineEmits<{
  close: []
  created: []
}>()

const coursesStore = useCoursesStore()

const loading = ref(false)
const error = ref<string | null>(null)

const formData = ref<CreateLessonRequest>({
  course_id: props.courseId || 0,
  title: '',
  type: '' as 'video' | 'document' | 'text',
  is_preview: false
})

const handleDocumentChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    formData.value.document = target.files[0]
  }
}

const handleSubmit = async () => {
  if (!props.courseId) {
    error.value = 'Course ID is required'
    return
  }

  loading.value = true
  error.value = null

  try {
    formData.value.course_id = props.courseId
    await coursesStore.createLesson(formData.value)
    emit('created')
    resetForm()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to create lesson'
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
    course_id: props.courseId || 0,
    title: '',
    type: '' as 'video' | 'document' | 'text',
    is_preview: false
  }
  error.value = null
}

watch(() => props.show, (newVal) => {
  if (newVal) {
    error.value = null
  }
})
</script>
