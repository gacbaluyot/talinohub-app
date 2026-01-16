<template>
  <BaseModal :show="show" @close="handleClose" size="large">
    <template #header>
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
        </div>
        <div>
          <h3 class="text-2xl font-bold text-surface-900 dark:text-surface-0">Update Blog Post</h3>
          <p class="text-sm text-surface-600 dark:text-surface-400">Edit your blog post</p>
        </div>
      </div>
    </template>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <div>
        <label for="title" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">
          Title <span class="text-red-500">*</span>
        </label>
        <BaseInput
          id="title"
          v-model="formData.title"
          placeholder="Enter blog post title"
          required
        />
      </div>

      <div>
        <label for="content" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">
          Content <span class="text-red-500">*</span>
        </label>
        <BaseTextarea
          id="content"
          v-model="formData.content"
          placeholder="Write your blog post content..."
          rows="12"
          required
        />
      </div>

      <div>
        <label for="category" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Category</label>
        <BaseSelect v-model="formData.category" id="category">
          <option value="">Select a category</option>
          <option value="technology">Technology</option>
          <option value="education">Education</option>
          <option value="business">Business</option>
          <option value="lifestyle">Lifestyle</option>
          <option value="tutorials">Tutorials</option>
          <option value="news">News</option>
        </BaseSelect>
      </div>

      <div class="flex items-center space-x-2 p-4 bg-gray-50 rounded-lg">
        <BaseCheckbox
          v-model="formData.is_published"
          id="is_published"
        />
        <div class="flex-1">
          <label for="is_published" class="font-medium cursor-pointer">Published</label>
          <p class="text-xs text-surface-500 dark:text-surface-400">Post is visible to readers</p>
        </div>
      </div>

      <!-- Error Message -->
      <BaseAlert v-if="error" variant="danger">
        {{ error }}
      </BaseAlert>
    </form>

    <template #footer>
      <div class="flex justify-end gap-3">
        <BaseButton
          variant="secondary"
          @click="handleClose"
          :disabled="loading"
        >
          Cancel
        </BaseButton>
        <BaseButton
          @click="handleSubmit"
          :loading="loading"
          :disabled="!formData.title || !formData.content"
        >
          Update Post
        </BaseButton>
      </div>
    </template>
  </BaseModal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useBlogsStore } from '~/stores/blogs'
import type { Blog } from '~/types/blogs'

const props = defineProps<{
  show: boolean
  blog: Blog | null
}>()

const emit = defineEmits<{
  close: []
  updated: []
}>()

const blogsStore = useBlogsStore()

const loading = ref(false)
const error = ref<string | null>(null)

const formData = ref({
  title: '',
  content: '',
  category: '',
  is_published: false
})

const handleSubmit = async () => {
  if (!props.blog) return
  
  loading.value = true
  error.value = null

  try {
    await blogsStore.updateBlog(props.blog.id, formData.value)
    emit('updated')
    emit('close')
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to update blog post'
  } finally {
    loading.value = false
  }
}

const handleClose = () => {
  if (!loading.value) {
    emit('close')
  }
}

watch(() => props.blog, (blog) => {
  if (blog) {
    formData.value = {
      title: blog.title || '',
      content: blog.content || '',
      category: blog.category || '',
      is_published: blog.is_published ?? false
    }
  }
}, { immediate: true })

watch(() => props.show, (newVal) => {
  if (newVal) {
    error.value = null
  }
})
</script>
