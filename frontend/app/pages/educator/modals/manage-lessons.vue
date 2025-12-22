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
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl sm:p-6">
              <div>
                <div class="flex items-center justify-between mb-6">
                  <div>
                    <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900">
                      Manage Lessons
                    </DialogTitle>
                    <p class="mt-1 text-sm text-gray-500">{{ course?.title }}</p>
                  </div>
                  <button
                    type="button"
                    @click="handleAddLesson"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                  >
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Add Lesson
                  </button>
                </div>

                <!-- Loading State -->
                <div v-if="coursesStore.loading" class="text-center py-12">
                  <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
                  <p class="mt-2 text-sm text-gray-600">Loading lessons...</p>
                </div>

                <!-- Error State -->
                <div v-else-if="coursesStore.error" class="rounded-md bg-red-50 p-4">
                  <p class="text-sm text-red-800">{{ coursesStore.error }}</p>
                </div>

                <!-- Empty State -->
                <div v-else-if="coursesStore.lessons.length === 0" class="text-center py-12 bg-gray-50 rounded-lg">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                  <h3 class="mt-2 text-sm font-semibold text-gray-900">No lessons</h3>
                  <p class="mt-1 text-sm text-gray-500">Get started by creating a new lesson.</p>
                  <div class="mt-6">
                    <button
                      @click="handleAddLesson"
                      type="button"
                      class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                    >
                      <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                      </svg>
                      Add First Lesson
                    </button>
                  </div>
                </div>

                <!-- Lessons List -->
                <div v-else class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Position</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Title</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Preview</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                          <span class="sr-only">Actions</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                      <tr v-for="lesson in coursesStore.lessons" :key="lesson.id" class="hover:bg-gray-50">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                          {{ lesson.position }}
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                          <div class="flex items-center">
                            <div class="h-8 w-8 shrink-0 rounded bg-indigo-100 flex items-center justify-center mr-3">
                              <component :is="getLessonIcon(lesson.type)" class="h-4 w-4 text-indigo-600" />
                            </div>
                            <span class="font-medium">{{ lesson.title }}</span>
                          </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700">
                            {{ lesson.type }}
                          </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                          <span
                            :class="[
                              'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium',
                              lesson.is_preview ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'
                            ]"
                          >
                            {{ lesson.is_preview ? 'Yes' : 'No' }}
                          </span>
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                          <div class="flex items-center justify-end gap-2">
                            <button
                              @click="handleEditLesson(lesson.id)"
                              class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-indigo-600 shadow-sm ring-1 ring-inset ring-indigo-300 hover:bg-indigo-50"
                              title="Edit"
                            >
                              <PencilSquareIcon class="h-4 w-4" />
                            </button>
                            <button
                              @click="handleDeleteLesson(lesson.id)"
                              class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50"
                              title="Delete"
                            >
                              <TrashIcon class="h-4 w-4" />
                            </button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Close Button -->
                <div class="mt-5 sm:mt-6">
                  <button
                    type="button"
                    @click="handleClose"
                    class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                  >
                    Close
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Lesson Modals -->
  <CreateLessonModal
    :show="showCreateLessonModal"
    :course-id="course?.id || null"
    @close="showCreateLessonModal = false"
    @created="handleLessonCreated"
  />

  <UpdateLessonModal
    :show="showUpdateLessonModal"
    :lesson-id="selectedLessonId"
    @close="showUpdateLessonModal = false"
    @updated="handleLessonUpdated"
  />

  <DeleteLessonModal
    :show="showDeleteLessonModal"
    :lesson-id="selectedLessonId"
    @close="showDeleteLessonModal = false"
    @deleted="handleLessonDeleted"
  />
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { VideoCameraIcon, DocumentTextIcon, BookOpenIcon } from '@heroicons/vue/24/solid'
import { useCoursesStore } from '~/stores/courses'
import type { Course } from '~/types/courses'
import CreateLessonModal from './create-lesson.vue'
import UpdateLessonModal from './update-lesson.vue'
import DeleteLessonModal from './delete-lesson.vue'

const props = defineProps<{
  show: boolean
  course: Course | null
}>()

const emit = defineEmits<{
  close: []
}>()

const coursesStore = useCoursesStore()

const showCreateLessonModal = ref(false)
const showUpdateLessonModal = ref(false)
const showDeleteLessonModal = ref(false)
const selectedLessonId = ref<number | null>(null)

const getLessonIcon = (type: string) => {
  switch (type) {
    case 'video':
      return VideoCameraIcon
    case 'document':
      return DocumentTextIcon
    case 'text':
      return BookOpenIcon
    default:
      return BookOpenIcon
  }
}

const loadLessons = async () => {
  if (props.course) {
    await coursesStore.fetchLessonsByCourse(props.course.id)
  }
}

const handleAddLesson = () => {
  showCreateLessonModal.value = true
}

const handleEditLesson = (lessonId: number) => {
  selectedLessonId.value = lessonId
  showUpdateLessonModal.value = true
}

const handleDeleteLesson = (lessonId: number) => {
  selectedLessonId.value = lessonId
  showDeleteLessonModal.value = true
}

const handleLessonCreated = () => {
  showCreateLessonModal.value = false
  loadLessons()
}

const handleLessonUpdated = () => {
  showUpdateLessonModal.value = false
  loadLessons()
}

const handleLessonDeleted = () => {
  showDeleteLessonModal.value = false
}

const handleClose = () => {
  emit('close')
}

watch(() => props.show, (newVal) => {
  if (newVal) {
    loadLessons()
  }
})
</script>
