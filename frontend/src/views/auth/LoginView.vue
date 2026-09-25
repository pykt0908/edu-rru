<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import eduLogo from '@/assets/logos/edu-logo-border-white.png'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const showPassword = ref(false)
const rememberMe = ref(true)
const errorMessage = ref('')
const lockoutSeconds = ref(0)
let timerInterval: any = null

const startLockoutTimer = (seconds: number) => {
  lockoutSeconds.value = seconds
  if (timerInterval) clearInterval(timerInterval)
  timerInterval = setInterval(() => {
    if (lockoutSeconds.value > 0) {
      lockoutSeconds.value--
    } else {
      clearInterval(timerInterval)
      errorMessage.value = ''
    }
  }, 1000)
}

const handleLogin = async () => {
  if (lockoutSeconds.value > 0) return

  if (!email.value || !password.value) {
    errorMessage.value = 'กรุณากรอกอีเมลและรหัสผ่านให้ครบถ้วน'
    return
  }

  errorMessage.value = ''
  const res = await authStore.login({
    email: email.value,
    password: password.value,
  })

  if (res.success) {
    const redirectUrl = (route.query.redirect as string) || '/admin'
    router.push(redirectUrl)
  } else {
    errorMessage.value = res.message
    if (res.status === 429 && res.retryAfter) {
      startLockoutTimer(res.retryAfter)
    }
  }
}
</script>

<template>
  <v-app class="!bg-slate-900 !min-h-screen">
    <div class="min-h-screen flex items-stretch bg-slate-900 font-sans selection:bg-emerald-500 selection:text-white">
      <!-- Left Hero Brand Column (Visible on lg screens) -->
      <div
        class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-emerald-950 via-[#064e3b] to-teal-950 p-12 flex-col items-center justify-center overflow-hidden text-white border-r border-emerald-900/50"
      >
        <!-- Subtle ambient lights -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none" />
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-400/10 rounded-full blur-3xl pointer-events-none" />

        <!-- Logo and Website Name -->
        <div class="relative z-10 text-center max-w-sm">
          <RouterLink to="/" class="inline-block group no-underline text-inherit mb-6">
            <img
              :src="eduLogo"
              alt="EDU RRU Logo"
              class="w-32 h-32 object-contain mx-auto drop-shadow-2xl group-hover:scale-105 transition-transform duration-300"
            />
          </RouterLink>

          <h1 class="text-3xl xl:text-4xl font-extrabold tracking-tight text-white mb-2">
            คณะครุศาสตร์
          </h1>
          <h2 class="text-lg xl:text-xl text-emerald-200 font-semibold mb-2">
            มหาวิทยาลัยราชภัฏราชนครินทร์
          </h2>
          <p class="text-xs xl:text-sm text-emerald-300/80 font-normal tracking-wide">
            Faculty of Education, Rajabhat Rajanagarinda University
          </p>
        </div>
      </div>

      <!-- Right Login Form Column -->
      <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-10 lg:p-16 bg-slate-50">
        <div class="w-full max-w-md">
          <!-- Mobile Logo Header (Visible on small screens) -->
          <div class="lg:hidden text-center mb-8">
            <img :src="eduLogo" alt="EDU RRU Logo" class="w-16 h-16 object-contain mx-auto mb-3 drop-shadow-md" />
            <h2 class="text-xl font-bold text-slate-900">คณะครุศาสตร์ มรภ.ราชนครินทร์</h2>
            <p class="text-xs text-slate-500">ระบบบริหารจัดการหลังบ้าน (Backoffice)</p>
          </div>

          <!-- Form Card Header -->
          <div class="mb-6">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
              เข้าสู่ระบบผู้ดูแล
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
              กรุณากรอกอีเมลและรหัสผ่านเพื่อเข้าใช้งานระบบ
            </p>
          </div>

          <!-- Error Message Alert -->
          <div
            v-if="errorMessage"
            class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2.5"
          >
            <v-icon icon="mdi-alert-circle" size="16" class="text-rose-600 mt-0.5 shrink-0" />
            <span class="font-medium">{{ errorMessage }}</span>
          </div>

          <!-- Login Form -->
          <form @submit.prevent="handleLogin" class="space-y-4">
            <!-- Email Field -->
            <div>
              <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                อีเมลผู้ใช้งาน (Email Address)
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                  <v-icon icon="mdi-email-outline" size="18" />
                </div>
                <input
                  id="email"
                  v-model="email"
                  type="email"
                  required
                  autocomplete="username"
                  placeholder="admin@rru.ac.th"
                  class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-hidden focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all shadow-xs"
                />
              </div>
            </div>

            <!-- Password Field -->
            <div>
              <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                รหัสผ่าน (Password)
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                  <v-icon icon="mdi-lock-outline" size="18" />
                </div>
                <input
                  id="password"
                  v-model="password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  autocomplete="current-password"
                  placeholder="••••••••"
                  class="w-full pl-10 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-hidden focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all shadow-xs"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                  title="แสดง/ซ่อนรหัสผ่าน"
                >
                  <v-icon :icon="showPassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline'" size="18" />
                </button>
              </div>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="flex items-center pt-1">
              <label class="flex items-center gap-2 cursor-pointer select-none text-xs text-slate-600 font-medium">
                <input
                  type="checkbox"
                  v-model="rememberMe"
                  class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer"
                />
                <span>จดจำการเข้าสู่ระบบบนอุปกรณ์นี้</span>
              </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
              <button
                type="submit"
                :disabled="authStore.loading || lockoutSeconds > 0"
                class="w-full py-3 px-4 rounded-xl text-white font-bold text-sm tracking-wide shadow-md transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                :class="
                  lockoutSeconds > 0
                    ? 'bg-rose-600/80 cursor-not-allowed text-white shadow-none'
                    : 'bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 shadow-emerald-700/20 hover:shadow-lg disabled:opacity-60'
                "
              >
                <v-progress-circular
                  v-if="authStore.loading"
                  indeterminate
                  color="white"
                  size="18"
                  width="2"
                />
                <span v-if="authStore.loading">กำลังตรวจสอบข้อมูล...</span>
                <span v-else-if="lockoutSeconds > 0" class="flex items-center gap-1.5 text-xs">
                  <v-icon icon="mdi-timer-sand" size="16" />
                  <span>ระงับการเข้าสู่ระบบชั่วคราว (รออีก {{ lockoutSeconds }} วินาที)</span>
                </span>
                <span v-else class="flex items-center gap-1.5">
                  <span>เข้าสู่ระบบ</span>
                  <v-icon icon="mdi-arrow-right" size="18" />
                </span>
              </button>
            </div>
          </form>

          <!-- Back to Public Site Link -->
          <div class="mt-8 pt-6 border-t border-slate-200/80 text-center">
            <RouterLink
              to="/"
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-emerald-700 transition-colors no-underline"
            >
              <v-icon icon="mdi-arrow-left" size="14" />
              <span>กลับสู่หน้าหลักเว็บไซต์ คณะครุศาสตร์</span>
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>
