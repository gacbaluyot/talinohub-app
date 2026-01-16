<template>
  <div>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold  text-surface-900 dark:text-surface-0">Blogs</h1>
        <p class="mt-2 text-sm text-surface-600 dark:text-surface-400">A list of all blogs including their title, content, and author.</p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <button 
          type="button" 
          @click="handleAddBlog"
          class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          Add blog
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="blogsStore.loading" class="mt-8 text-center">
      <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
      <p class="mt-2 text-sm text-surface-700 dark:text-surface-300 ">Loading blogs...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="blogsStore.error" class="mt-8 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
      <p class="text-sm text-red-800 dark:text-red-400">{{ blogsStore.error }}</p>
    </div>

    <!-- Blogs Table -->
    <div v-else class="mt-8">
      <div class="overflow-hidden shadow sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-surface-900 dark:text-surface-0 sm:pl-6">Title</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-surface-900 dark:text-surface-0">Created</th>
              <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                <span class="sr-only">Actions</span>
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="blogsStore.blogs.length === 0">
              <td colspan="3" class="py-16 text-center">
                <div class="flex flex-col items-center">
                  <svg class="h-12 w-12 text-surface-400 dark:text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <h3 class="mt-2 text-sm font-semibold text-surface-900 dark:text-surface-0">No blogs</h3>
                  <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Get started by creating a new blog post.</p>
                  <div class="mt-6">
                    <button
                      @click="handleAddBlog"
                      type="button"
                      class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                      <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                      </svg>
                      New Blog
                    </button>
                  </div>
                </div>
              </td>
            </tr>
            <tr v-for="blog in blogsStore.blogs" :key="blog?.uuid || blog?.id" class="hover:bg-gray-50 transition-colors">
              <td class="py-4 pl-4 pr-3 sm:pl-6">
                <div class="flex items-center">
                  <div class="h-10 w-10 shrink-0">
                    <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                      <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-surface-900 dark:text-surface-0">{{ blog?.title || 'Untitled' }}</div>
                    <div class="text-sm text-surface-500 dark:text-surface-400">{{ blog?.user?.name || 'Unknown Author' }}</div>
                  </div>
                </div>
              </td>
              <td class="whitespace-nowrap px-3 py-4">
                <div class="flex items-center text-sm text-surface-500 dark:text-surface-400">
                  <svg class="mr-1.5 h-5 w-5 shrink-0 text-surface-400 dark:text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  {{ blog?.created_at ? formatDate(blog.created_at) : 'N/A' }}
                </div>
              </td>
              <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                <div class="flex items-center justify-end gap-2">
                  <button 
                    v-if="blog?.uuid"
                    @click="handleView(blog.uuid)"
                    class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-surface-900 dark:text-surface-0 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                    title="View"
                  >
                    <EyeIcon class="h-4 w-4" />
                  </button>
                  <button 
                    v-if="blog?.uuid"
                    @click="handleEdit(blog.uuid)"
                    class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-indigo-600 shadow-sm ring-1 ring-inset ring-indigo-300 hover:bg-indigo-50 transition-colors"
                    title="Edit"
                  >
                    <PencilSquareIcon class="h-4 w-4" />
                  </button>
                  <button 
                    v-if="blog?.uuid"
                    @click="handleDelete(blog.uuid)"
                    class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50 transition-colors"
                    title="Delete"
                  >
                    <TrashIcon class="h-4 w-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <Pagination
        v-if="blogsStore.pagination && blogsStore.blogs.length > 0"
        :current-page="blogsStore.pagination.currentPage"
        :last-page="blogsStore.pagination.lastPage"
        :total="blogsStore.pagination.total"
        :from="(blogsStore.pagination.currentPage - 1) * blogsStore.pagination.perPage + 1"
        :to="Math.min(blogsStore.pagination.currentPage * blogsStore.pagination.perPage, blogsStore.pagination.total)"
        @page-change="handlePageChange"
      />
    </div>

    <!-- Modals -->
    <CreateBlogModal 
      :show="showCreateModal" 
      @close="showCreateModal = false"
    />
    
    <ViewBlogModal 
      :show="showViewModal" 
      :blog-uuid="selectedBlogUuid"
      @close="showViewModal = false"
    />
    
    <UpdateBlogModal 
      :show="showUpdateModal" 
      :blog-uuid="selectedBlogUuid"
      @close="showUpdateModal = false"
    />
    
    <DeleteBlogModal 
      :show="showDeleteModal" 
      :blog-uuid="selectedBlogUuid"
      @close="showDeleteModal = false"
    />
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useBlogsStore } from '~/stores/blogs'
import Pagination from '~/pages/components/pagination.vue'
import CreateBlogModal from '~/pages/dashboard/blogs/modals/create.vue'
import ViewBlogModal from '~/pages/dashboard/blogs/modals/view.vue'
import UpdateBlogModal from '~/pages/dashboard/blogs/modals/update.vue'
import DeleteBlogModal from '~/pages/dashboard/blogs/modals/delete.vue'
import { EyeIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'

const blogsStore = useBlogsStore()
const showCreateModal = ref(false)
const showViewModal = ref(false)
const showUpdateModal = ref(false)
const showDeleteModal = ref(false)
const selectedBlogUuid = ref<string | null>(null)

onMounted(async () => {
  await blogsStore.fetchBlogs()
})

const handlePageChange = async (page: number) => {
  await blogsStore.fetchBlogs(page)
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const handleAddBlog = () => {
  showCreateModal.value = true
}

const handleView = (uuid: string) => {
  selectedBlogUuid.value = uuid
  showViewModal.value = true
}

const handleEdit = (uuid: string) => {
  selectedBlogUuid.value = uuid
  showUpdateModal.value = true
}

const handleDelete = (uuid: string) => {
  selectedBlogUuid.value = uuid
  showDeleteModal.value = true
}
</script>