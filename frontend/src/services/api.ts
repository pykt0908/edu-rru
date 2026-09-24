import axios from 'axios'
import { POSTS, type Post } from '@/data/postsData'
import { departments, type Person } from '@/data/personnelData'

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

export const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  timeout: 10000,
})

// Flatten fallback personnel
const fallbackPersonnel: (Person & { departmentId: string; departmentName: string })[] = []
departments.forEach((dept) => {
  dept.members.forEach((m) => {
    fallbackPersonnel.push({
      ...m,
      departmentId: dept.id,
      departmentName: dept.name,
    })
  })
})

export const api = {
  // Stats
  async getStats() {
    try {
      const response = await apiClient.get('/stats')
      return response.data
    } catch (err) {
      console.warn('API getStats failed, returning fallback stats:', err)
      return {
        total_posts: POSTS.length,
        total_personnel: fallbackPersonnel.length,
        departments_count: departments.length,
        featured_posts: POSTS.filter((p) => p.featured).length,
        total_views: 18000,
        recent_posts: POSTS.slice(0, 5),
        recent_personnel: fallbackPersonnel.slice(0, 5),
        categories_stats: [
          { category: 'ข่าวประชาสัมพันธ์', count: 2 },
          { category: 'วิชาการ & วิจัย', count: 2 },
          { category: 'กิจกรรมนิสิต', count: 1 },
          { category: 'บริการวิชาการ', count: 1 },
        ],
      }
    }
  },

  // Posts
  async getPosts(params?: { category?: string; search?: string; page?: number; per_page?: number }) {
    try {
      const response = await apiClient.get('/posts', { params })
      return response.data
    } catch (err) {
      console.warn('API getPosts failed, using fallback data:', err)
      let filtered = [...POSTS]
      if (params?.category && params.category !== 'ทั้งหมด') {
        filtered = filtered.filter((p) => p.category === params.category)
      }
      if (params?.search) {
        const s = params.search.toLowerCase()
        filtered = filtered.filter((p) => p.title.toLowerCase().includes(s) || p.desc.toLowerCase().includes(s))
      }
      return { data: filtered, total: filtered.length }
    }
  },

  async getPost(id: string | number) {
    try {
      const response = await apiClient.get(`/posts/${id}`)
      return response.data
    } catch (err) {
      console.warn(`API getPost(${id}) failed, using fallback:`, err)
      return POSTS.find((p) => String(p.id) === String(id)) || null
    }
  },

  async createPost(data: Partial<Post>) {
    const response = await apiClient.post('/posts', data)
    return response.data
  },

  async updatePost(id: string | number, data: Partial<Post>) {
    const response = await apiClient.put(`/posts/${id}`, data)
    return response.data
  },

  async deletePost(id: string | number) {
    const response = await apiClient.delete(`/posts/${id}`)
    return response.data
  },

  // Personnel
  async getPersonnel(params?: { department_id?: string; search?: string; grouped?: boolean }) {
    try {
      const response = await apiClient.get('/personnel', { params })
      return response.data
    } catch (err) {
      console.warn('API getPersonnel failed, using fallback data:', err)
      if (params?.grouped) {
        return departments
      }
      let filtered = [...fallbackPersonnel]
      if (params?.department_id && params.department_id !== 'all') {
        filtered = filtered.filter((p) => p.departmentId === params.department_id)
      }
      if (params?.search) {
        const s = params.search.toLowerCase()
        filtered = filtered.filter((p) => p.name.toLowerCase().includes(s) || p.roleTitle.toLowerCase().includes(s))
      }
      return { data: filtered, total: filtered.length }
    }
  },

  async getPersonnelItem(id: string | number) {
    try {
      const response = await apiClient.get(`/personnel/${id}`)
      return response.data
    } catch (err) {
      console.warn(`API getPersonnelItem(${id}) failed:`, err)
      return fallbackPersonnel.find((p) => String(p.id) === String(id)) || null
    }
  },

  async createPersonnel(data: any) {
    const response = await apiClient.post('/personnel', data)
    return response.data
  },

  async updatePersonnel(id: string | number, data: any) {
    const response = await apiClient.put(`/personnel/${id}`, data)
    return response.data
  },

  async deletePersonnel(id: string | number) {
    const response = await apiClient.delete(`/personnel/${id}`)
    return response.data
  },

  // File Upload
  async uploadImage(file: File) {
    const formData = new FormData()
    formData.append('file', file)
    const response = await apiClient.post('/upload', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data
  },
}
