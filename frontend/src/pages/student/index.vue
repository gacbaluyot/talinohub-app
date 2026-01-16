<template>
  <div class="min-h-screen bg-white dark:bg-zinc-950">
    <!-- Top Navigation Bar -->
    <StudentNavbar
      v-model:search-query="searchQuery"
      @toggle-sidebar="sidebarExpanded = !sidebarExpanded"
      @navigate="currentView = $event"
      @logout="handleLogout"
    />

    <!-- Main Content -->
    <div class="flex">
      <!-- Left Sidebar -->
      <StudentSidebar
        v-model:expanded="sidebarExpanded"
        :current-view="currentView"
        :items="sidebarItems"
        @navigate="currentView = $event"
      />

      <!-- Main Content Area -->
      <main class="flex-1">
        <!-- Home View -->
        <div v-if="currentView === 'home'" class="p-6 lg:p-8 space-y-8">
          <!-- Recommended Courses -->
          <section>
            <div class="flex items-center justify-between mb-6">
              <div>
                <h2 class="text-2xl font-bold text-foreground">Recommended for you</h2>
                <p class="text-sm text-muted-foreground mt-1">Courses we think you'll love</p>
              </div>
            </div>

            <div v-if="coursesStore.loading" class="flex flex-col items-center justify-center py-16">
              <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
              <p class="mt-4 text-sm text-muted-foreground">Loading courses...</p>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              <div
                v-for="course in displayedCourses"
                :key="course.id"
                @click.stop="handleViewCourse(course)"
                @keydown.enter="handleViewCourse(course)"
                @keydown.space.prevent="handleViewCourse(course)"
                class="cursor-pointer"
                role="button"
                tabindex="0"
              >
              <Card
                class="hover:shadow-lg transition-all duration-200 h-full pointer-events-none"
              >
                <div class="relative aspect-video">
                  <img
                    v-if="course.thumbnail_path"
                    :src="course.thumbnail_path"
                    :alt="course.title"
                    class="w-full h-full object-cover rounded-t-xl"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center rounded-t-xl" style="background: hsl(0, 0%, 45.1%)">
                    <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                  </div>
                  <div class="absolute top-3 right-3">
                    <Badge variant="secondary" class="!bg-gray-900/80 dark:!bg-gray-900/80 !text-white border-0 backdrop-blur-sm">
                      {{ course.lessons_count || 0 }} lessons
                    </Badge>
                  </div>
                </div>
                <CardHeader>
                  <CardTitle class="text-base line-clamp-2 min-h-[3rem]">
                    {{ course.title }}
                  </CardTitle>
                  <CardDescription class="mb-3">
                    {{ course.educator?.name || 'Educator' }}
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                      <svg class="w-4 h-4 text-amber-400 fill-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <span class="text-sm font-semibold text-foreground">4.8</span>
                    </div>
                    <Badge v-if="course.is_free" variant="success">
                      Free
                    </Badge>
                    <Badge v-else variant="info">
                      ${{ course.price }}
                    </Badge>
                  </div>
                </CardContent>
              </Card>
              </div>
            </div>
          </section>

          <!-- Categories -->
          <section>
            <div class="mb-6">
              <h2 class="text-2xl font-bold text-foreground">Browse by Category</h2>
              <p class="text-sm text-muted-foreground mt-1">Explore courses in different fields</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
              <div
                v-for="category in categories"
                :key="category.id"
                @click.stop="handleCategoryClick(category.id)"
                class="cursor-pointer"
                role="button"
                tabindex="0"
              >
              <Card
                class="hover:shadow-md transition-all duration-200 text-center h-full"
              >
                <CardContent class="pt-6">
                  <div class="flex flex-col items-center gap-3 py-2">
                    <component :is="category.icon" class="w-10 h-10 text-primary" />
                    <p class="text-sm font-semibold text-surface-900 dark:text-gray-200">{{ category.name }}</p>
                  </div>
                </CardContent>
              </Card>
              </div>
            </div>
          </section>
        </div>

        <!-- My Courses View -->
        <div v-else-if="currentView === 'my-courses'" class="p-6 lg:p-8">
          <div class="mb-6">
            <h1 class="text-3xl font-bold text-foreground">My Courses</h1>
            <p class="text-sm text-muted-foreground mt-2">All your enrolled courses in one place</p>
          </div>

          <div v-if="enrollmentStore.loading" class="flex flex-col items-center justify-center py-16">
            <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
            <p class="mt-4 text-sm text-muted-foreground">Loading your courses...</p>
          </div>

          <Card v-else-if="enrollmentStore.enrolledCourses.length === 0">
            <CardContent class="pt-6">
              <div class="text-center py-16">
                <svg class="mx-auto w-16 h-16 text-surface-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="mt-6 text-xl font-semibold text-foreground">No enrolled courses</h3>
                <p class="mt-2 text-sm text-muted-foreground max-w-md mx-auto">
                  Start learning by enrolling in a course. Browse our catalog to find courses that interest you.
                </p>
                <Button
                  @click="currentView = 'home'"
                  size="lg"
                  class="mt-6"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                  Browse Courses
                </Button>
              </div>
            </CardContent>
          </Card>

          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
            <div
              v-for="course in enrollmentStore.enrolledCourses"
              :key="course.id"
              @click.stop="handleViewCourse(course)"
              @keydown.enter="handleViewCourse(course)"
              @keydown.space.prevent="handleViewCourse(course)"
              class="cursor-pointer"
              role="button"
              tabindex="0"
            >
            <Card
              class="hover:shadow-lg transition-all duration-200 h-full pointer-events-none"
            >
              <div class="relative aspect-video">
                <img
                  v-if="course.thumbnail_path"
                  :src="course.thumbnail_path"
                  :alt="course.title"
                  class="w-full h-full object-cover rounded-t-xl"
                />
                <div v-else class="w-full h-full flex items-center justify-center rounded-t-xl" style="background: hsl(0, 0%, 45.1%)">
                  <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-surface-200 dark:bg-surface-700">
                  <div class="h-full bg-indigo-600" :style="{ width: '35%' }"></div>
                </div>
              </div>
              <CardHeader>
                <CardTitle class="text-base line-clamp-2 min-h-[3rem]">
                  {{ course.title }}
                </CardTitle>
                <CardDescription>
                  {{ course.educator?.name || 'Educator' }}
                </CardDescription>
              </CardHeader>
            </Card>
            </div>
          </div>
        </div>

        <!-- Browse View -->
        <div v-else-if="currentView === 'browse'" class="p-6 lg:p-8">
          <div class="mb-6">
            <h1 class="text-3xl font-bold text-foreground">Browse Courses</h1>
            <p class="text-sm text-muted-foreground mt-2">Discover courses to expand your skills</p>
          </div>

          <!-- Filters -->
          <Card class="mb-6">
            <CardContent class="pt-6">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-foreground mb-2">Category</label>
                  <Select
                    v-model="selectedCategory"
                    class="w-full"
                  >
                    <option value="">All Categories</option>
                    <option value="programming">Programming</option>
                    <option value="design">Design</option>
                    <option value="business">Business</option>
                    <option value="marketing">Marketing</option>
                  </Select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-foreground mb-2">Level</label>
                  <Select
                    v-model="selectedLevel"
                    class="w-full"
                  >
                    <option value="">All Levels</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                  </Select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-foreground mb-2">Price</label>
                  <Select
                    v-model="priceFilter"
                    class="w-full"
                  >
                    <option value="">All Prices</option>
                    <option value="free">Free</option>
                    <option value="paid">Paid</option>
                  </Select>
                </div>
              </div>
            </CardContent>
          </Card>

          <div v-if="coursesStore.loading" class="flex flex-col items-center justify-center py-16">
            <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
            <p class="mt-4 text-sm text-muted-foreground">Loading courses...</p>
          </div>

          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
            <div
              v-for="course in filteredCourses"
              :key="course.id"
              @click.stop="handleViewCourse(course)"
              @keydown.enter="handleViewCourse(course)"
              @keydown.space.prevent="handleViewCourse(course)"
              class="cursor-pointer"
              role="button"
              tabindex="0"
            >
            <Card
              class="hover:shadow-lg transition-all duration-200 h-full"
            >
              <div class="relative aspect-video">
                <img
                  v-if="course.thumbnail_path"
                  :src="course.thumbnail_path"
                  :alt="course.title"
                  class="w-full h-full object-cover rounded-t-xl"
                />
                <div v-else class="w-full h-full flex items-center justify-center rounded-t-xl" style="background: hsl(0, 0%, 45.1%)">
                  <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
                <div class="absolute top-3 right-3">
                  <Badge variant="secondary" class="!bg-gray-900/80 dark:!bg-gray-900/80 !text-white border-0 backdrop-blur-sm">
                    {{ course.lessons_count || 0 }} lessons
                  </Badge>
                </div>
              </div>
              <CardHeader>
                <CardTitle class="text-base line-clamp-2 min-h-[3rem]">
                  {{ course.title }}
                </CardTitle>
                <CardDescription class="mb-3">
                  {{ course.educator?.name || 'Educator' }}
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-400 fill-amber-400" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-sm font-semibold text-foreground">4.8</span>
                  </div>
                  <Badge v-if="course.is_free" variant="success">
                    Free
                  </Badge>
                  <Badge v-else variant="info">
                    ${{ course.price }}
                  </Badge>
                </div>
              </CardContent>
            </Card>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Course Detail Modal -->
    <CourseDetailModal
      :show="showCourseModal"
      :course-id="selectedCourse?.id || null"
      :enrolled="isEnrolled(selectedCourse?.id)"
      @close="showCourseModal = false"
      @enroll="handleEnroll"
    />

    <!-- Toast Notification -->
    <Toast
      :show="toast.show"
      :message="toast.message"
      :variant="toast.variant"
      @close="toast.show = false"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, h } from 'vue'
