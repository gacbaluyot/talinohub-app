<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <DashboardHeader />

    <div class="flex">
      <!-- Sidebar -->
      <DashboardSidebar @create-course="handleAddCourse" />

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <!-- Page Header -->
        <div class="mb-8">
          <div class="sm:flex sm:items-center sm:justify-between">
            <div>
              <h1 class="text-3xl font-bold text-gray-900">My Courses</h1>
              <p class="mt-2 text-sm text-gray-700">Manage and organize all your courses</p>
            </div>
            <div class="mt-4 sm:mt-0">
              <button
                @click="handleAddCourse"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors"
              >
                <svg class="inline-block -ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Create Course
              </button>
            </div>
          </div>
        </div>

        <!-- Filters and Search -->
        <div class="mb-6 bg-white rounded-lg shadow p-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
              <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                <input
                  v-model="searchQuery"
                  type="text"
                  id="search"
                  placeholder="Search by title or description..."
                  class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
              </div>
            </div>

            <!-- Category Filter -->
            <div>
              <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
              <select
                v-model="selectedCategory"
                id="category"
                class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              >
                <option value="">All Categories</option>
                <option value="programming">Programming</option>
                <option value="design">Design</option>
                <option value="business">Business</option>
                <option value="marketing">Marketing</option>
                <option value="personal-development">Personal Development</option>
              </select>
            </div>

            <!-- Status Filter -->
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
              <select
                v-model="selectedStatus"
                id="status"
                class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              >
                <option value="">All Status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
              </select>
            </div>
          </div>

          <!-- View Toggle -->
          <div class="mt-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-700">{{ filteredCourses.length }} courses</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-700 mr-2">View:</span>
              <button
                @click="viewMode = 'grid'"
                :class="[
                  viewMode === 'grid' ? 'bg-indigo-100 text-indigo-600' : 'bg-white text-gray-600 hover:bg-gray-50',
                  'p-2 rounded-md transition-colors'
                ]"
                title="Grid View"
              >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
              </button>
              <button
                @click="viewMode = 'list'"
                :class="[
                  viewMode === 'list' ? 'bg-indigo-100 text-indigo-600' : 'bg-white text-gray-600 hover:bg-gray-50',
                  'p-2 rounded-md transition-colors'
                ]"
                title="List View"
              >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>
          </div>
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
        <div v-else-if="filteredCourses.length === 0" class="text-center py-16 bg-white rounded-lg shadow">
          <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
          <h3 class="mt-4 text-lg font-semibold text-gray-900">
            {{ searchQuery || selectedCategory || selectedStatus ? 'No courses found' : 'No courses yet' }}
          </h3>
          <p class="mt-2 text-sm text-gray-500">
            {{ searchQuery || selectedCategory || selectedStatus ? 'Try adjusting your filters' : 'Get started by creating your first course.' }}
          </p>
          <div v-if="!searchQuery && !selectedCategory && !selectedStatus" class="mt-6">
            <button
              @click="handleAddCourse"
              type="button"
              class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
            >
              <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
              </svg>
              Create Your First Course
            </button>
          </div>
        </div>

        <!-- Grid View -->
        <div v-else-if="viewMode === 'grid'" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="course in filteredCourses"
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
              <div class="absolute top-2 right-2">
                <span
                  :class="[
                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                    course.is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                  ]"
                >
                  {{ course.is_published ? 'Published' : 'Draft' }}
                </span>
              </div>
            </div>

            <div class="p-5">
              <h3 class="text-lg font-semibold text-gray-900 truncate">{{ course.title }}</h3>
              <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ course.short_description || 'No description' }}</p>
              
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

              <div class="mt-3">
                <span
                  :class="[
                    'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium',
                    course.is_free ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20' : 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20'
                  ]"
                >
                  {{ course.is_free ? 'Free' : `$${course.price}` }}
                </span>
              </div>

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

        <!-- List View -->
        <div v-else class="bg-white rounded-lg shadow overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Course
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Category
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Lessons
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Price
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr
                v-for="course in filteredCourses"
                :key="course.id"
                class="hover:bg-gray-50 cursor-pointer transition-colors"
                @click="navigateTo(`/educator/courses/${course.id}`)"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded overflow-hidden">
                      <img
                        v-if="course.thumbnail_path"
                        :src="course.thumbnail_path"
                        :alt="course.title"
                        class="h-full w-full object-cover"
                      />
                      <div v-else class="flex items-center justify-center h-full">
                        <svg class="h-6 w-6 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ course.title }}</div>
                      <div class="text-sm text-gray-500 line-clamp-1">{{ course.short_description }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm text-gray-900 capitalize">{{ course.category || '-' }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm text-gray-900">{{ course.lessons_count || 0 }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium',
                      course.is_free ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20' : 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20'
                    ]"
                  >
                    {{ course.is_free ? 'Free' : `$${course.price}` }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                      course.is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                    ]"
                  >
                    {{ course.is_published ? 'Published' : 'Draft' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-2">
                    <button
                      @click.stop="handleEditCourse(course.id)"
                      class="text-indigo-600 hover:text-indigo-900"
                      title="Edit"
                    >
                      <PencilSquareIcon class="h-5 w-5" />
                    </button>
                    <button
                      @click.stop="handleDeleteCourse(course.id)"
                      class="text-red-600 hover:text-red-900"
                      title="Delete"
                    >
                      <TrashIcon class="h-5 w-5" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
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
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'
import DashboardHeader from '~/components/layout/DashboardHeader.vue'
import DashboardSidebar from '~/components/layout/DashboardSidebar.vue'
import CreateCourseModal from '~/pages/educator/modals/create-course.vue'
import UpdateCourseModal from '~/pages/educator/modals/update-course.vue'
import DeleteCourseModal from '~/pages/educator/modals/delete-course.vue'
import { useCoursesStore } from '~/stores/courses'
import { useAuthStore } from '~/stores/auth'

definePageMeta({
  middleware: 'auth'
})

const coursesStore = useCoursesStore()
const authStore = useAuthStore()

const searchQuery = ref('')
const selectedCategory = ref('')
const selectedStatus = ref('')
const viewMode = ref<'grid' | 'list'>('grid')

const showCreateCourseModal = ref(false)
const showUpdateCourseModal = ref(false)
const showDeleteCourseModal = ref(false)
const selectedCourseId = ref<number | null>(null)

const filteredCourses = computed(() => {
  let courses = coursesStore.courses

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    courses = courses.filter(course =>
      course.title.toLowerCase().includes(query) ||
      course.short_description?.toLowerCase().includes(query) ||
      course.description?.toLowerCase().includes(query)
    )
  }

  // Filter by category
  if (selectedCategory.value) {
    courses = courses.filter(course => course.category === selectedCategory.value)
  }

  // Filter by status
  if (selectedStatus.value) {
    const isPublished = selectedStatus.value === 'published'
    courses = courses.filter(course => course.is_published === isPublished)
  }

  return courses
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

onMounted(async () => {
  if (authStore.user) {
    await coursesStore.fetchEducatorCourses(authStore.user.id)
  }
})
</script>
