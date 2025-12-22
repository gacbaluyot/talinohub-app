<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <textarea
      :id="id"
      :value="modelValue"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      :rows="rows"
      :maxlength="maxlength"
      :class="textareaClasses"
      @input="handleInput"
      @blur="$emit('blur', $event)"
      @focus="$emit('focus', $event)"
    />
    <p v-if="hint" class="mt-1 text-xs text-gray-500">{{ hint }}</p>
    <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(defineProps<{
  id?: string
  modelValue?: string
  label?: string
  placeholder?: string
  required?: boolean
  disabled?: boolean
  rows?: number
  maxlength?: number
  hint?: string
  error?: string
}>(), {
  required: false,
  disabled: false,
  rows: 4
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
  blur: [event: FocusEvent]
  focus: [event: FocusEvent]
}>()

const textareaClasses = computed(() => {
  const base = 'mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'
  const error = props.error ? 'border-red-300' : 'border-gray-300'
  const disabled = props.disabled ? 'bg-gray-100 cursor-not-allowed' : ''
  
  return `${base} ${error} ${disabled}`
})

const handleInput = (event: Event) => {
  const target = event.target as HTMLTextAreaElement
  emit('update:modelValue', target.value)
}
</script>
