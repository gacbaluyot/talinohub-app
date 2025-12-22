// Course interface
export interface Course {
  id: number
  user_id: number
  title: string
  slug: string
  short_description?: string
  description?: string
  is_free: boolean
  price?: number
  thumbnail_path?: string
  category?: string
  level?: 'beginner' | 'intermediate' | 'advanced'
  language?: string
  average_rating?: number
  ratings_count?: number
  is_published: boolean
  published_at?: string
  created_at: string
  updated_at: string
  deleted_at?: string
  educator?: {
    id: number
    name: string
    email: string
  }
  lessons?: Lesson[]
  lessons_count?: number
  pivot?: {
    enrolled_at: string
    completed_at?: string
    progress: number
  }
}

// Lesson interface
export interface Lesson {
  id: number
  course_id: number
  title: string
  slug: string
  position: number
  type: 'video' | 'document' | 'text'
  video_url?: string
  document_path?: string
  duration_seconds?: number
  is_preview: boolean
  created_at: string
  updated_at: string
  deleted_at?: string
  course?: Course
}

// Course Create Request
export interface CreateCourseRequest {
  user_id: number
  title: string
  slug?: string
  short_description?: string
  description?: string
  is_free: boolean
  price?: number
  thumbnail?: File
  category?: string
  level?: 'beginner' | 'intermediate' | 'advanced'
  language?: string
  is_published: boolean
}

// Course Update Request
export interface UpdateCourseRequest {
  title?: string
  slug?: string
  short_description?: string
  description?: string
  is_free?: boolean
  price?: number
  thumbnail?: File
  category?: string
  level?: 'beginner' | 'intermediate' | 'advanced'
  language?: string
  is_published?: boolean
}

// Lesson Create Request
export interface CreateLessonRequest {
  course_id: number
  title: string
  slug?: string
  position?: number
  type: 'video' | 'document' | 'text'
  video_url?: string
  document?: File
  duration_seconds?: number
  is_preview: boolean
}

// Lesson Update Request
export interface UpdateLessonRequest {
  title?: string
  slug?: string
  position?: number
  type?: 'video' | 'document' | 'text'
  video_url?: string
  document?: File
  duration_seconds?: number
  is_preview?: boolean
}

// API Response types
export interface CourseResponse {
  success: boolean
  data: Course
  message?: string
}

export interface CoursesResponse {
  success: boolean
  data: {
    data: Course[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
  }
}

export interface LessonResponse {
  success: boolean
  data: Lesson
  message?: string
}

export interface LessonsResponse {
  success: boolean
  data: Lesson[]
}

// Pagination interface
export interface Pagination {
  currentPage: number
  lastPage: number
  perPage: number
  total: number
  from: number
  to: number
}

// Store state
export interface CoursesState {
  courses: Course[]
  currentCourse: Course | null
  lessons: Lesson[]
  currentLesson: Lesson | null
  pagination: Pagination | null
  loading: boolean
  error: string | null
}
