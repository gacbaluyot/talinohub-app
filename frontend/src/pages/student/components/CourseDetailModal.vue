<template>
  <Dialog :open="show" @update:open="handleClose">
    <DialogContent class="max-w-4xl max-h-[90vh] overflow-y-auto bg-white dark:bg-zinc-900">
      <DialogHeader>
        <h2 class="text-2xl font-bold text-surface-900 dark:text-surface-0">Course Details</h2>
      </DialogHeader>

      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="h-10 w-10 animate-spin rounded-full border-4 border-solid border-indigo-600 border-r-transparent"></div>
      </div>

      <div v-else-if="error" class="py-8">
        <Alert variant="destructive">
          <AlertTitle>Error</AlertTitle>
          <AlertDescription>{{ error }}</AlertDescription>
        </Alert>
      </div>

      <div v-else-if="course" class="space-y-6">
        <!-- Course Thumbnail -->
        <div v-if="course.thumbnail_path" class="relative aspect-video rounded-lg overflow-hidden">
          <img
            :src="course.thumbnail_path"
            :alt="course.title"
            class="w-full h-full object-cover"
          />
        </div>

        <!-- Course Info -->
        <div>
          <h3 class="text-xl font-bold text-surface-900 dark:text-surface-0 mb-2">{{ course.title }}</h3>
          <p class="text-surface-600 dark:text-surface-400 mb-4">{{ course.description }}</p>

          <div class="flex flex-wrap gap-4 mb-4">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <span class="text-sm text-surface-700 dark:text-surface-300">{{ course.educator?.name || 'Educator' }}</span>
            </div>

            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
              <span class="text-sm text-surface-700 dark:text-surface-300">{{ course.lessons_count || 0 }} lessons</span>
            </div>

            <div class="flex items-center gap-2">
              <Badge :variant="course.level === 'beginner' ? 'default' : course.level === 'intermediate' ? 'secondary' : 'destructive'">
                {{ course.level }}
              </Badge>
            </div>

            <div class="flex items-center gap-2">
              <Badge v-if="course.is_free" variant="default" class="bg-green-600">
                Free
              </Badge>
              <Badge v-else variant="secondary">
                ${{ course.price }}
              </Badge>
            </div>
          </div>
        </div>

        <!-- Course Content -->
        <div v-if="course.lessons && course.lessons.length > 0">
          <h4 class="text-lg font-semibold text-surface-900 dark:text-surface-0 mb-3">Course Content</h4>
          <div class="space-y-2">
            <div
              v-for="(lesson, index) in course.lessons"
              :key="lesson.id"
              class="flex items-center gap-3 p-3 rounded-lg bg-surface-50 dark:bg-surface-900"
            >
              <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 text-sm font-semibold">
                {{ index + 1 }}
              </span>
              <div class="flex-1">
                <p class="font-medium text-surface-900 dark:text-surface-0">{{ lesson.title }}</p>
                <p v-if="lesson.description" class="text-sm text-surface-600 dark:text-surface-400 line-clamp-1">{{ lesson.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <DialogFooter class="flex gap-3">
        <Button variant="outline" @click="handleClose">Close</Button>
        <Button
          v-if="!enrolled"
          @click="handleEnrollClick"
          :disabled="enrolling"
        >
          {{ enrolling ? 'Enrolling...' : 'Enroll Now' }}
        </Button>
        <Button
          v-else
          @click="handleViewCourse"
        >
          View Course
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useCoursesStore } from '~/stores/courses'
import type { Course } from '~/types/courses'
import Dialog from '~/components/ui/Dialog.vue'
import DialogContent from '~/components/ui/DialogContent.vue'
import DialogHeader from '~/components/ui/DialogHeader.vue'
import DialogFooter from '~/components/ui/DialogFooter.vue'
import Button from '~/components/ui/Button.vue'
import Badge from '~/components/ui/Badge.vue'
import Alert from '~/components/ui/Alert.vue'
import AlertTitle from '~/components/ui/AlertTitle.vue'
import AlertDescription from '~/components/ui/AlertDescription.vue'

interface Props {
  show: boolean
  courseId: number | null
  enrolled: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  enroll: [courseId: number]
}>()

const coursesStore = useCoursesStore()

const course = ref<Course | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)
const enrolling = ref(false)

const handleClose = () => {
  emit('close')
}

const handleEnrollClick = () => {
  if (course.value) {
    enrolling.value = true
    emit('enroll', course.value.id)
    // Reset enrolling state after a delay
    setTimeout(() => {
      enrolling.value = false
    }, 1000)
  }
}

const handleViewCourse = () => {
  if (course.value) {
    navigateTo(`/student/courses/${course.value.id}`)
  }
}

watch(() => [props.show, props.courseId], async ([show, courseId]) => {
  console.log('Modal watch triggered - show:', show, 'courseId:', courseId)
  if (show && courseId) {
    console.log('Fetching course details...')
    loading.value = true
    error.value = null
    try {
      const foundCourse = coursesStore.courses.find(c => c.id === courseId)
      if (foundCourse) {
        course.value = foundCourse
      } else {
        error.value = 'Course not found'
      }
    } catch (err) {
      error.value = 'Failed to load course details'
      console.error('Error loading course:', err)
    } finally {
      loading.value = false
    }
  }
}, { immediate: true })
</script>
