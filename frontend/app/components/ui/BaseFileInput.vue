<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      :id="id"
      type="file"
      :accept="accept"
      :required="required"
      :disabled="disabled"
      :class="fileInputClasses"
      @change="handleChange"
    />
    <p v-if="hint" class="mt-1 text-xs text-gray-500">{{ hint }}</p>
    <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(defineProps<{
  id?: string
  label?: string
  accept?: string
  required?: boolean
  disabled?: boolean
  hint?: string
  error?: string
}>(), {
  required: false,
  disabled: false
})

const emit = defineEmits<{
  change: [file: File | null]
}>()

const fileInputClasses = computed(() => {
  const base = 'mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100'
  const disabled = props.disabled ? 'cursor-not-allowed opacity-50' : ''
  
  return `${base} ${disabled}`
})

const handleChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0] || null
  emit('change', file)
}
</script>
