<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { api } from '@/services/api'
import eduLogo from '@/assets/logos/edu-logo-border-white.png'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

// Admin Account Settings Modal State
const settingsModalOpen = ref(false)
const savingSettings = ref(false)
const settingsSuccess = ref('')
const settingsError = ref('')
const showCurrentPassword = ref(false)
const showNewPassword = ref(false)

const settingsForm = ref({
  name: '',
  username: '',
  email: '',
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
})

const openSettingsModal = () => {
  settingsError.value = ''
  settingsSuccess.value = ''
  settingsForm.value = {
    name: authStore.user?.name || '',
    username: authStore.user?.username || '',
    email: authStore.user?.email || '',
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
  }
  settingsModalOpen.value = true
}

const handleSaveSettings = async () => {
  settingsError.value = ''
  settingsSuccess.value = ''

  if (!settingsForm.value.name.trim()) {
    settingsError.value = 'กรุณากรอกชื่อ-นามสกุล'
    return
  }
  if (!settingsForm.value.username.trim()) {
    settingsError.value = 'กรุณากรอกชื่อผู้ใช้งาน (Username)'
    return
  }
  if (!settingsForm.value.email.trim()) {
    settingsError.value = 'กรุณากรอกอีเมล'
    return
  }
  if (settingsForm.value.new_password) {
    if (!settingsForm.value.current_password) {
      settingsError.value = 'กรุณากรอกรหัสผ่านปัจจุบันเพื่อยืนยันการตั้งรหัสผ่านใหม่'
      return
    }
    if (settingsForm.value.new_password.length < 6) {
      settingsError.value = 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 6 ตัวอักษร'
      return
    }
    if (settingsForm.value.new_password !== settingsForm.value.new_password_confirmation) {
      settingsError.value = 'การยืนยันรหัสผ่านใหม่ไม่ตรงกัน'
      return
    }
  }

  savingSettings.value = true
  try {
    const payload = {
      name: settingsForm.value.name.trim(),
      username: settingsForm.value.username.trim(),
      email: settingsForm.value.email.trim(),
      current_password: settingsForm.value.current_password || undefined,
      new_password: settingsForm.value.new_password || undefined,
      new_password_confirmation: settingsForm.value.new_password_confirmation || undefined,
    }

    let res: any
    if (typeof (authStore as any).updateProfile === 'function') {
      res = await authStore.updateProfile(payload)
    } else {
      const data = await api.updateProfile(payload)
      authStore.user = data.user
      localStorage.setItem('auth_user', JSON.stringify(data.user))
      res = { success: true, message: data.message, user: data.user }
    }

    if (res.success) {
      settingsSuccess.value = res.message || 'บันทึกการตั้งค่าบัญชีเรียบร้อยแล้ว'
      // Clear password inputs
      settingsForm.value.current_password = ''
      settingsForm.value.new_password = ''
      settingsForm.value.new_password_confirmation = ''
    } else {
      settingsError.value = res.message
    }
  } catch (err: any) {
    const msg = err.response?.data?.message || 'บันทึกการตั้งค่าบัญชีไม่สำเร็จ'
    settingsError.value = msg
  } finally {
    savingSettings.value = false
  }
}

// Collapse / Rail state (default false = open)
const rail = ref(false)
const mobileMenuOpen = ref(false)

const navMenuItems = [
  {
    title: 'แดชบอร์ดภาพรวม',
    icon: 'mdi-view-dashboard-outline',
    to: '/admin',
    exact: true,
  },
  {
    title: 'จัดการข่าวสาร & ประกาศ',
    icon: 'mdi-newspaper-variant-outline',
    to: '/admin/posts',
  },
  {
    title: 'จัดการบุคลากร & อาจารย์',
    icon: 'mdi-account-group-outline',
    to: '/admin/personnel',
  },
  {
    title: 'จัดการคณะผู้บริหาร',
    icon: 'mdi-account-tie-outline',
    to: '/admin/executives',
  },
  {
    title: 'จัดการหลักสูตรการศึกษา',
    icon: 'mdi-school-outline',
    to: '/admin/curricula',
  },
  {
    title: 'จัดการข้อมูลและนโยบาย',
    icon: 'mdi-book-cog-outline',
    to: '/admin/content',
  },
]

