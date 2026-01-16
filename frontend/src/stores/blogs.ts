import { defineStore } from 'pinia'
import axios, { AxiosError } from 'axios'
import type { Blog, BlogRequest, BlogUpdateRequest, PaginatedBlogsResponse, BlogResponse, BlogsState, ErrorResponse } from '~/types/blogs'

export const useBlogsStore = defineStore('blogs', {
  state: (): BlogsState => ({
    blogs: [],
    blog: null,
    pagination: null,
    loading: false,
    error: null
  }),

  actions: {
    async fetchBlogs(page: number = 1, perPage: number = 15) {
      this.loading = true
      this.error = null
      
      try {
        const { data } = await axios.get<{ success: boolean; data: PaginatedBlogsResponse }>('/blogs', {
          params: { page, per_page: perPage }
        })
        
        const paginatedData = data.data
        this.blogs = paginatedData.data
        this.pagination = {
          currentPage: paginatedData.current_page,
          perPage: paginatedData.per_page,
          total: paginatedData.total,
          lastPage: paginatedData.last_page
        }
      } catch (err) {
        const axiosError = err as AxiosError<ErrorResponse>
        this.error = axiosError.response?.data?.message || 'Failed to fetch blogs'
        throw err
      } finally {
        this.loading = false
      }
    },

    async fetchBlog(uuid: string) {
      this.loading = true
      this.error = null
      
      try {
        const { data } = await axios.get<{ success: boolean; data: Blog }>(`/blogs/${uuid}`)
        this.blog = data.data
      } catch (err) {
        const axiosError = err as AxiosError<ErrorResponse>
        this.error = axiosError.response?.data?.message || 'Failed to fetch blog'
        throw err
      } finally {
        this.loading = false
      }
    },

    async createBlog(blogData: BlogRequest) {
      this.loading = true
      this.error = null
      
      try {
        const { data } = await axios.post<{ success: boolean; message: string; data: Blog }>('/blogs', blogData)
        
        // Add the newly created blog to the beginning of the list
        this.blogs.unshift(data.data)
      } catch (err) {
        const axiosError = err as AxiosError<ErrorResponse>
        this.error = axiosError.response?.data?.message || 'Failed to create blog'
        throw err
      } finally {
        this.loading = false
      }
    },

    async updateBlog(uuid: string, blogData: BlogUpdateRequest) {
      this.loading = true
      this.error = null
      
      try {
        const { data } = await axios.put<{ success: boolean; data: Blog }>(`/blogs/${uuid}`, blogData)
        
        const updatedBlog = data.data
        const index = this.blogs.findIndex(b => b.uuid === uuid)
        if (index !== -1) {
          this.blogs[index] = updatedBlog
        }
        
        if (this.blog?.uuid === uuid) {
          this.blog = updatedBlog
        }
      } catch (err) {
        const axiosError = err as AxiosError<ErrorResponse>
        this.error = axiosError.response?.data?.message || 'Failed to update blog'
        throw err
      } finally {
        this.loading = false
      }
    },

    async deleteBlog(uuid: string) {
      this.loading = true
      this.error = null
      
      try {
        await axios.delete(`/blogs/${uuid}`)
        
        this.blogs = this.blogs.filter(b => b.uuid !== uuid)
        
        if (this.blog?.uuid === uuid) {
          this.blog = null
        }
      } catch (err) {
        const axiosError = err as AxiosError<ErrorResponse>
        this.error = axiosError.response?.data?.message || 'Failed to delete blog'
        throw err
      } finally {
        this.loading = false
      }
    },

    clearError() {
      this.error = null
    },

    clearBlog() {
      this.blog = null
    }
  }
})
