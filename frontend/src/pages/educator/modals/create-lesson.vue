<template>
  <Dialog v-model="isVisible" size="xl">
    <DialogHeader>
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </div>
        <div>
          <h3 class="text-2xl font-bold text-surface-900 dark:text-surface-0">Create New Lesson</h3>
          <p class="text-sm text-surface-600 dark:text-surface-400">Add a lesson to your course</p>
        </div>
      </div>
    </DialogHeader>

    <DialogContent>
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Lesson Title -->
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

        <!-- Lesson Type -->
        <div class="space-y-2">
          <label for="type" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
            Lesson Type <span class="text-red-600 dark:text-red-400">*</span>
          </label>
          <Select id="type" v-model="formData.type">
            <option v-for="option in lessonTypes" :key="option.value" :value="option.value">
              {{ option.label }}
            </option>
          </Select>
          <small class="text-surface-500 dark:text-surface-400">Choose the type of content for this lesson</small>
        </div>

        <!-- Video Upload (only show if type is video) -->
        <div v-if="formData.type === 'video'" class="space-y-4">
          <div class="space-y-2">
            <label for="video" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
              Video File
            </label>
            <div class="border-2 border-dashed border-surface-300 dark:border-surface-600 rounded-lg p-6 text-center hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors">
              <input
                type="file"
                id="video"
                ref="videoInput"
                accept="video/*"
                @change="handleVideoUpload"
                class="hidden"
              />
              <label for="video" class="cursor-pointer">
                <div v-if="!videoFile" class="space-y-2">
                  <svg class="w-12 h-12 mx-auto text-surface-400 dark:text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                  </svg>
                  <p class="text-sm text-surface-600 dark:text-surface-400">
                    Click to upload video or drag and drop
                  </p>
                  <p class="text-xs text-surface-500 dark:text-surface-400">
                    MP4, WebM, or OGG (max 500MB)
                  </p>
                </div>
                <div v-else class="space-y-2">
                  <svg class="w-12 h-12 mx-auto text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <p class="text-sm text-surface-900 dark:text-surface-0 font-medium">{{ videoFile.name }}</p>
                  <p class="text-xs text-surface-500 dark:text-surface-400">{{ formatFileSize(videoFile.size) }}</p>
                  <button
                    type="button"
                    @click.prevent="removeVideo"
                    class="text-sm text-red-600 hover:text-red-700 dark:text-red-400"
                  >
                    Remove
                  </button>
                </div>
              </label>
            </div>
          </div>
          
          <!-- Video URL (alternative) -->
          <div class="space-y-2">
            <label for="video_url" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
              Or enter video URL
            </label>
            <Input
              id="video_url"
              v-model="formData.video_url"
              placeholder="https://example.com/video.mp4"
            />
            <small class="text-surface-500 dark:text-surface-400">Enter a direct link to a video file</small>
          </div>

          <!-- Duration -->
          <div class="space-y-2">
            <label for="duration" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
              Duration (seconds)
            </label>
            <Input
              id="duration"
              v-model.number="formData.duration_seconds"
              type="number"
              min="0"
              placeholder="e.g., 300 for 5 minutes"
            />
          </div>
        </div>

        <!-- Document Upload (only show if type is document) -->
        <div v-if="formData.type === 'document'" class="space-y-2">
          <label for="document" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
            Document File <span class="text-red-600 dark:text-red-400">*</span>
          </label>
          <div class="border-2 border-dashed border-surface-300 dark:border-surface-600 rounded-lg p-6 text-center hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors">
            <input
              type="file"
              id="document"
              ref="documentInput"
              accept=".pdf,.doc,.docx,.ppt,.pptx"
              @change="handleDocumentUpload"
              class="hidden"
            />
            <label for="document" class="cursor-pointer">
              <div v-if="!documentFile" class="space-y-2">
                <svg class="w-12 h-12 mx-auto text-surface-400 dark:text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-sm text-surface-600 dark:text-surface-400">
                  Click to upload document or drag and drop
                </p>
                <p class="text-xs text-surface-500 dark:text-surface-400">
                  PDF, DOC, DOCX, PPT, PPTX (max 50MB)
                </p>
              </div>
              <div v-else class="space-y-2">
                <svg class="w-12 h-12 mx-auto text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-surface-900 dark:text-surface-0 font-medium">{{ documentFile.name }}</p>
                <p class="text-xs text-surface-500 dark:text-surface-400">{{ formatFileSize(documentFile.size) }}</p>
                <button
                  type="button"
                  @click.prevent="removeDocument"
                  class="text-sm text-red-600 hover:text-red-700 dark:text-red-400"
                >
                  Remove
                </button>
              </div>
            </label>
          </div>
        </div>

        <!-- Position/Order -->
        <div class="space-y-2">
          <label for="position" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
            Order
          </label>
          <Input
            id="position"
            v-model.number="formData.position"
            type="number"
            min="1"
            placeholder="1"
          />
          <small class="text-surface-500 dark:text-surface-400">The order in which this lesson appears in the course</small>
        </div>

        <!-- Is Preview -->
        <div class="flex items-start gap-3 p-4 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
          <Checkbox
            v-model="formData.is_preview"
            id="is_preview"
          />
          <div class="flex-1">
            <label for="is_preview" class="block text-sm font-semibold text-surface-900 dark:text-surface-0 cursor-pointer mb-1">
              Free Preview
            </label>
            <p class="text-sm text-surface-600 dark:text-surface-400">Allow users to preview this lesson without enrolling</p>
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
        :disabled="!canSubmit || loading"
      >
        <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ loading ? 'Creating...' : 'Create Lesson' }}
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
import Select from '~/components/ui/Select.vue'
import Checkbox from '~/components/ui/Checkbox.vue'
import Alert from '~/components/ui/Alert.vue'
import AlertTitle from '~/components/ui/AlertTitle.vue'
import AlertDescription from '~/components/ui/AlertDescription.vue'
import type { CreateLessonRequest } from '~/types/courses'

