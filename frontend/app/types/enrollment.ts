import type { Course } from './courses'

// Enrollment interface
export interface Enrollment {
  id: number
  user_id: number
  course_id: number
  enrolled_at: string
  completed_at?: string
  progress: number
  course?: Course
}

// Enroll Request
export interface EnrollCourseRequest {
  course_id: number
}

// Update Progress Request
export interface UpdateProgressRequest {
  progress: number
}

// API Response types
export interface EnrollmentResponse {
  success: boolean
  message?: string
  data?: Enrollment
}

export interface EnrollmentsResponse {
  success: boolean
  data: Course[]
}

export interface FavoritesResponse {
  success: boolean
  data: Course[]
}

// Store state
export interface EnrollmentState {
  enrolledCourses: Course[]
  favoriteCourses: Course[]
  loading: boolean
  error: string | null
}
