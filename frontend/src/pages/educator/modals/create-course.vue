<template>
  <Dialog v-model="isVisible" size="xl">
    <DialogHeader>
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <div>
          <h3 class="text-2xl font-bold text-surface-900 dark:text-surface-0">Create New Course</h3>
          <p class="text-sm text-surface-600 dark:text-surface-400">Fill in the details to create your course</p>
        </div>
      </div>
    </DialogHeader>

    <DialogContent>
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Basic Information -->
        <div class="space-y-4">
          <h4 class="text-base font-semibold text-surface-900 dark:text-surface-0 pb-2 border-b border-surface-200 dark:border-surface-700">Basic Information</h4>
          
          <div class="space-y-2">
            <label for="title" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
              Course Title <span class="text-red-600 dark:text-red-400">*</span>
            </label>
            <Input
              id="title"
              v-model="formData.title"
              placeholder="e.g., Introduction to Web Development"
              required
            />
          </div>

          <div class="space-y-2">
            <label for="short_description" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
              Short Description
            </label>
            <Input
              id="short_description"
              v-model="formData.short_description"
              placeholder="A brief overview of your course"
              maxlength="500"
            />
            <small class="text-surface-500 dark:text-surface-400">{{ formData.short_description?.length || 0 }}/500 characters</small>
          </div>

          <div class="space-y-2">
            <label for="description" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">
              Full Description
            </label>
            <Textarea
              id="description"
              v-model="formData.description"
              placeholder="Provide a detailed description of what students will learn..."
              rows="5"
            />
          </div>
        </div>

        <!-- Course Details -->
        <div class="space-y-4 pt-4 border-t border-surface-200 dark:border-surface-700">
          <h4 class="text-base font-semibold text-surface-900 dark:text-surface-0 pb-2 border-b border-surface-200 dark:border-surface-700">Course Details</h4>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
              <label for="category" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">Category</label>
              <Select id="category" v-model="formData.category">
                <option value="">Select a category</option>
                <option v-for="option in categoryOptions" :key="option.value" :value="option.value">
                  {{ option.label }}
                </option>
              </Select>
            </div>

            <div class="space-y-2">
              <label for="level" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">Difficulty Level</label>
              <Select id="level" v-model="formData.level">
                <option value="">Select difficulty level</option>
                <option v-for="option in levelOptions" :key="option.value" :value="option.value">
                  {{ option.label }}
                </option>
              </Select>
            </div>

            <div class="space-y-2 md:col-span-2">
              <label for="language" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">Language</label>
              <Select id="language" v-model="formData.language">
                <option v-for="option in languageOptions" :key="option.value" :value="option.value">
                  {{ option.label }}
                </option>
              </Select>
            </div>
          </div>
        </div>

        <!-- Pricing -->
        <div class="space-y-4 pt-4 border-t border-surface-200 dark:border-surface-700">
          <h4 class="text-base font-semibold text-surface-900 dark:text-surface-0 pb-2 border-b border-surface-200 dark:border-surface-700">Pricing</h4>
          
          <div class="flex items-start gap-3 p-4 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
            <Checkbox
              v-model="formData.is_free"
              id="is_free"
            />
            <div class="flex-1">
              <label for="is_free" class="block text-sm font-semibold text-surface-900 dark:text-surface-0 cursor-pointer mb-1">
                This is a free course
              </label>
              <p class="text-sm text-surface-600 dark:text-surface-400">Students can enroll without payment</p>
            </div>
          </div>
          
          <div v-if="!formData.is_free" class="space-y-2">
            <label for="price" class="block text-sm font-semibold text-surface-900 dark:text-surface-0">Course Price (USD)</label>
            <Input
              id="price"
              v-model.number="formData.price"
              type="number"
              min="0"
              step="0.01"
              placeholder="0.00"
              class="max-w-xs"
            />
          </div>
        </div>

        <!-- Course Thumbnail -->
        <div class="space-y-4 pt-4 border-t border-surface-200 dark:border-surface-700">
          <h4 class="text-base font-semibold text-surface-900 dark:text-surface-0 pb-2 border-b border-surface-200 dark:border-surface-700">Course Thumbnail</h4>
          
          <div class="flex flex-col sm:flex-row items-start gap-4">
            <input
              type="file"
              accept="image/*"
              @change="handleThumbnailChange"
              class="block w-full text-sm text-surface-900 dark:text-surface-0 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300"
            />
            
            <div v-if="thumbnailPreview" class="relative w-32 h-32 rounded-lg overflow-hidden border-2 border-surface-200 dark:border-surface-700 shadow-sm">
              <img :src="thumbnailPreview" alt="Preview" class="w-full h-full object-cover" />
              <Button
                @click="removeThumbnail"
                variant="destructive"
                size="icon"
                class="absolute top-1 right-1 h-6 w-6"
              >
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </Button>
            </div>
          </div>
        </div>

        <!-- Publishing -->
        <div class="space-y-4 pt-4 border-t border-surface-200 dark:border-surface-700">
          <h4 class="text-base font-semibold text-surface-900 dark:text-surface-0 pb-2 border-b border-surface-200 dark:border-surface-700">Publishing</h4>
          
          <div class="flex items-start gap-3 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-200 dark:border-indigo-700">
            <Checkbox
              v-model="formData.is_published"
              id="is_published"
            />
            <div class="flex-1">
              <label for="is_published" class="block text-sm font-semibold text-surface-900 dark:text-surface-0 cursor-pointer mb-1">
                Publish course immediately
              </label>
              <p class="text-sm text-surface-600 dark:text-surface-400">Make this course visible to students right away</p>
            </div>
          </div>
        </div>

        <!-- Error Message -->
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
        :disabled="!formData.title || loading"
      >
        <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ loading ? 'Creating...' : 'Create Course' }}
      </Button>
    </DialogFooter>
  </Dialog>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { useCoursesStore } from '~/stores/courses'
