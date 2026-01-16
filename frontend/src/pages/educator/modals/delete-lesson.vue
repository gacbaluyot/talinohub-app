<template>
  <Dialog v-model="isVisible" size="sm">
    <DialogHeader>
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </div>
        <div>
          <h3 class="text-xl font-bold text-surface-900 dark:text-surface-0">Delete Lesson</h3>
          <p class="text-sm text-surface-600 dark:text-surface-400">This action cannot be undone</p>
        </div>
      </div>
    </DialogHeader>

    <DialogContent>
      <div class="space-y-4">
        <p class="text-surface-700 dark:text-surface-300">
          Are you sure you want to delete <strong>{{ lesson?.title }}</strong>?
        </p>
        <p class="text-sm text-surface-600 dark:text-surface-400">
          This will permanently delete the lesson. Students will lose access to this content.
        </p>

        <Alert v-if="error" variant="destructive">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <AlertTitle>Error</AlertTitle>
          <AlertDescription>{{ error }}</AlertDescription>
        </Alert>
      </div>
    </DialogContent>

    <DialogFooter>
      <Button
        variant="outline"
        @click="handleClose"
        :disabled="loading"
      >
        Cancel
      </Button>
      <Button
        variant="destructive"
        @click="handleDelete"
        :disabled="loading"
      >
        <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ loading ? 'Deleting...' : 'Delete Lesson' }}
      </Button>
    </DialogFooter>
  </Dialog>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { useCoursesStore } from '~/stores/courses'
import Dialog from '~/components/ui/Dialog.vue'
import DialogHeader from '~/components/ui/DialogHeader.vue'
import DialogContent from '~/components/ui/DialogContent.vue'
import DialogFooter from '~/components/ui/DialogFooter.vue'
import Button from '~/components/ui/Button.vue'
import Alert from '~/components/ui/Alert.vue'
import AlertTitle from '~/components/ui/AlertTitle.vue'
import AlertDescription from '~/components/ui/AlertDescription.vue'
import type { Lesson } from '~/types/courses'

const props = defineProps<{
  show: boolean
  lesson: Lesson | null
}>()

const emit = defineEmits<{
  close: []
  deleted: []
}>()

const coursesStore = useCoursesStore()

const loading = ref(false)
const error = ref<string | null>(null)

const isVisible = computed({
  get: () => props.show,
  set: (value) => {
    if (!value) emit('close')
  }
})

const handleDelete = async () => {
  if (!props.lesson) return
  
  loading.value = true
  error.value = null

  try {
    await coursesStore.deleteLesson(props.lesson.id)
    emit('deleted')
    emit('close')
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to delete lesson'
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
