<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getSdgActivityById, getSdgGoalById, SDG_ACTIVITIES, type SdgActivity } from '@/data/sdgsData'

// Official UN SDG Logos
import sdg1 from '@/assets/sdgs/sdg-1.png'
import sdg2 from '@/assets/sdgs/sdg-2.png'
import sdg3 from '@/assets/sdgs/sdg-3.png'
import sdg4 from '@/assets/sdgs/sdg-4.png'
import sdg5 from '@/assets/sdgs/sdg-5.png'
import sdg6 from '@/assets/sdgs/sdg-6.png'
import sdg7 from '@/assets/sdgs/sdg-7.png'
import sdg8 from '@/assets/sdgs/sdg-8.png'
import sdg9 from '@/assets/sdgs/sdg-9.png'
import sdg10 from '@/assets/sdgs/sdg-10.png'
import sdg11 from '@/assets/sdgs/sdg-11.png'
import sdg12 from '@/assets/sdgs/sdg-12.png'
import sdg13 from '@/assets/sdgs/sdg-13.png'
import sdg14 from '@/assets/sdgs/sdg-14.png'
import sdg15 from '@/assets/sdgs/sdg-15.png'
import sdg16 from '@/assets/sdgs/sdg-16.png'
import sdg17 from '@/assets/sdgs/sdg-17.png'

const sdgLogos: Record<number, string> = {
  1: sdg1, 2: sdg2, 3: sdg3, 4: sdg4, 5: sdg5, 6: sdg6, 7: sdg7,
  8: sdg8, 9: sdg9, 10: sdg10, 11: sdg11, 12: sdg12, 13: sdg13,
  14: sdg14, 15: sdg15, 16: sdg16, 17: sdg17
}

const route = useRoute()
const router = useRouter()

// Get activity by route ID
const activity = computed<SdgActivity | undefined>(() => {
  const id = route.params.id as string
  return getSdgActivityById(id)
})

// Parent SDG Goal
const parentGoal = computed(() => {
  if (!activity.value) return undefined
  return getSdgGoalById(activity.value.sdgId)
})

// Related SDG activities
const relatedActivities = computed(() => {
  if (!activity.value) return []
  return SDG_ACTIVITIES.filter((a) => a.id !== activity.value?.id).slice(0, 3)
})

// Copy link state
const copyFeedback = ref(false)

const copyCurrentUrl = async () => {
  try {
    await navigator.clipboard.writeText(window.location.href)
    copyFeedback.value = true
    setTimeout(() => {
      copyFeedback.value = false
    }, 2000)
  } catch {
    copyFeedback.value = true
    setTimeout(() => {
      copyFeedback.value = false
    }, 2000)
  }
}

const shareToFacebook = () => {
  const url = encodeURIComponent(window.location.href)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400')
}

const shareToLine = () => {
  const url = encodeURIComponent(window.location.href)
  window.open(`https://social-plugins.line.me/lineit/share?url=${url}`, '_blank', 'width=600,height=500')
}
</script>

