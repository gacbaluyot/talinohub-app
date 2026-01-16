<template>
  <BaseModal :show="show" @close="handleClose" size="large">
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-2xl font-bold text-surface-900 dark:text-surface-0">{{ blog?.title }}</h3>
          <p class="text-sm text-surface-600 dark:text-surface-400 mt-1">
            <span v-if="blog?.category" class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">
              {{ blog.category }}
            </span>
            <span class="ml-2">{{ formatDate(blog?.created_at) }}</span>
          </p>
        </div>
        <BaseBadge :variant="blog?.is_published ? 'success' : 'secondary'">
          {{ blog?.is_published ? 'Published' : 'Draft' }}
        </BaseBadge>
      </div>
    </template>

    <div class="prose prose-sm max-w-none">
      <div class="whitespace-pre-wrap text-surface-700 dark:text-surface-300">{{ blog?.content }}</div>
    </div>

    <template #footer>
      <div class="flex justify-end">
        <BaseButton
          variant="secondary"
          @click="handleClose"
        >
          Close
        </BaseButton>
      </div>
    </template>
  </BaseModal>
</template>

<script setup lang="ts">
import type { Blog } from '~/types/blogs'

const props = defineProps<{
  show: boolean
  blog: Blog | null
}>()

const emit = defineEmits<{
  close: []
}>()

const handleClose = () => {
  emit('close')
}

const formatDate = (date: string | undefined) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>