const currentTitle = computed(() => {
  if (route.path === '/admin') return 'แดชบอร์ดภาพรวม (Dashboard Overview)'
  if (route.path === '/admin/posts/create') return 'สร้างข่าวสารใหม่'
  if (route.path.match(/\/admin\/posts\/\d+\/edit/)) return 'แก้ไขข่าวสาร'
  if (route.path.startsWith('/admin/posts')) return 'จัดการข่าวสารและกิจกรรม (News & Posts)'
  if (route.path.startsWith('/admin/personnel')) return 'จัดการบุคลากรและคณาจารย์ (Personnel)'
  if (route.path.startsWith('/admin/executives')) return 'จัดการคณะผู้บริหาร (Faculty Management)'
  if (route.path.startsWith('/admin/curricula')) return 'จัดการหลักสูตรการศึกษา (Curriculum Management)'
  if (route.path.startsWith('/admin/content')) return 'จัดการข้อมูลและนโยบาย (Content & Policy)'
  return 'ระบบจัดการหลังบ้าน'
})
</script>

<template>
  <div class="admin-wrapper min-h-screen bg-slate-50 text-slate-800">
    <!-- Mobile Backdrop Overlay -->
    <div
      v-if="mobileMenuOpen"
      class="fixed inset-0 z-40 bg-black/50 backdrop-blur-xs lg:hidden transition-opacity"
      @click="mobileMenuOpen = false"
    />

    <!-- Admin Sidebar Navigation -->
    <aside
      class="fixed top-0 bottom-0 left-0 z-50 bg-[#0f172a] border-r border-slate-800 flex flex-col transition-all duration-300 ease-in-out select-none shadow-2xl"
      :class="[
        rail ? 'w-[72px]' : 'w-[260px]',
        mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
      ]"
    >
      <!-- Brand Header -->
      <div class="h-16 px-4 flex items-center justify-between border-b border-slate-800/80 bg-slate-950/40">
        <div class="flex items-center gap-3 overflow-hidden">
          <img :src="eduLogo" alt="EDU RRU Logo" class="w-9 h-9 object-contain drop-shadow-md shrink-0" />
          <div v-if="!rail" class="overflow-hidden whitespace-nowrap">
            <div class="text-sm font-bold text-white tracking-wide leading-tight truncate">
              EDU RRU ADMIN
            </div>
            <div class="text-[11px] text-emerald-400 font-medium">
              ระบบจัดการหลังบ้าน
            </div>
          </div>
        </div>

        <button
          v-if="!rail"
          type="button"
          class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800/60 hidden lg:block transition-colors cursor-pointer"
          title="ย่อเมนูด้านข้าง"
          @click="rail = true"
        >
          <v-icon icon="mdi-chevron-left" size="18" />
        </button>
      </div>

      <!-- Navigation Menu Items -->
      <nav class="flex-1 px-2.5 py-4 space-y-1.5 overflow-y-auto">
        <RouterLink
          v-for="item in navMenuItems"
          :key="item.to"
          :to="item.to"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all no-underline group"
          :class="[
            (item.exact ? route.path === item.to : route.path.startsWith(item.to))
              ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30'
              : 'text-slate-300 hover:text-white hover:bg-slate-800/70',
            rail ? 'justify-center px-0' : '',
          ]"
          :title="rail ? item.title : undefined"
          @click="mobileMenuOpen = false"
        >
          <v-icon
            :icon="item.icon"
            size="20"
            class="shrink-0"
            :class="(item.exact ? route.path === item.to : route.path.startsWith(item.to)) ? 'text-white' : 'text-slate-400 group-hover:text-emerald-400'"
          />
          <span v-if="!rail" class="truncate">{{ item.title }}</span>
        </RouterLink>
      </nav>

      <!-- Bottom External Link & Profile -->
      <div class="p-3 border-t border-slate-800/80 bg-slate-950/50 space-y-2">
        <RouterLink
          to="/"
          target="_blank"
          class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-emerald-400 hover:bg-slate-800/60 transition-colors no-underline"
          :class="rail ? 'justify-center px-0' : ''"
          :title="rail ? 'ดูหน้าเว็บไซต์หลัก' : undefined"
        >
          <v-icon icon="mdi-open-in-new" size="18" class="shrink-0" />
          <span v-if="!rail" class="truncate">ดูหน้าเว็บไซต์หลัก</span>
        </RouterLink>

        <!-- Rail toggle button when collapsed -->
        <button
          v-if="rail"
          type="button"
          class="w-full flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors cursor-pointer"
          title="ขยายเมนูด้านข้าง"
          @click="rail = false"
        >
          <v-icon icon="mdi-chevron-right" size="18" />
        </button>

        <!-- User Profile Card -->
        <div
          v-if="!rail"
          class="pt-2 border-t border-slate-800/60 flex items-center justify-between group cursor-pointer hover:bg-slate-900/60 p-2 rounded-xl transition-colors"
          @click="openSettingsModal"
          title="คลิกเพื่อตั้งค่าบัญชีผู้ดูแล"
        >
          <div class="flex items-center gap-2.5 min-w-0">
            <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white shrink-0 shadow-sm">
              <v-icon icon="mdi-shield-account" size="18" />
            </div>
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">
                {{ authStore.user?.name || 'ผู้ดูแลระบบ' }}
              </div>
              <div class="text-[10px] text-emerald-400 truncate">
                {{ authStore.user?.username ? '@' + authStore.user.username : (authStore.user?.email || 'admin') }}
              </div>
            </div>
          </div>
          <v-icon icon="mdi-cog-outline" size="16" class="text-slate-500 group-hover:text-emerald-400 transition-colors" />
        </div>
      </div>
    </aside>

    <!-- Main Content Container (Smoothly shifts with rail state) -->
    <div
      class="min-h-screen flex flex-col transition-all duration-300 ease-in-out"
      :class="[
        rail ? 'lg:pl-[72px]' : 'lg:pl-[260px]',
        'pl-0',
      ]"
    >
      <!-- Top App Bar -->
      <header class="h-16 bg-white border-b border-slate-200/90 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <div class="flex items-center gap-3 min-w-0">
          <!-- Toggle sidebar button for desktop and mobile -->
          <button
            type="button"
            class="p-2 rounded-lg text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
            aria-label="ย่อ/ขยายเมนูนำทาง"
            @click="rail ? (rail = false) : (rail = true); mobileMenuOpen = !mobileMenuOpen"
          >
            <v-icon icon="mdi-menu" size="20" />
          </button>

          <div class="min-w-0">
            <h1 class="text-sm sm:text-base font-bold text-slate-900 leading-tight truncate">
              {{ currentTitle }}
            </h1>
            <div class="text-[11px] text-slate-500 hidden sm:block truncate">
              คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์ (EDU RRU)
            </div>
          </div>
        </div>

        <!-- Right Quick Actions -->
        <div class="flex items-center gap-2 shrink-0">
          <div class="items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold hidden md:flex">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
            <span>MySQL: nedu (Connected)</span>
          </div>

          <RouterLink
            to="/admin/posts/create"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs transition-colors no-underline"
          >
            <v-icon icon="mdi-plus" size="16" />
            <span class="hidden sm:inline">สร้างข่าวใหม่</span>
          </RouterLink>

          <!-- Profile menu dropdown -->
          <v-menu location="bottom end">
            <template #activator="{ props }">
              <button
                type="button"
                v-bind="props"
                class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 border border-slate-200 flex items-center justify-center text-slate-700 transition-colors cursor-pointer"
              >
                <v-icon icon="mdi-account" size="18" />
              </button>
            </template>
            <v-list density="compact" class="w-60 shadow-xl rounded-xl border border-slate-100 p-1">
              <div class="px-3 py-2 border-b border-slate-100">
                <div class="text-xs font-bold text-slate-800 truncate">
                  {{ authStore.user?.name || 'ผู้ดูแลระบบ (Admin)' }}
                </div>
                <div class="text-[11px] text-emerald-600 font-semibold truncate">
                  {{ authStore.user?.username ? '@' + authStore.user.username : '' }}
                </div>
                <div class="text-[10px] text-slate-400 truncate">
                  {{ authStore.user?.email || 'admin@rru.ac.th' }}
                </div>
              </div>
              <v-list-item
                prepend-icon="mdi-account-cog-outline"
                title="ตั้งค่าบัญชีผู้ดูแล"
                @click="openSettingsModal"
              />
              <v-list-item
                prepend-icon="mdi-open-in-new"
                title="หน้าเว็บหลัก"
                to="/"
                target="_blank"
              />
              <v-divider class="my-1" />
              <v-list-item
                prepend-icon="mdi-logout"
                title="ออกจากระบบ"
                class="text-rose-600"
                @click="handleLogout"
              />
            </v-list>
          </v-menu>
        </div>
      </header>

      <!-- View Main Page Content -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-[1600px] mx-auto">
        <router-view />
      </main>
    </div>

    <!-- Admin Account Settings Dialog -->
    <v-dialog v-model="settingsModalOpen" max-width="540" persistent>
      <v-card class="rounded-2xl overflow-hidden border border-slate-200 shadow-2xl">
        <!-- Dialog Header -->
        <div class="bg-gradient-to-r from-emerald-800 to-teal-800 px-6 py-4 flex items-center justify-between text-white">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center">
              <v-icon icon="mdi-account-cog" size="20" />
            </div>
            <div>
              <h2 class="text-base font-bold leading-tight">ตั้งค่าบัญชีผู้ดูแล (Admin Settings)</h2>
              <p class="text-xs text-emerald-200">แก้ไขข้อมูลโปรไฟล์ ชื่อผู้ใช้งาน และรหัสผ่าน</p>
            </div>
          </div>
          <button
            type="button"
            class="text-emerald-200 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
            @click="settingsModalOpen = false"
          >
            <v-icon icon="mdi-close" size="20" />
          </button>
        </div>

        <form @submit.prevent="handleSaveSettings" class="p-6 space-y-5 bg-white">
          <!-- Success Alert -->
          <div
            v-if="settingsSuccess"
            class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5"
          >
            <v-icon icon="mdi-check-circle" size="18" class="text-emerald-600 shrink-0" />
            <span class="font-medium">{{ settingsSuccess }}</span>
          </div>

          <!-- Error Alert -->
          <div
            v-if="settingsError"
            class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2.5"
          >
            <v-icon icon="mdi-alert-circle" size="18" class="text-rose-600 mt-0.5 shrink-0" />
            <span class="font-medium">{{ settingsError }}</span>
          </div>

          <!-- Profile Info Section -->
          <div class="space-y-4">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
              <v-icon icon="mdi-card-account-details-outline" size="16" class="text-emerald-700" />
              <span>ข้อมูลทั่วไป</span>
            </div>

            <!-- Full Name -->
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                ชื่อ-นามสกุล / ชื่อผู้ดูแล <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="settingsForm.name"
                type="text"
                required
                placeholder="Admin EDU RRU"
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all"
              />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Username -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                  ชื่อผู้ใช้งาน (Username) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                  <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs font-bold">@</span>
                  <input
                    v-model="settingsForm.username"
                    type="text"
                    required
                    placeholder="admin"
                    class="w-full pl-8 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all"
                  />
                </div>
                <p class="text-[10px] text-slate-400 mt-1">ใช้สำหรับเข้าสู่ระบบ</p>
              </div>

              <!-- Email -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                  อีเมล (Email) <span class="text-rose-500">*</span>
                </label>
                <input
                  v-model="settingsForm.email"
                  type="email"
                  required
                  placeholder="admin@rru.ac.th"
                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all"
                />
                <p class="text-[10px] text-slate-400 mt-1">ใช้รับแจ้งเตือนหรือเข้าสู่ระบบ</p>
              </div>
            </div>
          </div>

          <v-divider />

          <!-- Password Change Section -->
          <div class="space-y-4">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
              <v-icon icon="mdi-shield-key-outline" size="16" class="text-emerald-700" />
              <span>เปลี่ยนรหัสผ่าน (เว้นว่างไว้หากไม่ต้องการเปลี่ยน)</span>
            </div>

            <!-- Current Password -->
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                รหัสผ่านปัจจุบัน (Current Password)
              </label>
              <div class="relative">
                <input
                  v-model="settingsForm.current_password"
                  :type="showCurrentPassword ? 'text' : 'password'"
                  placeholder="กรอกรหัสผ่านปัจจุบันเพื่อยืนยัน"
                  class="w-full px-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all"
                />
                <button
                  type="button"
                  @click="showCurrentPassword = !showCurrentPassword"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                  tabindex="-1"
                >
                  <v-icon :icon="showCurrentPassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline'" size="16" />
                </button>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- New Password -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                  รหัสผ่านใหม่ (New Password)
                </label>
                <div class="relative">
                  <input
                    v-model="settingsForm.new_password"
                    :type="showNewPassword ? 'text' : 'password'"
                    placeholder="อย่างน้อย 6 ตัวอักษร"
                    class="w-full px-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all"
                  />
                  <button
                    type="button"
                    @click="showNewPassword = !showNewPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                    tabindex="-1"
                  >
                    <v-icon :icon="showNewPassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline'" size="16" />
                  </button>
                </div>
              </div>

              <!-- Confirm New Password -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                  ยืนยันรหัสผ่านใหม่
                </label>
                <input
                  v-model="settingsForm.new_password_confirmation"
                  :type="showNewPassword ? 'text' : 'password'"
                  placeholder="กรอกรหัสผ่านใหม่อีกครั้ง"
                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-hidden focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all"
                />
              </div>
            </div>
          </div>

          <!-- Dialog Actions -->
          <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-slate-100">
            <button
              type="button"
              class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"
              @click="settingsModalOpen = false"
            >
              ยกเลิก
            </button>
            <button
              type="submit"
              :disabled="savingSettings"
              class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-xs font-bold shadow-sm transition-colors flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
            >
              <v-progress-circular
                v-if="savingSettings"
                indeterminate
                color="white"
                size="16"
                width="2"
              />
              <v-icon v-else icon="mdi-content-save-outline" size="16" />
              <span>{{ savingSettings ? 'กำลังบันทึก...' : 'บันทึกการเปลี่ยนแปลง' }}</span>
            </button>
          </div>
        </form>
      </v-card>
    </v-dialog>
  </div>
</template>

<style scoped>
.admin-wrapper {
  font-family: 'LINE Seed Sans TH', system-ui, sans-serif;
}
</style>
