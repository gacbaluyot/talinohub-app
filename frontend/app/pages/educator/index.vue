<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <DashboardHeader />

    <div class="flex">
      <!-- Sidebar -->
      <DashboardSidebar @create-course="handleAddCourse" />

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <!-- Analytics Section -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Overview</h2>
          
          <!-- Stats Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <StatCard
              title="Total Courses"
              :value="coursesStore.courses.length"
              changeText="+2 this month"
              period="vs last month"
              trend="up"
              :icon="CoursesIcon"
              iconBgColor="bg-indigo-500"
            />
            <StatCard
              title="Total Students"
              :value="totalStudents"
              changeText="+12% increase"
              period="vs last month"
              trend="up"
              :icon="StudentsIcon"
              iconBgColor="bg-green-500"
            />
            <StatCard
              title="Active Enrollments"
              :value="activeEnrollments"
              changeText="+8% increase"
              period="vs last month"
              trend="up"
              :icon="EnrollmentsIcon"
              iconBgColor="bg-blue-500"
            />
            <StatCard
              title="Avg. Rating"
              :value="averageRating.toFixed(1)"
              changeText="+0.2 points"
              period="vs last month"
              trend="up"
              :icon="RatingIcon"
              iconBgColor="bg-yellow-500"
            />
          </div>

          <!-- Charts -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <SimpleBarChart
              title="Course Enrollments"
              :data="courseEnrollmentData"
              barColor="bg-indigo-500"
            />
            <SimpleBarChart
              title="Course Completion Rate"
              :data="courseCompletionData"
              barColor="bg-green-500"
            />
          </div>
        </div>

        <!-- Courses Section -->
        <div>
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">My Courses</h2>
            <button
              type="button"
              @click="handleAddCourse"
              class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors"
            >
              <svg class="inline-block -ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
              </svg>
              Create Course
            </button>
          </div>

          <!-- Loading State -->
          <div v-if="coursesStore.loading && !coursesStore.courses.length" class="text-center py-12">
            <div class="inline-block h-10 w-10 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
            <p class="mt-3 text-sm text-gray-600">Loading courses...</p>
          </div>

          <!-- Error State -->
          <div v-else-if="coursesStore.error" class="rounded-md bg-red-50 p-4">
            <div class="flex">
              <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
              </svg>
              <p class="ml-3 text-sm text-red-800">{{ coursesStore.error }}</p>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else-if="coursesStore.courses.length === 0" class="text-center py-16 bg-white rounded-lg shadow">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">No courses yet</h3>
            <p class="mt-2 text-sm text-gray-500">Get started by creating your first course.</p>
            <div class="mt-6">
              <button
                @click="handleAddCourse"
                type="button"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
              >
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Create Your First Course
              </button>
            </div>
          </div>

          <!-- Courses Grid -->
          <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
              v-for="course in coursesStore.courses"
              :key="course.id"
              class="group relative bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 overflow-hidden cursor-pointer"
              @click="navigateTo(`/educator/courses/${course.id}`)"
            >
              <!-- Course Thumbnail -->
              <div class="aspect-video bg-gradient-to-br from-indigo-500 to-purple-600 relative">
                <img
                  v-if="course.thumbnail_path"
                  :src="course.thumbnail_path"
                  :alt="course.title"
                  class="h-full w-full object-cover"
                />
                <div v-else class="flex items-center justify-center h-full">
                  <svg class="h-16 w-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
                <!-- Status Badge -->
                <div class="absolute top-2 right-2">
                  <span
                    :class="[
                      'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                      course.is_published
                        ? 'bg-green-100 text-green-800'
                        : 'bg-yellow-100 text-yellow-800'
                    ]"
                  >
                    {{ course.is_published ? 'Published' : 'Draft' }}
                  </span>
                </div>
              </div>

              <!-- Course Info -->
              <div class="p-5">
                <div class="flex items-start justify-between">
                  <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">
                      {{ course.title }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                      {{ course.short_description || 'No description' }}
                    </p>
                  </div>
                </div>

                <!-- Course Meta -->
                <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                  <div class="flex items-center">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    {{ course.lessons_count || 0 }} lessons
                  </div>
                  <div v-if="course.level" class="flex items-center">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    {{ course.level }}
                  </div>
                </div>

                <!-- Price -->
                <div class="mt-3">
                  <span
                    :class="[
                      'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium',
                      course.is_free
                        ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20'
                        : 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20'
                    ]"
                  >
                    {{ course.is_free ? 'Free' : `$${course.price}` }}
                  </span>
                </div>

                <!-- Actions -->
                <div class="mt-5 flex items-center gap-2">
                  <button
                    @click.stop="navigateTo(`/educator/courses/${course.id}`)"
                    class="flex-1 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                  >
                    View Details
                  </button>
                  <button
                    @click.stop="handleEditCourse(course.id)"
                    class="rounded-md bg-white p-2 text-indigo-600 shadow-sm ring-1 ring-inset ring-indigo-300 hover:bg-indigo-50 transition-colors"
                    title="Edit Course"
                  >
                    <PencilSquareIcon class="h-5 w-5" />
                  </button>
                  <button
                    @click.stop="handleDeleteCourse(course.id)"
                    class="rounded-md bg-white p-2 text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50 transition-colors"
                    title="Delete Course"
                  >
                    <TrashIcon class="h-5 w-5" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Modals -->
    <CreateCourseModal
      :show="showCreateCourseModal"
      @close="showCreateCourseModal = false"
      @created="handleCourseCreated"
    />

    <UpdateCourseModal
      :show="showUpdateCourseModal"
      :course-id="selectedCourseId"
      @close="showUpdateCourseModal = false"
      @updated="handleCourseUpdated"
    />

    <DeleteCourseModal
      :show="showDeleteCourseModal"
      :course-id="selectedCourseId"
      @close="showDeleteCourseModal = false"
      @deleted="handleCourseDeleted"
    />

    <ManageLessonsModal
      :show="showManageLessonsModal"
      :course="selectedCourse"
      @close="showManageLessonsModal = false"
    />
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, h, computed } from 'vue'
import { useCoursesStore } from '~/stores/courses'
import { useAuthStore } from '~/stores/auth'
import type { Course } from '~/types/courses'
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'
import DashboardHeader from '~/components/layout/DashboardHeader.vue'
import DashboardSidebar from '~/components/layout/DashboardSidebar.vue'
import StatCard from '~/components/analytics/StatCard.vue'
import SimpleBarChart from '~/components/analytics/SimpleBarChart.vue'
import CreateCourseModal from './modals/create-course.vue'
import UpdateCourseModal from './modals/update-course.vue'
import DeleteCourseModal from './modals/delete-course.vue'
import ManageLessonsModal from './modals/manage-lessons.vue'

