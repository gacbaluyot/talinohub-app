<template>
  <TransitionRoot as="template" :show="show">
    <Dialog class="relative z-50" @close="$emit('close')">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-75" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
              <div>
                <div class="mt-3 sm:mt-5">
                  <DialogTitle as="h3" class="text-base font-semibold text-gray-900">
                    Create New Blog
                  </DialogTitle>
                  
                  <!-- Error Message -->
                  <div v-if="blogsStore.error" class="mt-4 rounded-md bg-red-50 p-4">
                    <p class="text-sm text-red-800">{{ blogsStore.error }}</p>
                  </div>

                  <form @submit.prevent="handleSubmit" class="mt-6 space-y-6">
                    <!-- Title Field -->
                    <div>
                      <label for="title" class="block text-sm font-medium text-gray-900">
                        Title <span class="text-red-500">*</span>
                      </label>
                      <div class="mt-2">
                        <input
                          v-model="formData.title"
                          type="text"
                          name="title"
                          id="title"
                          required
                          class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 bg-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                          placeholder="Enter blog title"
                        />
                      </div>
                    </div>

                    <!-- Content Field -->
                    <div>
                      <label for="content" class="block text-sm font-medium text-gray-900">
                        Content <span class="text-red-500">*</span>
                      </label>
                      <div class="mt-2">
                        <div ref="editorContainer" class="bg-white rounded-md"></div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              
              <!-- Action Buttons -->
              <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                <button
                  type="submit"
                  @click="handleSubmit"
                  :disabled="blogsStore.loading || !formData.title || !formData.content"
                  class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="blogsStore.loading" class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating...
                  </span>
                  <span v-else>Create Blog</span>
                </button>
                <button
                  type="button"
                  @click="$emit('close')"
                  :disabled="blogsStore.loading"
                  class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Cancel
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
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { useBlogsStore } from '~/stores/blogs'
import type { BlogRequest } from '~/types/blogs'

interface Props {
  show: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  created: []
}>()

const blogsStore = useBlogsStore()
const editorContainer = ref<HTMLElement | null>(null)
let quillInstance: any = null
let Quill: any = null

const formData = ref<BlogRequest>({
  title: '',
  content: ''
})

const initQuill = async () => {
  if (editorContainer.value && !quillInstance && process.client) {
    // Dynamically import Quill only on client side
    if (!Quill) {
      const QuillModule = await import('quill')
      Quill = QuillModule.default
      await import('quill/dist/quill.snow.css')
    }

    quillInstance = new Quill(editorContainer.value, {
      theme: 'snow',
      placeholder: 'Write your blog content here...',
      modules: {
        toolbar: [
          [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          [{ 'indent': '-1'}, { 'indent': '+1' }],
          [{ 'align': [] }],
          ['link', 'image'],
          ['clean']
        ]
      }
    })

    // Sync Quill content with formData
    quillInstance.on('text-change', () => {
      if (quillInstance) {
        formData.value.content = quillInstance.root.innerHTML
      }
    })
  }
}

const destroyQuill = () => {
  if (quillInstance) {
    // Remove all event listeners and cleanup
    quillInstance.off('text-change')
    quillInstance = null
  }
  // Clear the container to remove Quill's DOM elements
  if (editorContainer.value) {
    editorContainer.value.innerHTML = ''
  }
}

// Initialize Quill when modal opens
watch(() => props.show, (newValue) => {
  if (newValue) {
    // Destroy any existing instance first
    destroyQuill()
    setTimeout(() => {
      initQuill()
    }, 100)
  } else if (!newValue) {
    // Destroy when closing
    destroyQuill()
  }
})

onMounted(() => {
  if (props.show) {
    initQuill()
  }
})

onBeforeUnmount(() => {
  destroyQuill()
})

const handleSubmit = async () => {
  if (!formData.value.title || !formData.value.content) {
    return
  }

  try {
    await blogsStore.createBlog(formData.value)
    
    // Reset form
    formData.value = {
      title: '',
      content: ''
    }
    
    // Emit success and close
    emit('created')
    emit('close')
  } catch (error) {
    // Error is already handled in the store
    console.error('Failed to create blog:', error)
  }
}
</script>

<style>
.ql-container {
  min-height: 250px;
  font-size: 14px;
}

.ql-editor {
  min-height: 250px;
}

.ql-toolbar {
  border-top-left-radius: 0.375rem;
  border-top-right-radius: 0.375rem;
}

.ql-container {
  border-bottom-left-radius: 0.375rem;
  border-bottom-right-radius: 0.375rem;
}
</style>