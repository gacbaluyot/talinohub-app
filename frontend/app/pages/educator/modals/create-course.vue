<template>
  <Dialog :open="show" @update:open="handleClose">
    <DialogContent class="max-w-4xl max-h-[90vh] overflow-hidden flex flex-col p-0">
      <!-- Header -->
      <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-6 shrink-0">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div>
            <DialogTitle class="text-2xl font-bold text-white">
              Create New Course
            </DialogTitle>
            <DialogDescription class="text-indigo-100 text-sm">
              Fill in the details to create your course
            </DialogDescription>
          </div>
        </div>
      </div>

      <!-- Form Content -->
      <form @submit.prevent="handleSubmit" class="flex-1 overflow-y-auto p-6">
        <div class="space-y-6">
          <!-- Basic Information Section -->
          <div>
            <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
              <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center mr-2">
                <span class="text-indigo-600 text-xs font-bold">1</span>
              </div>
              Basic Information
            </h4>
            <div class="space-y-4 ml-8">
              <!-- Title -->
              <div class="space-y-2">
                <Label for="title">
                  Course Title <span class="text-red-500">*</span>
                </Label>
                <Input
                  id="title"
                  v-model="formData.title"
                  placeholder="e.g., Introduction to Web Development"
                  required
                />
              </div>

              <!-- Short Description -->
              <div class="space-y-2">
                <Label for="short_description">Short Description</Label>
                <Input
                  id="short_description"
                  v-model="formData.short_description"
                  placeholder="A brief, catchy overview of your course (max 500 characters)"
                  maxlength="500"
                />
                <p class="text-xs text-muted-foreground">{{ formData.short_description?.length || 0 }}/500 characters</p>
              </div>

              <!-- Full Description -->
              <div class="space-y-2">
                <Label for="description">Full Description</Label>
                <Textarea
                  id="description"
                  v-model="formData.description"
                  placeholder="Provide a detailed description of what students will learn..."
                  rows="5"
                />
              </div>
            </div>
          </div>

          <!-- Course Details Section -->
          <div class="border-t border-gray-200 pt-6">
            <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
              <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center mr-2">
                <span class="text-indigo-600 text-xs font-bold">2</span>
              </div>
              Course Details
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-8">
              <!-- Category -->
              <div class="space-y-2">
                <Label for="category">Category</Label>
                <Select v-model="formData.category">
                  <SelectTrigger id="category">
                    <SelectValue placeholder="Select a category" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="programming">Programming</SelectItem>
                    <SelectItem value="design">Design</SelectItem>
                    <SelectItem value="business">Business</SelectItem>
                    <SelectItem value="marketing">Marketing</SelectItem>
                    <SelectItem value="personal-development">Personal Development</SelectItem>
                    <SelectItem value="data-science">Data Science</SelectItem>
                    <SelectItem value="photography">Photography</SelectItem>
                    <SelectItem value="music">Music</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <!-- Level -->
              <div class="space-y-2">
                <Label for="level">Difficulty Level</Label>
                <Select v-model="formData.level">
                  <SelectTrigger id="level">
                    <SelectValue placeholder="Select difficulty level" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="beginner">Beginner</SelectItem>
                    <SelectItem value="intermediate">Intermediate</SelectItem>
                    <SelectItem value="advanced">Advanced</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <!-- Language -->
              <div class="space-y-2">
                <Label for="language">Language</Label>
                <Select v-model="formData.language">
                  <SelectTrigger id="language">
                    <SelectValue placeholder="Select language" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="English">English</SelectItem>
                    <SelectItem value="Spanish">Spanish</SelectItem>
                    <SelectItem value="French">French</SelectItem>
                    <SelectItem value="German">German</SelectItem>
                    <SelectItem value="Chinese">Chinese</SelectItem>
                    <SelectItem value="Japanese">Japanese</SelectItem>
                    <SelectItem value="Korean">Korean</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
          </div>

          <!-- Pricing Section -->
          <div class="border-t border-gray-200 pt-6">
            <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
              <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center mr-2">
                <span class="text-indigo-600 text-xs font-bold">3</span>
              </div>
              Pricing
            </h4>
            <div class="space-y-4 ml-8">
              <!-- Free Course Toggle -->
              <div class="flex items-center space-x-2 p-4 bg-muted rounded-lg">
                <input
                  v-model="formData.is_free"
                  type="checkbox"
                  id="is_free"
                  class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                />
                <div class="flex-1">
                  <Label for="is_free" class="font-medium cursor-pointer">This is a free course</Label>
                  <p class="text-xs text-muted-foreground">Students can enroll without payment</p>
                </div>
              </div>
              
              <!-- Price Input -->
              <div v-if="!formData.is_free" class="space-y-2">
                <Label for="price">Course Price (USD)</Label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">$</span>
                  <Input
                    id="price"
                    v-model.number="formData.price"
                    type="number"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                    class="pl-7"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Media Section -->
          <div class="border-t border-gray-200 pt-6">
            <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
              <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center mr-2">
                <span class="text-indigo-600 text-xs font-bold">4</span>
              </div>
              Course Thumbnail
            </h4>
            <div class="ml-8">
              <Label class="mb-2 block">Upload Course Image</Label>
              <div class="flex items-center gap-4">
                <label
                  for="thumbnail"
                  class="flex-1 flex flex-col items-center px-6 py-8 border-2 border-dashed border-muted-foreground/25 rounded-lg cursor-pointer bg-muted/50 hover:bg-muted transition-colors"
                >
                  <svg class="w-10 h-10 text-muted-foreground mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span class="text-sm text-muted-foreground mb-1">
                    <span class="font-semibold text-primary">Click to upload</span> or drag and drop
                  </span>
                  <span class="text-xs text-muted-foreground">PNG, JPG, GIF up to 2MB</span>
                  <input
                    type="file"
                    id="thumbnail"
                    accept="image/*"
                    @change="handleThumbnailChange"
                    class="hidden"
                  />
                </label>
                <div v-if="thumbnailPreview" class="relative w-32 h-32 rounded-lg overflow-hidden border-2 border-border">
                  <img :src="thumbnailPreview" alt="Preview" class="w-full h-full object-cover" />
                  <Button
                    type="button"
                    @click="removeThumbnail"
                    variant="destructive"
                    size="icon"
                    class="absolute top-1 right-1 h-6 w-6"
                  >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </Button>
                </div>
              </div>
            </div>
          </div>

          <!-- Publishing Options -->
          <div class="border-t border-gray-200 pt-6">
            <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
              <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center mr-2">
                <span class="text-indigo-600 text-xs font-bold">5</span>
              </div>
              Publishing
            </h4>
            <div class="ml-8">
              <div class="flex items-center space-x-2 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <input
                  v-model="formData.is_published"
                  type="checkbox"
                  id="is_published"
                  class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                />
                <div class="flex-1">
                  <Label for="is_published" class="font-medium cursor-pointer">Publish course immediately</Label>
                  <p class="text-xs text-muted-foreground">Make this course visible to students right away</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="rounded-lg bg-destructive/10 p-4 border border-destructive/20">
            <div class="flex gap-3">
              <svg class="h-5 w-5 text-destructive shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
              </svg>
              <p class="text-sm text-destructive">{{ error }}</p>
            </div>
          </div>
        </div>
      </form>

      <!-- Footer -->
      <DialogFooter class="bg-muted/50 px-6 py-4 border-t shrink-0">
        <Button
          type="button"
          variant="outline"
          @click="handleClose"
          :disabled="loading"
        >
          Cancel
        </Button>
        <Button
          type="submit"
          @click="handleSubmit"
          :disabled="loading || !formData.title"
        >
          <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          {{ loading ? 'Creating Course...' : 'Create Course' }}
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useCoursesStore } from '~/stores/courses'
import { useAuthStore } from '~/stores/auth'
import type { CreateCourseRequest } from '~/types/courses'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogTitle,
} from '~/components/ui/dialog'
import { Button } from '~/components/ui/button'
import { Input } from '~/components/ui/input'
import { Label } from '~/components/ui/label'
import { Textarea } from '~/components/ui/textarea'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '~/components/ui/select'

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

const handleThumbnailChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    const file = target.files[0]
    formData.value.thumbnail = file
    
    // Create preview
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
  const fileInput = document.getElementById('thumbnail') as HTMLInputElement
  if (fileInput) fileInput.value = ''
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