definePageMeta({
  middleware: 'auth'
})

const coursesStore = useCoursesStore()
const authStore = useAuthStore()

const showCreateCourseModal = ref(false)
const showUpdateCourseModal = ref(false)
const showDeleteCourseModal = ref(false)
const showManageLessonsModal = ref(false)
const selectedCourseId = ref<number | null>(null)
const selectedCourse = ref<Course | null>(null)

// Icons for stats
const CoursesIcon = h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' })
])

const StudentsIcon = h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' })
])

const EnrollmentsIcon = h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' })
])

const RatingIcon = h('svg', { class: 'w-6 h-6', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z' })
])

// Analytics data (mock data - replace with real data from API)
const totalStudents = ref(156)
const activeEnrollments = ref(423)
const averageRating = ref(4.7)

const courseEnrollmentData = computed(() => {
  return coursesStore.courses.slice(0, 5).map(course => ({
    label: course.title.length > 15 ? course.title.substring(0, 15) + '...' : course.title,
    value: Math.floor(Math.random() * 100) + 20 // Mock data
  }))
})

const courseCompletionData = computed(() => {
  return coursesStore.courses.slice(0, 5).map(course => ({
    label: course.title.length > 15 ? course.title.substring(0, 15) + '...' : course.title,
    value: Math.floor(Math.random() * 40) + 60 // Mock data (60-100%)
  }))
})

onMounted(async () => {
  if (authStore.user) {
    await coursesStore.fetchEducatorCourses(authStore.user.id)
  }
})

const handleAddCourse = () => {
  showCreateCourseModal.value = true
}

const handleEditCourse = (courseId: number) => {
  selectedCourseId.value = courseId
  showUpdateCourseModal.value = true
}

const handleDeleteCourse = (courseId: number) => {
  selectedCourseId.value = courseId
  showDeleteCourseModal.value = true
}

const handleManageLessons = (course: Course) => {
  selectedCourse.value = course
  showManageLessonsModal.value = true
}

const handleCourseCreated = () => {
  showCreateCourseModal.value = false
  if (authStore.user) {
    coursesStore.fetchEducatorCourses(authStore.user.id)
  }
}

const handleCourseUpdated = () => {
  showUpdateCourseModal.value = false
  if (authStore.user) {
    coursesStore.fetchEducatorCourses(authStore.user.id)
  }
}

const handleCourseDeleted = () => {
  showDeleteCourseModal.value = false
}
</script>