import { useAuthStore } from '~/stores/auth'
import Dialog from '~/components/ui/Dialog.vue'
import DialogHeader from '~/components/ui/DialogHeader.vue'
import DialogContent from '~/components/ui/DialogContent.vue'
import DialogFooter from '~/components/ui/DialogFooter.vue'
import Button from '~/components/ui/Button.vue'
import Input from '~/components/ui/Input.vue'
import Textarea from '~/components/ui/Textarea.vue'
import Select from '~/components/ui/Select.vue'
import Checkbox from '~/components/ui/Checkbox.vue'
import Alert from '~/components/ui/Alert.vue'
import AlertTitle from '~/components/ui/AlertTitle.vue'
import AlertDescription from '~/components/ui/AlertDescription.vue'
import type { CreateCourseRequest } from '~/types/courses'

const props = defineProps<{
  show: boolean
}>()

const emit = defineEmits<{
  close: []
  created: []
}>()

const coursesStore = useCoursesStore()
const authStore = useAuthStore()

const loading = ref(false)
const error = ref<string | null>(null)
const thumbnailPreview = ref<string | null>(null)

const isVisible = computed({
  get: () => props.show,
  set: (value) => {
    if (!value) emit('close')
  }
})

const formData = ref<CreateCourseRequest>({
  user_id: authStore.user?.id || 0,
  title: '',
  short_description: '',
  description: '',
  is_free: true,
  price: 0,
  category: '',
  level: undefined,
  language: 'English',
  is_published: false
})

const categoryOptions = [
  { label: 'Programming', value: 'programming' },
  { label: 'Design', value: 'design' },
  { label: 'Business', value: 'business' },
  { label: 'Marketing', value: 'marketing' },
  { label: 'Personal Development', value: 'personal-development' },
  { label: 'Data Science', value: 'data-science' },
  { label: 'Photography', value: 'photography' },
  { label: 'Music', value: 'music' }
]

const levelOptions = [
  { label: 'Beginner', value: 'beginner' },
  { label: 'Intermediate', value: 'intermediate' },
  { label: 'Advanced', value: 'advanced' }
]

const languageOptions = [
  { label: 'English', value: 'English' },
  { label: 'Spanish', value: 'Spanish' },
  { label: 'French', value: 'French' },
  { label: 'German', value: 'German' },
  { label: 'Chinese', value: 'Chinese' },
  { label: 'Japanese', value: 'Japanese' },
  { label: 'Korean', value: 'Korean' }
]

const handleThumbnailChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  if (file) {
    formData.value.thumbnail = file
    
    const reader = new FileReader()
    reader.onload = (e) => {
      thumbnailPreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const removeThumbnail = () => {
  formData.value.thumbnail = undefined
  thumbnailPreview.value = null
}

const handleSubmit = async () => {
  loading.value = true
  error.value = null

  try {
    formData.value.user_id = authStore.user?.id || 0
    await coursesStore.createCourse(formData.value)
    emit('created')
    resetForm()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to create course'
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
    user_id: authStore.user?.id || 0,
    title: '',
    short_description: '',
    description: '',
    is_free: true,
    price: 0,
    category: '',
    level: undefined,
    language: 'English',
    is_published: false
  }
  thumbnailPreview.value = null
  error.value = null
}

watch(() => props.show, (newVal) => {
  if (newVal) {
    error.value = null
  }
})
</script>