const props = defineProps<{
  show: boolean
  courseId: number
}>()

const emit = defineEmits<{
  close: []
  created: []
}>()

const coursesStore = useCoursesStore()

const loading = ref(false)
const error = ref<string | null>(null)
const videoInput = ref<HTMLInputElement | null>(null)
const documentInput = ref<HTMLInputElement | null>(null)
const videoFile = ref<File | null>(null)
const documentFile = ref<File | null>(null)

const isVisible = computed({
  get: () => props.show,
  set: (value) => {
    if (!value) emit('close')
  }
})

const lessonTypes = [
  { label: 'Video', value: 'video' },
  { label: 'Document', value: 'document' },
  { label: 'Text', value: 'text' }
]

const formData = ref({
  title: '',
  type: 'video' as 'video' | 'document' | 'text',
  video_url: '',
  duration_seconds: undefined as number | undefined,
  position: 1,
  is_preview: false
})

const canSubmit = computed(() => {
  if (!formData.value.title || !formData.value.type) return false
  
  if (formData.value.type === 'document' && !documentFile.value) {
    return false
  }
  
  return true
})

const handleVideoUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    videoFile.value = target.files[0]
  }
}

const handleDocumentUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    documentFile.value = target.files[0]
  }
}

const removeVideo = () => {
  videoFile.value = null
  if (videoInput.value) {
    videoInput.value.value = ''
  }
}

const removeDocument = () => {
  documentFile.value = null
  if (documentInput.value) {
    documentInput.value.value = ''
  }
}

const formatFileSize = (bytes: number): string => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i]
}

const handleSubmit = async () => {
  loading.value = true
  error.value = null

  try {
    const lessonData: CreateLessonRequest = {
      course_id: props.courseId,
      title: formData.value.title,
      type: formData.value.type,
      position: formData.value.position,
      is_preview: formData.value.is_preview
    }

    // Add video-specific fields
    if (formData.value.type === 'video') {
      if (formData.value.video_url) {
        lessonData.video_url = formData.value.video_url
      }
      if (formData.value.duration_seconds) {
        lessonData.duration_seconds = formData.value.duration_seconds
      }
    }

    // Add document file if present
    if (formData.value.type === 'document' && documentFile.value) {
      lessonData.document = documentFile.value
    }

    await coursesStore.createLesson(lessonData)
    emit('created')
    resetForm()
  } catch (err: any) {
    error.value = err.response?.data?.message || err.message || 'Failed to create lesson'
    console.error('Error creating lesson:', err)
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
    type: 'video',
    video_url: '',
    duration_seconds: undefined,
    position: 1,
    is_preview: false
  }
  videoFile.value = null
  documentFile.value = null
  error.value = null
}

watch(() => props.show, (newVal) => {
  if (newVal) {
    error.value = null
  }
})

// Watch type changes and reset file uploads
watch(() => formData.value.type, () => {
  removeVideo()
  removeDocument()
})
</script>
