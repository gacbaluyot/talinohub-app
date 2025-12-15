<template>
  <TransitionRoot as="template" :show="show">
    <Dialog class="relative z-50" @close="$emit('close')">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl sm:p-6">
              <!-- Loading State -->
              <div v-if="blogsStore.loading" class="text-center py-12">
                <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
                <p class="mt-2 text-sm text-gray-700">Loading blog...</p>
              </div>

              <!-- Error State -->
              <div v-else-if="blogsStore.error" class="rounded-md bg-red-50 p-4">
                <p class="text-sm text-red-800">{{ blogsStore.error }}</p>
              </div>

              <!-- Blog Content -->
              <div v-else-if="blogsStore.blog">
                <div class="mt-3 sm:mt-5">
                  <DialogTitle as="h3" class="text-2xl font-bold text-gray-900">
                    {{ blogsStore.blog.title }}
                  </DialogTitle>
                  
                  <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                    <div class="flex items-center">
                      <svg class="mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      {{ blogsStore.blog.user?.name || 'Unknown Author' }}
                    </div>
                    <div class="flex items-center">
                      <svg class="mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      {{ formatDate(blogsStore.blog.created_at) }}
                    </div>
                  </div>

                  <div class="mt-6 prose prose-sm max-w-none">
                    <div class="text-gray-700 leading-relaxed" v-html="blogsStore.blog.content"></div>
                  </div>
                </div>
              </div>

              <!-- No Data State -->
              <div v-else class="text-center py-12">
                <p class="text-sm text-gray-500">No blog data available</p>
                <p class="text-xs text-gray-400 mt-2">UUID: {{ props.blogUuid }}</p>
              </div>
              
              <!-- Close Button -->
              <div class="mt-5 sm:mt-6">
                <button
                  type="button"
                  @click="$emit('close')"
                  class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                  Close
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup lang="ts">
import { watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { useBlogsStore } from '~/stores/blogs'

interface Props {
  show: boolean
  blogUuid: string | null
}

const props = defineProps<Props>()

defineEmits<{
  close: []
}>()

const blogsStore = useBlogsStore()

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Fetch blog when modal opens
watch(() => props.show, async (newValue) => {
  if (newValue && props.blogUuid) {
    blogsStore.clearBlog() // Clear previous blog data
    await blogsStore.fetchBlog(props.blogUuid)
  } else if (!newValue) {
    blogsStore.clearBlog() // Clear when closing
  }
}, { immediate: true })
</script>

<style>
.prose {
  color: #374151;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
  color: #111827;
  font-weight: 600;
  margin-top: 1.5em;
  margin-bottom: 0.5em;
}

.prose p {
  margin-bottom: 1em;
}

.prose ul, .prose ol {
  margin-bottom: 1em;
  padding-left: 1.5em;
}

.prose strong {
  font-weight: 600;
}

.prose em {
  font-style: italic;
}

.prose a {
  color: #4f46e5;
  text-decoration: underline;
}
</style>