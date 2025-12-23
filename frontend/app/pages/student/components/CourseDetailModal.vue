<template>
  <BaseModal :show="show" size="4xl" @close="$emit('close')">
    <div v-if="loading" class="text-center py-12">
      <BaseLoadingSpinner size="lg" text="Loading course details..." centered />
    </div>

    <div v-else-if="course" class="max-h-[80vh] overflow-y-auto">
      <!-- Header -->
      <div class="mb-6">
        <DialogTitle as="h3" class="text-2xl font-bold text-gray-900 mb-2">
          {{ course.title }}
        </DialogTitle>
        <div class="flex items-center gap-3 flex-wrap">
          <BaseBadge :variant="course.is_published ? 'success' : 'warning'">
            {{ course.is_published ? 'Published' : 'Draft' }}
          </BaseBadge>
          <BaseBadge :variant="course.is_free ? 'success' : 'primary'" size="lg">
            {{ course.is_free ? 'Free' : `$${course.price}` }}
          </BaseBadge>
          <BaseBadge v-if="course.level" variant="info">
            {{ course.level }}
          </BaseBadge>
          <BaseBadge v-if="enrolled" variant="success">Enrolled</BaseBadge>
        </div>
      </div>

      <!-- Thumbnail -->
      <div v-if="course.thumbnail_path" class="mb-6 rounded-lg overflow-hidden">
        <img
          :src="course.thumbnail_path"
          :alt="course.title"
          class="w-full h-64 object-cover"
        />
      </div>

      <!-- Educator Info -->
      <div v-if="course.educator" class="mb-6 flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
          <UserCircleIcon class="h-8 w-8 text-indigo-600" />
        </div>
        <div>
          <p class="text-sm text-gray-600">Instructor</p>
          <p class="font-semibold text-gray-900">{{ course.educator.name }}</p>
        </div>
      </div>

      <!-- Course Stats -->
      <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="text-center p-4 bg-gray-50 rounded-lg">
          <BookOpenIcon class="h-6 w-6 mx-auto text-indigo-600 mb-2" />
          <p class="text-2xl font-bold text-gray-900">{{ course.lessons_count || 0 }}</p>
          <p class="text-sm text-gray-600">Lessons</p>
        </div>
        <div class="text-center p-4 bg-gray-50 rounded-lg">
          <AcademicCapIcon class="h-6 w-6 mx-auto text-indigo-600 mb-2" />
          <p class="text-2xl font-bold text-gray-900">{{ course.level || 'Any' }}</p>
          <p class="text-sm text-gray-600">Level</p>
        </div>
        <div class="text-center p-4 bg-gray-50 rounded-lg">
          <GlobeAltIcon class="h-6 w-6 mx-auto text-indigo-600 mb-2" />
          <p class="text-2xl font-bold text-gray-900">{{ course.language || 'English' }}</p>
          <p class="text-sm text-gray-600">Language</p>
        </div>
      </div>

      <!-- Description -->
      <div class="mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-3">About this course</h4>
        <p class="text-gray-700 whitespace-pre-line">
          {{ course.description || course.short_description || 'No description available.' }}
        </p>
      </div>

      <!-- Lessons List -->
      <div v-if="lessons.length > 0" class="mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-3">Course Content</h4>
        <div class="border border-gray-200 rounded-lg overflow-hidden">
          <div
            v-for="lesson in lessons"
            :key="lesson.id"
            class="flex items-center justify-between p-4 border-b border-gray-200 last:border-b-0 hover:bg-gray-50"
          >
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center shrink-0">
                <component :is="getLessonIcon(lesson.type)" class="h-5 w-5 text-indigo-600" />
              </div>
              <div>
                <p class="font-medium text-gray-900">{{ lesson.title }}</p>
                <p class="text-sm text-gray-500">{{ lesson.type }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <BaseBadge v-if="lesson.is_preview" variant="success" size="sm">Preview</BaseBadge>
              <span v-if="lesson.duration_seconds" class="text-sm text-gray-500">
                {{ formatDuration(lesson.duration_seconds) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex items-center justify-between gap-4 pt-6 border-t border-gray-200">
        <div class="flex items-center gap-3">
          <BaseButton variant="secondary" @click="$emit('close')">
            Close
          </BaseButton>
          <BaseButton variant="outline" @click="handleViewFullDetails">
            View Full Details
          </BaseButton>
        </div>
        <BaseButton
          v-if="!enrolled"
          variant="primary"
          size="lg"
          @click="handleEnroll"
        >
          Enroll Now
        </BaseButton>
        <BaseButton
          v-else
          variant="primary"
          size="lg"
          @click="handleContinue"
        >
          Continue Learning
        </BaseButton>
      </div>
    </div>

    <BaseAlert
      v-else-if="error"
      variant="error"
      :message="error"
    />
  </BaseModal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { DialogTitle } from '@headlessui/vue'
import { BaseModal, BaseButton, BaseBadge, BaseLoadingSpinner, BaseAlert } from '~/components/ui'
import { 
  BookOpenIcon, 
  AcademicCapIcon, 
  GlobeAltIcon,
  UserCircleIcon,
  VideoCameraIcon,
  DocumentTextIcon
} from '@heroicons/vue/24/outline'
import { BookOpenIcon as BookOpenIconSolid } from '@heroicons/vue/24/solid'
import { useCoursesStore } from '~/stores/courses'
import type { Course, Lesson } from '~/types/courses'

const props = defineProps<{
  show: boolean
  courseId: number | null
  enrolled: boolean
}>()

const emit = defineEmits<{
  close: []
  enroll: [courseId: number]
}>()

const coursesStore = useCoursesStore()

const loading = ref(false)
const error = ref<string | null>(null)
const course = ref<Course | null>(null)
const lessons = ref<Lesson[]>([])

const loadCourseDetails = async () => {
  if (!props.courseId) return

  loading.value = true
  error.value = null

  try {
    await coursesStore.fetchCourse(props.courseId)
    course.value = coursesStore.currentCourse

    // Load lessons
    await coursesStore.fetchLessonsByCourse(props.courseId)
    lessons.value = coursesStore.lessons
  } catch (err) {
    error.value = 'Failed to load course details'
    console.error('Error loading course:', err)
  } finally {
    loading.value = false
  }
}

const getLessonIcon = (type: string) => {
  switch (type) {
    case 'video':
      return VideoCameraIcon
    case 'document':
      return DocumentTextIcon
    case 'text':
      return BookOpenIconSolid
    default:
      return BookOpenIconSolid
  }
}

const formatDuration = (seconds: number) => {
  const mins = Math.floor(seconds / 60)
  const secs = seconds % 60
  return `${mins}:${secs.toString().padStart(2, '0')}`
}

const handleEnroll = () => {
  if (props.courseId) {
    emit('enroll', props.courseId)
  }
}

const handleContinue = () => {
  // Navigate to course learning page
  if (props.courseId) {
    navigateTo(`/student/courses/${props.courseId}`)
    emit('close')
  }
}

const handleViewFullDetails = () => {
  if (props.courseId) {
    navigateTo(`/student/courses/${props.courseId}`)
    emit('close')
  }
}

watch(() => props.show, (newVal) => {
  if (newVal && props.courseId) {
    loadCourseDetails()
  }
})
</script>
