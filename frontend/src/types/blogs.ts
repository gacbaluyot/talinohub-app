export interface Blog {
  id: number
  uuid: string
  title: string
  content: string
  user_id: number
  user?: {
    id: number
    name: string
    email: string
  }
  created_at: string
  updated_at: string
}

export interface BlogRequest {
  title: string
  content: string
}

export interface BlogUpdateRequest {
  title?: string
  content?: string
}

export interface BlogsResponse {
  data: Blog[]
}

export interface PaginatedBlogsResponse {
  data: Blog[]
  current_page: number
  per_page: number
  total: number
  last_page: number
  from: number
  to: number
}

export interface BlogsState {
  blogs: Blog[]
  blog: Blog | null
  pagination: {
    currentPage: number
    perPage: number
    total: number
    lastPage: number
  } | null
  loading: boolean
  error: string | null
}

export interface BlogResponse {
  data: Blog
}

export interface ErrorResponse {
  message: string
  errors?: Record<string, string[]>
}

export type { Blog as default }
