<template>
  <TransitionRoot appear :show="show" as="template">
    <Dialog as="div" @close="$emit('close')" class="relative z-50">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-6xl transform overflow-hidden rounded-2xl bg-surface-0 dark:bg-surface-900 text-left align-middle shadow-2xl transition-all border border-surface-200 dark:border-surface-700">
              <!-- Header -->
              <div class="flex items-center justify-between border-b border-surface-200 dark:border-surface-700 px-6 py-4 bg-surface-50 dark:bg-surface-800">
                <div class="flex-1 min-w-0 pr-4">
                  <DialogTitle as="h3" class="text-lg font-semibold text-surface-900 dark:text-surface-0 truncate">
                    {{ lesson?.title || 'Lesson' }}
                  </DialogTitle>
                  <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 capitalize">
                      {{ lesson?.type || 'Content' }}
                    </span>
                    <span v-if="lesson?.duration_seconds" class="text-sm text-surface-500 dark:text-surface-400">
                      {{ formatDuration(lesson.duration_seconds) }}
                    </span>
                  </div>
                </div>
                <button
                  @click="$emit('close')"
                  class="ml-4 rounded-lg p-2 text-surface-400 dark:text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 hover:text-surface-600 dark:hover:text-surface-300 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  aria-label="Close modal"
                >
                  <XMarkIcon class="h-6 w-6" />
                </button>
              </div>

              <!-- Video Player or Content -->
              <div class="relative">
                <!-- Video Player -->
                <div v-if="lesson?.type === 'video'" class="aspect-video bg-black">
                  <!-- If video_path exists, show video player -->
                  <video
                    v-if="lesson?.video_path"
                    :src="lesson.video_path"
                    controls
                    class="w-full h-full"
                    controlsList="nodownload"
                    autoplay
                  >
                    Your browser does not support the video tag.
                  </video>
                  
                  <!-- Placeholder if no video -->
                  <div v-else class="flex items-center justify-center h-full text-center text-white p-12">
                    <div>
                      <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white/10 backdrop-blur-sm mb-6">
                        <VideoCameraIcon class="h-12 w-12 opacity-50" />
                      </div>
                      <p class="text-xl font-semibold mb-2">Video not available</p>
                      <p class="text-sm text-gray-400">This lesson's video is being processed or not yet uploaded.</p>
                    </div>
                  </div>
                </div>

                <!-- Document Preview -->
                <div v-else-if="lesson?.type === 'document'" class="aspect-video bg-surface-50 dark:bg-surface-800 flex items-center justify-center p-8">
                  <div v-if="lesson?.document_path" class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-100 dark:bg-indigo-900/30 mb-6">
                      <DocumentTextIcon class="h-10 w-10 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <p class="text-lg font-semibold text-surface-900 dark:text-surface-0 mb-4">Document Lesson</p>
                    <p class="text-sm text-surface-600 dark:text-surface-400 mb-6 max-w-md">
                      This lesson contains a document. Click the button below to view or download it.
                    </p>
                    <a
                      :href="lesson.document_path"
                      target="_blank"
                      class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-indigo-500 hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                      <ArrowDownTrayIcon class="h-5 w-5 mr-2" />
                      Open Document
                    </a>
                  </div>
                  <div v-else class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-surface-200 dark:bg-surface-700 mb-6">
                      <DocumentTextIcon class="h-10 w-10 text-surface-400 dark:text-surface-500" />
                    </div>
                    <p class="text-lg font-semibold text-surface-900 dark:text-surface-0 mb-2">Document not available</p>
                    <p class="text-sm text-surface-500 dark:text-surface-400">This document has not been uploaded yet.</p>
                  </div>
                </div>

                <!-- Text Content -->
                <div v-else class="aspect-video bg-surface-50 dark:bg-surface-800 overflow-y-auto">
                  <div class="max-w-3xl mx-auto p-8 md:p-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900/30 mb-6">
                      <BookOpenIcon class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div v-if="lesson?.content" class="prose prose-lg dark:prose-invert max-w-none">
                      <div class="text-surface-700 dark:text-surface-300 whitespace-pre-line leading-relaxed">{{ lesson.content }}</div>
                    </div>
                    <div v-else class="text-center text-surface-500 dark:text-surface-400 py-12">
                      <p class="text-lg">No content available for this lesson.</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Lesson Details -->
              <div class="px-6 py-5 bg-surface-50 dark:bg-surface-800 border-t border-surface-200 dark:border-surface-700">
                <div class="flex items-start justify-between gap-4">
                  <div class="flex-1">
                    <h4 class="text-sm font-semibold text-surface-900 dark:text-surface-0 mb-2 flex items-center gap-2">
                      <InformationCircleIcon class="h-4 w-4 text-indigo-600" />
                      About this lesson
                    </h4>
                    <p v-if="lesson?.description" class="text-sm text-surface-600 dark:text-surface-400 leading-relaxed">
                      {{ lesson.description }}
                    </p>
                    <p v-else class="text-sm text-surface-500 dark:text-surface-400 italic">
                      No description available.
                    </p>
                  </div>
                  <div v-if="lesson?.duration_seconds" class="text-right shrink-0">
                    <p class="text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Duration</p>
                    <p class="text-lg font-bold text-surface-900 dark:text-surface-0">{{ formatDuration(lesson.duration_seconds) }}</p>
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex items-center justify-between gap-4 px-6 py-4 bg-surface-0 dark:bg-surface-900 border-t border-surface-200 dark:border-surface-700">
                <button
                  @click="markAsComplete"
                  class="inline-flex items-center rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                  <CheckCircleIcon class="h-5 w-5 mr-2" />
                  Mark as Complete
                </button>
                <div class="flex items-center gap-2">
                  <button
                    @click="handlePrevious"
                    :disabled="!hasPrevious"
                    :class="[
                      'inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold shadow-sm transition-all focus:outline-none',
                      hasPrevious
                        ? 'bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 hover:bg-surface-200 dark:hover:bg-surface-700 border border-surface-300 dark:border-surface-600 focus:ring-2 focus:ring-indigo-500'
                        : 'bg-surface-100 dark:bg-surface-800 text-surface-400 dark:text-surface-500 cursor-not-allowed opacity-50'
                    ]"
                  >
                    <ChevronLeftIcon class="h-5 w-5 mr-1" />
                    Previous
                  </button>
                  <button
                    @click="handleNext"
                    :disabled="!hasNext"
                    :class="[
                      'inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold shadow-sm transition-all focus:outline-none',
                      hasNext
                        ? 'bg-indigo-600 text-white hover:bg-indigo-500 hover:shadow-md focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2'
                        : 'bg-surface-100 dark:bg-surface-800 text-surface-400 dark:text-surface-500 cursor-not-allowed opacity-50'
                    ]"
                  >
                    Next
                    <ChevronRightIcon class="h-5 w-5 ml-1" />
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import {
  XMarkIcon,
  VideoCameraIcon,
  DocumentTextIcon,
  BookOpenIcon,
  ArrowDownTrayIcon,
  CheckCircleIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  InformationCircleIcon
} from '@heroicons/vue/24/outline'
import type { Lesson } from '~/types/courses'

const props = defineProps<{
  show: boolean
  lesson: Lesson | null
}>()

const emit = defineEmits<{
  close: []
  next: []
  previous: []
  complete: [lessonId: number]
}>()

// These would normally come from props or store
const hasPrevious = computed(() => false) // TODO: Implement based on lesson order
const hasNext = computed(() => false) // TODO: Implement based on lesson order

const formatDuration = (seconds: number) => {
  const hours = Math.floor(seconds / 3600)
  const mins = Math.floor((seconds % 3600) / 60)
  const secs = seconds % 60

  if (hours > 0) {
    return `${hours}h ${mins}m ${secs}s`
  }
  if (mins > 0) {
    return `${mins}m ${secs}s`
  }
  return `${secs}s`
}

const markAsComplete = () => {
  if (props.lesson) {
    emit('complete', props.lesson.id)
    // Show a success toast or notification
    console.log('Lesson marked as complete:', props.lesson.id)
  }
}

const handleNext = () => {
  emit('next')
}

const handlePrevious = () => {
  emit('previous')
}
</script>
