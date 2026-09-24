<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { api } from '@/services/api'

interface Stats {
  total_posts: number
  total_personnel: number
  departments_count: number
  featured_posts: number
  total_views: number
  recent_posts: any[]
  recent_personnel: any[]
  categories_stats: { category: string; count: number }[]
}

const loading = ref(true)
const stats = ref<Stats>({
  total_posts: 0,
  total_personnel: 0,
  departments_count: 0,
  featured_posts: 0,
  total_views: 0,
  recent_posts: [],
  recent_personnel: [],
  categories_stats: [],
})

const fetchStats = async () => {
  loading.value = true
  try {
    const data = await api.getStats()
    stats.value = data
  } catch (err) {
    console.error('Failed to load stats:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchStats()
})
</script>

<template>
  <div class="space-y-6">
    <!-- Top Welcome Banner -->
    <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 rounded-2xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
      <div class="relative z-10 max-w-2xl">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-emerald-200 text-xs font-semibold backdrop-blur-sm mb-3">
          <v-icon icon="mdi-shield-check" size="14" />
          ระบบหลังบ้านคณะครุศาสตร์ มรภ.ราชนครินทร์
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">
          ยินดีต้อนรับสู่ EDU RRU Backoffice
        </h2>
        <p class="text-sm sm:text-base text-emerald-100 leading-relaxed">
          จัดการข้อมูลข่าวสาร บทความ กิจกรรม และฐานข้อมูลคณาจารย์ประจำสาขาวิชา เชื่อมโยงตรงสู่ MySQL (nedu) และ RESTful API
        </p>
      </div>
      <!-- Decorative circle background -->
      <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none" />
    </div>

    <!-- Stat KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- 1. Total Posts -->
      <v-card elevation="0" class="!rounded-xl border border-slate-200/90 hover:shadow-md transition-shadow p-5 bg-white">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">ข่าวสารและกิจกรรม</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">
              {{ loading ? '...' : stats.total_posts }}
            </div>
            <div class="text-xs text-emerald-600 font-medium mt-1 flex items-center gap-1">
              <v-icon icon="mdi-check-circle" size="13" />
              เผยแพร่อยู่ในระบบ
            </div>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
            <v-icon icon="mdi-newspaper-variant-outline" size="26" />
          </div>
        </div>
      </v-card>

      <!-- 2. Total Personnel -->
      <v-card elevation="0" class="!rounded-xl border border-slate-200/90 hover:shadow-md transition-shadow p-5 bg-white">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">อาจารย์ & บุคลากร</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">
              {{ loading ? '...' : stats.total_personnel }}
            </div>
            <div class="text-xs text-blue-600 font-medium mt-1 flex items-center gap-1">
              <v-icon icon="mdi-account-multiple-outline" size="13" />
              ครอบคลุมทุกสาขาวิชา
            </div>
          </div>
          <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
            <v-icon icon="mdi-account-group-outline" size="26" />
          </div>
        </div>
      </v-card>

      <!-- 3. Departments -->
      <v-card elevation="0" class="!rounded-xl border border-slate-200/90 hover:shadow-md transition-shadow p-5 bg-white">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">สาขาวิชา & ภาควิชา</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">
              {{ loading ? '...' : (stats.departments_count || 9) }}
            </div>
            <div class="text-xs text-purple-600 font-medium mt-1 flex items-center gap-1">
              <v-icon icon="mdi-school-outline" size="13" />
              หลักสูตร ค.บ. & ป.โท
            </div>
          </div>
          <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0">
            <v-icon icon="mdi-domain" size="26" />
          </div>
        </div>
      </v-card>

      <!-- 4. Total Views -->
      <v-card elevation="0" class="!rounded-xl border border-slate-200/90 hover:shadow-md transition-shadow p-5 bg-white">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">ยอดผู้เข้าชมรวม</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">
              {{ loading ? '...' : stats.total_views.toLocaleString() }}
            </div>
            <div class="text-xs text-amber-600 font-medium mt-1 flex items-center gap-1">
              <v-icon icon="mdi-chart-line" size="13" />
              สถิติการเปิดอ่านข่าว
            </div>
          </div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0">
            <v-icon icon="mdi-eye-outline" size="26" />
          </div>
        </div>
      </v-card>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left Column: Recent Posts Table (2 cols) -->
      <div class="lg:col-span-2 space-y-6">
        <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-5 bg-white">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <v-icon icon="mdi-newspaper-variant" size="18" class="text-emerald-700" />
                ข่าวสารล่าสุดในระบบ
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">รายการข่าวและประกาศที่เผยแพร่บนหน้าเว็บ</p>
            </div>
            <v-btn
              to="/admin/posts"
              variant="tonal"
              color="emerald"
              size="small"
              rounded="lg"
              class="!capitalize font-medium text-xs"
            >
              จัดการทั้งหมด
            </v-btn>
          </div>

          <div v-if="loading" class="py-12 text-center text-slate-400">
            <v-progress-circular indeterminate color="emerald" size="32" />
            <div class="text-xs mt-2">กำลังโหลดข้อมูลข่าว...</div>
          </div>

          <div v-else-if="stats.recent_posts.length === 0" class="py-10 text-center text-slate-400 text-sm">
            ยังไม่มีรายการข่าวในระบบ
          </div>

          <div v-else class="divide-y divide-slate-100">
            <div
              v-for="post in stats.recent_posts"
              :key="post.id"
              class="py-3 flex items-start justify-between gap-3 hover:bg-slate-50/80 rounded-lg px-2 transition-colors"
            >
              <div class="flex items-start gap-3 min-w-0">
                <img
                  v-if="post.thumbnail"
                  :src="post.thumbnail"
                  alt=""
                  class="w-14 h-12 object-cover rounded-lg border border-slate-200 shrink-0"
                />
                <div class="min-w-0">
                  <div class="flex items-center gap-2">
                    <span class="text-[11px] px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 font-semibold">
                      {{ post.category }}
                    </span>
                    <span class="text-xs text-slate-400">{{ post.date }}</span>
                  </div>
                  <h4 class="text-sm font-semibold text-slate-800 line-clamp-1 mt-1">
                    {{ post.title }}
                  </h4>
                </div>
              </div>

              <div class="flex items-center gap-1 shrink-0">
                <v-btn
                  :to="`/posts/${post.id}`"
                  target="_blank"
                  icon="mdi-eye-outline"
                  variant="text"
                  size="small"
                  color="slate-500"
                  title="ดูบนหน้าเว็บ"
                />
                <v-btn
                  to="/admin/posts"
                  icon="mdi-pencil-outline"
                  variant="text"
                  size="small"
                  color="emerald-darken-1"
                  title="แก้ไข"
                />
              </div>
            </div>
          </div>
        </v-card>
      </div>

      <!-- Right Column: Quick Status & Categories (1 col) -->
      <div class="space-y-6">
        <!-- Database & Server Status -->
        <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-5 bg-white">
          <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
            <v-icon icon="mdi-server" size="16" class="text-emerald-700" />
            สถานะระบบ (System Environment)
          </h3>
          <div class="space-y-2.5 text-xs">
            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50">
              <span class="text-slate-600 font-medium">Backend Framework</span>
              <span class="font-bold text-slate-900">Laravel 12 / PHP 8.4</span>
            </div>
            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50">
              <span class="text-slate-600 font-medium">Database (XAMPP)</span>
              <span class="font-bold text-emerald-700 flex items-center gap-1">
                <v-icon icon="mdi-database" size="14" />
                MySQL (nedu)
              </span>
            </div>
            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50">
              <span class="text-slate-600 font-medium">Admin Framework</span>
              <span class="font-bold text-slate-900">Vuetify 4 + Vite</span>
            </div>
            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50">
              <span class="text-slate-600 font-medium">API Endpoint</span>
              <code class="text-slate-700 font-mono text-[11px] bg-slate-200/70 px-1.5 py-0.5 rounded">
                :8000/api
              </code>
            </div>
          </div>
        </v-card>

        <!-- Categories Distribution -->
        <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-5 bg-white">
          <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
            <v-icon icon="mdi-tag-outline" size="16" class="text-emerald-700" />
            หมวดหมู่ข่าวสาร
          </h3>
          <div class="space-y-2">
            <div
              v-for="cat in stats.categories_stats"
              :key="cat.category"
              class="flex items-center justify-between text-xs py-1.5 px-2 rounded-md hover:bg-slate-50"
            >
              <span class="font-medium text-slate-700">{{ cat.category }}</span>
              <v-chip size="x-small" color="emerald" variant="flat" class="font-bold">
                {{ cat.count }} รายการ
              </v-chip>
            </div>
          </div>
        </v-card>
      </div>
    </div>
  </div>
</template>
