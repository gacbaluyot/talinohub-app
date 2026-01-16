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
      <main class="flex-1 p-4 md:p-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
              <Button
                variant="ghost"
                size="sm"
                @click="navigateTo('/educator')"
                class="inline-flex items-center text-sm font-medium"
              >
                <HomeIcon class="w-4 h-4 mr-2" />
                Dashboard
              </Button>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="w-5 h-5 text-surface-400 dark:text-surface-500" />
                <span class="ml-1 text-sm font-medium text-surface-500 dark:text-surface-400 md:ml-2">Course Details</span>
              </div>
            </li>
          </ol>
        </nav>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-20">
          <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
          <p class="mt-4 text-sm text-surface-600 dark:text-surface-400">Loading course details...</p>
        </div>

        <!-- Error State -->
        <Alert v-else-if="error" variant="destructive" class="mb-6">
          <ExclamationCircleIcon class="h-5 w-5" />
          <AlertTitle>Error loading course</AlertTitle>
          <AlertDescription>{{ error }}</AlertDescription>
        </Alert>

        <!-- Course Content -->
        <div v-else-if="course" class="space-y-6">
          <!-- Course Hero Card -->
          <Card class="overflow-hidden">
            <!-- Thumbnail -->
            <div class="h-48 md:h-56 bg-gradient-to-br from-indigo-600 via-blue-500 to-cyan-400 relative overflow-hidden">
              <img
                v-if="course.thumbnail_path"
                :src="course.thumbnail_path"
                :alt="course.title"
                class="h-full w-full object-cover"
              />
              <div v-else class="flex items-center justify-center h-full">
                <div class="text-center">
                  <BookOpenIcon class="h-16 w-16 text-white/40 mx-auto mb-2" />
                  <p class="text-white/60 text-sm">No thumbnail</p>
                </div>
              </div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
            </div>

            <!-- Course Info -->
            <CardContent class="p-6 md:p-8">
              <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex-1 min-w-0">
                  <!-- Title & Badges -->
                  <div class="flex flex-wrap items-start gap-3 mb-3">
                    <h1 class="text-2xl md:text-3xl font-bold text-surface-900 dark:text-surface-0 flex-shrink-0">
                      {{ course.title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-2">
                      <Badge :variant="course.is_published ? 'success' : 'warning'">
                        {{ course.is_published ? '✓ Published' : '⏳ Draft' }}
                      </Badge>
                      <Badge :variant="course.is_free ? 'success' : 'info'">
                        {{ course.is_free ? '🎁 Free' : `💰 $${course.price}` }}
                      </Badge>
                    </div>
                  </div>

                  <!-- Short Description -->
                  <p class="text-base md:text-lg text-surface-600 dark:text-surface-400 mb-4 leading-relaxed">
                    {{ course.short_description }}
                  </p>

                  <!-- Meta Info -->
                  <div class="flex flex-wrap items-center gap-4 md:gap-6 text-sm text-surface-600 dark:text-surface-400">
                    <div class="flex items-center gap-2">
                      <div class="p-1.5 bg-indigo-100 dark:bg-indigo-500/20 rounded-lg">
                        <BookOpenIcon class="h-4 w-4 text-indigo-600 dark:text-indigo-300" />
                      </div>
                      <span class="font-medium">{{ course.lessons?.length || 0 }} lessons</span>
                    </div>
                    <div v-if="course.level" class="flex items-center gap-2">
                      <div class="p-1.5 bg-blue-100 dark:bg-blue-500/20 rounded-lg">
                        <AcademicCapIcon class="h-4 w-4 text-blue-600 dark:text-blue-300" />
                      </div>
                      <span class="font-medium">{{ course.level }}</span>
                    </div>
                    <div v-if="course.category" class="flex items-center gap-2">
                      <div class="p-1.5 bg-cyan-100 dark:bg-cyan-500/20 rounded-lg">
                        <TagIcon class="h-4 w-4 text-cyan-600 dark:text-cyan-300" />
                      </div>
                      <span class="font-medium">{{ course.category }}</span>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-2 lg:shrink-0">
                  <Button variant="outline" @click="navigateTo(`/educator/courses/preview/${courseId}`)">
                    <EyeIcon class="h-4 w-4 mr-2" />
                    Preview
                  </Button>
                  <Button variant="outline" @click="handleEditCourse">
                    <PencilSquareIcon class="h-4 w-4 mr-2" />
                    Edit Course
                  </Button>
                  <Button @click="showAddLessonModal = true">
                    <PlusIcon class="h-4 w-4 mr-2" />
                    Add Lesson
                  </Button>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Statistics Grid -->
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Total Enrollments -->
            <Card class="hover:shadow-md transition-shadow">
              <CardContent class="p-5 md:p-6">
                <div class="flex items-center justify-between mb-3">
                  <div class="p-2.5 bg-indigo-100 dark:bg-indigo-500/20 rounded-xl">
                    <UsersIcon class="w-6 h-6 text-indigo-600 dark:text-indigo-300" />
                  </div>
                </div>
                <p class="text-xs font-semibold text-surface-600 dark:text-surface-400 uppercase tracking-wider mb-1">
                  Enrollments
                </p>
                <p class="text-2xl md:text-3xl font-bold text-surface-900 dark:text-surface-0">
                  {{ mockStats.enrollments }}
                </p>
              </CardContent>
            </Card>

            <!-- Completion Rate -->
            <Card class="hover:shadow-md transition-shadow">
              <CardContent class="p-5 md:p-6">
                <div class="flex items-center justify-between mb-3">
                  <div class="p-2.5 bg-blue-100 dark:bg-blue-500/20 rounded-xl">
                    <CheckCircleIcon class="w-6 h-6 text-blue-600 dark:text-blue-300" />
                  </div>
                </div>
                <p class="text-xs font-semibold text-surface-600 dark:text-surface-400 uppercase tracking-wider mb-1">
                  Completion
                </p>
                <p class="text-2xl md:text-3xl font-bold text-surface-900 dark:text-surface-0">
                  {{ mockStats.completionRate }}%
                </p>
              </CardContent>
            </Card>

            <!-- Average Rating -->
            <Card class="hover:shadow-md transition-shadow">
              <CardContent class="p-5 md:p-6">
                <div class="flex items-center justify-between mb-3">
                  <div class="p-2.5 bg-cyan-100 dark:bg-cyan-500/20 rounded-xl">
                    <StarIcon class="w-6 h-6 text-cyan-600 dark:text-cyan-300" />
                  </div>
                </div>
                <p class="text-xs font-semibold text-surface-600 dark:text-surface-400 uppercase tracking-wider mb-1">
                  Rating
                </p>
                <p class="text-2xl md:text-3xl font-bold text-surface-900 dark:text-surface-0">
                  {{ mockStats.rating }}
                </p>
              </CardContent>
            </Card>

            <!-- Total Revenue -->
            <Card class="hover:shadow-md transition-shadow">
              <CardContent class="p-5 md:p-6">
                <div class="flex items-center justify-between mb-3">
                  <div class="p-2.5 bg-indigo-100 dark:bg-indigo-500/20 rounded-xl">
                    <CurrencyDollarIcon class="w-6 h-6 text-indigo-600 dark:text-indigo-300" />
                  </div>
                </div>
                <p class="text-xs font-semibold text-surface-600 dark:text-surface-400 uppercase tracking-wider mb-1">
                  Revenue
                </p>
                <p class="text-2xl md:text-3xl font-bold text-surface-900 dark:text-surface-0">
                  ${{ mockStats.revenue }}
                </p>
              </CardContent>
            </Card>
          </div>

          <!-- Description Section -->
          <Card>
            <CardHeader>
              <CardTitle class="text-xl flex items-center gap-2">
                <DocumentTextIcon class="h-5 w-5 text-indigo-600" />
                Course Description
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div class="prose prose-sm md:prose-base dark:prose-invert max-w-none">
                <p class="text-surface-700 dark:text-surface-300 whitespace-pre-line leading-relaxed">
                  {{ course.description || 'No detailed description available.' }}
                </p>
              </div>
            </CardContent>
          </Card>

          <!-- Course Curriculum -->
          <Card class="overflow-hidden">
            <CardHeader class="border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-800">
              <CardTitle class="text-xl flex items-center gap-2">
                <BookOpenIcon class="h-5 w-5 text-indigo-600" />
                Course Curriculum
              </CardTitle>
              <CardDescription class="mt-1">
                {{ course.lessons?.length || 0 }} lessons in total
              </CardDescription>
            </CardHeader>
            
            <!-- Empty State -->
            <CardContent v-if="!course.lessons || course.lessons.length === 0" class="p-12 text-center">
              <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-100 dark:bg-surface-800 mb-4">
                <BookOpenIcon class="h-8 w-8 text-surface-400 dark:text-surface-500" />
              </div>
              <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-0 mb-2">No lessons yet</h3>
              <p class="text-sm text-surface-500 dark:text-surface-400 mb-6 max-w-sm mx-auto">
                Start building your course by adding lessons. Each lesson can include videos, documents, or text content.
              </p>
              <Button @click="showAddLessonModal = true">
                <PlusIcon class="h-5 w-5 mr-2" />
                Add First Lesson
              </Button>
            </CardContent>

            <!-- Lessons List -->
            <div v-else class="divide-y divide-surface-200 dark:divide-surface-700">
              <div
                v-for="(lesson, index) in course.lessons"
                :key="lesson.id"
                class="group p-5 md:p-6 hover:bg-surface-50 dark:hover:bg-surface-800 transition-colors cursor-pointer"
                @click="navigateTo(`/educator/lessons/${lesson.id}`)"
              >
                <div class="flex items-start gap-4">
                  <!-- Lesson Number Badge -->
                  <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-500/20 dark:to-blue-500/20 flex items-center justify-center border border-indigo-300 dark:border-indigo-500/30 group-hover:scale-110 transition-transform">
                    <span class="text-base md:text-lg font-bold text-indigo-600 dark:text-indigo-300">
                      {{ index + 1 }}
                    </span>
                  </div>

                  <!-- Lesson Content -->
                  <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-start gap-2 mb-2">
                      <h3 class="text-base md:text-lg font-semibold text-surface-900 dark:text-surface-0 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                        {{ lesson.title }}
                      </h3>
                      <div class="flex flex-wrap items-center gap-2">
                        <Badge
                          :variant="
                            lesson.type === 'video' 
                              ? 'default' 
                              : lesson.type === 'document'
                              ? 'info'
                              : 'success'
                          "
                          class="capitalize"
                        >
                          {{ lesson.type }}
                        </Badge>
                        <Badge v-if="lesson.is_preview" variant="success">
                          Free Preview
                        </Badge>
                      </div>
                    </div>
                    
                    <p class="text-sm text-surface-600 dark:text-surface-400 line-clamp-2 mb-3">
                      {{ lesson.content || 'No description available' }}
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-4 text-xs text-surface-500 dark:text-surface-400">
                      <span v-if="lesson.duration" class="flex items-center gap-1">
                        <ClockIcon class="h-3.5 w-3.5" />
                        {{ formatDuration(lesson.duration) }}
                      </span>
                      <span class="flex items-center gap-1">
                        <ChartBarIcon class="h-3.5 w-3.5" />
                        Order: {{ lesson.order }}
                      </span>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <Button
                      variant="outline"
                      size="icon"
                      @click.stop="handleEditLesson(lesson.id)"
                      title="Edit Lesson"
                    >
                      <PencilSquareIcon class="h-4 w-4" />
                    </Button>
                    <Button
                      variant="destructive"
                      size="icon"
                      @click.stop="handleDeleteLesson(lesson.id)"
                      title="Delete Lesson"
                    >
                      <TrashIcon class="h-4 w-4" />
                    </Button>
                  </div>
                </div>
              </div>
            </div>
          </Card>
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
import {
  PencilSquareIcon,
  TrashIcon,
  EyeIcon,
  BookOpenIcon,
  AcademicCapIcon,
  TagIcon,
  PlusIcon,
  UsersIcon,
  CheckCircleIcon,
  StarIcon,
  CurrencyDollarIcon,
  DocumentTextIcon,
  ClockIcon,
  ChartBarIcon,
  HomeIcon,
  ChevronRightIcon,
  ExclamationCircleIcon
} from '@heroicons/vue/24/outline'
import DashboardHeader from '~/components/layout/DashboardHeader.vue'
import DashboardSidebar from '~/components/layout/DashboardSidebar.vue'
import UpdateCourseModal from '~/pages/educator/modals/update-course.vue'
import CreateLessonModal from '~/pages/educator/modals/create-lesson.vue'
import UpdateLessonModal from '~/pages/educator/modals/update-lesson.vue'
import DeleteLessonModal from '~/pages/educator/modals/delete-lesson.vue'
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
import { useCoursesStore } from '~/stores/courses'
import type { Course } from '~/types/courses'

definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const coursesStore = useCoursesStore()

const sidebarCollapsed = ref(true)
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
