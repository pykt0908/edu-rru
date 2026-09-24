<script setup lang="ts">
import { ref, onMounted } from 'vue'
import PageHeroBanner from '@/components/PageHeroBanner.vue'
import { api, type HistoryItem } from '@/services/api'

const branches = [
  'การศึกษาปฐมวัย',
  'จิตวิทยาและการแนะแนว',
  'เทคโนโลยีการศึกษา',
  'ภาษาอังกฤษ',
  'ภาษาไทย',
  'สังคมศึกษา',
  'คณิตศาสตร์',
  'วิทยาศาสตร์ทั่วไป',
  'คอมพิวเตอร์ศึกษา',
  'ภาษาจีน',
]

const groups = [
  'พื้นฐานการศึกษาและบริหารการศึกษา',
  'ทดสอบและวัดผลการศึกษา',
  'หลักสูตรและการสอน',
  'พลศึกษาและนันทนาการ',
]

const historyItems = ref<HistoryItem[]>([])
const loading = ref(true)

const loadHistory = async () => {
  try {
    const data = await api.getHistory()
    if (data && data.length) {
      historyItems.value = data
    }
  } catch (err) {
    console.warn('Failed to load history from API, fallback to default:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadHistory()
})

const isMilestone = (year: string) => year === '2535' || year === '2547'
</script>

<template>
  <div class="min-h-screen bg-white">
    <!-- Hero Banner -->
    <PageHeroBanner
      badge="Faculty History"
      badge-icon="mdi-book-open-page-variant-outline"
      title="ประวัติ"
      title-highlight="คณะครุศาสตร์"
      subtitle="กว่า 80 ปีแห่งการสร้างครูดีมีคุณภาพสู่สังคมไทย ตั้งแต่ พ.ศ. 2483 จนถึงปัจจุบัน"
    />

    <!-- Timeline Content -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
      <div v-if="loading" class="py-20 text-center">
        <v-icon icon="mdi-loading" size="40" class="animate-spin text-emerald-600 mb-3" />
        <p class="text-sm text-slate-500 font-medium">กำลังโหลดข้อมูลประวัติคณะ...</p>
      </div>

      <!-- Timeline wrapper -->
      <div v-else class="relative">
        <!-- Vertical line -->
        <div class="hidden sm:block absolute left-[calc(theme(spacing.24)+theme(spacing.4))] top-0 bottom-0 w-px bg-gradient-to-b from-emerald-400/80 via-emerald-500/40 to-transparent" />

        <div class="space-y-10 sm:space-y-12">
          <div
            v-for="item in historyItems"
            :key="item.id"
            class="flex flex-col sm:flex-row gap-4 sm:gap-8 group"
          >
            <!-- Year -->
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. {{ item.year }}</span>
            </div>

            <!-- Content -->
            <div class="relative sm:pl-8 flex-1">
              <div
                class="hidden sm:flex absolute -left-[5px] top-1 rounded-full ring-4 shadow"
                :class="isMilestone(item.year) ? 'w-4 h-4 bg-emerald-500 ring-emerald-100' : 'w-3 h-3 bg-emerald-500 ring-white'"
              />

              <!-- Milestone Card -->
              <div
                v-if="isMilestone(item.year)"
                class="bg-emerald-900 border border-emerald-700/60 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-lg group-hover:shadow-emerald-900/30"
              >
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider mb-2">
                  <v-icon icon="mdi-crown-outline" size="12" />
                  จุดสำคัญ
                </div>
                <h3 class="font-bold text-white text-sm sm:text-base mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0" />
                  {{ item.title }}
                </h3>
                <p class="text-emerald-200/80 text-sm leading-relaxed">
                  {{ item.detail }}
                </p>
              </div>

              <!-- Standard Card -->
              <div
                v-else
                class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md"
              >
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  {{ item.title }}
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-3">
                  {{ item.detail }}
                </p>

                <!-- If year 2550, render branches and groups info -->
                <div v-if="item.year === '2550'" class="mt-4 pt-4 border-t border-slate-200">
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <p class="text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">10 สาขาวิชา</p>
                      <ul class="space-y-1.5">
                        <li v-for="s in branches" :key="s" class="flex items-start gap-1.5 text-xs text-slate-600">
                          <v-icon icon="mdi-circle-small" size="16" class="text-emerald-500 shrink-0 mt-0.5" />
                          {{ s }}
                        </li>
                      </ul>
                    </div>
                    <div>
                      <p class="text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">4 กลุ่มวิชา</p>
                      <ul class="space-y-1.5">
                        <li v-for="g in groups" :key="g" class="flex items-start gap-1.5 text-xs text-slate-600">
                          <v-icon icon="mdi-circle-small" size="16" class="text-emerald-500 shrink-0 mt-0.5" />
                          {{ g }}
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
