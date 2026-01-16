// Login Request
export interface LoginRequest {
  email: string
  password: string
  remember?: boolean
}

// Login Response
export interface LoginResponse {
  access_token: string
  user: User
  roles: string[]
}

// User interface
export interface User {
  id: number
  email: string
  name: string
  created_at?: string
  updated_at?: string
}

// Register Request
export interface RegisterRequest {
  name: string
  email: string
  address: string
  password: string
  password_confirmation: string
}

// Register Response
export interface RegisterResponse {
  access_token: string
  user: User
  roles: string[]
}

// Error Response
export interface ErrorResponse {
  message: string
  errors?: Record<string, string[]>
}

// Auth State (for Pinia store)
export interface AuthState {
  user: User | null
  token: string | null
  roles: string[]
  isAuthenticated: boolean
  loading: boolean
  error: string | null
}