<template>
  <Dialog v-model="isVisible" size="xl">
    <DialogHeader>
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
        </div>
        <div>
          <h3 class="text-2xl font-bold text-surface-900 dark:text-surface-0">Update Lesson</h3>
          <p class="text-sm text-surface-600 dark:text-surface-400">Edit your lesson details</p>
        </div>
      </div>
    </DialogHeader>

    <DialogContent>
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="space-y-2">
          <label for="title" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
            Lesson Title <span class="text-red-600 dark:text-red-400">*</span>
          </label>
          <Input
            id="title"
            v-model="formData.title"
            placeholder="e.g., Introduction to Variables"
            required
          />
        </div>

        <div class="space-y-2">
          <label for="content" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
            Lesson Content <span class="text-red-600 dark:text-red-400">*</span>
          </label>
          <Textarea
            id="content"
            v-model="formData.content"
            placeholder="Write your lesson content here..."
            rows="10"
            required
          />
        </div>

        <div class="space-y-2">
          <label for="order" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
            Order
          </label>
          <Input
            id="order"
            v-model.number="formData.order"
            type="number"
            min="1"
            placeholder="1"
          />
          <small class="text-surface-500 dark:text-surface-400">The order in which this lesson appears in the course</small>
        </div>

        <div class="flex items-start gap-3 p-4 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
          <Checkbox
            v-model="formData.is_published"
            id="is_published"
          />
          <div class="flex-1">
            <label for="is_published" class="block text-sm font-semibold text-surface-900 dark:text-surface-0 cursor-pointer mb-1">
              Published
            </label>
            <p class="text-sm text-surface-600 dark:text-surface-400">Lesson is visible to students</p>
          </div>
        </div>

        <Alert v-if="error" variant="destructive">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <AlertTitle>Error</AlertTitle>
          <AlertDescription>{{ error }}</AlertDescription>
        </Alert>
      </form>
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
        @click="handleSubmit"
        :disabled="!formData.title || !formData.content || loading"
      >
        <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ loading ? 'Updating...' : 'Update Lesson' }}
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
import Input from '~/components/ui/Input.vue'
import Textarea from '~/components/ui/Textarea.vue'
import Checkbox from '~/components/ui/Checkbox.vue'
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
  updated: []
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

const formData = ref({
  title: '',
  content: '',
  order: 1,
  is_published: false
})

const handleSubmit = async () => {
  if (!props.lesson) return

  loading.value = true
  error.value = null

  try {
    await coursesStore.updateLesson(props.lesson.id, formData.value)
    emit('updated')
    emit('close')
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to update lesson'
  } finally {
    loading.value = false
  }
}

const handleClose = () => {
  if (!loading.value) {
    emit('close')
  }
}

watch(() => props.lesson, (lesson) => {
  if (lesson) {
    formData.value = {
      title: lesson.title || '',
      content: lesson.content || '',
      order: lesson.position || 1,
      is_published: lesson.is_preview ?? false
    }
  }
}, { immediate: true })

watch(() => props.show, (newVal) => {
  if (newVal) {
    error.value = null
  }
})
</script>
