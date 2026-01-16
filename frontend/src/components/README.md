# UI Components

Reusable UI components for the TalinoHub application.

## Components

### BaseModal
Modal dialog wrapper with transition animations.

**Props:**
- `show` (boolean, required) - Controls modal visibility
- `size` ('sm' | 'md' | 'lg' | 'xl' | '2xl' | '4xl') - Modal size, default: '2xl'
- `preventClose` (boolean) - Prevents closing on backdrop click

**Events:**
- `close` - Emitted when modal should close

**Usage:**
```vue
<BaseModal :show="showModal" size="lg" @close="showModal = false">
  <DialogTitle>Modal Title</DialogTitle>
  <!-- Modal content -->
</BaseModal>
```

### BaseButton
Customizable button component with loading state.

**Props:**
- `type` ('button' | 'submit' | 'reset') - Button type, default: 'button'
- `variant` ('primary' | 'secondary' | 'danger' | 'ghost') - Button style, default: 'primary'
- `size` ('sm' | 'md' | 'lg') - Button size, default: 'md'
- `fullWidth` (boolean) - Makes button full width
- `disabled` (boolean) - Disables the button
- `loading` (boolean) - Shows loading spinner
- `loadingText` (string) - Text to show when loading

**Usage:**
```vue
<BaseButton 
  variant="primary" 
  :loading="isLoading" 
  loadingText="Saving..."
  @click="handleClick"
>
  Save
</BaseButton>
```

### BaseInput
Text input field with label and validation support.

**Props:**
- `modelValue` (string | number) - Input value (v-model)
- `type` ('text' | 'email' | 'password' | 'number' | 'url' | 'tel') - Input type
- `label` (string) - Field label
- `placeholder` (string) - Placeholder text
- `required` (boolean) - Makes field required
- `disabled` (boolean) - Disables the input
- `hint` (string) - Helper text
- `error` (string) - Error message

**Usage:**
```vue
<BaseInput 
  v-model="form.email" 
  type="email" 
  label="Email Address" 
  required 
  :error="errors.email"
/>
```

### BaseTextarea
Textarea field with label and validation support.

**Props:**
- `modelValue` (string) - Textarea value (v-model)
- `label` (string) - Field label
- `rows` (number) - Number of rows, default: 4
- `required` (boolean) - Makes field required
- `hint` (string) - Helper text
- `error` (string) - Error message

**Usage:**
```vue
<BaseTextarea 
  v-model="form.description" 
  label="Description" 
  rows="6"
/>
```

### BaseSelect
Select dropdown with options.

**Props:**
- `modelValue` (string | number) - Selected value (v-model)
- `options` (SelectOption[]) - Array of options
- `label` (string) - Field label
- `placeholder` (string) - Placeholder option
- `required` (boolean) - Makes field required
- `hint` (string) - Helper text
- `error` (string) - Error message

**Usage:**
```vue
<BaseSelect 
  v-model="form.level" 
  label="Level" 
  :options="levelOptions"
  placeholder="Select level"
/>
```

### BaseFileInput
File upload input.

**Props:**
- `label` (string) - Field label
- `accept` (string) - Accepted file types
- `required` (boolean) - Makes field required
- `hint` (string) - Helper text
- `error` (string) - Error message

**Events:**
- `change` - Emits selected file

**Usage:**
```vue
<BaseFileInput 
  label="Thumbnail" 
  accept="image/*" 
  hint="PNG, JPG up to 2MB"
  @change="handleFileChange"
/>
```

### BaseCheckbox
Checkbox input with label.

**Props:**
- `modelValue` (boolean) - Checked state (v-model)
- `label` (string) - Checkbox label
- `disabled` (boolean) - Disables the checkbox

**Usage:**
```vue
<BaseCheckbox v-model="form.isPublished" label="Publish immediately" />
```

### BaseBadge
Badge/tag component for status indicators.

**Props:**
- `variant` ('primary' | 'success' | 'warning' | 'danger' | 'info' | 'gray') - Badge color
- `size` ('sm' | 'md' | 'lg') - Badge size, default: 'md'

**Usage:**
```vue
<BaseBadge variant="success">Published</BaseBadge>
<BaseBadge variant="warning">Draft</BaseBadge>
```

### BaseEmptyState
Empty state component with icon and action button.

**Props:**
- `title` (string) - Empty state title
- `description` (string) - Description text
- `icon` (Component) - Icon component
- `compact` (boolean) - Compact padding

**Slots:**
- `icon` - Custom icon slot
- `action` - Action button slot

**Usage:**
```vue
<BaseEmptyState 
  title="No courses yet" 
  description="Get started by creating your first course"
>
  <template #action>
    <BaseButton @click="createCourse">Create Course</BaseButton>
  </template>
</BaseEmptyState>
```

### BaseLoadingSpinner
Loading spinner with optional text.

**Props:**
- `size` ('sm' | 'md' | 'lg') - Spinner size
- `text` (string) - Loading text
- `centered` (boolean) - Centers the spinner

**Usage:**
```vue
<BaseLoadingSpinner size="lg" text="Loading courses..." centered />
```

### BaseAlert
Alert/notification component.

**Props:**
- `show` (boolean) - Controls visibility, default: true
- `variant` ('success' | 'warning' | 'error' | 'info') - Alert type
- `title` (string) - Alert title
- `message` (string) - Alert message
- `dismissible` (boolean) - Shows close button

**Events:**
- `close` - Emitted when alert is dismissed

**Usage:**
```vue
<BaseAlert 
  variant="error" 
  :show="hasError"
  title="Error" 
  :message="errorMessage"
  dismissible
  @close="hasError = false"
/>
```

## Import Example

```vue
<script setup lang="ts">
import { BaseButton, BaseInput, BaseModal } from '~/components/ui'
</script>
```
