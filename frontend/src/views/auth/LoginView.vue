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
const showForgotDialog = ref(false)
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

// Fill test credentials for quick review
const fillDemoCredentials = () => {
  email.value = 'admin@rru.ac.th'
  password.value = 'admin1234'
  errorMessage.value = ''
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
      class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-emerald-950 via-[#064e3b] to-teal-950 p-12 flex-col justify-between overflow-hidden text-white border-r border-emerald-900/50"
    >
      <!-- Ambient decorative lights -->
      <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none" />
      <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-400/15 rounded-full blur-3xl pointer-events-none" />
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-white/5 rounded-full blur-2xl pointer-events-none" />

      <!-- Top Brand -->
      <div class="relative z-10">
        <RouterLink to="/" class="inline-flex items-center gap-3 group no-underline text-inherit">
          <img
            :src="eduLogo"
            alt="EDU RRU Logo"
            class="w-12 h-12 object-contain drop-shadow-lg group-hover:scale-105 transition-transform"
          />
          <div>
            <div class="text-lg font-bold tracking-wide leading-tight text-white">
              คณะครุศาสตร์
            </div>
            <div class="text-xs text-emerald-300 font-medium">
              มหาวิทยาลัยราชภัฏราชนครินทร์ (EDU RRU)
            </div>
          </div>
        </RouterLink>
      </div>

      <!-- Center Hero Message -->
      <div class="relative z-10 max-w-lg my-auto py-12">
        <span
          class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/40 text-emerald-300 text-xs font-semibold border border-emerald-700/50 backdrop-blur-sm mb-6"
        >
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" />
          ระบบสารสนเทศกลางสำหรับผู้ดูแล
        </span>

        <h1 class="text-3xl xl:text-4xl font-extrabold tracking-tight text-white mb-4 leading-snug">
          ระบบบริหารจัดการข้อมูล & เว็บไซต์หลังบ้าน
        </h1>

        <p class="text-emerald-100/80 text-sm xl:text-base leading-relaxed mb-8 font-light">
          ศูนย์กลางการจัดการข่าวสาร กิจกรรม ข้อมูลคณาจารย์ 9 สาขาวิชา โครงสร้างหลักสูตรการศึกษา และตัวชี้วัดความยั่งยืน SDGs เชื่อมต่อฐานข้อมูล MySQL แบบ Real-time
        </p>

        <!-- Feature List Pills -->
        <div class="space-y-3 text-xs text-emerald-100/90 font-medium">
          <div class="flex items-center gap-2.5">
            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
              <v-icon icon="mdi-check" size="13" />
            </div>
            <span>ความปลอดภัยระดับสูงด้วย Laravel Sanctum Token</span>
          </div>
          <div class="flex items-center gap-2.5">
            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
              <v-icon icon="mdi-check" size="13" />
            </div>
            <span>แดชบอร์ดสรุปสถิติเชิงลึก พร้อมกราฟวิเคราะห์ข้อมูลแบบเรียลไทม์</span>
          </div>
          <div class="flex items-center gap-2.5">
            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
              <v-icon icon="mdi-check" size="13" />
            </div>
            <span>จัดการข้อมูลหลักสูตร คณาจารย์ ข้อบังคับ และตัวชี้วัด ITA ครบวงจร</span>
          </div>
        </div>
      </div>

      <!-- Bottom Info -->
      <div class="relative z-10 flex items-center justify-between text-xs text-emerald-200/60 pt-6 border-t border-emerald-900/40">
        <span>© 2026 Faculty of Education, RRU</span>
        <span class="font-mono text-[11px] bg-emerald-900/60 px-2 py-0.5 rounded text-emerald-300">
          v1.0.0
        </span>
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

        <!-- Demo Credentials Helper Banner -->
        <div class="mb-6 p-3.5 rounded-xl bg-emerald-50/80 border border-emerald-200/90 text-xs">
          <div class="flex items-center justify-between gap-2 mb-2">
            <span class="font-bold text-emerald-900 flex items-center gap-1.5">
              <v-icon icon="mdi-key" size="14" class="text-emerald-700" />
              บัญชีผู้ดูแลสำหรับทดสอบ (Demo Account)
            </span>
            <button
              type="button"
              @click="fillDemoCredentials"
              class="px-2 py-0.5 rounded bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-[11px] transition-colors cursor-pointer"
            >
              คลิกกรอกอัตโนมัติ
            </button>
          </div>
          <div class="space-y-0.5 font-mono text-[11px] text-emerald-800">
            <div>อีเมล: <strong class="select-all">admin@rru.ac.th</strong></div>
            <div>รหัสผ่าน: <strong class="select-all">admin1234</strong></div>
          </div>
        </div>

        <!-- Error Message Alert -->
        <div
          v-if="errorMessage"
          class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2.5 animate-shake"
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
            <div class="flex items-center justify-between mb-1.5">
              <label for="password" class="block text-xs font-semibold text-slate-700">
                รหัสผ่าน (Password)
              </label>
              <button
                type="button"
                @click="showForgotDialog = true"
                class="text-xs text-emerald-700 hover:text-emerald-800 font-medium cursor-pointer"
              >
                ลืมรหัสผ่าน?
              </button>
            </div>
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

    <!-- Forgot Password Modal -->
    <v-dialog v-model="showForgotDialog" max-width="440">
      <v-card class="!rounded-2xl p-6 bg-white">
        <div class="text-center mb-4">
          <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-700 mx-auto flex items-center justify-center mb-3">
            <v-icon icon="mdi-shield-lock-outline" size="24" />
          </div>
          <h3 class="text-lg font-bold text-slate-900">กู้คืนรหัสผ่านผู้ดูแลระบบ</h3>
          <p class="text-xs text-slate-500 mt-1 leading-relaxed">
            ระบบความปลอดภัยไม่อนุญาตให้รีเซ็ตรหัสผ่านแบบสาธารณะ กรุณาติดต่อผู้ดูแลระบบสารสนเทศเพื่อยืนยันตัวตน
          </p>
        </div>

        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700 space-y-2 mb-6">
          <div class="flex items-center gap-2">
            <v-icon icon="mdi-email" size="15" class="text-emerald-700" />
            <span>อีเมล: <strong>edu@rru.ac.th</strong></span>
          </div>
          <div class="flex items-center gap-2">
            <v-icon icon="mdi-phone" size="15" class="text-emerald-700" />
            <span>โทรศัพท์: <strong>038-515822</strong></span>
          </div>
          <div class="flex items-center gap-2">
            <v-icon icon="mdi-domain" size="15" class="text-emerald-700" />
            <span>งานสารสนเทศและเทคโนโลยี คณะครุศาสตร์</span>
          </div>
        </div>

        <v-btn
          color="emerald-darken-1"
          variant="flat"
          block
          rounded="lg"
          @click="showForgotDialog = false"
        >
          เข้าใจแล้ว
        </v-btn>
      </v-card>
    </v-dialog>
    </div>
  </v-app>
</template>
