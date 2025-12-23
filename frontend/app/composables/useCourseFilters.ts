import { ref, computed } from 'vue'
import type { Course } from '~/types/courses'

export const useCourseFilters = (courses: Ref<Course[]>) => {
  const searchQuery = ref('')
  const selectedCategory = ref('')
  const selectedLevel = ref('')
  const priceFilter = ref('')

  const filteredCourses = computed(() => {
    let filtered = courses.value.filter(c => c.is_published)

    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase()
      filtered = filtered.filter(course =>
        course.title.toLowerCase().includes(query) ||
        course.short_description?.toLowerCase().includes(query)
      )
    }

    if (selectedCategory.value) {
      filtered = filtered.filter(c => c.category === selectedCategory.value)
    }

    if (selectedLevel.value) {
      filtered = filtered.filter(c => c.level === selectedLevel.value)
    }

    if (priceFilter.value === 'free') {
      filtered = filtered.filter(c => c.is_free)
    } else if (priceFilter.value === 'paid') {
      filtered = filtered.filter(c => !c.is_free)
    }

    return filtered
  })

  const displayedCourses = computed(() => {
    return filteredCourses.value.slice(0, 12)
  })

  return {
    searchQuery,
    selectedCategory,
    selectedLevel,
    priceFilter,
    filteredCourses,
    displayedCourses
  }
}
