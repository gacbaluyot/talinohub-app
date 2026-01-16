<template>
  <BaseModal :show="show" @close="handleClose" size="small">
    <template #header>
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </div>
        <div>
          <h3 class="text-xl font-bold text-surface-900 dark:text-surface-0">Delete Blog Post</h3>
          <p class="text-sm text-surface-600 dark:text-surface-400">This action cannot be undone</p>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <p class="text-surface-700 dark:text-surface-300">
        Are you sure you want to delete <strong>{{ blog?.title }}</strong>?
      </p>
      <p class="text-sm text-surface-600 dark:text-surface-400">
        This will permanently delete the blog post and all associated data.
      </p>

      <!-- Error Message -->
      <BaseAlert v-if="error" variant="danger">
        {{ error }}
      </BaseAlert>
    </div>

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
          variant="danger"
          @click="handleDelete"
          :loading="loading"
        >
          Delete Post
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
  deleted: []
}>()

const blogsStore = useBlogsStore()

const loading = ref(false)
const error = ref<string | null>(null)

const handleDelete = async () => {
  if (!props.blog) return
  
  loading.value = true
  error.value = null

  try {
    await blogsStore.deleteBlog(props.blog.id)
    emit('deleted')
    emit('close')
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to delete blog post'
  } finally {
    loading.value = false
  }
}

const handleClose = () => {
  if (!loading.value) {
    emit('close')
  }
}

watch(() => props.show, (newVal) => {
  if (newVal) {
    error.value = null
  }
})
</script>
