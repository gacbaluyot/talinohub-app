import { defineStore } from 'pinia'
import axios, { AxiosError } from 'axios'
import type { LoginRequest, LoginResponse, RegisterRequest, RegisterResponse, User, ErrorResponse, AuthState } from '~/types/auth'
import Cookies from 'js-cookie'

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: null,
    token: null,
    roles: [],
    isAuthenticated: false,
    loading: false,
    error: null
  }),

  getters: {
    isAdmin: (state) => state.roles.includes('admin'),
    isEducator: (state) => state.roles.includes('educator'),
    isStudent: (state) => state.roles.includes('student'),
    primaryRole: (state) => {
      // Priority: admin > educator > student
      if (state.roles.includes('admin')) return 'admin'
      if (state.roles.includes('educator')) return 'educator'
      if (state.roles.includes('student')) return 'student'
      return null
    }
  },

  actions: {
    // Initialize auth from cookies
    initAuth() {
      const access_token = Cookies.get('access_token')
      const userStr = Cookies.get('user')
      const rolesStr = Cookies.get('roles')
      
      if (access_token && userStr) {
        this.token = access_token
        this.user = JSON.parse(userStr)
        this.roles = rolesStr ? JSON.parse(rolesStr) : []
        this.isAuthenticated = true
        
        // Set axios default header
        axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`
      }
    },

    // Login action
    async login(credentials: LoginRequest) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.post<LoginResponse>('/auth/login', credentials)
        
        this.user = data.user
        this.token = data.access_token
        this.roles = data.roles || []
        this.isAuthenticated = true

        Cookies.set('user', JSON.stringify(data.user))
        Cookies.set('access_token', data.access_token)
        Cookies.set('roles', JSON.stringify(data.roles))

        // Set axios default header
        axios.defaults.headers.common['Authorization'] = `Bearer ${data.access_token}`
      } catch (err) {
        const axiosError = err as AxiosError<ErrorResponse>
        this.error = axiosError.response?.data?.message || 'Login failed. Please try again.'
        throw err
      } finally {
        this.loading = false
      }
    },

    // Register action
    async register(credentials: RegisterRequest) {
      this.loading = true
      this.error = null

      try {
        const { data } = await axios.post<RegisterResponse>('/auth/register', credentials)
        
        // Store token and user
        this.token = data.access_token
        this.user = data.user
        this.roles = data.roles || []
        this.isAuthenticated = true

        // Save to cookies
        Cookies.set('access_token', data.access_token)
        Cookies.set('user', JSON.stringify(data.user))
        Cookies.set('roles', JSON.stringify(data.roles))

        // Set axios default header
        axios.defaults.headers.common['Authorization'] = `Bearer ${data.access_token}`
      } catch (err) {
        const axiosError = err as AxiosError<ErrorResponse>
        this.error = axiosError.response?.data?.message || 'Registration failed. Please try again.'
        throw err
      } finally {
        this.loading = false
      }
    },

    // Logout action
    async logout() {
      try {
        // Optional: call logout endpoint
        await axios.post('/auth/logout')
      } catch (err) {
        console.error('Logout error:', err)
      } finally {
        // Clear state
        this.token = null
        this.user = null
        this.roles = []
        this.isAuthenticated = false
        this.error = null

        // Clear cookies
        Cookies.remove('access_token')
        Cookies.remove('user')
        Cookies.remove('roles')

        // Clear axios header
        delete axios.defaults.headers.common['Authorization']
      }
    },

    // Clear error
    clearError() {
      this.error = null
    }
  }
})
