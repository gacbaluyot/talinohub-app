<template>
  <div class="min-h-screen bg-background">
    <!-- Top Navigation Bar -->
    <nav class="bg-background border-b border-border sticky top-0 z-50">
      <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <Button
              @click="navigateTo('/student')"
              variant="ghost"
              size="icon"
              class="mr-4"
            >
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </Button>
            <h1 class="text-2xl font-bold text-indigo-600">TalinoHub</h1>
          </div>

          <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->
            <ThemeToggle />
            
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
                <MenuItems class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-surface-0 dark:bg-surface-900 py-1 shadow-lg ring-1 ring-surface-200 dark:ring-surface-700 focus:outline-none">
                  <MenuItem v-slot="{ active }">
                    <a
                      href="#"
                      @click.prevent="navigateTo('/student/profile')"
                      :class="[active ? 'bg-surface-100 dark:bg-surface-800' : '', 'block px-4 py-2 text-sm text-surface-900 dark:text-gray-200']"
                    >
                      Your Profile
                    </a>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <a
                      href="#"
                      @click.prevent="navigateTo('/student')"
                      :class="[active ? 'bg-surface-100 dark:bg-surface-800' : '', 'block px-4 py-2 text-sm text-surface-900 dark:text-gray-200']"
                    >
                      Dashboard
                    </a>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <Button
                      @click="handleLogout"
                      variant="ghost"
                      :class="[active ? 'bg-surface-100 dark:bg-surface-800' : '', 'w-full justify-start px-4 py-2 text-sm']"
                    >
                      Sign out
                    </Button>
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
      <Alert variant="destructive">
        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
        </svg>
        <AlertTitle>Error</AlertTitle>
        <AlertDescription>{{ error }}</AlertDescription>
      </Alert>
    </div>

    <!-- Course Content -->
    <div v-else-if="course" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Course Header Card -->
      <Card class="mb-8">
        <CardHeader>
          <div class="flex items-center gap-3 mb-4 flex-wrap">
            <Badge :variant="course.is_published ? 'default' : 'secondary'">
              {{ course.is_published ? 'Published' : 'Draft' }}
            </Badge>
            <Badge variant="secondary">
              {{ course.is_free ? 'Free' : `$${course.price}` }}
            </Badge>
            <Badge v-if="course.level" variant="outline">
              {{ course.level }}
            </Badge>
            <Badge v-if="enrolled" variant="default" class="bg-green-600">
              Enrolled
            </Badge>
          </div>
          <CardTitle class="text-4xl">{{ course.title }}</CardTitle>
          <CardDescription class="text-lg mt-2">{{ course.short_description }}</CardDescription>
        </CardHeader>
        
        <CardContent>
          <!-- Course Stats -->
          <div class="flex items-center gap-8 text-sm mb-6 flex-wrap">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
              <span>{{ course.lessons_count || 0 }} lessons</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
              </svg>
              <span>{{ course.level || 'All levels' }}</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ course.language || 'English' }}</span>
            </div>
          </div>

          <!-- Action Button -->
          <Button
            v-if="!enrolled"
            @click="handleEnroll"
            size="lg"
            class="w-full sm:w-auto"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path d="M12 14l9-5-9-5-9 5 9 5z" />
              <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
            </svg>
            Enroll Now
          </Button>
          <Button
            v-else
            @click="handleStartLearning"
            size="lg"
            class="w-full sm:w-auto"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Continue Learning
          </Button>
        </CardContent>
      </Card>

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
          <Card>
            <CardHeader>
              <CardTitle class="text-2xl">About this course</CardTitle>
            </CardHeader>
            <CardContent>
              <p class="text-surface-600 dark:text-gray-400 whitespace-pre-line leading-relaxed">
                {{ course.description || course.short_description || 'No description available.' }}
              </p>
            </CardContent>
          </Card>

          <!-- Course Content / Lessons -->
          <Card>
            <CardHeader>
              <CardTitle class="text-2xl">Course Content</CardTitle>
            </CardHeader>
            <CardContent>
              <div v-if="loadingLessons" class="text-center py-8">
                <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
              </div>

              <div v-else-if="lessons.length === 0" class="text-center py-8">
                <svg class="mx-auto w-12 h-12 text-surface-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <p class="mt-2 text-sm text-surface-600 dark:text-gray-400">No lessons available yet.</p>
              </div>

              <div v-else class="space-y-3">
                <div
                  v-for="(lesson, index) in lessons"
                  :key="lesson.id"
                  @click="handleLessonClick(lesson)"
                  class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-indigo-50/50 dark:hover:bg-indigo-950/30 cursor-pointer transition-all group"
                  style="background-color: var(--card-bg)"
                >
                  <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center shrink-0 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-900/50 transition-colors">
                      <svg v-if="lesson.type === 'video'" class="h-7 w-7 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" />
                      </svg>
                      <svg v-else-if="lesson.type === 'reading'" class="h-7 w-7 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                      </svg>
                      <svg v-else class="h-7 w-7 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div>
                      <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Lesson {{ index + 1 }}</span>
                        <Badge v-if="lesson.is_preview" variant="default" class="bg-green-600">
                          Preview
                        </Badge>
                      </div>
                      <p class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">{{ lesson.title }}</p>
                      <p class="text-sm text-gray-600 dark:text-gray-400">{{ lesson.type }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3">
                    <span v-if="lesson.duration_seconds" class="text-sm text-gray-600 dark:text-gray-400 font-medium">
                      {{ formatDuration(lesson.duration_seconds) }}
                    </span>
                    <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
          <!-- Instructor Info -->
          <Card v-if="course.educator">
            <CardHeader>
              <CardTitle>Instructor</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="flex items-center gap-3 mb-4">
                <div class="h-16 w-16 rounded-full bg-primary/10 flex items-center justify-center">
                  <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="font-semibold text-surface-900 dark:text-gray-200">{{ course.educator.name }}</p>
                  <p class="text-sm text-surface-600 dark:text-gray-400">{{ course.educator.email }}</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Course Stats Card -->
          <Card>
            <CardHeader>
              <CardTitle>Course Details</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2 text-surface-600 dark:text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-sm">Lessons</span>
                  </div>
                  <span class="font-semibold text-surface-900 dark:text-gray-200">{{ course.lessons_count || 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2 text-surface-600 dark:text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path d="M12 14l9-5-9-5-9 5 9 5z" />
                      <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                    <span class="text-sm">Level</span>
                  </div>
                  <span class="font-semibold text-surface-900 dark:text-gray-200">{{ course.level || 'Any' }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2 text-surface-600 dark:text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">Language</span>
                  </div>
                  <span class="font-semibold text-surface-900 dark:text-gray-200">{{ course.language || 'English' }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2 text-surface-600 dark:text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">Price</span>
                  </div>
                  <span class="font-semibold text-surface-900 dark:text-gray-200">{{ course.is_free ? 'Free' : `$${course.price}` }}</span>
                </div>
              </div>
            </CardContent>
          </Card>
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
import { useCoursesStore } from '~/stores/courses'
import { useEnrollmentStore } from '~/stores/enrollment'
import { useAuthStore } from '~/stores/auth'
import type { Course, Lesson } from '~/types/courses'
import VideoPlayerModal from '~/components/student/VideoPlayerModal.vue'
import Button from '~/components/ui/Button.vue'
import Card from '~/components/ui/Card.vue'
import CardHeader from '~/components/ui/CardHeader.vue'
import CardTitle from '~/components/ui/CardTitle.vue'
import CardDescription from '~/components/ui/CardDescription.vue'
import CardContent from '~/components/ui/CardContent.vue'
import Badge from '~/components/ui/Badge.vue'
import Alert from '~/components/ui/Alert.vue'
import AlertTitle from '~/components/ui/AlertTitle.vue'
import AlertDescription from '~/components/ui/AlertDescription.vue'
import ThemeToggle from '~/components/ThemeToggle.vue'

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
  return type || 'text'
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
