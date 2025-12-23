<template>
  <div class="w-full">
    <label v-if="label" :for="id" class="block text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 mb-2">
      {{ label }}
      <span v-if="required" class="text-destructive ml-1">*</span>
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
      :class="cn(inputVariants({ variant: error ? 'destructive' : 'default' }), $attrs.class as string)"
      @input="handleInput"
      @blur="$emit('blur', $event)"
      @focus="$emit('focus', $event)"
    />
    <p v-if="hint && !error" class="mt-1.5 text-xs text-muted-foreground">{{ hint }}</p>
    <p v-if="error" class="mt-1.5 text-xs text-destructive">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { cn } from '../../../lib/utils'
import { cva, type VariantProps } from 'class-variance-authority'

const inputVariants = cva(
  'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
  {
    variants: {
      variant: {
        default: '',
        destructive: 'border-destructive focus-visible:ring-destructive',
      },
    },
    defaultVariants: {
      variant: 'default',
    },
  },
)

const props = withDefaults(defineProps<{
  id?: string
  modelValue?: string | number
  type?: 'text' | 'email' | 'password' | 'number' | 'url' | 'tel' | 'search' | 'date' | 'time'
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

const handleInput = (event: Event) => {
  const target = event.target as HTMLInputElement
  const value = props.type === 'number' ? Number(target.value) : target.value
  emit('update:modelValue', value)
}
</script>