import { useCoursesStore } from '~/stores/courses'
import { useEnrollmentStore } from '~/stores/enrollment'
import { useAuthStore } from '~/stores/auth'
import type { Course } from '~/types/courses'
import StudentNavbar from '~/components/student/StudentNavbar.vue'
import StudentSidebar from '~/components/student/StudentSidebar.vue'
import CourseDetailModal from './components/CourseDetailModal.vue'
import Button from '~/components/ui/Button.vue'
import Card from '~/components/ui/Card.vue'
import CardHeader from '~/components/ui/CardHeader.vue'
import CardTitle from '~/components/ui/CardTitle.vue'
import CardDescription from '~/components/ui/CardDescription.vue'
import CardContent from '~/components/ui/CardContent.vue'
import Badge from '~/components/ui/Badge.vue'
import Select from '~/components/ui/Select.vue'
import Toast from '~/components/ui/Toast.vue'

definePageMeta({
  middleware: 'auth'
})

const coursesStore = useCoursesStore()
const enrollmentStore = useEnrollmentStore()
const authStore = useAuthStore()

const currentView = ref('home')
const searchQuery = ref('')
const selectedCategory = ref('')
const selectedLevel = ref('')
const priceFilter = ref('')
const showCourseModal = ref(false)
const selectedCourse = ref<Course | null>(null)
const sidebarExpanded = ref(true)
const toast = ref({
  show: false,
  message: '',
  variant: 'info' as 'success' | 'error' | 'warning' | 'info'
})

