<template>
  <div
    class="group relative bg-surface-0 dark:bg-surface-900 border border-surface-200 dark:border-surface-700 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden cursor-pointer"
    @click="$emit('click', course)"
  >
    <!-- Course Thumbnail -->
    <div class="aspect-[4/3] relative overflow-hidden" style="background: hsl(0, 0%, 45.1%)">
      <img
        v-if="course.thumbnail_path"
        :src="course.thumbnail_path"
        :alt="course.title"
        class="h-full w-full object-cover"
      />
      <div v-else class="flex items-center justify-center h-full">
        <svg class="h-12 w-12 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>
      <!-- Status Badge -->
      <div class="absolute top-2 right-2">
        <span
          :class="[
            'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
            course.is_published 
              ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' 
              : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200'
          ]"
        >
          {{ course.is_published ? 'Published' : 'Draft' }}
        </span>
      </div>
    </div>

    <!-- Course Info -->
    <div class="p-3">
      <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
          <h3 class="text-sm font-semibold text-surface-900 dark:text-gray-200 truncate">
            {{ course.title }}
          </h3>
          <p class="mt-1 text-xs text-surface-600 dark:text-gray-400 line-clamp-2 min-h-[2rem]">
            {{ course.short_description || 'No description' }}
          </p>
        </div>
      </div>

      <!-- Course Meta -->
      <div class="mt-2 flex items-center gap-3 text-xs text-surface-600 dark:text-gray-500">
        <div class="flex items-center">
          <svg class="mr-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
          {{ course.lessons_count || 0 }} lessons
        </div>
        <div v-if="course.level" class="flex items-center capitalize">
          <svg class="mr-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          {{ course.level }}
        </div>
      </div>

      <!-- Price -->
      <div class="mt-2">
        <span
          :class="[
            'inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium',
            course.is_free
              ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 ring-1 ring-inset ring-green-600/20 dark:ring-green-500/30'
              : 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 ring-1 ring-inset ring-primary-600/20 dark:ring-primary-500/30'
          ]"
        >
          {{ course.is_free ? 'Free' : `$${course.price}` }}
        </span>
      </div>

      <!-- Actions -->
      <div class="mt-3 flex items-center gap-1.5">
        <button
          @click.stop="$emit('view', course.id)"
          class="flex-1 rounded-md bg-surface-0 dark:bg-transparent px-2.5 py-1.5 text-xs font-semibold text-surface-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-surface-300 dark:ring-gray-600 hover:bg-surface-50 dark:hover:bg-gray-800 transition-colors"
        >
          View
        </button>
        <button
          @click.stop="$emit('preview', course.id)"
          class="rounded-md bg-surface-0 dark:bg-transparent p-1.5 text-primary-600 dark:text-gray-400 shadow-sm ring-1 ring-inset ring-surface-300 dark:ring-gray-600 hover:bg-primary-50 dark:hover:bg-gray-800 transition-colors"
          title="Preview as Student"
        >
          <EyeIcon class="h-4 w-4" />
        </button>
        <button
          @click.stop="$emit('edit', course.id)"
          class="rounded-md bg-surface-0 dark:bg-transparent p-1.5 text-primary-600 dark:text-gray-400 shadow-sm ring-1 ring-inset ring-surface-300 dark:ring-gray-600 hover:bg-primary-50 dark:hover:bg-gray-800 transition-colors"
          title="Edit Course"
        >
          <PencilSquareIcon class="h-4 w-4" />
        </button>
        <button
          @click.stop="$emit('delete', course.id)"
          class="rounded-md bg-surface-0 dark:bg-transparent p-1.5 text-red-600 dark:text-gray-400 shadow-sm ring-1 ring-inset ring-surface-300 dark:ring-gray-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
          title="Delete Course"
        >
          <TrashIcon class="h-4 w-4" />
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
