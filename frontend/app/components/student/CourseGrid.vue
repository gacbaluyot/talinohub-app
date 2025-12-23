<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    <div
      v-for="course in courses"
      :key="course.id"
      @click="$emit('view-course', course)"
      class="group cursor-pointer"
    >
      <div class="relative aspect-video bg-gray-900 rounded-lg overflow-hidden mb-2">
        <img
          v-if="course.thumbnail_path"
          :src="course.thumbnail_path"
          :alt="course.title"
          class="w-full h-full object-cover group-hover:opacity-90 transition-opacity"
        />
        <div v-else class="flex items-center justify-center h-full bg-gradient-to-br from-indigo-500 to-purple-600">
          <svg class="h-12 w-12 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        
        <!-- Progress Bar (if enrolled) -->
        <div v-if="showProgress" class="absolute bottom-0 left-0 right-0 h-1 bg-gray-700">
          <div class="h-full bg-red-600" :style="{ width: '35%' }"></div>
        </div>
        
        <!-- Lesson Count Badge -->
        <div v-else class="absolute bottom-2 right-2 bg-black/80 text-white text-xs px-2 py-1 rounded">
          {{ course.lessons_count || 0 }} lessons
        </div>
      </div>
      
      <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1">{{ course.title }}</h3>
      <p class="text-xs text-gray-500 mb-1">{{ course.educator?.name || 'Educator' }}</p>
      
      <div v-if="!showProgress" class="flex items-center gap-2">
        <div class="flex items-center">
          <svg class="h-4 w-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <span class="text-xs text-gray-600 ml-1">4.8</span>
        </div>
        <span class="text-xs text-gray-400">•</span>
        <span
          v-if="course.is_free"
          class="text-xs font-semibold text-green-600"
        >
          Free
        </span>
        <span
          v-else
          class="text-xs font-semibold text-gray-900"
        >
          ${{ course.price }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Course } from '~/types/courses'

defineProps<{
  courses: Course[]
  showProgress?: boolean
}>()

defineEmits<{
  'view-course': [course: Course]
}>()
</script>
