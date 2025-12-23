<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <DashboardHeader />

    <div class="flex">
      <!-- Sidebar -->
      <DashboardSidebar @create-course="navigateTo('/educator')" />

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <a href="#" @click.prevent="navigateTo('/educator')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Dashboard
              </a>
            </li>
            <li aria-current="page">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Course Details</span>
              </div>
            </li>
          </ol>
        </nav>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <div class="inline-block h-10 w-10 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
          <p class="mt-3 text-sm text-gray-600">Loading course...</p>
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

        <!-- Course Content -->
        <div v-else-if="course" class="space-y-6">
          <!-- Course Header -->
          <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="aspect-video bg-gradient-to-br from-indigo-500 to-purple-600 relative">
              <img
                v-if="course.thumbnail_path"
                :src="course.thumbnail_path"
                :alt="course.title"
                class="h-full w-full object-cover"
              />
              <div v-else class="flex items-center justify-center h-full">
                <svg class="h-24 w-24 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
            </div>

            <div class="p-6">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">{{ course.title }}</h1>
                    <span
                      :class="[
                        'inline-flex items-center rounded-full px-3 py-1 text-sm font-medium',
                        course.is_published
                          ? 'bg-green-100 text-green-800'
                          : 'bg-yellow-100 text-yellow-800'
                      ]"
                    >
                      {{ course.is_published ? 'Published' : 'Draft' }}
                    </span>
                  </div>
                  <p class="text-lg text-gray-600 mb-4">{{ course.short_description }}</p>
                  <div class="flex items-center gap-6 text-sm text-gray-500">
                    <div class="flex items-center">
                      <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                      </svg>
                      {{ course.lessons?.length || 0 }} lessons
                    </div>
                    <div v-if="course.level" class="flex items-center">
                      <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                      </svg>
                      {{ course.level }}
                    </div>
                    <div v-if="course.category" class="flex items-center">
                      <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                      </svg>
                      {{ course.category }}
                    </div>
                    <div class="flex items-center font-semibold text-gray-900">
                      {{ course.is_free ? 'Free' : `$${course.price}` }}
                    </div>
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <button
                    @click="navigateTo(`/educator/courses/preview/${courseId}`)"
                    class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm ring-1 ring-inset ring-blue-300 hover:bg-blue-50 transition-colors"
                  >
                    <EyeIcon class="h-5 w-5 mr-2" />
                    Preview as Student
                  </button>
                  <button
                    @click="handleEditCourse"
                    class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-indigo-600 shadow-sm ring-1 ring-inset ring-indigo-300 hover:bg-indigo-50 transition-colors"
                  >
                    <PencilSquareIcon class="h-5 w-5 mr-2" />
                    Edit Course
                  </button>
                  <button
                    @click="showAddLessonModal = true"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors"
                  >
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Lesson
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Course Statistics -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600">Total Enrollments</p>
                  <p class="mt-2 text-3xl font-semibold text-gray-900">{{ mockStats.enrollments }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                </div>
              </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                  <p class="mt-2 text-3xl font-semibold text-gray-900">{{ mockStats.completionRate }}%</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600">Average Rating</p>
                  <p class="mt-2 text-3xl font-semibold text-gray-900">{{ mockStats.rating }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                  </svg>
                </div>
              </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                  <p class="mt-2 text-3xl font-semibold text-gray-900">${{ mockStats.revenue }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Description</h2>
            <p class="text-gray-700 whitespace-pre-line">{{ course.description || 'No detailed description available.' }}</p>
          </div>

          <!-- Lessons List -->
          <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
              <h2 class="text-xl font-bold text-gray-900">Course Curriculum</h2>
            </div>
            
            <div v-if="!course.lessons || course.lessons.length === 0" class="p-12 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <h3 class="mt-4 text-lg font-semibold text-gray-900">No lessons yet</h3>
              <p class="mt-2 text-sm text-gray-500">Start building your course by adding lessons.</p>
              <button
                @click="showAddLessonModal = true"
                class="mt-6 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
              >
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add First Lesson
              </button>
            </div>

            <div v-else class="divide-y divide-gray-200">
              <div
                v-for="(lesson, index) in course.lessons"
                :key="lesson.id"
                class="p-6 hover:bg-gray-50 transition-colors cursor-pointer group"
                @click="navigateTo(`/educator/lessons/${lesson.id}`)"
              >
                <div class="flex items-start justify-between">
                  <div class="flex items-start flex-1">
                    <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-semibold">
                      {{ index + 1 }}
                    </div>
                    <div class="ml-4 flex-1">
                      <div class="flex items-center gap-3">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">{{ lesson.title }}</h3>
                        <span
                          :class="[
                            'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                            lesson.type === 'video' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'
                          ]"
                        >
                          {{ lesson.type }}
                        </span>
                        <span
                          v-if="lesson.is_preview"
                          class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700"
                        >
                          Preview
                        </span>
                      </div>
                      <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ lesson.content || 'No description' }}</p>
                      <div class="mt-2 flex items-center gap-4 text-sm text-gray-500">
                        <span v-if="lesson.duration" class="flex items-center">
                          <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          {{ formatDuration(lesson.duration) }}
                        </span>
                        <span class="flex items-center">
                          Order: {{ lesson.order }}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 ml-4">
                    <button
                      @click.stop="handleEditLesson(lesson.id)"
                      class="rounded-md bg-white p-2 text-indigo-600 shadow-sm ring-1 ring-inset ring-indigo-300 hover:bg-indigo-50 opacity-0 group-hover:opacity-100 transition-opacity"
                      title="Edit Lesson"
                    >
                      <PencilSquareIcon class="h-5 w-5" />
                    </button>
                    <button
                      @click.stop="handleDeleteLesson(lesson.id)"
                      class="rounded-md bg-white p-2 text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50 opacity-0 group-hover:opacity-100 transition-opacity"
                      title="Delete Lesson"
                    >
                      <TrashIcon class="h-5 w-5" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Modals -->
    <UpdateCourseModal
      :show="showUpdateCourseModal"
      :course-id="courseId"
      @close="showUpdateCourseModal = false"
      @updated="loadCourse"
    />

    <CreateLessonModal
      :show="showAddLessonModal"
      :course-id="courseId"
      @close="showAddLessonModal = false"
      @created="loadCourse"
    />

    <UpdateLessonModal
      :show="showUpdateLessonModal"
      :lesson-id="selectedLessonId"
      @close="showUpdateLessonModal = false"
      @updated="loadCourse"
    />

    <DeleteLessonModal
      :show="showDeleteLessonModal"
      :lesson-id="selectedLessonId"
      @close="showDeleteLessonModal = false"
      @deleted="loadCourse"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { PencilSquareIcon, TrashIcon, EyeIcon } from '@heroicons/vue/24/outline'