const showToast = (message: string, variant: 'success' | 'error' | 'warning' | 'info' = 'info') => {
  toast.value = { show: true, message, variant }
  setTimeout(() => {
    toast.value.show = false
  }, 5000)
}

// Sidebar items
const sidebarItems = [
  {
    id: 'home',
    name: 'Home',
    icon: h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' })
    ])
  },
  {
    id: 'my-courses',
    name: 'My Courses',
    icon: h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' })
    ])
  },
  {
    id: 'browse',
    name: 'Browse',
    icon: h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z' })
    ])
  }
]

// Categories
const categories = [
  {
    id: 'programming',
    name: 'Programming',
    icon: h('svg', { class: 'w-8 h-8', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4' })
    ])
  },
  {
    id: 'design',
    name: 'Design',
    icon: h('svg', { class: 'w-8 h-8', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01' })
    ])
  },
  {
    id: 'business',
    name: 'Business',
    icon: h('svg', { class: 'w-8 h-8', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' })
    ])
  },
  {
    id: 'marketing',
    name: 'Marketing',
    icon: h('svg', { class: 'w-8 h-8', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z' })
    ])
  },
  {
    id: 'personal',
    name: 'Personal Dev',
    icon: h('svg', { class: 'w-8 h-8', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' })
    ])
  }
]

