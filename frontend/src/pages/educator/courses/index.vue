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
      <main class="flex-1 p-4 md:p-8">
        <!-- Page Header -->
        <div class="mb-6 md:mb-8">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 class="text-2xl md:text-3xl font-bold text-surface-900 dark:text-surface-0">My Courses</h1>
              <p class="mt-2 text-sm text-surface-600 dark:text-surface-400">Manage and organize all your courses</p>
            </div>
            <div>
              <Button @click="handleAddCourse" size="lg">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Course
              </Button>
            </div>
          </div>
        </div>

        <!-- Filters and Search -->
        <Card class="mb-6">
          <CardContent class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <!-- Search -->
              <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Search</label>
                <div class="relative">
                  <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-surface-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                  <input
                    id="search"
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search by title or description..."
                    class="w-full pl-10 pr-4 py-2 border border-surface-200 dark:border-surface-700 rounded-lg bg-surface-0 dark:bg-surface-900 text-surface-900 dark:text-surface-0 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                  />
                </div>
              </div>

              <!-- Category Filter -->
              <div>
                <label for="category" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Category</label>
                <select
                  id="category"
                  v-model="selectedCategory"
                  class="w-full px-4 py-2 border border-surface-200 dark:border-surface-700 rounded-lg bg-surface-0 dark:bg-surface-900 text-surface-900 dark:text-surface-0 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                  <option v-for="option in categoryOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                  </option>
                </select>
              </div>

              <!-- Status Filter -->
              <div>
                <label for="status" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Status</label>
                <select
                  id="status"
                  v-model="selectedStatus"
                  class="w-full px-4 py-2 border border-surface-200 dark:border-surface-700 rounded-lg bg-surface-0 dark:bg-surface-900 text-surface-900 dark:text-surface-0 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                  <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                  </option>
                </select>
              </div>
            </div>

            <!-- View Toggle -->
            <div class="mt-4 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <Badge variant="secondary">{{ filteredCourses.length }} courses</Badge>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-sm text-surface-700 dark:text-surface-300 mr-2">View:</span>
                <Button
                  @click="viewMode = 'grid'"
                  :variant="viewMode === 'grid' ? 'default' : 'outline'"
                  size="icon"
                  aria-label="Grid View"
                >
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                  </svg>
                </Button>
                <Button
                  @click="viewMode = 'list'"
                  :variant="viewMode === 'list' ? 'default' : 'outline'"
                  size="icon"
                  aria-label="List View"
                >
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
                </Button>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Loading State -->
        <div v-if="coursesStore.loading && !coursesStore.courses.length" class="flex flex-col items-center justify-center py-16">
          <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
          <p class="mt-4 text-sm text-surface-600 dark:text-surface-400">Loading courses...</p>
        </div>

        <!-- Error State -->
        <Alert v-else-if="coursesStore.error" variant="destructive" class="mb-6">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <AlertTitle>Error</AlertTitle>
          <AlertDescription>{{ coursesStore.error }}</AlertDescription>
        </Alert>

        <!-- Empty State -->
        <Card v-else-if="filteredCourses.length === 0">
          <CardContent class="p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-surface-400 dark:text-surface-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-surface-900 dark:text-surface-0">
              {{ searchQuery || selectedCategory || selectedStatus ? 'No courses found' : 'No courses yet' }}
            </h3>
            <p class="mt-2 text-sm text-surface-600 dark:text-surface-400">
              {{ searchQuery || selectedCategory || selectedStatus ? 'Try adjusting your filters' : 'Get started by creating your first course.' }}
            </p>
            <div v-if="!searchQuery && !selectedCategory && !selectedStatus" class="mt-6">
              <Button @click="handleAddCourse" size="lg">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Your First Course
              </Button>
            </div>
          </CardContent>
        </Card>

        <!-- Grid View -->
        <div v-else-if="viewMode === 'grid'" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 md:gap-6">
          <Card
            v-for="course in filteredCourses"
            :key="course.id"
            class="cursor-pointer hover:shadow-lg transition-shadow duration-200 overflow-hidden group"
            @click="navigateTo(`/educator/courses/${course.id}`)"
          >
            <div class="relative aspect-video overflow-hidden">
              <div v-if="course.thumbnail_path" class="w-full h-full">
                <img
                  :src="course.thumbnail_path"
                  :alt="course.title"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                />
              </div>
              <div v-else class="w-full h-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center">
                <svg class="w-16 h-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <div class="absolute top-3 right-3">
                <Badge :variant="course.is_published ? 'success' : 'warning'">
                  {{ course.is_published ? 'Published' : 'Draft' }}
                </Badge>
              </div>
            </div>

            <CardContent class="p-5">
              <div class="text-lg font-semibold text-surface-900 dark:text-surface-0 truncate mb-2">
                {{ course.title }}
              </div>

              <p class="text-sm text-surface-600 dark:text-surface-400 line-clamp-2 min-h-[2.5rem] mb-4">
                {{ course.short_description || 'No description' }}
              </p>

              <div class="flex items-center gap-4 text-sm text-surface-600 dark:text-surface-400 mb-3">
                <div class="flex items-center gap-1.5">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                  <span>{{ course.lessons_count || 0 }} lessons</span>
                </div>
                <div v-if="course.level" class="flex items-center gap-1.5">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                  <span class="capitalize">{{ course.level }}</span>
                </div>
              </div>

              <div class="mb-4">
                <Badge :variant="course.is_free ? 'success' : 'info'">
                  {{ course.is_free ? 'Free' : `$${course.price}` }}
                </Badge>
              </div>

              <div class="flex items-center gap-2">
                <Button
                  @click.stop="navigateTo(`/educator/courses/${course.id}`)"
                  variant="outline"
                  class="flex-1"
                  size="sm"
                >
                  View Details
                </Button>
                <Button
                  @click.stop="handleEditCourse(course.id)"
                  variant="outline"
                  size="icon"
                  class="shrink-0"
                  title="Edit Course"
                >
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </Button>
                <Button
                  @click.stop="handleDeleteCourse(course.id)"
                  variant="destructive"
                  size="icon"
                  class="shrink-0"
                  title="Delete Course"
                >
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- List View -->
        <Card v-else>
          <CardContent class="p-0">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-surface-200 dark:divide-surface-700">
                <thead class="bg-surface-50 dark:bg-surface-800">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Course</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Lessons</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Price</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-surface-0 dark:bg-surface-900 divide-y divide-surface-200 dark:divide-surface-700">
                  <tr
                    v-for="course in filteredCourses"
                    :key="course.id"
                    class="hover:bg-surface-50 dark:hover:bg-surface-800 cursor-pointer transition-colors"
                    @click="navigateTo(`/educator/courses/${course.id}`)"
                  >
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-20 h-12 rounded overflow-hidden">
                          <div v-if="course.thumbnail_path" class="w-full h-full">
                            <img
                              :src="course.thumbnail_path"
                              :alt="course.title"
                              class="w-full h-full object-cover"
                            />
                          </div>
                          <div v-else class="w-full h-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                          </div>
                        </div>
                        <div class="min-w-0 flex-1">
                          <div class="text-sm font-medium text-surface-900 dark:text-surface-0 truncate">
                            {{ course.title }}
                          </div>
                          <div class="text-sm text-surface-600 dark:text-surface-400 truncate">
                            {{ course.short_description || 'No description' }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm capitalize text-surface-900 dark:text-surface-0">{{ course.category || '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm text-surface-900 dark:text-surface-0">{{ course.lessons_count || 0 }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <Badge :variant="course.is_free ? 'success' : 'info'">
                        {{ course.is_free ? 'Free' : `$${course.price}` }}
                      </Badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <Badge :variant="course.is_published ? 'success' : 'warning'">
                        {{ course.is_published ? 'Published' : 'Draft' }}
                      </Badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                      <div class="flex items-center justify-end gap-2">
                        <Button
                          @click.stop="handleEditCourse(course.id)"
                          variant="ghost"
                          size="icon"
                          title="Edit"
                        >
                          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                        </Button>
                        <Button
                          @click.stop="handleDeleteCourse(course.id)"
                          variant="ghost"
                          size="icon"
                          class="text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20"
                          title="Delete"
                        >
                          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                        </Button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </CardContent>
        </Card>
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
import DashboardHeader from '~/components/layout/DashboardHeader.vue'
import DashboardSidebar from '~/components/layout/DashboardSidebar.vue'
import Button from '~/components/ui/Button.vue'
import Card from '~/components/ui/Card.vue'
import CardContent from '~/components/ui/CardContent.vue'
import Badge from '~/components/ui/Badge.vue'
import Alert from '~/components/ui/Alert.vue'
import AlertTitle from '~/components/ui/AlertTitle.vue'
import AlertDescription from '~/components/ui/AlertDescription.vue'
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

const sidebarCollapsed = ref(true)
const searchQuery = ref('')
const selectedCategory = ref('')
const selectedStatus = ref('')
const viewMode = ref<'grid' | 'list'>('grid')

const showCreateCourseModal = ref(false)
const showUpdateCourseModal = ref(false)
const showDeleteCourseModal = ref(false)
const selectedCourseId = ref<number | null>(null)

const categoryOptions = [
  { label: 'All Categories', value: '' },
  { label: 'Programming', value: 'programming' },
  { label: 'Design', value: 'design' },
  { label: 'Business', value: 'business' },
  { label: 'Marketing', value: 'marketing' },
  { label: 'Personal Development', value: 'personal-development' }
]

const statusOptions = [
  { label: 'All Status', value: '' },
  { label: 'Published', value: 'published' },
  { label: 'Draft', value: 'draft' }
]

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
