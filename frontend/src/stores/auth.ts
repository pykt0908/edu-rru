import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { api } from '@/services/api'

export interface User {
  id: number
  name: string
  email: string
}

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('auth_token'))
  const user = ref<User | null>(
    localStorage.getItem('auth_user') ? JSON.parse(localStorage.getItem('auth_user')!) : null
  )
  const loading = ref(false)

  const isAuthenticated = computed(() => !!token.value)

  async function login(credentials: { email: string; password: string }) {
    loading.value = true
    try {
      const data = await api.login(credentials)
      token.value = data.token
      user.value = data.user
      localStorage.setItem('auth_token', data.token)
      localStorage.setItem('auth_user', JSON.stringify(data.user))
      return { success: true, message: data.message }
    } catch (err: any) {
      const msg = err.response?.data?.message || 'การเข้าสู่ระบบล้มเหลว กรุณาตรวจสอบอีเมลและรหัสผ่าน'
      const status = err.response?.status
      const retryAfter = err.response?.data?.retry_after || (status === 429 ? 60 : 0)
      return { success: false, message: msg, status, retryAfter }
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      if (token.value) {
        await api.logout()
      }
    } catch {
      // ignore network errors on logout
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
    }
  }

  async function checkAuth() {
    if (!token.value) return false
    try {
      const res = await api.getMe()
      user.value = res.user
      localStorage.setItem('auth_user', JSON.stringify(res.user))
      return true
    } catch {
      await logout()
      return false
    }
  }

  return {
    token,
    user,
    loading,
    isAuthenticated,
    login,
    logout,
    checkAuth,
  }
})
