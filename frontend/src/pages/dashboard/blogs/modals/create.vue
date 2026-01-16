<template>
  <BaseModal :show="show" @close="handleClose" size="large">
    <template #header>
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
        </div>
        <div>
          <h3 class="text-2xl font-bold text-surface-900 dark:text-surface-0">Create New Blog Post</h3>
          <p class="text-sm text-surface-600 dark:text-surface-400">Share your thoughts and knowledge</p>
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
          <label for="is_published" class="font-medium cursor-pointer">Publish immediately</label>
          <p class="text-xs text-surface-500 dark:text-surface-400">Make this post visible to readers</p>
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
          Create Post
        </BaseButton>
      </div>
    </template>
  </BaseModal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useBlogsStore } from '~/stores/blogs'
import { useAuthStore } from '~/stores/auth'

const props = defineProps<{
  show: boolean
}>()

const emit = defineEmits<{
  close: []
  created: []
}>()

const blogsStore = useBlogsStore()
const authStore = useAuthStore()

const loading = ref(false)
const error = ref<string | null>(null)

const formData = ref({
  title: '',
  content: '',
  category: '',
  is_published: false
})

const handleSubmit = async () => {
  loading.value = true
  error.value = null

  try {
    await blogsStore.createBlog({
      ...formData.value,
      author_id: authStore.user?.id || 0
    })
    emit('created')
    resetForm()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to create blog post'
  } finally {
    loading.value = false
  }
}

const handleClose = () => {
  if (!loading.value) {
    resetForm()
    emit('close')
  }
}

const resetForm = () => {
  formData.value = {
    title: '',
    content: '',
    category: '',
    is_published: false
  }
  error.value = null
}

watch(() => props.show, (newVal) => {
  if (newVal) {
    error.value = null
  }
})
</script>
