<template>
  <div class="min-h-screen bg-surface-50 dark:bg-surface-950 transition-colors">
    <!-- Header -->
    <DashboardHeader @toggle-sidebar="sidebarCollapsed = !sidebarCollapsed" />

    <div class="flex">
      <!-- Sidebar -->
      <DashboardSidebar
        :is-collapsed="sidebarCollapsed"
        @create-course="navigateTo('/educator')"
        @update:is-collapsed="sidebarCollapsed = $event"
      />

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <a href="#" @click.prevent="navigateTo('/educator')" class="inline-flex items-center text-sm font-medium text-surface-700 dark:text-surface-300 hover:text-indigo-600">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Dashboard
              </a>
            </li>
            <li v-if="lesson?.course_id">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-surface-400 dark:text-surface-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <a href="#" @click.prevent="navigateTo(`/educator/courses/${lesson.course_id}`)" class="ml-1 text-sm font-medium text-surface-700 dark:text-surface-300 hover:text-indigo-600 md:ml-2">
                  Course
                </a>
              </div>
            </li>
            <li aria-current="page">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-surface-400 dark:text-surface-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-surface-500 dark:text-surface-400 md:ml-2">Lesson Details</span>
              </div>
            </li>
          </ol>
        </nav>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <div class="inline-block h-10 w-10 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
          <p class="mt-3 text-sm text-surface-600 dark:text-surface-400">Loading lesson...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="rounded-md bg-red-50 p-4">
          <div class="flex">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
            </svg>
            <p class="ml-3 text-sm text-red-800">{{ error }}</p>
          </div>
        </div>

        <!-- Lesson Content -->
        <div v-else-if="lesson" class="space-y-6">
          <!-- Lesson Header -->
          <div class="bg-surface-0 dark:bg-surface-900 rounded-xl shadow-sm border border-surface-200 dark:border-surface-700 p-6 md:p-8">
            <div class="flex items-start justify-between mb-4">
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                  <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0">{{ lesson.title }}</h1>
                  <span
                    :class="[
                      'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border capitalize',
                      lesson.type === 'video' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 border-purple-300 dark:border-purple-800' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 border-blue-300 dark:border-blue-800'
                    ]"
                  >
                    {{ lesson.type }}
                  </span>
                  <span
                    v-if="lesson.is_preview"
                    class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-3 py-1 text-xs font-semibold text-emerald-800 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800"
                  >
                    Free Preview
                  </span>
                </div>
                <div class="flex items-center gap-6 text-sm text-surface-500 dark:text-surface-400">
                  <div v-if="lesson.duration" class="flex items-center">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Duration: {{ formatDuration(lesson.duration) }}
                  </div>
                  <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                    Order: {{ lesson.order }}
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <Button @click="handleEditLesson">
                  <PencilSquareIcon class="h-5 w-5 mr-2" />
                  Edit Lesson
                </Button>
                <Button
                  @click="handleDeleteLesson"
                  variant="destructive"
                >
                  <TrashIcon class="h-5 w-5 mr-2" />
                  Delete
                </Button>
              </div>
            </div>
          </div>

          <!-- Lesson Statistics -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-surface-0 dark:bg-surface-900 rounded-xl shadow-sm border border-surface-200 dark:border-surface-700 p-5 md:p-6 hover:shadow-md transition-shadow">
              <div class="flex items-center justify-between mb-3">
                <div class="p-2.5 bg-indigo-100 dark:bg-indigo-500/20 rounded-xl">
                  <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </div>
              </div>
              <p class="text-xs font-semibold text-surface-600 dark:text-surface-400 uppercase tracking-wider mb-1">Views</p>
              <p class="text-2xl md:text-3xl font-bold text-surface-900 dark:text-surface-0">{{ mockStats.views }}</p>
            </div>

            <div class="bg-surface-0 dark:bg-surface-900 rounded-xl shadow-sm border border-surface-200 dark:border-surface-700 p-5 md:p-6 hover:shadow-md transition-shadow">
              <div class="flex items-center justify-between mb-3">
                <div class="p-2.5 bg-blue-100 dark:bg-blue-500/20 rounded-xl">
                  <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <p class="text-xs font-semibold text-surface-600 dark:text-surface-400 uppercase tracking-wider mb-1">Completions</p>
              <p class="text-2xl md:text-3xl font-bold text-surface-900 dark:text-surface-0">{{ mockStats.completions }}</p>
            </div>

            <div class="bg-surface-0 dark:bg-surface-900 rounded-xl shadow-sm border border-surface-200 dark:border-surface-700 p-5 md:p-6 hover:shadow-md transition-shadow">
              <div class="flex items-center justify-between mb-3">
                <div class="p-2.5 bg-cyan-100 dark:bg-cyan-500/20 rounded-xl">
                  <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <p class="text-xs font-semibold text-surface-600 dark:text-surface-400 uppercase tracking-wider mb-1">Avg. Time Spent</p>
              <p class="text-2xl md:text-3xl font-bold text-surface-900 dark:text-surface-0">{{ mockStats.avgTime }}m</p>
            </div>
          </div>

          <!-- Content Section -->
          <div class="bg-surface-0 dark:bg-surface-900 rounded-xl shadow-sm border border-surface-200 dark:border-surface-700 p-6 md:p-8">
            <h2 class="text-xl font-bold text-surface-900 dark:text-surface-0 mb-4">Lesson Content</h2>

            <!-- Video Type -->
            <div v-if="lesson.type === 'video'" class="space-y-4">
              <div v-if="lesson.video_url" class="aspect-video bg-gray-900 rounded-lg overflow-hidden">
                <video
                  :src="lesson.video_url"
                  controls
                  class="w-full h-full"
                >
                  Your browser does not support the video tag.
                </video>
              </div>
              <div v-else class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center">
                <div class="text-center">
                  <svg class="mx-auto h-12 w-12 text-surface-400 dark:text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                  <p class="mt-2 text-sm text-surface-500 dark:text-surface-400">No video uploaded</p>
                </div>
              </div>
            </div>

            <!-- Document Type -->
            <div v-else-if="lesson.type === 'document'" class="space-y-4">
              <div v-if="lesson.document_url" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                  <svg class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                  </svg>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-surface-900 dark:text-surface-0">Lesson Document</p>
                    <p class="text-sm text-surface-500 dark:text-surface-400">PDF or Document file</p>
                  </div>
                </div>
                <a
                  :href="lesson.document_url"
                  target="_blank"
                  class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-surface-900 dark:text-surface-0 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                >
                  <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                  Download
                </a>
              </div>
              <div v-else class="p-12 text-center bg-gray-50 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-surface-400 dark:text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <p class="mt-2 text-sm text-surface-500 dark:text-surface-400">No document uploaded</p>
              </div>
            </div>

            <!-- Text Content -->
            <div class="mt-6">
              <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-0 mb-3">Description</h3>
              <div class="prose max-w-none">
                <p class="text-surface-700 dark:text-surface-300 whitespace-pre-line">{{ lesson.content || 'No content available.' }}</p>
              </div>
            </div>
          </div>

          <!-- Student Progress (if applicable) -->
          <div class="bg-surface-0 dark:bg-surface-900 rounded-xl shadow-sm border border-surface-200 dark:border-surface-700 p-6 md:p-8">
            <h2 class="text-xl font-bold text-surface-900 dark:text-surface-0 mb-4">Student Progress</h2>
            <div class="space-y-3">
              <div class="flex items-center justify-between p-4 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
                <span class="text-sm font-medium text-surface-700 dark:text-surface-300">Students who started</span>
                <span class="text-sm font-semibold text-surface-900 dark:text-surface-0">{{ mockStats.views }}</span>
              </div>
              <div class="flex items-center justify-between p-4 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
                <span class="text-sm font-medium text-surface-700 dark:text-surface-300">Students who completed</span>
                <span class="text-sm font-semibold text-surface-900 dark:text-surface-0">{{ mockStats.completions }}</span>
              </div>
              <div class="flex items-center justify-between p-4 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
                <span class="text-sm font-medium text-surface-700 dark:text-surface-300">Completion rate</span>
                <span class="text-sm font-semibold text-surface-900 dark:text-surface-0">{{ Math.round((mockStats.completions / mockStats.views) * 100) }}%</span>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Modals -->
    <UpdateLessonModal
      :show="showUpdateLessonModal"
      :lesson-id="lessonId"
      @close="showUpdateLessonModal = false"
      @updated="loadLesson"
    />

    <DeleteLessonModal
      :show="showDeleteLessonModal"
      :lesson-id="lessonId"
      @close="showDeleteLessonModal = false"
      @deleted="handleDeleted"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'
