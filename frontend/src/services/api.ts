import axios from 'axios'
import { POSTS, type Post } from '@/data/postsData'
export type { Post }
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

// Map snake_case API response → camelCase Post interface
function mapPost(raw: any): Post {
  return {
    id: raw.id,
    title: raw.title ?? '',
    desc: raw.desc ?? '',
    content: raw.content ?? [],
    keyHighlights: raw.key_highlights ?? raw.keyHighlights ?? [],
    quote: raw.quote ?? undefined,
    thumbnail: raw.thumbnail ?? '',
    gallery: raw.gallery ?? [],
    date: raw.date ?? '',
    category: raw.category ?? '',
    categoryBadgeClass: raw.category_badge_class ?? raw.categoryBadgeClass ?? '',
    views: raw.views ?? '0',
    readTime: raw.read_time ?? raw.readTime ?? '',
    author: raw.author ?? { name: '', role: '', avatar: '' },
    attachments: raw.attachments ?? [],
    tags: raw.tags ?? [],
    sdgs: Array.isArray(raw.sdgs) ? raw.sdgs : [],
    featured: !!raw.featured,
    gridClass: raw.grid_class ?? raw.gridClass ?? undefined,
  }
}

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
        total_curricula: 11,
        total_carousels: 3,
        total_regulations: 12,
        total_ita: 24,
        recent_posts: POSTS.slice(0, 5),
        recent_personnel: fallbackPersonnel.slice(0, 5),
        top_viewed_posts: POSTS.slice(0, 5).map((p) => ({
          id: p.id,
          title: p.title,
          category: p.category,
          views: typeof p.views === 'string' ? parseInt(p.views) || 0 : p.views,
          views_formatted: String(p.views),
          date: p.date,
          thumbnail: p.thumbnail,
        })),
        categories_stats: [
          { category: 'ข่าวประชาสัมพันธ์', count: 2 },
          { category: 'วิชาการ', count: 2 },
          { category: 'กิจกรรมนักศึกษา', count: 2 },
        ],
        sdgs_stats: [
          { sdg_id: 1, count: 1 },
          { sdg_id: 4, count: 1 },
        ],
        department_personnel_stats: departments.map((d) => ({
          id: d.id,
          slug: d.id,
          name: d.name,
          count: d.members.length,
        })),
        academic_titles_stats: [
          { academic_title: 'อาจารย์', count: 21 },
          { academic_title: 'ผู้ช่วยศาสตราจารย์', count: 11 },
          { academic_title: 'รองศาสตราจารย์', count: 3 },
        ],
        curricula_stats: [
          { degree_level: 'bachelor', count: 8 },
          { degree_level: 'master', count: 2 },
          { degree_level: 'grad-diploma', count: 1 },
        ],
      }
    }
  },

  // Posts
  async getPosts(params?: { category?: string; search?: string; page?: number; per_page?: number; sdg?: number | string }) {
    try {
      const response = await apiClient.get('/posts', { params })
      const raw = response.data
      const mapped = (raw.data ?? raw).map(mapPost)
      return { data: mapped, total: raw.total ?? mapped.length }
    } catch (err) {
      console.warn('API getPosts failed, using fallback data:', err)
      let filtered = [...POSTS]
      if (params?.category && params.category !== 'ทั้งหมด') {
        filtered = filtered.filter((p) => p.category === params.category)
      }
      if (params?.sdg) {
        const targetSdg = Number(params.sdg)
        filtered = filtered.filter((p) => p.sdgs?.includes(targetSdg))
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
      return mapPost(response.data)
    } catch (err) {
      console.warn(`API getPost(${id}) failed, using fallback:`, err)
      return POSTS.find((p) => String(p.id) === String(id)) || null
    }
  },

  async createPost(data: any) {
    const payload: any = { ...data }
    if (payload.keyHighlights !== undefined && payload.key_highlights === undefined) {
      payload.key_highlights = payload.keyHighlights
    }
    if (payload.categoryBadgeClass !== undefined && payload.category_badge_class === undefined) {
      payload.category_badge_class = payload.categoryBadgeClass
    }
    if (payload.readTime !== undefined && payload.read_time === undefined) {
      payload.read_time = payload.readTime
    }
    const response = await apiClient.post('/posts', payload)
    return response.data
  },

  async updatePost(id: string | number, data: any) {
    const payload: any = { ...data }
    if (payload.keyHighlights !== undefined && payload.key_highlights === undefined) {
      payload.key_highlights = payload.keyHighlights
    }
    if (payload.categoryBadgeClass !== undefined && payload.category_badge_class === undefined) {
      payload.category_badge_class = payload.categoryBadgeClass
    }
    if (payload.readTime !== undefined && payload.read_time === undefined) {
      payload.read_time = payload.readTime
    }
    const response = await apiClient.put(`/posts/${id}`, payload)
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

  async reorderPersonnel(orders: { id: number; sort_order: number }[]) {
    const response = await apiClient.post('/personnel/reorder', { orders })
    return response.data
  },

  // Departments
  async getDepartments(params?: { active_only?: boolean; with_members?: boolean }) {
    const response = await apiClient.get('/departments', { params })
    return response.data as DepartmentRecord[]
  },

  async createDepartment(data: Partial<DepartmentRecord>) {
    const response = await apiClient.post('/departments', data)
    return response.data as DepartmentRecord
  },

  async updateDepartment(id: number, data: Partial<DepartmentRecord>) {
    const response = await apiClient.put(`/departments/${id}`, data)
    return response.data as DepartmentRecord
  },

  async deleteDepartment(id: number) {
    const response = await apiClient.delete(`/departments/${id}`)
    return response.data
  },

  async reorderDepartments(orders: { id: number; sort_order: number }[]) {
    const response = await apiClient.post('/departments/reorder', { orders })
    return response.data
  },

  async setDepartmentHead(id: number, personnelId: number | null) {
    const response = await apiClient.post(`/departments/${id}/set-head`, { personnel_id: personnelId })
    return response.data
  },

  // File Upload
  async uploadFile(file: File) {
    const formData = new FormData()
    formData.append('file', file)
    const response = await apiClient.post('/upload', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data as {
      message: string
      url: string
      path: string
      name?: string
      original_name?: string
      file_size?: string
      size?: string
      size_bytes?: number
      extension?: string
      type?: string
    }
  },

  async uploadImage(file: File) {
    return this.uploadFile(file)
  },

  // Categories
  async getCategories() {
    try {
      const response = await apiClient.get('/categories')
      return (response.data || []) as Category[]
    } catch (err) {
      console.warn('Failed to fetch categories:', err)
      return []
    }
  },

  async createCategory(data: Partial<Category>) {
    const response = await apiClient.post('/categories', data)
    return response.data
  },

  async updateCategory(id: number, data: Partial<Category>) {
    const response = await apiClient.put(`/categories/${id}`, data)
    return response.data
  },

  async deleteCategory(id: number) {
    const response = await apiClient.delete(`/categories/${id}`)
    return response.data
  },

  // Faculty History
  async getHistory() {
    const response = await apiClient.get('/history')
    return response.data as HistoryItem[]
  },

  async createHistory(data: Partial<HistoryItem>) {
    const response = await apiClient.post('/history', data)
    return response.data
  },

  async updateHistory(id: number, data: Partial<HistoryItem>) {
    const response = await apiClient.put(`/history/${id}`, data)
    return response.data
  },

  async deleteHistory(id: number) {
    const response = await apiClient.delete(`/history/${id}`)
    return response.data
  },

  // Philosophy & Vision
  async getPhilosophy() {
    const response = await apiClient.get('/philosophy')
    return response.data as PhilosophyData
  },

  async updatePhilosophy(data: Partial<PhilosophyData>) {
    const response = await apiClient.post('/philosophy', data)
    return response.data as PhilosophyData
  },

  async createMission(data: { text: string; sort_order?: number }) {
    const response = await apiClient.post('/philosophy/missions', data)
    return response.data
  },

  async updateMission(id: number, data: { text: string; sort_order?: number }) {
    const response = await apiClient.put(`/philosophy/missions/${id}`, data)
    return response.data
  },

  async deleteMission(id: number) {
    const response = await apiClient.delete(`/philosophy/missions/${id}`)
    return response.data
  },

  // ITA Years
  async getItaYears(params?: { active_only?: boolean }) {
    const response = await apiClient.get('/ita-years', { params })
    return response.data as ItaYear[]
  },

  async createItaYear(data: Partial<ItaYear>) {
    const response = await apiClient.post('/ita-years', data)
    return response.data
  },

  async updateItaYear(id: number, data: Partial<ItaYear>) {
    const response = await apiClient.put(`/ita-years/${id}`, data)
    return response.data
  },

  async deleteItaYear(id: number, cascade = false) {
    const response = await apiClient.delete(`/ita-years/${id}`, { params: { cascade } })
    return response.data
  },

  // ITA
  async getIta(params?: { year?: string }) {
    const response = await apiClient.get('/ita', { params })
    return response.data as ItaItem[]
  },

  async createIta(data: Partial<ItaItem>) {
    const response = await apiClient.post('/ita', data)
    return response.data
  },

  async updateIta(id: number, data: Partial<ItaItem>) {
    const response = await apiClient.put(`/ita/${id}`, data)
    return response.data
  },

  async deleteIta(id: number) {
    const response = await apiClient.delete(`/ita/${id}`)
    return response.data
  },

  async reorderIta(ids: number[]) {
    const response = await apiClient.post('/ita/reorder', { ids })
    return response.data
  },

  // Regulations
  async getRegulations(params?: { category?: string; year?: string; search?: string }) {
    const response = await apiClient.get('/regulations', { params })
    return response.data as RegulationItem[]
  },

  async createRegulation(data: Partial<RegulationItem>) {
    const response = await apiClient.post('/regulations', data)
    return response.data
  },

  async updateRegulation(id: number, data: Partial<RegulationItem>) {
    const response = await apiClient.put(`/regulations/${id}`, data)
    return response.data
  },

  async deleteRegulation(id: number) {
    const response = await apiClient.delete(`/regulations/${id}`)
    return response.data
  },

  // Regulation Categories
  async getRegulationCategories(params?: { active_only?: boolean }) {
    const response = await apiClient.get('/regulation-categories', { params })
    return response.data as RegulationCategory[]
  },

  async createRegulationCategory(data: Partial<RegulationCategory>) {
    const response = await apiClient.post('/regulation-categories', data)
    return response.data
  },

  async updateRegulationCategory(id: number, data: Partial<RegulationCategory>) {
    const response = await apiClient.put(`/regulation-categories/${id}`, data)
    return response.data
  },

  async deleteRegulationCategory(id: number, cascade = false) {
    const response = await apiClient.delete(`/regulation-categories/${id}`, {
      params: { cascade },
    })
    return response.data
  },

  // Carousel Slides
  async getCarouselSlides(params?: { active_only?: boolean }) {
    const response = await apiClient.get('/carousel-slides', { params })
    return response.data as CarouselSlide[]
  },

  async createCarouselSlide(data: Partial<CarouselSlide>) {
    const response = await apiClient.post('/carousel-slides', data)
    return response.data
  },

  async updateCarouselSlide(id: number, data: Partial<CarouselSlide>) {
    const response = await apiClient.put(`/carousel-slides/${id}`, data)
    return response.data
  },

  async deleteCarouselSlide(id: number) {
    const response = await apiClient.delete(`/carousel-slides/${id}`)
    return response.data
  },

  async reorderCarouselSlides(orders: { id: number; sort_order: number }[]) {
    const response = await apiClient.post('/carousel-slides/reorder', { orders })
    return response.data
  },

  // Committee Members
  async getCommitteeMembers(params?: { active_only?: boolean; search?: string }) {
    const response = await apiClient.get('/committee-members', { params })
    return response.data as CommitteeMember[]
  },

  async createCommitteeMember(data: Partial<CommitteeMember>) {
    const response = await apiClient.post('/committee-members', data)
    return response.data as CommitteeMember
  },

  async updateCommitteeMember(id: number, data: Partial<CommitteeMember>) {
    const response = await apiClient.put(`/committee-members/${id}`, data)
    return response.data as CommitteeMember
  },

  async deleteCommitteeMember(id: number) {
    const response = await apiClient.delete(`/committee-members/${id}`)
    return response.data
  },

  async reorderCommitteeMembers(orders: { id: number; sort_order: number }[]) {
    const response = await apiClient.post('/committee-members/reorder', { orders })
    return response.data
  },

  // Executives (Categories & Members)
  async getExecutives(params?: { active_only?: boolean }) {
    try {
      const response = await apiClient.get('/executives', { params })
      return response.data as ExecutiveCategory[]
    } catch (err) {
      console.warn('API getExecutives failed, fallback to empty:', err)
      return [] as ExecutiveCategory[]
    }
  },

  async createExecutiveCategory(data: Partial<ExecutiveCategory>) {
    const response = await apiClient.post('/executive-categories', data)
    return response.data as ExecutiveCategory
  },

  async updateExecutiveCategory(id: number, data: Partial<ExecutiveCategory>) {
    const response = await apiClient.put(`/executive-categories/${id}`, data)
    return response.data as ExecutiveCategory
  },

  async deleteExecutiveCategory(id: number) {
    const response = await apiClient.delete(`/executive-categories/${id}`)
    return response.data
  },

  async reorderExecutiveCategories(orders: { id: number; sort_order: number }[]) {
    const response = await apiClient.post('/executive-categories/reorder', { orders })
    return response.data
  },

  async createExecutiveMember(data: Partial<ExecutiveMember>) {
    const response = await apiClient.post('/executive-members', data)
    return response.data as ExecutiveMember
  },

  async updateExecutiveMember(id: number, data: Partial<ExecutiveMember>) {
    const response = await apiClient.put(`/executive-members/${id}`, data)
    return response.data as ExecutiveMember
  },

  async deleteExecutiveMember(id: number) {
    const response = await apiClient.delete(`/executive-members/${id}`)
    return response.data
  },

  async reorderExecutiveMembers(orders: { id: number; sort_order: number; category_id?: number }[]) {
    const response = await apiClient.post('/executive-members/reorder', { orders })
    return response.data
  },

  // Curricula (หลักสูตร)
  async getCurricula(params?: { degree_level?: string; active_only?: boolean; search?: string }) {
    try {
      const response = await apiClient.get('/curricula', { params })
      return response.data as CurriculumRecord[]
    } catch (err) {
      console.warn('API getCurricula failed, returning empty:', err)
      return [] as CurriculumRecord[]
    }
  },

  async getCurriculum(idOrSlug: string | number) {
    const response = await apiClient.get(`/curricula/${idOrSlug}`)
    return response.data as CurriculumRecord
  },

  async createCurriculum(data: Partial<CurriculumRecord>) {
    const response = await apiClient.post('/curricula', data)
    return response.data as CurriculumRecord
  },

  async updateCurriculum(id: number, data: Partial<CurriculumRecord>) {
    const response = await apiClient.put(`/curricula/${id}`, data)
    return response.data as CurriculumRecord
  },

  async deleteCurriculum(id: number) {
    const response = await apiClient.delete(`/curricula/${id}`)
    return response.data
  },

  async reorderCurricula(orders: { id: number; sort_order: number; degree_level?: string }[]) {
    const response = await apiClient.post('/curricula/reorder', { orders })
    return response.data
  },
}

export interface Category {
  id: number
  name: string
  slug: string
  badge_class: string
  color_hex?: string
  sort_order: number
}

export interface HistoryItem {
  id: number
  year: string
  title: string
  detail: string
  sort_order?: number
}

export interface Mission {
  id?: number
  text: string
  sort_order?: number
}

export interface PhilosophyData {
  philosophy: string
  philosophy_detail: string
  vision: string
  identity: string
  missions: Mission[]
}

export interface ItaLink {
  id?: number
  title: string
  url: string
  type?: 'internal' | 'external' | 'pdf'
}

export interface ItaComponent {
  text: string
  subnotes?: string[]
}

export interface ItaYear {
  id: number
  year: string
  title?: string
  is_active: boolean
  sort_order: number
  items_count?: number
  created_at?: string
  updated_at?: string
}

export interface ItaItem {
  id: number
  year: string
  code: string
  indicator: string
  components?: string | ItaComponent[]
  links: ItaLink[]
  sort_order?: number
}

export interface RegulationCategory {
  id: number
  key: string
  name: string
  short_name?: string
  description?: string
  icon?: string
  color?: string
  badge_class?: string
  sort_order?: number
  is_active?: boolean
  items_count?: number
  created_at?: string
  updated_at?: string
}

export interface RegulationItem {
  id: number
  title: string
  category: string
  year?: string
  effective_date?: string
  file_size?: string
  file_url?: string
  description?: string
  sort_order?: number
}

export interface CarouselSlide {
  id: number
  title?: string
  image_url: string
  alt_text?: string
  link_url?: string
  target?: '_self' | '_blank'
  sort_order: number
  is_active: boolean
  created_at?: string
  updated_at?: string
}

export interface CommitteeMember {
  id: number
  name: string
  position: string
  sort_order: number
  is_active: boolean
  created_at?: string
  updated_at?: string
}

export interface DepartmentRecord {
  id: number
  slug: string
  name: string
  degree_title?: string
  head_personnel_id?: number | null
  head?: any
  sort_order: number
  is_active: boolean
  personnels_count?: number
  personnels?: any[]
  members?: any[]
  created_at?: string
  updated_at?: string
}

export interface ExecutiveMember {
  id: number
  category_id: number
  personnel_id?: number | null
  position: string
  position_suffix?: string
  custom_name?: string
  custom_avatar?: string
  custom_email?: string
  custom_phone?: string
  sort_order: number
  is_active: boolean
  name?: string
  avatar?: string
  email?: string
  phone?: string
  degrees?: string
  personnel?: any
  category?: any
  created_at?: string
  updated_at?: string
}

export interface ExecutiveCategory {
  id: number
  title: string
  description?: string
  sort_order: number
  is_active: boolean
  members?: ExecutiveMember[]
  members_count?: number
  created_at?: string
  updated_at?: string
}

export interface CurriculumRecord {
  id: number
  slug: string
  degree_level: 'bachelor' | 'grad-diploma' | 'master' | 'doctoral'
  title: string
  title_en?: string
  degree_title: string
  degree_title_en?: string
  duration: string
  credits: string
  desc?: string
  image?: string
  tags?: string[]
  highlight?: boolean
  department_id?: string
  document_url?: string
  detail_content?: any
  sort_order: number
  is_active: boolean
  created_at?: string
  updated_at?: string
}





