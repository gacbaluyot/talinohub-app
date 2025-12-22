<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      :maxlength="maxlength"
      :min="min"
      :max="max"
      :step="step"
      :class="inputClasses"
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
  modelValue?: string | number
  type?: 'text' | 'email' | 'password' | 'number' | 'url' | 'tel'
  label?: string
  placeholder?: string
  required?: boolean
  disabled?: boolean
  maxlength?: number
  min?: number
  max?: number
  step?: number | string
  hint?: string
  error?: string
}>(), {
  type: 'text',
  required: false,
  disabled: false
})

const emit = defineEmits<{
  'update:modelValue': [value: string | number]
  blur: [event: FocusEvent]
  focus: [event: FocusEvent]
}>()

const inputClasses = computed(() => {
  const base = 'mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'
  const error = props.error ? 'border-red-300' : 'border-gray-300'
  const disabled = props.disabled ? 'bg-gray-100 cursor-not-allowed' : ''
  
  return `${base} ${error} ${disabled}`
})

const handleInput = (event: Event) => {
  const target = event.target as HTMLInputElement
  const value = props.type === 'number' ? Number(target.value) : target.value
  emit('update:modelValue', value)
}
</script>
