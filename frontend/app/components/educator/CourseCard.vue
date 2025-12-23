<template>
  <div
    class="group relative bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 overflow-hidden cursor-pointer"
    @click="$emit('click', course)"
  >
    <!-- Course Thumbnail -->
    <div class="aspect-video bg-gradient-to-br from-indigo-500 to-purple-600 relative">
      <img
        v-if="course.thumbnail_path"
        :src="course.thumbnail_path"
        :alt="course.title"
        class="h-full w-full object-cover"
      />
      <div v-else class="flex items-center justify-center h-full">
        <svg class="h-16 w-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>
      <!-- Status Badge -->
      <div class="absolute top-2 right-2">
        <span
          :class="[
            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
            course.is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
          ]"
        >
          {{ course.is_published ? 'Published' : 'Draft' }}
        </span>
      </div>
    </div>

    <!-- Course Info -->
    <div class="p-5">
      <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
          <h3 class="text-lg font-semibold text-gray-900 truncate">
            {{ course.title }}
          </h3>
          <p class="mt-1 text-sm text-gray-500 line-clamp-2">
            {{ course.short_description || 'No description' }}
          </p>
        </div>
      </div>

      <!-- Course Meta -->
      <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
        <div class="flex items-center">
          <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
          {{ course.lessons_count || 0 }} lessons
        </div>
        <div v-if="course.level" class="flex items-center">
          <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          {{ course.level }}
        </div>
      </div>

      <!-- Price -->
      <div class="mt-3">
        <span
          :class="[
            'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium',
            course.is_free
              ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20'
              : 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20'
          ]"
        >
          {{ course.is_free ? 'Free' : `$${course.price}` }}
        </span>
      </div>

      <!-- Actions -->
      <div class="mt-5 flex items-center gap-2">
        <button
          @click.stop="$emit('view', course.id)"
          class="flex-1 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
        >
          View Details
        </button>
        <button
          @click.stop="$emit('preview', course.id)"
          class="rounded-md bg-white p-2 text-blue-600 shadow-sm ring-1 ring-inset ring-blue-300 hover:bg-blue-50 transition-colors"
          title="Preview as Student"
        >
          <EyeIcon class="h-5 w-5" />
        </button>
        <button
          @click.stop="$emit('edit', course.id)"
          class="rounded-md bg-white p-2 text-indigo-600 shadow-sm ring-1 ring-inset ring-indigo-300 hover:bg-indigo-50 transition-colors"
          title="Edit Course"
        >
          <PencilSquareIcon class="h-5 w-5" />
        </button>
        <button
          @click.stop="$emit('delete', course.id)"
          class="rounded-md bg-white p-2 text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50 transition-colors"
          title="Delete Course"
        >
          <TrashIcon class="h-5 w-5" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { PencilSquareIcon, TrashIcon, EyeIcon } from '@heroicons/vue/24/outline'
import type { Course } from '~/types/courses'

defineProps<{
  course: Course
}>()

defineEmits<{
  'click': [course: Course]
  'view': [courseId: number]
  'preview': [courseId: number]
  'edit': [courseId: number]
  'delete': [courseId: number]
}>()
</script>
