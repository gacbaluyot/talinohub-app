<template>
  <div class="min-h-screen bg-background">
    <!-- Top Navigation Bar -->
    <nav class="bg-background border-b border-border sticky top-0 z-50">
      <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <button
              @click="navigateTo('/student')"
              class="p-2 rounded-lg hover:bg-accent mr-4"
            >
              <svg class="w-6 h-6 text-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <h1 class="text-2xl font-bold text-indigo-600">TalinoHub</h1>
          </div>

          <div class="flex items-center space-x-4">
            <Menu as="div" class="relative">
              <MenuButton class="flex items-center space-x-2 focus:outline-none">
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center">
                  <span class="text-sm font-medium text-white">{{ userInitials }}</span>
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
                <MenuItems class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-popover py-1 shadow-lg ring-1 ring-border focus:outline-none">
                  <MenuItem v-slot="{ active }">
                    <a
                      href="#"
                      @click.prevent="navigateTo('/student/profile')"
                      :class="[active ? 'bg-accent' : '', 'block px-4 py-2 text-sm text-popover-foreground']"
                    >
                      Your Profile
                    </a>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <a
                      href="#"
                      @click.prevent="navigateTo('/student')"
                      :class="[active ? 'bg-accent' : '', 'block px-4 py-2 text-sm text-popover-foreground']"
                    >
                      Dashboard
                    </a>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <button
                      @click="handleLogout"
                      :class="[active ? 'bg-accent' : '', 'block w-full text-left px-4 py-2 text-sm text-popover-foreground']"
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
    </nav>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="rounded-md bg-destructive/10 border border-destructive/20 p-4">
        <div class="flex">
          <svg class="h-5 w-5 text-destructive" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
          </svg>
          <p class="ml-3 text-sm text-destructive">{{ error }}</p>
        </div>
      </div>
    </div>

    <!-- Course Content -->
    <div v-else-if="course" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Course Header -->
      <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-lg p-8 mb-8 text-white">
        <div class="flex items-center gap-3 mb-4">
          <span
            :class="[
              'inline-flex items-center rounded-full px-3 py-1 text-sm font-medium',
              course.is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
            ]"
          >
            {{ course.is_published ? 'Published' : 'Draft' }}
          </span>
          <span
            :class="[
              'inline-flex items-center rounded-full px-3 py-1 text-sm font-medium',
              course.is_free ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'
            ]"
          >
            {{ course.is_free ? 'Free' : `$${course.price}` }}
          </span>
          <span v-if="course.level" class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-sm font-medium">
            {{ course.level }}
          </span>
          <span v-if="enrolled" class="inline-flex items-center rounded-full bg-green-100 text-green-800 px-3 py-1 text-sm font-medium">
            Enrolled
          </span>
        </div>
        <h1 class="text-4xl font-bold mb-4">{{ course.title }}</h1>
        <p class="text-lg opacity-90 mb-6">{{ course.short_description }}</p>

        <!-- Course Stats -->
        <div class="flex items-center gap-8 text-sm">
          <div class="flex items-center gap-2">
            <BookOpenIcon class="h-5 w-5" />
            <span>{{ course.lessons_count || 0 }} lessons</span>
          </div>
          <div class="flex items-center gap-2">
            <AcademicCapIcon class="h-5 w-5" />
            <span>{{ course.level || 'All levels' }}</span>
          </div>
          <div class="flex items-center gap-2">
            <GlobeAltIcon class="h-5 w-5" />
            <span>{{ course.language || 'English' }}</span>
          </div>
        </div>

        <!-- Enroll Button -->
        <div class="mt-6">
          <button
            v-if="!enrolled"
            @click="handleEnroll"
            class="inline-flex items-center rounded-md bg-white px-6 py-3 text-base font-semibold text-indigo-600 shadow-sm hover:bg-gray-100 transition-colors"
          >
            <AcademicCapIcon class="h-5 w-5 mr-2" />
            Enroll Now
          </button>
          <button
            v-else
            @click="handleStartLearning"
            class="inline-flex items-center rounded-md bg-white px-6 py-3 text-base font-semibold text-indigo-600 shadow-sm hover:bg-gray-100 transition-colors"
          >
            <PlayIcon class="h-5 w-5 mr-2" />
            Continue Learning
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Course Thumbnail -->
          <div v-if="course.thumbnail_path" class="rounded-lg overflow-hidden shadow-lg">
            <img
              :src="course.thumbnail_path"
              :alt="course.title"
              class="w-full h-96 object-cover"
            />
          </div>

          <!-- Description -->
          <div class="bg-card rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-card-foreground mb-4">About this course</h2>
            <p class="text-muted-foreground whitespace-pre-line leading-relaxed">
              {{ course.description || course.short_description || 'No description available.' }}
            </p>
          </div>

          <!-- Course Content / Lessons -->
          <div class="bg-card rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-card-foreground mb-4">Course Content</h2>

            <div v-if="loadingLessons" class="text-center py-8">
              <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
            </div>

            <div v-else-if="lessons.length === 0" class="text-center py-8">
              <BookOpenIcon class="mx-auto h-12 w-12 text-muted-foreground" />
              <p class="mt-2 text-sm text-muted-foreground">No lessons available yet.</p>
            </div>

            <div v-else class="space-y-2">
              <div
                v-for="(lesson, index) in lessons"
                :key="lesson.id"
                @click="handleLessonClick(lesson)"
                class="flex items-center justify-between p-4 border border-border rounded-lg hover:bg-accent hover:border-primary cursor-pointer transition-all group"
              >
                <div class="flex items-center gap-4">
                  <div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary/20">
                    <component :is="getLessonIcon(lesson.type)" class="h-6 w-6 text-primary" />
                  </div>
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="text-sm font-medium text-muted-foreground">Lesson {{ index + 1 }}</span>
                      <span v-if="lesson.is_preview" class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 px-2 py-0.5 text-xs font-medium">
                        Preview
                      </span>
                    </div>
                    <p class="font-semibold text-foreground group-hover:text-primary">{{ lesson.title }}</p>
                    <p class="text-sm text-muted-foreground">{{ lesson.type }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <span v-if="lesson.duration_seconds" class="text-sm text-muted-foreground">
                    {{ formatDuration(lesson.duration_seconds) }}
                  </span>
                  <svg class="h-5 w-5 text-muted-foreground group-hover:text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
          <!-- Instructor Info -->
          <div v-if="course.educator" class="bg-card rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-card-foreground mb-4">Instructor</h3>
            <div class="flex items-center gap-3 mb-4">
              <div class="h-16 w-16 rounded-full bg-primary/10 flex items-center justify-center">
                <UserCircleIcon class="h-10 w-10 text-primary" />
              </div>
              <div>
                <p class="font-semibold text-card-foreground">{{ course.educator.name }}</p>
                <p class="text-sm text-muted-foreground">{{ course.educator.email }}</p>
              </div>
            </div>
          </div>

          <!-- Course Stats Card -->
          <div class="bg-card rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-card-foreground mb-4">Course Details</h3>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-muted-foreground">
                  <BookOpenIcon class="h-5 w-5" />
                  <span class="text-sm">Lessons</span>
                </div>
                <span class="font-semibold text-card-foreground">{{ course.lessons_count || 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-muted-foreground">
                  <AcademicCapIcon class="h-5 w-5" />
                  <span class="text-sm">Level</span>
                </div>
                <span class="font-semibold text-card-foreground">{{ course.level || 'Any' }}</span>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-muted-foreground">
                  <GlobeAltIcon class="h-5 w-5" />
                  <span class="text-sm">Language</span>
                </div>
                <span class="font-semibold text-card-foreground">{{ course.language || 'English' }}</span>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-muted-foreground">
                  <CurrencyDollarIcon class="h-5 w-5" />
                  <span class="text-sm">Price</span>
                </div>
                <span class="font-semibold text-card-foreground">{{ course.is_free ? 'Free' : `$${course.price}` }}</span>
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
  PlayIcon
} from '@heroicons/vue/24/outline'
import { BookOpenIcon as BookOpenIconSolid } from '@heroicons/vue/24/solid'
import { useCoursesStore } from '~/stores/courses'
import { useEnrollmentStore } from '~/stores/enrollment'
import { useAuthStore } from '~/stores/auth'
import type { Course, Lesson } from '~/types/courses'
import VideoPlayerModal from '~/components/student/VideoPlayerModal.vue'

definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const coursesStore = useCoursesStore()
const enrollmentStore = useEnrollmentStore()
const authStore = useAuthStore()

const loading = ref(false)
const loadingLessons = ref(false)
const error = ref<string | null>(null)
const course = ref<Course | null>(null)
const lessons = ref<Lesson[]>([])
const enrolled = ref(false)
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

    // Check if enrolled
    if (authStore.user) {
      await enrollmentStore.fetchMyEnrollments()
      enrolled.value = enrollmentStore.isEnrolled(courseId.value)
    }

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

const handleEnroll = async () => {
  try {
    await enrollmentStore.enrollInCourse(courseId.value)
    enrolled.value = true
  } catch (error) {
    console.error('Failed to enroll:', error)
  }
}

const handleStartLearning = () => {
  if (lessons.value.length > 0) {
    handleLessonClick(lessons.value[0])
  }
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