import DashboardHeader from '~/components/layout/DashboardHeader.vue'
import DashboardSidebar from '~/components/layout/DashboardSidebar.vue'
import UpdateCourseModal from '~/pages/educator/modals/update-course.vue'
import CreateLessonModal from '~/pages/educator/modals/create-lesson.vue'
import UpdateLessonModal from '~/pages/educator/modals/update-lesson.vue'
import DeleteLessonModal from '~/pages/educator/modals/delete-lesson.vue'
import { useCoursesStore } from '~/stores/courses'
import type { Course } from '~/types/courses'

definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const coursesStore = useCoursesStore()

const courseId = ref(parseInt(route.params.id as string))
const course = ref<Course | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)

const showUpdateCourseModal = ref(false)
const showAddLessonModal = ref(false)
const showUpdateLessonModal = ref(false)
const showDeleteLessonModal = ref(false)
const selectedLessonId = ref<number | null>(null)

// Mock statistics (replace with real API data)
const mockStats = ref({
  enrollments: 87,
  completionRate: 72,
  rating: 4.6,
  revenue: 2340
})

const loadCourse = async () => {
  loading.value = true
  error.value = null

  try {
    await coursesStore.fetchCourseById(courseId.value)
    course.value = coursesStore.courses.find(c => c.id === courseId.value) || null
    
    if (!course.value) {
      error.value = 'Course not found'
    }
  } catch (err: any) {
    error.value = err.message || 'Failed to load course'
  } finally {
    loading.value = false
  }
}

const formatDuration = (seconds: number) => {
  const minutes = Math.floor(seconds / 60)
  const remainingSeconds = seconds % 60
  return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`
}

const handleEditCourse = () => {
  showUpdateCourseModal.value = true
}

const handleEditLesson = (lessonId: number) => {
  selectedLessonId.value = lessonId
  showUpdateLessonModal.value = true
}

const handleDeleteLesson = (lessonId: number) => {
  selectedLessonId.value = lessonId
  showDeleteLessonModal.value = true
}

onMounted(() => {
  loadCourse()
})
</script>
