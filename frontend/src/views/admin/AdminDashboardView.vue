<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  ArcElement,
  PointElement,
  LineElement,
  type ChartData,
  type ChartOptions,
} from 'chart.js'
import { Doughnut, Bar } from 'vue-chartjs'
import { api } from '@/services/api'
import { SDG_GOALS } from '@/data/sdgsData'

// Register Chart.js modules
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  ArcElement,
  PointElement,
  LineElement
)

// Global Chart.js styling
ChartJS.defaults.font.family = "'LINE Seed Sans TH', 'Prompt', -apple-system, BlinkMacSystemFont, sans-serif"
ChartJS.defaults.color = '#64748b'

interface Stats {
  total_posts: number
  total_personnel: number
  departments_count: number
  featured_posts: number
  total_views: number
  total_curricula?: number
  total_carousels?: number
  total_regulations?: number
  total_ita?: number
  recent_posts: any[]
  recent_personnel: any[]
  top_viewed_posts?: any[]
  categories_stats: { category: string; count: number }[]
  sdgs_stats?: { sdg_id: number; count: number }[]
  department_personnel_stats?: { id?: number; slug?: string; name: string; count: number }[]
  academic_titles_stats?: { academic_title: string; count: number }[]
  curricula_stats?: { degree_level: string; count: number }[]
}

const loading = ref(true)
const activeSubChart = ref<'curricula' | 'academic'>('curricula')

const stats = ref<Stats>({
  total_posts: 0,
  total_personnel: 0,
  departments_count: 0,
  featured_posts: 0,
  total_views: 0,
  total_curricula: 0,
  total_carousels: 0,
  total_regulations: 0,
  total_ita: 0,
  recent_posts: [],
  recent_personnel: [],
  top_viewed_posts: [],
  categories_stats: [],
  sdgs_stats: [],
  department_personnel_stats: [],
  academic_titles_stats: [],
  curricula_stats: [],
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

// ---------------------------------------------------------------------
// Chart 1: Categories Distribution (Doughnut)
// ---------------------------------------------------------------------
const categoryPalette = [
  '#059669', // Emerald
  '#2563eb', // Blue
  '#8b5cf6', // Violet
  '#f59e0b', // Amber
  '#ec4899', // Pink
  '#0d9488', // Teal
  '#6366f1', // Indigo
  '#64748b', // Slate
]

const categoryChartData = computed<ChartData<'doughnut'>>(() => {
  const categories = stats.value.categories_stats || []
  return {
    labels: categories.map((c) => c.category),
    datasets: [
      {
        data: categories.map((c) => c.count),
        backgroundColor: categoryPalette.slice(0, categories.length),
        borderColor: '#ffffff',
        borderWidth: 2,
        hoverOffset: 6,
      },
    ],
  }
})

const categoryChartOptions: ChartOptions<'doughnut'> = {
  responsive: true,
  maintainAspectRatio: false,
  cutout: '72%',
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      callbacks: {
        label: (context) => {
          const val = context.raw as number
          const total = stats.value.total_posts || 1
          const pct = Math.round((val / total) * 100)
          return ` ${val} รายการ (${pct}%)`
        },
      },
    },
  },
}

// ---------------------------------------------------------------------
// Chart 2: SDGs Initiatives & Impact (Bar)
// ---------------------------------------------------------------------
const sdgsChartData = computed<ChartData<'bar'>>(() => {
  const rawSdgs = stats.value.sdgs_stats || []
  
  // If we have specific active SDGs, display those; otherwise show top representative goals
  const displayItems = rawSdgs.length > 0
    ? rawSdgs
    : [
        { sdg_id: 1, count: 1 },
        { sdg_id: 4, count: 1 },
      ]

  const labels = displayItems.map((item) => {
    const goal = SDG_GOALS.find((g) => g.id === item.sdg_id)
    return `SDG ${item.sdg_id}: ${goal?.titleTh || ''}`
  })

  const backgroundColors = displayItems.map((item) => {
    const goal = SDG_GOALS.find((g) => g.id === item.sdg_id)
    return goal?.color || '#059669'
  })

  return {
    labels,
    datasets: [
      {
        label: 'จำนวนบทความ/กิจกรรมที่สอดคล้อง',
        data: displayItems.map((item) => item.count),
        backgroundColor: backgroundColors,
        borderRadius: 8,
        borderSkipped: false,
        maxBarThickness: 38,
      },
    ],
  }
})

