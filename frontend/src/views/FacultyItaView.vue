<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import PageHeroBanner from '@/components/PageHeroBanner.vue'
import { api, type ItaItem, type ItaYear } from '@/services/api'

const selectedYear = ref('2569')
const years = ref<ItaYear[]>([])
const searchQuery = ref('')
const activeFilter = ref('2569')
const items = ref<ItaItem[]>([])
const loading = ref(false)
const yearsLoading = ref(false)

const loadYears = async () => {
  yearsLoading.value = true
  try {
    const data = await api.getItaYears({ active_only: true })
    years.value = data
    if (data.length > 0) {
      // Default to the first active year if not already selected
      if (!data.some((y) => y.year === selectedYear.value)) {
        selectedYear.value = data[0].year
        activeFilter.value = data[0].year
      }
    }
  } catch (err) {
    console.warn('Failed to load ITA years from API:', err)
    years.value = [
      { id: 1, year: '2569', title: 'ปีงบประมาณ พ.ศ. 2569', is_active: true, sort_order: 1 },
      { id: 2, year: '2568', title: 'ปีงบประมาณ พ.ศ. 2568', is_active: true, sort_order: 2 },
    ]
  } finally {
    yearsLoading.value = false
  }
}

const loadItaData = async () => {
  loading.value = true
  try {
    const data = await api.getIta({ year: activeFilter.value })
    items.value = data
  } catch (err) {
    console.warn('Failed to load ITA items from API:', err)
  } finally {
    loading.value = false
  }
}

const handleSearch = () => {
  activeFilter.value = selectedYear.value
  loadItaData()
}

onMounted(async () => {
  await loadYears()
  await loadItaData()
})

watch(activeFilter, () => {
  loadItaData()
})

const currentItems = computed(() => {
  if (!searchQuery.value.trim()) return items.value
  const q = searchQuery.value.trim().toLowerCase()
  return items.value.filter((item) => {
    let compText = ''
    if (typeof item.components === 'string') {
      compText = item.components.replace(/<[^>]*>/g, '').toLowerCase()
    } else if (Array.isArray(item.components)) {
      compText = item.components
        .map((c) => c.text + ' ' + (c.subnotes || []).join(' '))
        .join(' ')
        .toLowerCase()
    }
    return (
      item.code.toLowerCase().includes(q) ||
      item.indicator.toLowerCase().includes(q) ||
      compText.includes(q)
    )
  })
})
</script>

