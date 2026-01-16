<template>
  <div class="min-h-screen bg-surface-50 dark:bg-surface-950">
    <!-- Header -->
    <DashboardHeader @toggle-sidebar="sidebarCollapsed = !sidebarCollapsed" />
    <div class="flex">
      <!-- Sidebar -->
      <DashboardSidebar
        :is-collapsed="sidebarCollapsed"
        @create-course="handleAddCourse"
        @update:is-collapsed="sidebarCollapsed = $event"
      />

      <!-- Main Content -->
      <main class="flex-1 p-4 md:p-6 lg:p-8">
        <!-- Analytics Section -->
        <div class="mb-6 md:mb-8">
          <h2 class="text-xl md:text-2xl font-bold text-surface-900 dark:text-surface-0 mb-4 md:mb-6">Dashboard Overview</h2>

          <!-- Stats Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-4 md:mb-6">
            <StatCard
              title="Total Courses"
              :value="coursesStore.courses.length"
              changeText="+2 this month"
              period="vs last month"
              trend="up"
              :icon="stats.CoursesIcon"
              iconBgColor="bg-indigo-500"
            />
            <StatCard
              title="Total Students"
              :value="stats.totalStudents.value"
              changeText="+12% increase"
              period="vs last month"
              trend="up"
              :icon="stats.StudentsIcon"
              iconBgColor="bg-green-500"
            />
            <StatCard
              title="Active Enrollments"
              :value="stats.activeEnrollments.value"
              changeText="+8% increase"
              period="vs last month"
              trend="up"
              :icon="stats.EnrollmentsIcon"
              iconBgColor="bg-blue-500"
            />
            <StatCard
              title="Avg. Rating"
              :value="stats.averageRating.value.toFixed(1)"
              changeText="+0.2 points"
              period="vs last month"
              trend="up"
              :icon="stats.RatingIcon"
              iconBgColor="bg-yellow-500"
            />
          </div>

          <!-- Charts -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
            <SimpleBarChart
              title="Course Enrollments"
              :data="stats.courseEnrollmentData.value"
              barColor="bg-indigo-500"
            />
            <SimpleBarChart
              title="Course Completion Rate"
              :data="stats.courseCompletionData.value"
              barColor="bg-green-500"
            />
          </div>
        </div>

        <!-- Courses Section -->
        <div>
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 md:mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-surface-900 dark:text-surface-0">My Courses</h2>
            <Button @click="handleAddCourse">
              <svg class="inline-block -ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
              </svg>
              Create Course
            </Button>
          </div>

          <!-- Loading State -->
          <div v-if="coursesStore.loading && !coursesStore.courses.length" class="text-center py-12">
            <div class="inline-block h-10 w-10 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
            <p class="mt-3 text-sm text-surface-600 dark:text-surface-400">Loading courses...</p>
          </div>

          <!-- Error State -->
          <div v-else-if="coursesStore.error" class="rounded-md bg-red-50 dark:bg-red-900/20 p-4">
            <div class="flex">
              <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
              </svg>
              <p class="ml-3 text-sm text-red-800 dark:text-red-200">{{ coursesStore.error }}</p>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else-if="coursesStore.courses.length === 0" class="text-center py-12 md:py-16 bg-surface-0 dark:bg-surface-900 rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 md:h-16 md:w-16 text-surface-400 dark:text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h3 class="mt-4 text-base md:text-lg font-semibold text-surface-900 dark:text-surface-0">No courses yet</h3>
            <p class="mt-2 text-sm text-surface-500 dark:text-surface-400 px-4">Get started by creating your first course.</p>
            <div class="mt-6">
              <Button @click="handleAddCourse">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Create Your First Course
              </Button>
            </div>
          </div>

          <!-- Courses Grid -->
          <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 md:gap-6">
            <CourseCard
              v-for="course in coursesStore.courses"
              :key="course.id"
              :course="course"
              @click="navigateTo(`/educator/courses/${course.id}`)"
              @view="navigateTo(`/educator/courses/${$event}`)"
              @preview="navigateTo(`/educator/courses/preview/${$event}`)"
              @edit="handleEditCourse"
              @delete="handleDeleteCourse"
            />
          </div>
        </div>
      </main>
    </div>

    <!-- Modals -->
    <CreateCourseModal
      :show="modals.showCreate"
      @close="modals.showCreate = false"
      @created="handleCourseCreated"
    />

    <UpdateCourseModal
      :show="modals.showUpdate"
      :course-id="modals.selectedCourseId"
      @close="modals.showUpdate = false"
      @updated="handleCourseUpdated"
    />

    <DeleteCourseModal
      :show="modals.showDelete"
      :course-id="modals.selectedCourseId"
      @close="modals.showDelete = false"
      @deleted="handleCourseDeleted"
    />
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed, reactive, ref } from 'vue'
import { useCoursesStore } from '~/stores/courses'
import { useAuthStore } from '~/stores/auth'
import { useEducatorStats } from '~/composables/useEducatorStats'
import DashboardHeader from '~/components/layout/DashboardHeader.vue'
import DashboardSidebar from '~/components/layout/DashboardSidebar.vue'
import StatCard from '~/components/analytics/StatCard.vue'
import SimpleBarChart from '~/components/analytics/SimpleBarChart.vue'
import CourseCard from '~/components/educator/CourseCard.vue'
import Button from '~/components/ui/Button.vue'
import CreateCourseModal from './modals/create-course.vue'
import UpdateCourseModal from './modals/update-course.vue'
import DeleteCourseModal from './modals/delete-course.vue'

definePageMeta({
  middleware: 'auth'
})

const coursesStore = useCoursesStore()
const authStore = useAuthStore()

// Sidebar state - collapsed by default on mobile
const sidebarCollapsed = ref(true)

// Use composable for stats
const courses = computed(() => coursesStore.courses)
const stats = useEducatorStats(courses)

// Modal state
const modals = reactive({
  showCreate: false,
  showUpdate: false,
  showDelete: false,
  selectedCourseId: null as number | null
})

// Handlers
const handleAddCourse = () => {
  modals.showCreate = true
}

const handleEditCourse = (courseId: number) => {
  modals.selectedCourseId = courseId
  modals.showUpdate = true
}

const handleDeleteCourse = (courseId: number) => {
  modals.selectedCourseId = courseId
  modals.showDelete = true
}

const handleCourseCreated = () => {
  modals.showCreate = false
  if (authStore.user) {
    coursesStore.fetchEducatorCourses(authStore.user.id)
  }
}

const handleCourseUpdated = () => {
  modals.showUpdate = false
  if (authStore.user) {
    coursesStore.fetchEducatorCourses(authStore.user.id)
  }
}

const handleCourseDeleted = () => {
  modals.showDelete = false
}

onMounted(async () => {
  if (authStore.user) {
    await coursesStore.fetchEducatorCourses(authStore.user.id)
  }
})
</script>