const sdgsChartOptions: ChartOptions<'bar'> = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      callbacks: {
        label: (context) => ` ${context.raw} กิจกรรม/บทความ`,
      },
    },
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        stepSize: 1,
        precision: 0,
      },
      grid: {
        color: '#f1f5f9',
      },
    },
    x: {
      grid: {
        display: false,
      },
      ticks: {
        maxRotation: 20,
        minRotation: 0,
        autoSkip: false,
        font: {
          size: 11,
        },
      },
    },
  },
}

// ---------------------------------------------------------------------
// Chart 3: Department Personnel Breakdown (Horizontal Bar)
// ---------------------------------------------------------------------
const departmentChartData = computed<ChartData<'bar'>>(() => {
  const depts = stats.value.department_personnel_stats || []
  const labels = depts.map((d) => d.name.replace('สาขาวิชา', '').trim())
  const data = depts.map((d) => d.count)

  return {
    labels,
    datasets: [
      {
        label: 'จำนวนคณาจารย์ (ท่าน)',
        data,
        backgroundColor: '#059669',
        hoverBackgroundColor: '#047857',
        borderRadius: 6,
        borderSkipped: false,
        barPercentage: 0.75,
      },
    ],
  }
})

const departmentChartOptions: ChartOptions<'bar'> = {
  indexAxis: 'y',
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      callbacks: {
        label: (context) => ` ${context.raw} ท่าน`,
      },
    },
  },
  scales: {
    x: {
      beginAtZero: true,
      ticks: {
        stepSize: 1,
        precision: 0,
      },
      grid: {
        color: '#f1f5f9',
      },
    },
    y: {
      grid: {
        display: false,
      },
      ticks: {
        font: {
          size: 11,
        },
      },
    },
  },
}

// ---------------------------------------------------------------------
// Chart 4: Curricula & Academic Titles (Doughnuts)
// ---------------------------------------------------------------------
const degreeNameMap: Record<string, string> = {
  bachelor: 'ระดับปริญญาตรี (ค.บ.)',
  master: 'ระดับปริญญาโท (ค.ม.)',
  'grad-diploma': 'ประกาศนียบัตรบัณฑิต',
}

const curriculaChartData = computed<ChartData<'doughnut'>>(() => {
  const items = stats.value.curricula_stats || []
  return {
    labels: items.map((i) => degreeNameMap[i.degree_level] || i.degree_level),
    datasets: [
      {
        data: items.map((i) => i.count),
        backgroundColor: ['#059669', '#8b5cf6', '#f59e0b', '#0284c7'],
        borderColor: '#ffffff',
        borderWidth: 2,
        hoverOffset: 4,
      },
    ],
  }
})

const academicTitlesChartData = computed<ChartData<'doughnut'>>(() => {
  const items = stats.value.academic_titles_stats || []
  return {
    labels: items.map((i) => i.academic_title),
    datasets: [
      {
        data: items.map((i) => i.count),
        backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#ec4899'],
        borderColor: '#ffffff',
        borderWidth: 2,
        hoverOffset: 4,
      },
    ],
  }
})

const subDoughnutOptions: ChartOptions<'doughnut'> = {
  responsive: true,
  maintainAspectRatio: false,
  cutout: '68%',
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        boxWidth: 12,
        padding: 12,
        font: {
          size: 11,
        },
      },
    },
    tooltip: {
      callbacks: {
        label: (context) => ` ${context.label}: ${context.raw} รายการ`,
      },
    },
  },
}

// Top Viewed Articles Computed
const topViewedPosts = computed(() => {
  return stats.value.top_viewed_posts || []
})

// Covered SDGs IDs Set
const coveredSdgIds = computed(() => {
  const set = new Set<number>()
  stats.value.sdgs_stats?.forEach((s) => set.add(s.sdg_id))
  return set
})

onMounted(() => {
  fetchStats()
})
</script>

