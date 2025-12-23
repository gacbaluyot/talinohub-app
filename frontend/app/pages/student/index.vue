<template>
  <div class="min-h-screen bg-background">
    <!-- Top Navigation Bar -->
    <nav class="bg-background sticky top-0 z-50">
      <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">
          <!-- Logo and Search -->
          <div class="flex items-center flex-1 max-w-2xl">
            <!-- Hamburger Menu -->
            <button
              @click="sidebarExpanded = !sidebarExpanded"
              class="p-2 rounded-lg hover:bg-accent mr-4"
            >
              <svg class="w-6 h-6 text-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
            <h1 class="text-xl font-bold text-primary mr-8">TalinoHub</h1>
            <div class="flex-1 max-w-xl">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search courses..."
                  class="block w-full pl-10 pr-3 py-2 border border-input rounded-lg leading-5 bg-background placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-input sm:text-sm"
                />
              </div>
            </div>
          </div>

          <!-- User Menu -->
          <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->

            <!-- Notifications -->
            <button class="p-2 text-muted-foreground hover:text-foreground rounded-full hover:bg-accent">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
            </button>

            <!-- User Avatar -->
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
                      @click.prevent="currentView = 'my-courses'"
                      :class="[active ? 'bg-accent' : '', 'block px-4 py-2 text-sm text-popover-foreground']"
                    >
                      My Learning
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

    <!-- Main Content -->
    <div class="flex">
      <!-- Left Sidebar -->
      <aside
        :class="[
          sidebarExpanded ? 'w-64' : 'w-20',
          'bg-background min-h-screen sticky top-14 hidden md:block transition-all duration-300'
        ]"
      >
        <nav class="px-3 py-4 space-y-0.5">
          <a
            v-for="item in sidebarItems"
            :key="item.name"
            href="#"
            @click.prevent="currentView = item.id"
            :class="[
              currentView === item.id
                ? 'bg-accent text-accent-foreground'
                : 'text-muted-foreground hover:bg-accent/50 hover:text-accent-foreground',
              'group flex flex-col items-center py-3 text-xs font-medium rounded-lg transition-colors',
              sidebarExpanded && 'flex-row px-3 py-2 text-sm'
            ]"
          >
            <component :is="item.icon" :class="[sidebarExpanded ? 'mr-3 h-4 w-4' : 'h-5 w-5 mb-1']" />
            <span :class="{ 'text-center': !sidebarExpanded }">{{ item.name }}</span>
          </a>
        </nav>
      </aside>

      <!-- Main Content Area -->
      <main class="flex-1">
        <!-- Home View -->
        <div v-if="currentView === 'home'" class="p-4 space-y-6">
          <!-- Continue Learning Section -->
          <section v-if="enrollmentStore.enrolledCourses.length > 0">
            <div class="flex items-center justify-between mb-3">
              <h2 class="text-xl font-bold text-foreground">Continue Learning</h2>
              <a href="#" @click.prevent="currentView = 'my-courses'" class="text-sm text-primary hover:text-primary/80 font-medium">
                View all
              </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
              <div
                v-for="course in enrollmentStore.enrolledCourses.slice(0, 4)"
                :key="course.id"
                @click="handleViewCourse(course)"
                class="group cursor-pointer"
              >
                <div class="relative aspect-video bg-gray-900 rounded-lg overflow-hidden mb-2">
                  <img
                    v-if="course.thumbnail_path"
                    :src="course.thumbnail_path"
                    :alt="course.title"
                    class="w-full h-full object-cover group-hover:opacity-90 transition-opacity"
                  />
                  <div v-else class="flex items-center justify-center h-full bg-gradient-to-br from-indigo-500 to-purple-600">
                    <svg class="h-12 w-12 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <!-- Progress Bar -->
                  <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-700">
                    <div class="h-full bg-red-600" :style="{ width: '35%' }"></div>
                  </div>
                </div>
                <h3 class="text-sm font-semibold text-foreground line-clamp-2 mb-1">{{ course.title }}</h3>
                <p class="text-xs text-muted-foreground">{{ course.educator?.name || 'Educator' }}</p>
              </div>
            </div>
          </section>

          <!-- Featured/Recommended Courses -->
          <section>
            <div class="flex items-center justify-between mb-3">
              <h2 class="text-xl font-bold text-foreground">Recommended for you</h2>
            </div>
            <div v-if="coursesStore.loading" class="text-center py-12">
              <div class="inline-block h-10 w-10 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
            </div>
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
              <div
                v-for="course in displayedCourses"
                :key="course.id"
                @click="handleViewCourse(course)"
                class="group cursor-pointer"
              >
                <div class="relative aspect-video bg-gray-900 rounded-lg overflow-hidden mb-2">
                  <img
                    v-if="course.thumbnail_path"
                    :src="course.thumbnail_path"
                    :alt="course.title"
                    class="w-full h-full object-cover group-hover:opacity-90 transition-opacity"
                  />
                  <div v-else class="flex items-center justify-center h-full bg-gradient-to-br from-indigo-500 to-purple-600">
                    <svg class="h-12 w-12 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <!-- Duration Badge -->
                  <div class="absolute bottom-2 right-2 bg-black/80 dark:bg-white/20 text-white text-xs px-2 py-1 rounded">
                    {{ course.lessons_count || 0 }} lessons
                  </div>
                </div>
                <h3 class="text-sm font-semibold text-foreground line-clamp-2 mb-1">{{ course.title }}</h3>
                <p class="text-xs text-muted-foreground mb-1">{{ course.educator?.name || 'Educator' }}</p>
                <div class="flex items-center gap-2">
                  <div class="flex items-center">
                    <svg class="h-4 w-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-xs text-muted-foreground ml-1">4.8</span>
                  </div>
                  <span class="text-xs text-muted-foreground">•</span>
                  <span
                    v-if="course.is_free"
                    class="text-xs font-semibold text-green-600 dark:text-green-500"
                  >
                    Free
                  </span>
                  <span
                    v-else
                    class="text-xs font-semibold text-foreground"
                  >
                    ${{ course.price }}
                  </span>
                </div>
              </div>
            </div>
          </section>

          <!-- Categories -->
          <section>
            <h2 class="text-xl font-bold text-foreground mb-3">Browse by Category</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
              <button
                v-for="category in categories"
                :key="category.id"
                @click="handleCategoryClick(category.id)"
                class="p-4 bg-card rounded-lg hover:bg-accent/50 transition-all group"
              >
                <component :is="category.icon" class="h-6 w-6 mx-auto mb-2 text-muted-foreground group-hover:text-primary" />
                <p class="text-sm font-medium text-card-foreground text-center">{{ category.name }}</p>
              </button>
            </div>
          </section>
        </div>

        <!-- My Courses View -->
        <div v-else-if="currentView === 'my-courses'" class="p-4">
          <h1 class="text-2xl font-bold text-foreground mb-4">My Courses</h1>

          <div v-if="enrollmentStore.loading" class="text-center py-12">
            <div class="inline-block h-10 w-10 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
          </div>

          <div v-else-if="enrollmentStore.enrolledCourses.length === 0" class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-foreground">No enrolled courses</h3>
            <p class="mt-2 text-sm text-muted-foreground">Start learning by enrolling in a course.</p>
            <button
              @click="currentView = 'home'"
              class="mt-6 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
            >
              Browse Courses
            </button>
          </div>

          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <div
              v-for="course in enrollmentStore.enrolledCourses"
              :key="course.id"
              @click="handleViewCourse(course)"
              class="group cursor-pointer"
            >
              <div class="relative aspect-video bg-gray-900 rounded-lg overflow-hidden mb-2">
                <img
                  v-if="course.thumbnail_path"
                  :src="course.thumbnail_path"
                  :alt="course.title"
                  class="w-full h-full object-cover group-hover:opacity-90 transition-opacity"
                />
                <div v-else class="flex items-center justify-center h-full bg-gradient-to-br from-indigo-500 to-purple-600">
                  <svg class="h-12 w-12 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-700">
                  <div class="h-full bg-red-600" :style="{ width: '35%' }"></div>
                </div>
              </div>
              <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1">{{ course.title }}</h3>
              <p class="text-xs text-gray-500">{{ course.educator?.name || 'Educator' }}</p>
            </div>
          </div>
        </div>

        <!-- Browse View -->
        <div v-else-if="currentView === 'browse'" class="p-4">
          <h1 class="text-2xl font-bold text-foreground mb-4">Browse Courses</h1>

          <!-- Filters -->
          <div class="mb-4 flex flex-wrap gap-3">
            <select
              v-model="selectedCategory"
              class="px-4 py-2 border border-input rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-ring"
            >
              <option value="">All Categories</option>
              <option value="programming">Programming</option>
              <option value="design">Design</option>
              <option value="business">Business</option>
              <option value="marketing">Marketing</option>
            </select>

            <select
              v-model="selectedLevel"
              class="px-4 py-2 border border-input rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-ring"
            >
              <option value="">All Levels</option>
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
            </select>

            <select
              v-model="priceFilter"
              class="px-4 py-2 border border-input rounded-lg bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-ring"
            >
              <option value="">All Prices</option>
              <option value="free">Free</option>
              <option value="paid">Paid</option>
            </select>
          </div>

          <div v-if="coursesStore.loading" class="text-center py-12">
            <div class="inline-block h-10 w-10 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
          </div>

          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <div
              v-for="course in filteredCourses"
              :key="course.id"
              @click="handleViewCourse(course)"
              class="group cursor-pointer"
            >
              <div class="relative aspect-video bg-gray-900 rounded-lg overflow-hidden mb-2">
                <img
                  v-if="course.thumbnail_path"
                  :src="course.thumbnail_path"
                  :alt="course.title"
                  class="w-full h-full object-cover group-hover:opacity-90 transition-opacity"
                />
                <div v-else class="flex items-center justify-center h-full bg-gradient-to-br from-indigo-500 to-purple-600">
                  <svg class="h-12 w-12 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="absolute bottom-2 right-2 bg-black/80 text-white text-xs px-2 py-1 rounded">
                  {{ course.lessons_count || 0 }} lessons
                </div>
              </div>
              <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1">{{ course.title }}</h3>
              <p class="text-xs text-gray-500 mb-1">{{ course.educator?.name || 'Educator' }}</p>
              <div class="flex items-center gap-2">
                <div class="flex items-center">
                  <svg class="h-4 w-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                  <span class="text-xs text-gray-600 ml-1">4.8</span>
                </div>
                <span class="text-xs text-gray-400">•</span>
                <span
                  v-if="course.is_free"
                  class="text-xs font-semibold text-green-600"
                >
                  Free
                </span>
                <span
                  v-else
                  class="text-xs font-semibold text-gray-900"
                >
                  ${{ course.price }}
                </span>
              </div>
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
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, h } from 'vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { useCoursesStore } from '~/stores/courses'
import { useEnrollmentStore } from '~/stores/enrollment'
import { useAuthStore } from '~/stores/auth'
import type { Course } from '~/types/courses'
import CourseDetailModal from './components/CourseDetailModal.vue'
import ThemeToggle from '~/components/ThemeToggle.vue'

definePageMeta({
  middleware: 'auth'
})

useSeoMeta({
  title: 'Student Dashboard',
  ogTitle: 'My Amazing Site',
  description: 'This is my amazing site, let me tell you all about it.',
  ogDescription: 'This is my amazing site, let me tell you all about it.',
  ogImage: 'https://example.com/image.png',
  twitterCard: 'summary_large_image',
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
const sidebarExpanded = ref(false)

const userInitials = computed(() => {
  const name = authStore.user?.name || 'U'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

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
  let courses = coursesStore.courses.filter(c => c.is_published)

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
  selectedCourse.value = course
  showCourseModal.value = true
}

const isEnrolled = (courseId: number | undefined) => {
  if (!courseId) return false
  return enrollmentStore.enrolledCourses.some(c => c.id === courseId)
}

const handleEnroll = async (courseId: number) => {
  try {
    await enrollmentStore.enrollInCourse(courseId)
    showCourseModal.value = false
    // Refresh enrolled courses
    await enrollmentStore.fetchMyEnrollments()
  } catch (error) {
    console.error('Failed to enroll:', error)
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
