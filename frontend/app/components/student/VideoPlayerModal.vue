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
        <div class="fixed inset-0 bg-black/75" />
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
            <DialogPanel class="w-full max-w-5xl transform overflow-hidden rounded-2xl bg-white text-left align-middle shadow-xl transition-all">
              <!-- Header -->
              <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <div class="flex-1 min-w-0">
                  <DialogTitle as="h3" class="text-lg font-semibold text-gray-900 truncate">
                    {{ lesson?.title || 'Lesson' }}
                  </DialogTitle>
                  <p class="text-sm text-gray-500 mt-1">{{ lesson?.type || 'Video' }}</p>
                </div>
                <button
                  @click="$emit('close')"
                  class="ml-4 rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 transition-colors"
                >
                  <XMarkIcon class="h-6 w-6" />
                </button>
              </div>

              <!-- Video Player or Content -->
              <div class="bg-black">
                <!-- Video Player -->
                <div v-if="lesson?.type === 'video'" class="aspect-video bg-gray-900 flex items-center justify-center">
                  <!-- If video_path exists, show video player -->
                  <video
                    v-if="lesson?.video_path"
                    :src="lesson.video_path"
                    controls
                    class="w-full h-full"
                    autoplay
                  >
                    Your browser does not support the video tag.
                  </video>
                  
                  <!-- Placeholder if no video -->
                  <div v-else class="text-center text-white p-12">
                    <VideoCameraIcon class="h-24 w-24 mx-auto mb-4 opacity-50" />
                    <p class="text-lg font-medium">Video not available</p>
                    <p class="text-sm text-gray-400 mt-2">This lesson's video is being processed or not yet uploaded.</p>
                  </div>
                </div>

                <!-- Document Preview -->
                <div v-else-if="lesson?.type === 'document'" class="aspect-video bg-gray-50 flex items-center justify-center p-8">
                  <div v-if="lesson?.document_path" class="text-center">
                    <DocumentTextIcon class="h-16 w-16 mx-auto mb-4 text-indigo-600" />
                    <p class="text-lg font-medium text-gray-900 mb-4">Document Lesson</p>
                    <a
                      :href="lesson.document_path"
                      target="_blank"
                      class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                    >
                      <ArrowDownTrayIcon class="h-5 w-5 mr-2" />
                      Download Document
                    </a>
                  </div>
                  <div v-else class="text-center">
                    <DocumentTextIcon class="h-24 w-24 mx-auto mb-4 text-gray-400" />
                    <p class="text-lg font-medium text-gray-900">Document not available</p>
                  </div>
                </div>

                <!-- Text Content -->
                <div v-else class="aspect-video bg-gray-50 overflow-y-auto p-8">
                  <div class="max-w-3xl mx-auto">
                    <BookOpenIcon class="h-12 w-12 text-indigo-600 mb-4" />
                    <div v-if="lesson?.content" class="prose prose-indigo max-w-none">
                      <div class="text-gray-700 whitespace-pre-line">{{ lesson.content }}</div>
                    </div>
                    <div v-else class="text-center text-gray-500">
                      <p>No content available for this lesson.</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Lesson Details -->
              <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900 mb-2">About this lesson</h4>
                    <p v-if="lesson?.description" class="text-sm text-gray-600">
                      {{ lesson.description }}
                    </p>
                    <p v-else class="text-sm text-gray-500 italic">
                      No description available.
                    </p>
                  </div>
                  <div v-if="lesson?.duration_seconds" class="ml-4 text-right">
                    <p class="text-xs text-gray-500">Duration</p>
                    <p class="text-sm font-semibold text-gray-900">{{ formatDuration(lesson.duration_seconds) }}</p>
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex items-center justify-between gap-4 px-6 py-4 bg-white border-t border-gray-200">
                <button
                  @click="markAsComplete"
                  class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors"
                >
                  <CheckCircleIcon class="h-5 w-5 mr-2" />
                  Mark as Complete
                </button>
                <div class="flex items-center gap-2">
                  <button
                    @click="handlePrevious"
                    :disabled="!hasPrevious"
                    :class="[
                      'inline-flex items-center rounded-md px-4 py-2 text-sm font-semibold shadow-sm transition-colors',
                      hasPrevious
                        ? 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                        : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                    ]"
                  >
                    <ChevronLeftIcon class="h-5 w-5 mr-1" />
                    Previous
                  </button>
                  <button
                    @click="handleNext"
                    :disabled="!hasNext"
                    :class="[
                      'inline-flex items-center rounded-md px-4 py-2 text-sm font-semibold shadow-sm transition-colors',
                      hasNext
                        ? 'bg-indigo-600 text-white hover:bg-indigo-500'
                        : 'bg-gray-100 text-gray-400 cursor-not-allowed'
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
  ChevronRightIcon
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
    return `${hours}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`
  }
  return `${mins}:${secs.toString().padStart(2, '0')}`
}

const markAsComplete = () => {
  if (props.lesson) {
    emit('complete', props.lesson.id)
  }
}

const handleNext = () => {
  emit('next')
}

const handlePrevious = () => {
  emit('previous')
}
</script>
