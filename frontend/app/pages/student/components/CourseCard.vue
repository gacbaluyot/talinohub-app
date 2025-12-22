<template>
  <div class="group relative bg-white rounded-lg shadow hover:shadow-xl transition-all duration-200 overflow-hidden flex flex-col">
    <!-- Course Thumbnail -->
    <div class="aspect-video bg-gradient-to-br from-indigo-500 to-purple-600 relative overflow-hidden">
      <img
        v-if="course.thumbnail_path"
        :src="course.thumbnail_path"
        :alt="course.title"
        class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-200"
      />
      <div v-else class="flex items-center justify-center h-full">
        <svg class="h-16 w-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>

      <!-- Favorite Button -->
      <button
        v-if="!enrolled"
        @click.stop="$emit('toggle-favorite', course.id)"
        class="absolute top-2 right-2 p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition-colors"
        :title="favorite ? 'Remove from favorites' : 'Add to favorites'"
      >
        <HeartIcon
          :class="[
            'h-5 w-5 transition-colors',
            favorite ? 'text-red-500 fill-red-500' : 'text-gray-400 hover:text-red-500'
          ]"
        />
      </button>

      <!-- Status Badges -->
      <div class="absolute top-2 left-2 flex gap-2">
        <BaseBadge v-if="enrolled" variant="success">Enrolled</BaseBadge>
        <BaseBadge v-if="course.is_published" variant="primary">Published</BaseBadge>
      </div>
    </div>

    <!-- Course Info -->
    <div class="flex-1 flex flex-col p-5">
      <div class="flex-1">
        <!-- Title -->
        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 mb-2">
          {{ course.title }}
        </h3>

        <!-- Description -->
        <p class="text-sm text-gray-600 line-clamp-2 mb-4">
          {{ course.short_description || 'No description available' }}
        </p>

        <!-- Educator -->
        <div v-if="course.educator" class="flex items-center text-sm text-gray-500 mb-3">
          <UserCircleIcon class="h-4 w-4 mr-1" />
          {{ course.educator.name }}
        </div>

        <!-- Course Meta -->
        <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
          <div class="flex items-center">
            <BookOpenIcon class="h-4 w-4 mr-1" />
            {{ course.lessons_count || 0 }} lessons
          </div>
          <div v-if="course.level" class="flex items-center">
            <AcademicCapIcon class="h-4 w-4 mr-1" />
            {{ course.level }}
          </div>
        </div>

        <!-- Progress Bar (for enrolled courses) -->
        <div v-if="enrolled && course.pivot" class="mb-4">
          <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
            <span>Progress</span>
            <span class="font-medium">{{ Math.round(course.pivot.progress || 0) }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div
              class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
              :style="{ width: `${course.pivot.progress || 0}%` }"
            ></div>
          </div>
        </div>
      </div>

      <!-- Price & Actions -->
      <div class="flex items-center justify-between pt-4 border-t border-gray-100">
        <div>
          <BaseBadge
            :variant="course.is_free ? 'success' : 'primary'"
            size="lg"
          >
            {{ course.is_free ? 'Free' : `$${course.price}` }}
          </BaseBadge>
        </div>

        <div class="flex gap-2">
          <BaseButton
            variant="secondary"
            size="sm"
            @click="$emit('view-details', course)"
          >
            View Details
          </BaseButton>
          <BaseButton
            v-if="!enrolled"
            variant="primary"
            size="sm"
            @click="$emit('enroll', course.id)"
          >
            Enroll
          </BaseButton>
          <BaseButton
            v-else
            variant="primary"
            size="sm"
            @click="$emit('continue', course)"
          >
            Continue
          </BaseButton>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { BaseButton, BaseBadge } from '~/components/ui'
import { 
  HeartIcon, 
  BookOpenIcon, 
  AcademicCapIcon, 
  UserCircleIcon 
} from '@heroicons/vue/24/outline'
import type { Course } from '~/types/courses'

defineProps<{
  course: Course
  enrolled?: boolean
  favorite?: boolean
}>()

defineEmits<{
  'view-details': [course: Course]
  enroll: [courseId: number]
  continue: [course: Course]
  'toggle-favorite': [courseId: number]
}>()
</script>