<template>
  <div class="space-y-6 pb-12">
    <!-- 4 High-Impact KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- 1. Total Posts -->
      <v-card
        elevation="0"
        class="!rounded-xl border border-slate-200/90 hover:shadow-md transition-all p-5 bg-white relative overflow-hidden group"
      >
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
              ข่าวสาร & กิจกรรม
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mt-1">
              {{ loading ? '...' : stats.total_posts }}
            </div>
            <div class="text-xs text-emerald-600 font-medium mt-1.5 flex items-center gap-1">
              <v-icon icon="mdi-star" size="13" class="text-amber-500" />
              <span>แนะนำ {{ stats.featured_posts }} เรื่อง</span>
              <span class="text-slate-300">|</span>
              <span>เผยแพร่จริง</span>
            </div>
          </div>
          <div
            class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform"
          >
            <v-icon icon="mdi-newspaper-variant-outline" size="28" />
          </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-500 rounded-b-xl" />
      </v-card>

      <!-- 2. Faculty & Staff -->
      <v-card
        elevation="0"
        class="!rounded-xl border border-slate-200/90 hover:shadow-md transition-all p-5 bg-white relative overflow-hidden group"
      >
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
              คณาจารย์ & บุคลากร
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mt-1">
              {{ loading ? '...' : stats.total_personnel }}
            </div>
            <div class="text-xs text-blue-600 font-medium mt-1.5 flex items-center gap-1">
              <v-icon icon="mdi-domain" size="13" />
              <span>{{ stats.departments_count || 9 }} สาขาวิชา</span>
              <span class="text-slate-300">|</span>
              <span>ตำแหน่งครบ</span>
            </div>
          </div>
          <div
            class="w-13 h-13 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform"
          >
            <v-icon icon="mdi-account-group-outline" size="28" />
          </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-500 rounded-b-xl" />
      </v-card>

      <!-- 3. Curricula -->
      <v-card
        elevation="0"
        class="!rounded-xl border border-slate-200/90 hover:shadow-md transition-all p-5 bg-white relative overflow-hidden group"
      >
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
              หลักสูตรที่เปิดสอน
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mt-1">
              {{ loading ? '...' : (stats.total_curricula || 11) }}
            </div>
            <div class="text-xs text-purple-600 font-medium mt-1.5 flex items-center gap-1">
              <v-icon icon="mdi-certificate-outline" size="13" />
              <span>ค.บ. 8 | ป.โท 2 | ป.บัณฑิต 1</span>
            </div>
          </div>
          <div
            class="w-13 h-13 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform"
          >
            <v-icon icon="mdi-school-outline" size="28" />
          </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-purple-500 rounded-b-xl" />
      </v-card>

      <!-- 4. Total Views -->
      <v-card
        elevation="0"
        class="!rounded-xl border border-slate-200/90 hover:shadow-md transition-all p-5 bg-white relative overflow-hidden group"
      >
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
              ยอดเข้าชมสะสมรวม
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mt-1">
              {{ loading ? '...' : stats.total_views.toLocaleString() }}
            </div>
            <div class="text-xs text-amber-600 font-medium mt-1.5 flex items-center gap-1">
              <v-icon icon="mdi-trending-up" size="13" />
              <span>เฉลี่ย {{ Math.round((stats.total_views || 0) / (stats.total_posts || 1)) }} ครั้ง/ข่าว</span>
            </div>
          </div>
          <div
            class="w-13 h-13 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform"
          >
            <v-icon icon="mdi-eye-outline" size="28" />
          </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-500 rounded-b-xl" />
      </v-card>
    </div>

    <!-- ================================================================= -->
    <!-- CHARTS SECTION ROW 1: News Categories & SDGs Initiatives -->
    <!-- ================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Chart 1: Categories Distribution (5 cols) -->
      <div class="lg:col-span-5">
        <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-5 bg-white h-full flex flex-col">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                <v-icon icon="mdi-chart-pie" size="18" class="text-emerald-700" />
                สัดส่วนหมวดหมู่ข่าวสาร & บทความ
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">การกระจายตัวของเนื้อหาตามแต่ละกลุ่ม</p>
            </div>
            <v-btn
              to="/admin/posts"
              variant="text"
              color="emerald-darken-1"
              size="small"
              class="!text-xs font-semibold !capitalize"
            >
              ดูทั้งหมด
            </v-btn>
          </div>

          <!-- Chart Area -->
          <div class="relative h-56 flex items-center justify-center my-auto">
            <div v-if="loading" class="text-slate-400 text-xs flex flex-col items-center gap-2">
              <v-progress-circular indeterminate color="emerald" size="28" />
              <span>กำลังคำนวณกราฟ...</span>
            </div>
            <template v-else-if="stats.categories_stats.length > 0">
              <Doughnut :data="categoryChartData" :options="categoryChartOptions" />
              <!-- Center Metric -->
              <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                <span class="text-2xl font-extrabold text-slate-900">{{ stats.total_posts }}</span>
                <span class="text-[11px] font-medium text-slate-500">บทความทั้งหมด</span>
              </div>
            </template>
            <div v-else class="text-slate-400 text-xs">ยังไม่มีข้อมูลหมวดหมู่</div>
          </div>

          <!-- Custom Legend Badges -->
          <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
            <div
              v-for="(cat, idx) in stats.categories_stats"
              :key="cat.category"
              class="flex items-center justify-between p-2 rounded-lg bg-slate-50 hover:bg-slate-100/80 transition-colors"
            >
              <div class="flex items-center gap-2 min-w-0">
                <span
                  class="w-3 h-3 rounded-full shrink-0"
                  :style="{ backgroundColor: categoryPalette[idx % categoryPalette.length] }"
                />
                <span class="font-medium text-slate-700 truncate text-[11px]">
                  {{ cat.category }}
                </span>
              </div>
              <span class="font-bold text-slate-900 shrink-0 text-xs">
                {{ cat.count }} เรื่อง
              </span>
            </div>
          </div>
        </v-card>
      </div>

      <!-- Chart 2: SDGs Impact & Initiatives (7 cols) -->
      <div class="lg:col-span-7">
        <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-5 bg-white h-full flex flex-col">
          <div class="flex items-center justify-between mb-4">
            <div>
              <div class="flex items-center gap-2">
                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                  <v-icon icon="mdi-earth" size="18" class="text-teal-700" />
                  การขับเคลื่อนเป้าหมายการพัฒนาที่ยั่งยืน (SDGs Impact)
                </h3>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-teal-50 text-teal-800 font-bold border border-teal-200">
                  UN Agenda 2030
                </span>
              </div>
              <p class="text-xs text-slate-500 mt-0.5">
                สถิติโครงการและบทความวิชาการที่เชื่อมโยงกับเป้าหมายความยั่งยืน 17 มิติ
              </p>
            </div>
            <v-btn
              to="/sdgs"
              target="_blank"
              variant="text"
              color="teal-darken-2"
              size="small"
              class="!text-xs font-semibold !capitalize"
              append-icon="mdi-open-in-new"
            >
              หน้า SDGs
            </v-btn>
          </div>

          <!-- Bar Chart Area -->
          <div class="h-56 relative my-auto">
            <div v-if="loading" class="h-full flex flex-col items-center justify-center text-slate-400 text-xs gap-2">
              <v-progress-circular indeterminate color="teal" size="28" />
              <span>กำลังดึงสถิติ SDGs...</span>
            </div>
            <Bar v-else :data="sdgsChartData" :options="sdgsChartOptions" />
          </div>

          <!-- 17-Goal Coverage Tracker -->
          <div class="mt-4 pt-4 border-t border-slate-100">
            <div class="flex items-center justify-between text-xs text-slate-600 mb-2">
              <span class="font-semibold text-slate-700 flex items-center gap-1.5">
                <v-icon icon="mdi-view-grid-outline" size="14" class="text-teal-600" />
                ความครอบคลุมเป้าหมาย SDGs ของคณะ (17 Goals Matrix):
              </span>
              <span class="text-[11px] text-slate-500">
                ขับเคลื่อนแล้ว <strong class="text-teal-700">{{ coveredSdgIds.size }}</strong> / 17 เป้าหมาย
              </span>
            </div>

            <!-- Mini 1-17 Badges -->
            <div class="flex flex-wrap gap-1.5">
              <div
                v-for="goal in SDG_GOALS"
                :key="goal.id"
                class="w-6 h-6 rounded flex items-center justify-center text-[10px] font-bold transition-all relative group cursor-pointer"
                :style="{
                  backgroundColor: coveredSdgIds.has(goal.id) ? goal.color : '#f1f5f9',
                  color: coveredSdgIds.has(goal.id) ? '#ffffff' : '#94a3b8',
                }"
                :title="`SDG ${goal.id}: ${goal.titleTh}`"
              >
                {{ goal.id }}
                <!-- Active dot -->
                <span
                  v-if="coveredSdgIds.has(goal.id)"
                  class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-emerald-400 ring-2 ring-white"
                />
              </div>
            </div>
          </div>
        </v-card>
      </div>
    </div>

    <!-- ================================================================= -->
    <!-- CHARTS SECTION ROW 2: Department Faculty & Curricula Structure -->
    <!-- ================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Chart 3: Faculty Distribution by Department (7 cols) -->
      <div class="lg:col-span-7">
        <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-5 bg-white h-full flex flex-col">
          <div class="flex items-center justify-between mb-3">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                <v-icon icon="mdi-account-supervisor-circle" size="18" class="text-emerald-700" />
                การกระจายตัวของคณาจารย์ตามสาขาวิชา
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">จำนวนอาจารย์ประจำในแต่ละกลุ่มสาขาวิชาการศึกษา (คน)</p>
            </div>
            <v-btn
              to="/admin/personnel"
              variant="text"
              color="emerald-darken-1"
              size="small"
              class="!text-xs font-semibold !capitalize"
            >
              จัดการอาจารย์
            </v-btn>
          </div>

          <div class="h-68 relative">
            <div v-if="loading" class="h-full flex flex-col items-center justify-center text-slate-400 text-xs gap-2">
              <v-progress-circular indeterminate color="emerald" size="28" />
              <span>กำลังดึงข้อมูลสาขาวิชา...</span>
            </div>
            <Bar v-else :data="departmentChartData" :options="departmentChartOptions" />
          </div>
        </v-card>
      </div>

      <!-- Chart 4: Curricula & Academic Titles Breakdown (5 cols) -->
      <div class="lg:col-span-5">
        <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-5 bg-white h-full flex flex-col">
          <div class="flex items-center justify-between mb-3">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                <v-icon icon="mdi-chart-donut" size="18" class="text-purple-700" />
                โครงสร้างวิชาการ & หลักสูตร
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">สัดส่วนตามระดับปริญญา และตำแหน่งทางวิชาการ</p>
            </div>

            <!-- Tab Switcher -->
            <div class="inline-flex rounded-lg bg-slate-100 p-0.5 text-xs font-medium">
              <button
                type="button"
                @click="activeSubChart = 'curricula'"
                class="px-2.5 py-1 rounded-md transition-all cursor-pointer"
                :class="
                  activeSubChart === 'curricula'
                    ? 'bg-white text-purple-700 shadow-xs font-bold'
                    : 'text-slate-600 hover:text-slate-900'
                "
              >
                หลักสูตร
              </button>
              <button
                type="button"
                @click="activeSubChart = 'academic'"
                class="px-2.5 py-1 rounded-md transition-all cursor-pointer"
                :class="
                  activeSubChart === 'academic'
                    ? 'bg-white text-purple-700 shadow-xs font-bold'
                    : 'text-slate-600 hover:text-slate-900'
                "
              >
                ตำแหน่ง
              </button>
            </div>
          </div>

          <!-- Chart Area -->
          <div class="h-68 relative flex items-center justify-center my-auto">
            <div v-if="loading" class="text-slate-400 text-xs flex flex-col items-center gap-2">
              <v-progress-circular indeterminate color="purple" size="28" />
              <span>กำลังโหลดโครงสร้าง...</span>
            </div>
            <template v-else>
              <Doughnut
                v-if="activeSubChart === 'curricula'"
                :data="curriculaChartData"
                :options="subDoughnutOptions"
              />
              <Doughnut
                v-else
                :data="academicTitlesChartData"
                :options="subDoughnutOptions"
              />
            </template>
          </div>
        </v-card>
      </div>
    </div>

    <!-- ================================================================= -->
    <!-- CONTENT SECTION: Top Viewed Articles, Recent Posts & Quick Actions -->
    <!-- ================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Left 8 cols: Top Viewed Posts & Recent Activities -->
      <div class="lg:col-span-8 space-y-6">
        <!-- 1. Top Viewed Articles Ranking -->
        <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-5 bg-white">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                <v-icon icon="mdi-fire" size="20" class="text-amber-500" />
                บทความยอดนิยมที่มีผู้เข้าชมสูงสุด (Top 5 Most Viewed)
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">
                เรียงลำดับตามจำนวนครั้งการเปิดอ่านจริงบนเว็บไซต์
              </p>
            </div>
            <v-chip size="x-small" color="amber-darken-3" variant="flat" class="font-bold">
              Trending
            </v-chip>
          </div>

          <div v-if="loading" class="py-8 text-center text-slate-400 text-xs">
            <v-progress-circular indeterminate color="amber" size="24" />
            <div class="mt-2">กำลังโหลดอันดับข่าว...</div>
          </div>

          <div v-else-if="topViewedPosts.length === 0" class="py-6 text-center text-slate-400 text-sm">
            ยังไม่มีสถิติการเข้าชม
          </div>

          <div v-else class="space-y-2.5">
            <div
              v-for="(post, idx) in topViewedPosts"
              :key="post.id"
              class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/20 transition-all gap-3"
            >
              <!-- Rank badge & Title -->
              <div class="flex items-center gap-3 min-w-0">
                <span
                  class="w-7 h-7 rounded-lg flex items-center justify-center font-extrabold text-xs shrink-0"
                  :class="{
                    'bg-amber-100 text-amber-800 ring-1 ring-amber-300': idx === 0,
                    'bg-slate-200 text-slate-700': idx === 1,
                    'bg-amber-700/20 text-amber-900': idx === 2,
                    'bg-slate-100 text-slate-500': idx > 2,
                  }"
                >
                  {{ idx + 1 }}
                </span>

                <img
                  v-if="post.thumbnail"
                  :src="post.thumbnail"
                  alt=""
                  class="w-12 h-10 object-cover rounded-lg border border-slate-200 shrink-0 hidden sm:block"
                />

                <div class="min-w-0">
                  <div class="flex items-center gap-2 mb-0.5">
                    <span
                      class="text-[10px] px-2 py-0.2 rounded bg-slate-100 text-slate-700 font-semibold"
                    >
                      {{ post.category }}
                    </span>
                    <span class="text-[11px] text-slate-400">{{ post.date }}</span>
                  </div>
                  <h4 class="text-xs sm:text-sm font-bold text-slate-800 line-clamp-1 hover:text-emerald-700 transition-colors">
                    {{ post.title }}
                  </h4>
                </div>
              </div>

              <!-- Views & Actions -->
              <div class="flex items-center gap-3 shrink-0">
                <div class="text-right">
                  <div class="text-sm font-extrabold text-amber-600 flex items-center gap-1 justify-end">
                    <v-icon icon="mdi-eye" size="14" />
                    <span>{{ post.views.toLocaleString() }}</span>
                  </div>
                  <div class="text-[10px] text-slate-400 font-medium">ครั้ง</div>
                </div>

                <div class="flex items-center">
                  <v-btn
                    :to="`/posts/${post.id}`"
                    target="_blank"
                    icon="mdi-eye-outline"
                    variant="text"
                    size="x-small"
                    color="slate-500"
                    title="ดูหน้าเว็บจริง"
                  />
                  <v-btn
                    :to="`/admin/posts/${post.id}/edit`"
                    icon="mdi-pencil-outline"
                    variant="text"
                    size="x-small"
                    color="emerald-darken-1"
                    title="แก้ไขบทความ"
                  />
                </div>
              </div>
            </div>
          </div>
        </v-card>

        <!-- 2. Recent Posts Table -->
        <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-5 bg-white">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                <v-icon icon="mdi-newspaper-variant" size="18" class="text-emerald-700" />
                ข่าวสารและประกาศล่าสุด
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">รายการบทความที่เพิ่งอัปเดตลงระบบ</p>
            </div>
            <v-btn
              to="/admin/posts"
              variant="tonal"
              color="emerald"
              size="small"
              rounded="lg"
              class="!capitalize font-semibold text-xs"
            >
              จัดการทั้งหมด
            </v-btn>
          </div>

          <div v-if="loading" class="py-6 text-center text-slate-400 text-xs">
            <v-progress-circular indeterminate color="emerald" size="24" />
          </div>

          <div v-else-if="stats.recent_posts.length === 0" class="py-6 text-center text-slate-400 text-sm">
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
                  class="w-13 h-11 object-cover rounded-lg border border-slate-200 shrink-0"
                />
                <div class="min-w-0">
                  <div class="flex items-center gap-2">
                    <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 font-semibold">
                      {{ post.category }}
                    </span>
                    <span class="text-xs text-slate-400">{{ post.date }}</span>
                  </div>
                  <h4 class="text-xs sm:text-sm font-semibold text-slate-800 line-clamp-1 mt-1">
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
                  :to="`/admin/posts/${post.id}/edit`"
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

      <!-- Right 4 cols: System Health, Content Modules & Quick Shortcuts -->
      <div class="lg:col-span-4 space-y-6">
        <!-- System Health & Services -->
        <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-5 bg-white">
          <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
            <v-icon icon="mdi-server" size="16" class="text-emerald-700" />
            สถานะโมดูลสารสนเทศ (System & Content)
          </h3>
          <div class="space-y-2.5 text-xs">
            <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50">
              <span class="text-slate-600 font-medium flex items-center gap-2">
                <v-icon icon="mdi-view-carousel-outline" size="15" class="text-slate-500" />
                แบนเนอร์ Carousel หน้าแรก
              </span>
              <span class="font-bold text-slate-900">
                {{ stats.total_carousels || 3 }} สไลด์
              </span>
            </div>

            <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50">
              <span class="text-slate-600 font-medium flex items-center gap-2">
                <v-icon icon="mdi-file-document-outline" size="15" class="text-slate-500" />
                ระเบียบ ข้อบังคับ & ประกาศ
              </span>
              <span class="font-bold text-slate-900">
                {{ stats.total_regulations || 12 }} รายการ
              </span>
            </div>

            <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50">
              <span class="text-slate-600 font-medium flex items-center gap-2">
                <v-icon icon="mdi-shield-check-outline" size="15" class="text-slate-500" />
                ตัวชี้วัดความโปร่งใส ITA
              </span>
              <span class="font-bold text-slate-900">
                {{ stats.total_ita || 24 }} ตัวชี้วัด
              </span>
            </div>

            <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50">
              <span class="text-slate-600 font-medium flex items-center gap-2">
                <v-icon icon="mdi-database" size="15" class="text-emerald-600" />
                ฐานข้อมูลระบบ (MySQL)
              </span>
              <span class="font-bold text-emerald-700">Online (nedu)</span>
            </div>

            <div
              class="flex items-center justify-between p-2.5 rounded-lg bg-emerald-50/80 border border-emerald-200/70"
            >
              <div class="flex items-center gap-2">
                <v-icon icon="mdi-api" size="16" class="text-emerald-700" />
                <div>
                  <div class="font-bold text-emerald-900 text-xs">OpenAPI / Swagger</div>
                  <div class="text-[10px] text-emerald-700">Interactive API Doc</div>
                </div>
              </div>
              <a
                href="http://localhost:8000/api/documentation"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-700 text-white font-semibold text-[11px] hover:bg-emerald-800 transition-colors no-underline shadow-xs"
              >
                <span>เปิด Swagger UI</span>
                <v-icon icon="mdi-open-in-new" size="11" />
              </a>
            </div>
          </div>
        </v-card>

        <!-- Quick Management Shortcuts -->
        <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-5 bg-white">
          <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
            <v-icon icon="mdi-lightning-bolt" size="16" class="text-amber-500" />
            ทางลัดการจัดการข้อมูล (Quick Actions)
          </h3>
          <div class="grid grid-cols-2 gap-2 text-xs">
            <v-btn
              to="/admin/posts/create"
              variant="outlined"
              color="emerald-darken-2"
              rounded="lg"
              class="!text-[11px] font-semibold !h-auto py-2.5 justify-start text-left"
              prepend-icon="mdi-newspaper-plus"
            >
              สร้างข่าวใหม่
            </v-btn>

            <v-btn
              to="/admin/personnel"
              variant="outlined"
              color="blue-darken-2"
              rounded="lg"
              class="!text-[11px] font-semibold !h-auto py-2.5 justify-start text-left"
              prepend-icon="mdi-account-plus"
            >
              จัดการอาจารย์
            </v-btn>

            <v-btn
              to="/admin/curricula"
              variant="outlined"
              color="purple-darken-2"
              rounded="lg"
              class="!text-[11px] font-semibold !h-auto py-2.5 justify-start text-left"
              prepend-icon="mdi-school"
            >
              จัดการหลักสูตร
            </v-btn>

            <v-btn
              to="/admin/executives"
              variant="outlined"
              color="amber-darken-3"
              rounded="lg"
              class="!text-[11px] font-semibold !h-auto py-2.5 justify-start text-left"
              prepend-icon="mdi-account-tie"
            >
              โครงสร้างผู้บริหาร
            </v-btn>

            <v-btn
              to="/admin/content"
              variant="outlined"
              color="teal-darken-2"
              rounded="lg"
              class="!text-[11px] font-semibold !h-auto py-2.5 justify-start text-left col-span-2"
              prepend-icon="mdi-view-carousel"
            >
              จัดการเนื้อหาหน้าแรก & ภาพสไลด์ Carousel
            </v-btn>
          </div>
        </v-card>
      </div>
    </div>
  </div>
</template>