<template>
  <div class="min-h-screen bg-white">
    <!-- Hero Banner with Logo -->
    <PageHeroBanner
      badge="ITA Assessment"
      badge-icon="mdi-shield-check-outline"
      title="การประเมินคุณธรรมและความโปร่งใส"
      title-highlight="(ITA)"
      subtitle="การเปิดเผยข้อมูลสาธารณะ (Open Data Integrity and Transparency Assessment: OIT) คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์"
    />

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-8">
      <!-- Search & Filter Bar -->
      <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <!-- Year Select Dropdown -->
        <div class="relative w-52 sm:w-56">
          <select
            v-model="selectedYear"
            class="w-full appearance-none bg-white border border-slate-300 hover:border-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 text-sm font-semibold rounded-lg px-4 py-2.5 pr-9 cursor-pointer transition-all shadow-xs"
          >
            <option v-for="y in years" :key="y.id" :value="y.year">
              {{ y.title || `ปี พ.ศ. ${y.year}` }}
            </option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
            <v-icon icon="mdi-chevron-down" size="18" />
          </div>
        </div>

        <!-- Search Input -->
        <input
          v-model="searchQuery"
          type="text"
          placeholder="ค้นหาตัวชี้วัดหรือข้อความ..."
          class="w-full sm:w-72 bg-white border border-slate-300 hover:border-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 text-sm rounded-lg px-4 py-2.5 transition-all shadow-xs"
          @keydown.enter="handleSearch"
        />

        <!-- Search Button -->
        <button
          type="button"
          class="bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white font-medium text-sm rounded-lg px-5 py-2.5 flex items-center justify-center gap-2 shadow-xs transition-colors cursor-pointer"
          @click="handleSearch"
        >
          <v-icon icon="mdi-magnify" size="18" />
          <span>ค้นหา</span>
        </button>
      </div>

      <!-- ITA Table -->
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div v-if="loading" class="py-20 text-center">
          <v-icon icon="mdi-loading" size="36" class="animate-spin text-emerald-600 mb-2" />
          <p class="text-xs text-slate-500">กำลังโหลดตัวชี้วัด ITA ประจำปี {{ activeFilter }}...</p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
              <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-900 text-sm font-bold">
                <th scope="col" class="py-4 px-6 text-center w-20">ลำดับ</th>
                <th scope="col" class="py-4 px-6 w-60">ตัวชี้วัด/ประเด็น คำถาม</th>
                <th scope="col" class="py-4 px-6">องค์ประกอบด้านข้อมูล</th>
                <th scope="col" class="py-4 px-6 w-56 text-right">ลิงก์หรือ URL ข้อมูล</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr
                v-for="item in currentItems"
                :key="item.id"
                class="hover:bg-slate-50/50 transition-colors align-top"
              >
                <!-- ลำดับ (O1, O2...) -->
                <td class="py-5 px-6 text-center">
                  <span class="text-base font-bold text-emerald-800">
                    {{ item.code }}
                  </span>
                </td>

                <!-- ตัวชี้วัด/ประเด็น คำถาม -->
                <td class="py-5 px-6">
                  <h3 class="text-sm font-bold text-slate-900 leading-snug">
                    {{ item.indicator }}
                  </h3>
                </td>

                <!-- องค์ประกอบด้านข้อมูล (RichText HTML or Legacy Array) -->
                <td class="py-5 px-6 text-xs sm:text-sm text-slate-700">
                  <!-- RichText HTML rendering -->
                  <div
                    v-if="typeof item.components === 'string' && item.components"
                    class="prose prose-sm max-w-none text-slate-700 leading-relaxed font-normal ita-components-html"
                    v-html="item.components"
                  ></div>

                  <!-- Legacy Array fallback -->
                  <div
                    v-else-if="Array.isArray(item.components) && item.components.length"
                    class="space-y-2.5"
                  >
                    <div
                      v-for="(comp, cIdx) in item.components"
                      :key="cIdx"
                      class="space-y-1"
                    >
                      <!-- Main bullet -->
                      <div class="flex items-start gap-2 leading-relaxed">
                        <span class="text-slate-500 font-bold leading-none mt-1 shrink-0 text-xs">○</span>
                        <span>{{ comp.text }}</span>
                      </div>

                      <!-- Subnotes / Guidelines -->
                      <div
                        v-if="comp.subnotes && comp.subnotes.length"
                        class="pl-4 space-y-0.5 text-xs text-slate-500"
                      >
                        <p
                          v-for="(note, nIdx) in comp.subnotes"
                          :key="nIdx"
                          class="leading-relaxed"
                        >
                          {{ note }}
                        </p>
                      </div>
                    </div>
                  </div>

                  <div v-else class="text-xs text-slate-400 italic">
                    -
                  </div>
                </td>

                <!-- ลิงก์หรือ URL ข้อมูล -->
                <td class="py-5 px-6 text-right">
                  <div class="flex flex-col items-end gap-2">
                    <template v-if="item.links && item.links.length">
                      <template v-for="(link, lIdx) in item.links" :key="lIdx">
                        <RouterLink
                          v-if="link.type === 'internal'"
                          :to="link.url"
                          class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 hover:text-emerald-900 hover:underline leading-tight"
                        >
                          <v-icon icon="mdi-link-variant" size="14" />
                          <span>{{ link.title }}</span>
                        </RouterLink>

                        <a
                          v-else
                          :href="link.url"
                          target="_blank"
                          rel="noopener noreferrer"
                          class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 hover:text-emerald-900 hover:underline leading-tight"
                        >
                          <v-icon :icon="link.type === 'pdf' ? 'mdi-file-pdf-box' : 'mdi-open-in-new'" size="14" class="text-rose-600" />
                          <span>{{ link.title }}</span>
                        </a>
                      </template>
                    </template>
                    <span v-else class="text-xs text-slate-400 italic">ไม่มีลิงก์แนบ</span>
                  </div>
                </td>
              </tr>

              <tr v-if="!currentItems.length">
                <td colspan="4" class="py-16 text-center text-slate-400">
                  <v-icon icon="mdi-shield-outline" size="40" class="mb-2 opacity-40" />
                  <p class="text-sm">ไม่พบข้อมูลตัวชี้วัด ITA ประจำปี {{ activeFilter }}</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
:deep(.ita-components-html p) {
  margin-bottom: 0.5rem;
}
:deep(.ita-components-html p:last-child) {
  margin-bottom: 0;
}
:deep(.ita-components-html ul) {
  list-style-type: disc;
  padding-left: 1.25rem;
  margin-top: 0.35rem;
  margin-bottom: 0.5rem;
}
:deep(.ita-components-html ol) {
  list-style-type: decimal;
  padding-left: 1.25rem;
  margin-top: 0.35rem;
  margin-bottom: 0.5rem;
}
:deep(.ita-components-html li) {
  margin-bottom: 0.25rem;
  line-height: 1.5;
}
:deep(.ita-components-html strong) {
  color: #1e293b;
  font-weight: 700;
}
:deep(.ita-components-html a) {
  color: #047857;
  text-decoration: underline;
  font-weight: 600;
}
</style>
