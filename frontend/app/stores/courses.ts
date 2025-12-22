import { defineStore } from 'pinia'
import axios, { AxiosError } from 'axios'
import type {
  Course,
  Lesson,
  CreateCourseRequest,
  UpdateCourseRequest,
  CreateLessonRequest,
  UpdateLessonRequest,
  CoursesResponse,
  CourseResponse,
  LessonsResponse,
  LessonResponse,
  CoursesState
} from '~/types/courses'

export const useCoursesStore = defineStore('courses', {
  state: (): CoursesState => ({
    courses: [],
    currentCourse: null,
    lessons: [],
    currentLesson: null,
    pagination: null,
    loading: false,
    error: null
  }),

  actions: {
    // Fetch all courses with pagination
    async fetchCourses(page: number = 1, perPage: number = 15) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.get<CoursesResponse>('/courses', {
          params: { page, per_page: perPage }
        })

        this.courses = data.data.data
        this.pagination = {
          currentPage: data.data.current_page,
          lastPage: data.data.last_page,
          perPage: data.data.per_page,
          total: data.data.total,
          from: data.data.from,
          to: data.data.to
        }
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to fetch courses'
        console.error('Error fetching courses:', err)
      } finally {
        this.loading = false
      }
    },

    // Fetch courses by educator
    async fetchEducatorCourses(userId: number) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.get<CoursesResponse>(`/courses/educator/${userId}`)
        this.courses = data.data.data || data.data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to fetch educator courses'
        console.error('Error fetching educator courses:', err)
      } finally {
        this.loading = false
      }
    },

    // Fetch single course
    async fetchCourse(identifier: string | number) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.get<CourseResponse>(`/courses/${identifier}`)
        this.currentCourse = data.data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to fetch course'
        console.error('Error fetching course:', err)
      } finally {
        this.loading = false
      }
    },

    // Fetch course by ID and add to courses array
    async fetchCourseById(id: number) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.get<CourseResponse>(`/courses/${id}`)
        this.currentCourse = data.data
        
        // Update or add to courses array
        const index = this.courses.findIndex(c => c.id === id)
        if (index !== -1) {
          this.courses[index] = data.data
        } else {
          this.courses.push(data.data)
        }
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to fetch course'
        console.error('Error fetching course:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    // Create course
    async createCourse(courseData: CreateCourseRequest) {
      this.loading = true
      this.error = null

      try {
        const formData = new FormData()
        
        Object.entries(courseData).forEach(([key, value]) => {
          if (value !== undefined && value !== null) {
            if (key === 'thumbnail' && value instanceof File) {
              formData.append(key, value)
            } else if (typeof value === 'boolean') {
              formData.append(key, value ? '1' : '0')
            } else {
              formData.append(key, String(value))
            }
          }
        })

        const { data } = await axios.post<CourseResponse>('/courses', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        this.currentCourse = data.data
        
        // Add to courses list
        if (this.courses) {
          this.courses.unshift(data.data)
        }

        return data.data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to create course'
        console.error('Error creating course:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    // Update course
    async updateCourse(id: number, courseData: UpdateCourseRequest) {
      this.loading = true
      this.error = null

      try {
        const formData = new FormData()
        formData.append('_method', 'PUT')
        
        Object.entries(courseData).forEach(([key, value]) => {
          if (value !== undefined && value !== null) {
            if (key === 'thumbnail' && value instanceof File) {
              formData.append(key, value)
            } else if (typeof value === 'boolean') {
              formData.append(key, value ? '1' : '0')
            } else {
              formData.append(key, String(value))
            }
          }
        })

        await axios.post(`/courses/${id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        // Update in courses list
        const index = this.courses.findIndex(c => c.id === id)
        if (index !== -1) {
          await this.fetchCourse(id)
          if (this.currentCourse) {
            this.courses[index] = this.currentCourse
          }
        }
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to update course'
        console.error('Error updating course:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    // Delete course
    async deleteCourse(id: number) {
      this.loading = true
      this.error = null

      try {
        await axios.delete(`/courses/${id}`)
        
        // Remove from courses list
        this.courses = this.courses.filter(c => c.id !== id)
        
        if (this.currentCourse?.id === id) {
          this.currentCourse = null
        }
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to delete course'
        console.error('Error deleting course:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    // Publish/Unpublish course
    async togglePublishCourse(id: number, publish: boolean) {
      this.loading = true
      this.error = null

      try {
        const endpoint = publish ? `/courses/${id}/publish` : `/courses/${id}/unpublish`
        await axios.post(endpoint)

        // Update in courses list
        const index = this.courses.findIndex(c => c.id === id)
        if (index !== -1) {
          this.courses[index].is_published = publish
        }

        if (this.currentCourse?.id === id) {
          this.currentCourse.is_published = publish
        }
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || `Failed to ${publish ? 'publish' : 'unpublish'} course`
        console.error(`Error ${publish ? 'publishing' : 'unpublishing'} course:`, err)
        throw err
      } finally {
        this.loading = false
      }
    },

    // Fetch lessons by course
    async fetchLessonsByCourse(courseId: number) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.get<LessonsResponse>(`/lessons/course/${courseId}`)
        this.lessons = data.data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to fetch lessons'
        console.error('Error fetching lessons:', err)
      } finally {
        this.loading = false
      }
    },

    // Fetch single lesson
    async fetchLesson(identifier: string | number) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.get<LessonResponse>(`/lessons/${identifier}`)
        this.currentLesson = data.data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to fetch lesson'
        console.error('Error fetching lesson:', err)
      } finally {
        this.loading = false
      }
    },

    // Fetch lesson by ID and add to lessons array
    async fetchLessonById(id: number) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.get<LessonResponse>(`/lessons/${id}`)
        this.currentLesson = data.data
        
        // Update or add to lessons array
        const index = this.lessons.findIndex(l => l.id === id)
        if (index !== -1) {
          this.lessons[index] = data.data
        } else {
          this.lessons.push(data.data)
        }
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to fetch lesson'
        console.error('Error fetching lesson:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    // Create lesson
    async createLesson(lessonData: CreateLessonRequest) {
      this.loading = true
      this.error = null

      try {
        const formData = new FormData()
        
        Object.entries(lessonData).forEach(([key, value]) => {
          if (value !== undefined && value !== null) {
            if (key === 'document' && value instanceof File) {
              formData.append(key, value)
            } else if (typeof value === 'boolean') {
              formData.append(key, value ? '1' : '0')
            } else {
              formData.append(key, String(value))
            }
          }
        })

        const { data } = await axios.post<LessonResponse>('/lessons', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        this.currentLesson = data.data
        
        // Add to lessons list
        if (this.lessons) {
          this.lessons.push(data.data)
          this.lessons.sort((a, b) => a.position - b.position)
        }

        return data.data
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to create lesson'
        console.error('Error creating lesson:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    // Update lesson
    async updateLesson(id: number, lessonData: UpdateLessonRequest) {
      this.loading = true
      this.error = null

      try {
        const formData = new FormData()
        formData.append('_method', 'PUT')
        
        Object.entries(lessonData).forEach(([key, value]) => {
          if (value !== undefined && value !== null) {
            if (key === 'document' && value instanceof File) {
              formData.append(key, value)
            } else if (typeof value === 'boolean') {
              formData.append(key, value ? '1' : '0')
            } else {
              formData.append(key, String(value))
            }
          }
        })

        await axios.post(`/lessons/${id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        // Update in lessons list
        const index = this.lessons.findIndex(l => l.id === id)
        if (index !== -1) {
          await this.fetchLesson(id)
          if (this.currentLesson) {
            this.lessons[index] = this.currentLesson
          }
        }
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to update lesson'
        console.error('Error updating lesson:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    // Delete lesson
    async deleteLesson(id: number) {
      this.loading = true
      this.error = null

      try {
        await axios.delete(`/lessons/${id}`)
        
        // Remove from lessons list
        this.lessons = this.lessons.filter(l => l.id !== id)
        
        if (this.currentLesson?.id === id) {
          this.currentLesson = null
        }
      } catch (err) {
        const axiosError = err as AxiosError
        this.error = axiosError.response?.data?.message || 'Failed to delete lesson'
        console.error('Error deleting lesson:', err)
        throw err
      } finally {
        this.loading = false
      }
    },

    // Clear error
    clearError() {
      this.error = null
    },

    // Reset store
    resetStore() {
      this.courses = []
      this.currentCourse = null
      this.lessons = []
      this.currentLesson = null
      this.pagination = null
      this.loading = false
      this.error = null
    }
  }
})
