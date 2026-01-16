<template>
  <div class="min-h-screen bg-surface-50 dark:bg-surface-950 transition-colors">
    <!-- Top Navigation Bar -->
    <nav class="bg-surface-0 dark:bg-surface-900 border-b border-surface-200 dark:border-surface-700 sticky top-0 z-50 shadow-sm">
      <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center gap-4">
            <Button
              @click="navigateTo('/educator/courses')"
              variant="ghost"
              size="icon"
              aria-label="Go back"
            >
              <svg class="w-5 h-5 text-surface-700 dark:text-surface-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </Button>
            <h1 class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
              TalinoHub
            </h1>
            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-3 py-1 text-xs font-medium border border-blue-200 dark:border-blue-800">
              <EyeIcon class="h-3.5 w-3.5 mr-1.5" />
              Preview Mode
            </span>
          </div>

          <div class="flex items-center gap-3">
            <Button variant="outline" @click="navigateTo(`/educator/courses/${courseId}`)">
              <PencilSquareIcon class="h-4 w-4 mr-2" />
              Edit Course
            </Button>
            <Menu as="div" class="relative">
              <MenuButton class="flex items-center focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center shadow-md hover:shadow-lg transition-shadow">
                  <span class="text-sm font-semibold text-white">{{ userInitials }}</span>
                </div>
              </MenuButton>
              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <MenuItems class="absolute right-0 mt-2 w-56 origin-top-right rounded-lg bg-surface-0 dark:bg-surface-900 shadow-lg ring-1 ring-black/5 dark:ring-white/10 focus:outline-none overflow-hidden">
                  <div class="px-4 py-3 border-b border-surface-200 dark:border-surface-700">
                    <p class="text-sm font-medium text-surface-900 dark:text-surface-0">{{ authStore.user?.name }}</p>
                    <p class="text-xs text-surface-500 dark:text-surface-400 truncate">{{ authStore.user?.email }}</p>
                  </div>
                  <div class="py-1">
                    <MenuItem v-slot="{ active }">
                      <Button
                        @click="navigateTo('/educator/profile')"
                        variant="ghost"
                        :class="[active ? 'bg-surface-100 dark:bg-surface-800' : '', 'flex items-center w-full px-4 py-2 text-sm text-surface-700 dark:text-surface-300 transition-colors justify-start']"
                      >
                        <UserCircleIcon class="h-4 w-4 mr-3" />
                        Your Profile
                      </Button>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                      <Button
                        @click="navigateTo('/educator')"
                        variant="ghost"
                        :class="[active ? 'bg-surface-100 dark:bg-surface-800' : '', 'flex items-center w-full px-4 py-2 text-sm text-surface-700 dark:text-surface-300 transition-colors justify-start']"
                      >
                        <HomeIcon class="h-4 w-4 mr-3" />
                        Dashboard
                      </Button>
                    </MenuItem>
                  </div>
                  <div class="border-t border-surface-200 dark:border-surface-700 py-1">
                    <MenuItem v-slot="{ active }">
                      <Button
                        @click="handleLogout"
                        variant="ghost"
                        :class="[active ? 'bg-red-50 dark:bg-red-900/20' : '', 'flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 transition-colors justify-start']"
                      >
                        <ArrowRightOnRectangleIcon class="h-4 w-4 mr-3" />
                        Sign out
                      </Button>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>
          </div>
        </div>
      </div>
    </nav>

    <!-- Preview Notice Banner -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <InformationCircleIcon class="h-5 w-5 text-blue-600 dark:text-blue-400" />
            <p class="text-sm text-blue-800 dark:text-blue-300">
              You are previewing this course as a student would see it. This view is read-only.
            </p>
          </div>
          <Button
            @click="navigateTo(`/educator/courses/${courseId}`)"
            variant="ghost"
            class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors"
          >
            Exit Preview
          </Button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="text-center">
        <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
        <p class="mt-4 text-sm text-surface-600 dark:text-surface-400">Loading course...</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
        <div class="flex items-start gap-3">
          <ExclamationCircleIcon class="h-5 w-5 text-red-600 dark:text-red-400 mt-0.5" />
          <div>
            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Error loading course</h3>
            <p class="mt-1 text-sm text-red-700 dark:text-red-400">{{ error }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Course Content -->
    <div v-else-if="course" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Course Hero Section -->
      <div class="bg-gradient-to-br from-indigo-600 via-blue-500 to-cyan-400 rounded-xl p-8 md:p-12 mb-8 text-white shadow-xl relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
          <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        </div>
        
        <div class="relative">
          <!-- Badges -->
          <div class="flex flex-wrap items-center gap-2 mb-4">
            <span
              :class="[
                'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold shadow-sm',
                course.is_published 
                  ? 'bg-green-500 text-white' 
                  : 'bg-yellow-500 text-yellow-900'
              ]"
            >
              {{ course.is_published ? '✓ Published' : '⏳ Draft' }}
            </span>
            <span
              :class="[
                'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold shadow-sm',
                course.is_free 
                  ? 'bg-emerald-500 text-white' 
                  : 'bg-blue-500 text-white'
              ]"
            >
              {{ course.is_free ? '🎁 Free' : `💰 $${course.price}` }}
            </span>
            <span v-if="course.level" class="inline-flex items-center rounded-full bg-white/20 backdrop-blur-sm px-3 py-1 text-xs font-semibold text-white shadow-sm border border-white/30">
              🎓 {{ course.level }}
            </span>
          </div>

          <!-- Title & Description -->
          <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 leading-tight">
            {{ course.title }}
          </h1>
          <p class="text-lg md:text-xl text-white/90 mb-8 max-w-3xl leading-relaxed">
            {{ course.short_description }}
          </p>

          <!-- Course Stats -->
          <div class="flex flex-wrap items-center gap-6 text-sm md:text-base mb-8">
            <div class="flex items-center gap-2">
              <div class="p-2 bg-white/10 backdrop-blur-sm rounded-lg">
                <BookOpenIcon class="h-5 w-5" />
              </div>
              <span class="font-medium">{{ course.lessons_count || 0 }} lessons</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="p-2 bg-white/10 backdrop-blur-sm rounded-lg">
                <AcademicCapIcon class="h-5 w-5" />
              </div>
              <span class="font-medium">{{ course.level || 'All levels' }}</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="p-2 bg-white/10 backdrop-blur-sm rounded-lg">
                <GlobeAltIcon class="h-5 w-5" />
              </div>
              <span class="font-medium">{{ course.language || 'English' }}</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-wrap items-center gap-3">
            <Button
              disabled
              variant="secondary"
              size="lg"
              class="bg-white/20 backdrop-blur-sm text-white cursor-not-allowed border border-white/30"
            >
              <AcademicCapIcon class="h-5 w-5 mr-2" />
              Enroll Now (Preview Only)
            </Button>
            <Button
              @click="navigateTo(`/educator/courses/${courseId}`)"
              size="lg"
              class="bg-white text-indigo-600 shadow-lg hover:shadow-xl hover:scale-105 transition-all"
            >
              <PencilSquareIcon class="h-5 w-5 mr-2" />
              Edit Course
            </Button>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Course Thumbnail -->
          <div v-if="course.thumbnail_path" class="rounded-xl overflow-hidden shadow-lg border border-surface-200 dark:border-surface-700">
            <img
              :src="course.thumbnail_path"
              :alt="course.title"
              class="w-full h-80 md:h-96 object-cover"
            />
          </div>

          <!-- About Section -->
          <div class="bg-surface-0 dark:bg-surface-900 rounded-xl shadow-sm border border-surface-200 dark:border-surface-700 p-6 md:p-8">
            <h2 class="text-2xl font-bold text-surface-900 dark:text-surface-0 mb-4 flex items-center gap-2">
              <InformationCircleIcon class="h-6 w-6 text-indigo-600" />
              About this course
            </h2>
            <div class="prose prose-sm md:prose-base dark:prose-invert max-w-none">
              <p class="text-surface-700 dark:text-surface-300 whitespace-pre-line leading-relaxed">
                {{ course.description || course.short_description || 'No description available.' }}
              </p>
            </div>
          </div>

          <!-- Course Content / Lessons -->
          <div class="bg-surface-0 dark:bg-surface-900 rounded-xl shadow-sm border border-surface-200 dark:border-surface-700 p-6 md:p-8">
            <h2 class="text-2xl font-bold text-surface-900 dark:text-surface-0 mb-6 flex items-center gap-2">
              <BookOpenIcon class="h-6 w-6 text-indigo-600" />
              Course Content
            </h2>

            <div v-if="loadingLessons" class="text-center py-12">
              <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
              <p class="mt-3 text-sm text-surface-600 dark:text-surface-400">Loading lessons...</p>
            </div>

            <div v-else-if="lessons.length === 0" class="text-center py-12">
              <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-100 dark:bg-surface-800 mb-4">
                <BookOpenIcon class="h-8 w-8 text-surface-400 dark:text-surface-500" />
              </div>
              <p class="text-sm font-medium text-surface-900 dark:text-surface-0">No lessons available yet</p>
              <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Start creating lessons to build your course.</p>
            </div>

            <div v-else class="space-y-3">
              <div
                v-for="(lesson, index) in lessons"
                :key="lesson.id"
                @click="handleLessonClick(lesson)"
                class="group flex items-center justify-between p-4 md:p-5 border border-surface-200 dark:border-surface-700 rounded-xl hover:bg-surface-50 dark:hover:bg-surface-800 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-md cursor-pointer transition-all"
              >
                <div class="flex items-center gap-4 flex-1 min-w-0">
                  <div class="h-12 w-12 md:h-14 md:w-14 rounded-xl bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-500/20 dark:to-blue-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform border border-indigo-300 dark:border-indigo-500/30">
                    <component :is="getLessonIcon(lesson.type)" class="h-6 w-6 md:h-7 md:w-7 text-indigo-600 dark:text-indigo-300" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                      <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">
                        Lesson {{ index + 1 }}
                      </span>
                      <span v-if="lesson.is_preview" class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2 py-0.5 text-xs font-semibold border border-green-200 dark:border-green-800">
                        Free Preview
                      </span>
                    </div>
                    <p class="font-semibold text-surface-900 dark:text-surface-0 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors truncate">
                      {{ lesson.title }}
                    </p>
                    <p class="text-xs md:text-sm text-surface-500 dark:text-surface-400 capitalize mt-0.5">
                      {{ lesson.type }}
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-3 ml-4">
                  <span v-if="lesson.duration_seconds" class="hidden sm:block text-sm font-medium text-surface-600 dark:text-surface-400">
                    {{ formatDuration(lesson.duration_seconds) }}
                  </span>
                  <PlayCircleIcon class="h-6 w-6 text-surface-400 dark:text-surface-500 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
          <!-- Instructor Card -->
          <div v-if="course.educator" class="bg-surface-0 dark:bg-surface-900 rounded-xl shadow-sm border border-surface-200 dark:border-surface-700 p-6 sticky top-24">
            <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-0 mb-4 flex items-center gap-2">
              <UserCircleIcon class="h-5 w-5 text-indigo-600" />
              Instructor
            </h3>
            <div class="flex items-center gap-3 mb-4 p-4 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
              <div class="h-14 w-14 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center shadow-md">
                <UserCircleIcon class="h-8 w-8 text-white" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-surface-900 dark:text-surface-0 truncate">
                  {{ course.educator.name }}
                </p>
                <p class="text-xs text-surface-500 dark:text-surface-400 truncate">
                  {{ course.educator.email }}
                </p>
              </div>
            </div>
          </div>

          <!-- Course Details Card -->
          <div class="bg-surface-0 dark:bg-surface-900 rounded-xl shadow-sm border border-surface-200 dark:border-surface-700 p-6">
            <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-0 mb-4 flex items-center gap-2">
              <InformationCircleIcon class="h-5 w-5 text-indigo-600" />
              Course Details
            </h3>
            <div class="space-y-4">
              <div class="flex items-center justify-between p-3 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
                <div class="flex items-center gap-2 text-surface-600 dark:text-surface-400">
                  <BookOpenIcon class="h-5 w-5 text-indigo-600" />
                  <span class="text-sm font-medium">Lessons</span>
                </div>
                <span class="font-semibold text-surface-900 dark:text-surface-0">{{ course.lessons_count || 0 }}</span>
              </div>
              <div class="flex items-center justify-between p-3 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
                <div class="flex items-center gap-2 text-surface-600 dark:text-surface-400">
                  <AcademicCapIcon class="h-5 w-5 text-indigo-600" />
                  <span class="text-sm font-medium">Level</span>
                </div>
                <span class="font-semibold text-surface-900 dark:text-surface-0">{{ course.level || 'Any' }}</span>
              </div>
              <div class="flex items-center justify-between p-3 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
                <div class="flex items-center gap-2 text-surface-600 dark:text-surface-400">
                  <GlobeAltIcon class="h-5 w-5 text-indigo-600" />
                  <span class="text-sm font-medium">Language</span>
                </div>
                <span class="font-semibold text-surface-900 dark:text-surface-0">{{ course.language || 'English' }}</span>
              </div>
              <div class="flex items-center justify-between p-3 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
                <div class="flex items-center gap-2 text-surface-600 dark:text-surface-400">
                  <CurrencyDollarIcon class="h-5 w-5 text-indigo-600" />
                  <span class="text-sm font-medium">Price</span>
                </div>
                <span class="font-semibold text-surface-900 dark:text-surface-0">
                  {{ course.is_free ? 'Free' : `$${course.price}` }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Video Player Modal -->
    <VideoPlayerModal
      :show="showVideoModal"
      :lesson="selectedLesson"
      @close="showVideoModal = false"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import {
  BookOpenIcon,
  AcademicCapIcon,
  GlobeAltIcon,
  UserCircleIcon,
  VideoCameraIcon,
  DocumentTextIcon,
  CurrencyDollarIcon,
  EyeIcon,
  PencilSquareIcon,
  InformationCircleIcon,
  ExclamationCircleIcon,
  PlayCircleIcon,
  HomeIcon,
  ArrowRightOnRectangleIcon
} from '@heroicons/vue/24/outline'
import { BookOpenIcon as BookOpenIconSolid } from '@heroicons/vue/24/solid'
import { useCoursesStore } from '~/stores/courses'
import { useAuthStore } from '~/stores/auth'
import type { Course, Lesson } from '~/types/courses'
import VideoPlayerModal from '~/components/student/VideoPlayerModal.vue'
import Button from '~/components/ui/Button.vue'

definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const coursesStore = useCoursesStore()
const authStore = useAuthStore()

const loading = ref(false)
const loadingLessons = ref(false)
const error = ref<string | null>(null)
const course = ref<Course | null>(null)
const lessons = ref<Lesson[]>([])
const showVideoModal = ref(false)
const selectedLesson = ref<Lesson | null>(null)

const courseId = computed(() => parseInt(route.params.id as string))

const userInitials = computed(() => {
  const name = authStore.user?.name || 'U'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const loadCourseDetails = async () => {
  loading.value = true
  error.value = null

  try {
    await coursesStore.fetchCourse(courseId.value)
    course.value = coursesStore.currentCourse

    // Load lessons
    loadingLessons.value = true
    await coursesStore.fetchLessonsByCourse(courseId.value)
    lessons.value = coursesStore.lessons
  } catch (err) {
    error.value = 'Failed to load course details'
    console.error('Error loading course:', err)
  } finally {
    loading.value = false
    loadingLessons.value = false
  }
}

const getLessonIcon = (type: string) => {
  switch (type) {
    case 'video':
      return VideoCameraIcon
    case 'document':
      return DocumentTextIcon
    case 'text':
      return BookOpenIconSolid
    default:
      return BookOpenIconSolid
  }
}

const formatDuration = (seconds: number) => {
  const mins = Math.floor(seconds / 60)
  const secs = seconds % 60
  return `${mins}:${secs.toString().padStart(2, '0')}`
}

const handleLessonClick = (lesson: Lesson) => {
  selectedLesson.value = lesson
  showVideoModal.value = true
}

const handleLogout = async () => {
  await authStore.logout()
  await navigateTo('/auth/login', { replace: true })
}

onMounted(async () => {
  await loadCourseDetails()
})
</script>
