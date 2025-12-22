<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <select
      :id="id"
      :value="modelValue"
      :required="required"
      :disabled="disabled"
      :class="selectClasses"
      @change="handleChange"
      @blur="$emit('blur', $event)"
      @focus="$emit('focus', $event)"
    >
      <option v-if="placeholder" value="">{{ placeholder }}</option>
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>
    <p v-if="hint" class="mt-1 text-xs text-gray-500">{{ hint }}</p>
    <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

export interface SelectOption {
  value: string | number
  label: string
}

const props = withDefaults(defineProps<{
  id?: string
  modelValue?: string | number
  label?: string
  placeholder?: string
  options: SelectOption[]
  required?: boolean
  disabled?: boolean
  hint?: string
  error?: string
}>(), {
  required: false,
  disabled: false
})

const emit = defineEmits<{
  'update:modelValue': [value: string | number]
  blur: [event: FocusEvent]
  focus: [event: FocusEvent]
}>()

const selectClasses = computed(() => {
  const base = 'mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'
  const error = props.error ? 'border-red-300' : 'border-gray-300'
  const disabled = props.disabled ? 'bg-gray-100 cursor-not-allowed' : ''
  
  return `${base} ${error} ${disabled}`
})

const handleChange = (event: Event) => {
  const target = event.target as HTMLSelectElement
  emit('update:modelValue', target.value)
}
</script>