<template>
  <div class="min-h-screen bg-slate-50/50 pb-24 text-slate-800 antialiased">
    <!-- If Activity Found -->
    <template v-if="activity">
      <!-- Minimalist Breadcrumb Bar -->
      <div class="bg-white border-b border-slate-200/80">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
          <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap scrollbar-none">
            <RouterLink to="/" class="hover:text-emerald-700 transition-colors flex items-center gap-1 no-underline">
              <v-icon icon="mdi-home-outline" size="14" />
              <span>หน้าแรก</span>
            </RouterLink>
            <span class="text-slate-300">/</span>
            <RouterLink to="/sdgs" class="hover:text-emerald-700 transition-colors no-underline">
              <span>SDGs เป้าหมายการพัฒนาที่ยั่งยืน</span>
            </RouterLink>
            <span class="text-slate-300">/</span>
            <span v-if="parentGoal" class="text-emerald-700 font-medium">
              SDG {{ parentGoal.numberStr }}
            </span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-800 font-medium truncate max-w-xs sm:max-w-md">
              {{ activity.titleTh }}
            </span>
          </nav>
        </div>
      </div>

      <!-- Main Article Container -->
      <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12">
        <article class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-10 lg:p-12 overflow-hidden">
          <!-- SDG Goal Alignment Header -->
          <div class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
              <!-- Linked SDG Badge -->
              <RouterLink
                to="/sdgs"
                class="inline-flex items-center gap-2.5 p-1.5 pr-4 rounded-xl border border-slate-200 hover:border-emerald-600 transition-all no-underline bg-white shadow-xs group"
              >
                <div class="w-10 h-10 rounded-lg overflow-hidden shrink-0 shadow-xs">
                  <img
                    :src="sdgLogos[activity.sdgId]"
                    :alt="'SDG ' + activity.sdgId"
                    class="w-full h-full object-cover"
                  />
                </div>
                <div>
                  <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                    ขับเคลื่อนเป้าหมาย
                  </span>
                  <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 transition-colors block">
                    SDG {{ activity.sdgId }}: {{ parentGoal?.titleTh }}
                  </span>
                </div>
              </RouterLink>

              <!-- Category Badge -->
              <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                {{ activity.categoryTh }}
              </span>
            </div>

            <!-- Main Title -->
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-snug sm:leading-tight mb-4">
              {{ activity.titleTh }}
            </h1>
            <h2 class="text-sm sm:text-base font-medium text-slate-500 mb-6">
              {{ activity.titleEn }}
            </h2>

            <!-- Author & Metadata Row -->
            <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-slate-100">
              <div class="flex items-center gap-2 text-xs text-slate-600">
                <v-icon icon="mdi-account-circle-outline" size="18" class="text-emerald-700" />
                <span class="font-semibold">{{ activity.author }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-500 flex items-center gap-1">
                  <v-icon icon="mdi-calendar-blank-outline" size="14" />
                  {{ activity.date }}
                </span>
              </div>

              <!-- Share Buttons -->
              <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 font-medium hidden sm:inline">แชร์:</span>
                <button
                  type="button"
                  class="w-8 h-8 rounded-full bg-[#1877F2]/10 text-[#1877F2] hover:bg-[#1877F2] hover:text-white transition-all flex items-center justify-center cursor-pointer"
                  title="แชร์ไปยัง Facebook"
                  @click="shareToFacebook"
                >
                  <v-icon icon="mdi-facebook" size="16" />
                </button>
                <button
                  type="button"
                  class="w-8 h-8 rounded-full bg-[#06C755]/10 text-[#06C755] hover:bg-[#06C755] hover:text-white transition-all flex items-center justify-center cursor-pointer"
                  title="แชร์ไปยัง LINE"
                  @click="shareToLine"
                >
                  <v-icon icon="mdi-chat" size="16" />
                </button>
                <button
                  type="button"
                  class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all flex items-center justify-center cursor-pointer"
                  title="คัดลอกลิงก์"
                  @click="copyCurrentUrl"
                >
                  <v-icon :icon="copyFeedback ? 'mdi-check' : 'mdi-link-variant'" size="16" />
                </button>
              </div>
            </div>

            <!-- Toast feedback -->
            <div v-if="copyFeedback" class="mt-2 text-xs text-emerald-700 font-semibold flex items-center gap-1">
              <v-icon icon="mdi-check-circle" size="14" />
              <span>คัดลอกลิงก์เรียบร้อยแล้ว</span>
            </div>
          </div>

          <!-- Featured Hero Image -->
          <div class="relative overflow-hidden rounded-2xl shadow-sm border border-slate-100 mb-8 sm:mb-10">
            <img
              :src="activity.image"
              :alt="activity.titleTh"
              class="w-full h-[280px] sm:h-[400px] lg:h-[460px] object-cover"
            />
          </div>

          <!-- UN SDG Goal Alignment Box -->
          <div
            v-if="parentGoal"
            class="rounded-2xl p-6 sm:p-7 mb-8 border border-slate-200/90 bg-gradient-to-r from-slate-50 to-emerald-50/30"
          >
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
              <div class="w-16 h-16 rounded-xl overflow-hidden shadow-xs shrink-0 border border-slate-200">
                <img :src="sdgLogos[parentGoal.id]" :alt="parentGoal.titleEn" class="w-full h-full object-cover" />
              </div>
              <div class="flex-1 min-w-0">
                <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block mb-1">
                  การตอบสนองต่อเป้าหมายความยั่งยืนระดับสากล
                </span>
                <h4 class="text-base sm:text-lg font-bold text-slate-900 leading-snug">
                  {{ parentGoal.titleTh }} ({{ parentGoal.titleEn }})
                </h4>
                <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed">
                  {{ parentGoal.descTh }}
                </p>
              </div>
            </div>
          </div>

          <!-- Article Content -->
          <div class="space-y-6 text-slate-700 text-base sm:text-lg leading-relaxed font-normal">
            <p class="text-justify leading-relaxed font-medium text-slate-800">
              {{ activity.summaryTh }}
            </p>
            <p class="text-justify leading-relaxed">
              โครงการนี้เป็นส่วนหนึ่งของยุทธศาสตร์การขับเคลื่อนเป้าหมายการพัฒนาที่ยั่งยืน (SDGs) ของคณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์ ซึ่งมุ่งเน้นการนำองค์ความรู้ นวัตกรรมทางการศึกษา และการมีส่วนร่วมของคณาจารย์และนักศึกษาไปแก้ปัญหาและยกระดับคุณภาพชีวิตในระดับท้องถิ่น
            </p>
            <p class="text-justify leading-relaxed">
              คณะครุศาสตร์มุ่งมั่นดำเนินงานอย่างต่อเนื่องเพื่อสร้างผลกระทบเชิงบวก (Positive Impact) ต่อชุมชนและสังคม ตลอดจนปลูกฝังจิตสำนึกแห่งความยั่งยืนให้แก่นิสิตครูรุ่นใหม่ เพื่อนำไปส่งต่อให้แก่เยาวชนในสถานศึกษาต่อไป
            </p>
          </div>

          <!-- Impact Metrics Grid -->
          <div class="my-10 pt-8 border-t border-slate-100">
            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
              <v-icon icon="mdi-chart-areaspline" size="18" class="text-emerald-700" />
              <span>ผลสัมฤทธิ์และสถิติโครงการ</span>
            </h4>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                <span class="text-2xl font-black text-[#0E351E] block">100%</span>
                <span class="text-xs text-slate-500 font-medium mt-0.5 block">ความพึงพอใจของผู้เข้าร่วม</span>
              </div>
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                <span class="text-2xl font-black text-[#0E351E] block">ภาคตะวันออก</span>
                <span class="text-xs text-slate-500 font-medium mt-0.5 block">พื้นที่ดำเนินโครงการ</span>
              </div>
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-center col-span-2 sm:col-span-1">
                <span class="text-2xl font-black text-[#0E351E] block">ต่อเนื่อง</span>
                <span class="text-xs text-slate-500 font-medium mt-0.5 block">การติดตามผลระยะยาว</span>
              </div>
            </div>
          </div>

          <!-- Bottom Actions -->
          <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <RouterLink
              to="/sdgs"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0E351E] text-white text-xs font-semibold hover:bg-emerald-800 transition-colors shadow-xs no-underline"
            >
              <v-icon icon="mdi-view-grid-outline" size="16" />
              <span>ดูเป้าหมาย SDGs ทั้งหมด 17 ข้อ</span>
            </RouterLink>

            <button
              type="button"
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-emerald-700 cursor-pointer"
              @click="router.back()"
            >
              <v-icon icon="mdi-arrow-left" size="14" />
              <span>ย้อนกลับ</span>
            </button>
          </div>
        </article>

        <!-- Related SDG Projects -->
        <section v-if="relatedActivities.length > 0" class="mt-14 sm:mt-16">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg sm:text-xl font-bold text-slate-900">
              โครงการ SDGs อื่น ๆ ของคณะครุศาสตร์
            </h3>
            <RouterLink to="/sdgs" class="text-xs font-semibold text-emerald-700 hover:underline">
              ดูทั้งหมด
            </RouterLink>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <RouterLink
              v-for="rel in relatedActivities"
              :key="rel.id"
              :to="'/sdgs/post/' + rel.id"
              class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-md transition-all duration-200 flex flex-col no-underline group"
            >
              <div class="relative h-44 overflow-hidden bg-slate-100">
                <img
                  :src="rel.image"
                  :alt="rel.titleTh"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  loading="lazy"
                />
                <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                  <span class="px-2 py-0.5 rounded-md text-[10px] font-bold text-white bg-[#0E351E]">
                    SDG {{ rel.sdgId }}
                  </span>
                  <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-black/60 text-white backdrop-blur-xs">
                    {{ rel.categoryTh }}
                  </span>
                </div>
              </div>

              <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                <div>
                  <span class="text-[11px] text-slate-400 block mb-1.5">{{ rel.date }}</span>
                  <h4 class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 leading-snug">
                    {{ rel.titleTh }}
                  </h4>
                </div>

                <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-xs text-emerald-700 font-semibold">
                  <span>อ่านต่อ</span>
                  <v-icon icon="mdi-arrow-right" size="14" class="group-hover:translate-x-1 transition-transform" />
                </div>
              </div>
            </RouterLink>
          </div>
        </section>
      </main>
    </template>

    <!-- Not Found State -->
    <template v-else>
      <div class="max-w-xl mx-auto px-4 py-24 text-center">
        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
          <v-icon icon="mdi-file-question-outline" size="32" />
        </div>
        <h2 class="text-xl font-bold text-slate-800 mb-2">ไม่พบข้อมูลโครงการ SDGs ที่ต้องการ</h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-6">
          โครงการนี้อาจถูกย้ายหรือปรับปรุงข้อมูล สามารถกลับไปดูเป้าหมาย SDGs อื่น ๆ ได้
        </p>
        <RouterLink
          to="/sdgs"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-700 text-white text-xs font-semibold shadow-xs hover:bg-emerald-800 transition-colors no-underline"
        >
          <v-icon icon="mdi-earth" size="14" />
          <span>กลับสู่หน้า SDGs</span>
        </RouterLink>
      </div>
    </template>
  </div>
</template>