const displayedCourses = computed(() => {
  // Filter out enrolled courses from recommendations
  let courses = coursesStore.courses.filter(c => 
    c.is_published && !isEnrolled(c.id)
  )

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    courses = courses.filter(course =>
      course.title.toLowerCase().includes(query) ||
      course.short_description?.toLowerCase().includes(query)
    )
  }

  return courses.slice(0, 12)
})

const filteredCourses = computed(() => {
  let courses = coursesStore.courses.filter(c => c.is_published)

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    courses = courses.filter(course =>
      course.title.toLowerCase().includes(query) ||
      course.short_description?.toLowerCase().includes(query)
    )
  }

  if (selectedCategory.value) {
    courses = courses.filter(c => c.category === selectedCategory.value)
  }

  if (selectedLevel.value) {
    courses = courses.filter(c => c.level === selectedLevel.value)
  }

  if (priceFilter.value === 'free') {
    courses = courses.filter(c => c.is_free)
  } else if (priceFilter.value === 'paid') {
    courses = courses.filter(c => !c.is_free)
  }

  return courses
})

const handleLogout = async () => {
  await authStore.logout()
  await navigateTo('/auth/login', { replace: true })
}

const handleViewCourse = (course: Course) => {
  console.log('Viewing course:', course.title, 'Enrolled:', isEnrolled(course.id))
  // Check if user is enrolled
  if (isEnrolled(course.id)) {
    // Navigate to enrolled course page
    console.log('Navigating to enrolled course...')
    navigateTo(`/student/courses/${course.id}`)
  } else {
    // Show course details modal for enrollment
    console.log('Showing course modal for enrollment...')
    selectedCourse.value = course
    showCourseModal.value = true
  }
}

const isEnrolled = (courseId: number | undefined) => {
  if (!courseId) return false
  return enrollmentStore.enrolledCourses.some(c => c.id === courseId)
}

const handleEnroll = async (courseId: number) => {
  try {
    const course = coursesStore.courses.find(c => c.id === courseId)

    // Check if course is free
    if (course?.is_free) {
      // Free course - direct enrollment
      await enrollmentStore.enrollInCourse(courseId)
      showCourseModal.value = false
      await enrollmentStore.fetchMyEnrollments()
      showToast('Successfully enrolled in the course!', 'success')
    } else {
      // Paid course - show message
      showCourseModal.value = false
      showToast('Payment integration coming soon! This course costs $' + (course?.price || '0'), 'warning')
    }
  } catch (error: any) {
    console.error('Failed to enroll:', error)
    showCourseModal.value = false
    // Show user-friendly error message
    const errorMessage = error?.response?.data?.message || 'Failed to enroll in course. Please try again.'
    showToast(errorMessage, 'error')
  }
}

const handleCategoryClick = (categoryId: string) => {
  selectedCategory.value = categoryId
  currentView.value = 'browse'
}

onMounted(async () => {
  await coursesStore.fetchCourses()
  if (authStore.user) {
    await enrollmentStore.fetchMyEnrollments()
  }
})
</script>