import DashboardHeader from '~/components/layout/DashboardHeader.vue'
import DashboardSidebar from '~/components/layout/DashboardSidebar.vue'
import UpdateLessonModal from '~/pages/educator/modals/update-lesson.vue'
import DeleteLessonModal from '~/pages/educator/modals/delete-lesson.vue'
import Button from '~/components/ui/Button.vue'
import { useCoursesStore } from '~/stores/courses'
import type { Lesson } from '~/types/courses'

definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const router = useRouter()
const coursesStore = useCoursesStore()

const sidebarCollapsed = ref(true)
const lessonId = ref(parseInt(route.params.id as string))
const lesson = ref<Lesson | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)

const showUpdateLessonModal = ref(false)
const showDeleteLessonModal = ref(false)

// Mock statistics (replace with real API data)
const mockStats = ref({
  views: 143,
  completions: 98,
  avgTime: 12
})

const loadLesson = async () => {
  loading.value = true
  error.value = null

  try {
    await coursesStore.fetchLessonById(lessonId.value)
    lesson.value = coursesStore.lessons.find(l => l.id === lessonId.value) || null

    if (!lesson.value) {
      error.value = 'Lesson not found'
    }
  } catch (err: any) {
    error.value = err.message || 'Failed to load lesson'
  } finally {
    loading.value = false
  }
}

const formatDuration = (seconds: number) => {
  const minutes = Math.floor(seconds / 60)
  const remainingSeconds = seconds % 60
  return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`
}

const handleEditLesson = () => {
  showUpdateLessonModal.value = true
}

const handleDeleteLesson = () => {
  showDeleteLessonModal.value = true
}

const handleDeleted = () => {
  showDeleteLessonModal.value = false
  if (lesson.value?.course_id) {
    navigateTo(`/educator/courses/${lesson.value.course_id}`)
  } else {
    navigateTo('/educator')
  }
}

onMounted(() => {
  loadLesson()
})
</script>
