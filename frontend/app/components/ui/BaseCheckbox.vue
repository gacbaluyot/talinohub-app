<template>
  <div class="flex items-center">
    <input
      :id="id"
      type="checkbox"
      :checked="modelValue"
      :disabled="disabled"
      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
      @change="handleChange"
    />
    <label v-if="label" :for="id" class="ml-2 block text-sm text-gray-700">
      {{ label }}
    </label>
  </div>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{
  id?: string
  modelValue?: boolean
  label?: string
  disabled?: boolean
}>(), {
  modelValue: false,
  disabled: false
})

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
}>()

const handleChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  emit('update:modelValue', target.checked)
}
</script>
