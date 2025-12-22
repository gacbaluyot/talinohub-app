import { defineStore } from 'pinia'
import axios, { AxiosError } from 'axios'
import type {
  EnrollCourseRequest,
  UpdateProgressRequest,
  EnrollmentResponse,
  EnrollmentsResponse,
  FavoritesResponse,
  EnrollmentState
} from '~/types/enrollment'

export const useEnrollmentStore = defineStore('enrollment', {
  state: (): EnrollmentState => ({
    enrolledCourses: [],
    favoriteCourses: [],
    loading: false,
    error: null
  }),

  actions: {
    // Enroll in course
    async enrollInCourse(courseId: number) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.post<EnrollmentResponse>('/enrollments', {
          course_id: courseId
        })

        if (data.success) {
          // Refresh enrolled courses
          await this.fetchMyEnrollments()
        }

        return data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to enroll in course'
        throw err
      } finally {
        this.loading = false
      }
    },

    // Unenroll from course
    async unenrollFromCourse(courseId: number) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.delete<EnrollmentResponse>(`/enrollments/${courseId}`)

        if (data.success) {
          // Remove from enrolled courses
          this.enrolledCourses = this.enrolledCourses.filter(c => c.id !== courseId)
        }

        return data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to unenroll from course'
        throw err
      } finally {
        this.loading = false
      }
    },

    // Fetch my enrollments
    async fetchMyEnrollments() {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.get<EnrollmentsResponse>('/enrollments/my-enrollments')
        this.enrolledCourses = data.data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to fetch enrollments'
        console.error('Error fetching enrollments:', err)
      } finally {
        this.loading = false
      }
    },

    // Update course progress
    async updateCourseProgress(courseId: number, progress: number) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.put<EnrollmentResponse>(
          `/enrollments/${courseId}/progress`,
          { progress }
        )

        if (data.success) {
          // Update progress in enrolled courses
          const course = this.enrolledCourses.find(c => c.id === courseId)
          if (course && course.pivot) {
            course.pivot.progress = progress
          }
        }

        return data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to update progress'
        throw err
      } finally {
        this.loading = false
      }
    },

    // Add to favorites
    async addToFavorites(courseId: number) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.post<EnrollmentResponse>(`/enrollments/${courseId}/favorite`)

        if (data.success) {
          await this.fetchMyFavorites()
        }

        return data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to add to favorites'
        throw err
      } finally {
        this.loading = false
      }
    },

    // Remove from favorites
    async removeFromFavorites(courseId: number) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.delete<EnrollmentResponse>(`/enrollments/${courseId}/favorite`)

        if (data.success) {
          this.favoriteCourses = this.favoriteCourses.filter(c => c.id !== courseId)
        }

        return data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to remove from favorites'
        throw err
      } finally {
        this.loading = false
      }
    },

    // Fetch my favorites
    async fetchMyFavorites() {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.get<FavoritesResponse>('/enrollments/my-favorites')
        this.favoriteCourses = data.data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to fetch favorites'
        console.error('Error fetching favorites:', err)
      } finally {
        this.loading = false
      }
    },

    // Check if enrolled in course
    isEnrolled(courseId: number): boolean {
      return this.enrolledCourses.some(c => c.id === courseId)
    },

    // Check if course is favorited
    isFavorite(courseId: number): boolean {
      return this.favoriteCourses.some(c => c.id === courseId)
    },

    // Clear error
    clearError() {
      this.error = null
    },

    // Reset store
    resetStore() {
      this.enrolledCourses = []
      this.favoriteCourses = []
      this.loading = false
      this.error = null
    }
  }
})
