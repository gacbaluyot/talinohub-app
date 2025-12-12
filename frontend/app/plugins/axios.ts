import axios from 'axios'

export default defineNuxtPlugin(() => {
  // Set default headers
  axios.defaults.headers.common['Content-Type'] = 'application/json'
  axios.defaults.headers.common['Accept'] = 'application/json'

  // Get API URL from environment
  const config = useRuntimeConfig()
  const apiUrl = config.public.apiUrl as string || 'http://localhost:8000/api'

  // Request interceptor
  axios.interceptors.request.use(
    (config) => {
      // Prepend base URL to all requests
      config.url = `${apiUrl}${config.url}`
      
      // Add auth token if it exists
      if (import.meta.client) {
        const token = localStorage.getItem('token')
        if (token) {
          config.headers.Authorization = `Bearer ${token}`
        }
      }
      
      return config
    },
    (error) => {
      return Promise.reject(error)
    }
  )

  // Response interceptor
  axios.interceptors.response.use(
    (response) => {
      return response
    },
    (error) => {
      // Handle 401 Unauthorized
      if (error.response?.status === 401) {
        // Clear auth and redirect to login
        if (import.meta.client) {
          localStorage.removeItem('token')
          localStorage.removeItem('user')
          window.location.href = '/auth/login'
        }
      }
      
      // Handle 403 Forbidden
      if (error.response?.status === 403) {
        console.error('Forbidden: You do not have permission to access this resource')
      }
      
      // Handle 500 Server Error
      if (error.response?.status === 500) {
        console.error('Server Error: Something went wrong on the server')
      }
      
      return Promise.reject(error)
    }
  )

  return {
    provide: {
      axios
    }
  }
})
