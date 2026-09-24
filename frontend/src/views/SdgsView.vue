<script setup lang="ts">
import { ref, computed } from 'vue'
import { SDG_GOALS, SDG_ACTIVITIES, type SdgGoal, type SdgActivity } from '@/data/sdgsData'
import sdgsHeroPlant from '@/assets/sdgs_hero_plant.jpg'

// Official UN SDG Logos (1 to 17)
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

// Language toggle state: 'th' | 'en'
const currentLang = ref<'th' | 'en'>('th')

// Selected SDG goal (default: 1 as per design mockup)
const selectedGoalId = ref<number>(1)

// Selected category filter
const selectedCategory = ref<string>('all')

// Selected goal computed object
const selectedGoal = computed<SdgGoal | undefined>(() => {
  return SDG_GOALS.find((g) => g.id === selectedGoalId.value)
})

// Filtered activities for current selected goal
const filteredActivities = computed<SdgActivity[]>(() => {
  let list = SDG_ACTIVITIES.filter((a) => a.sdgId === selectedGoalId.value)
  if (selectedCategory.value !== 'all') {
    list = list.filter((a) =>
      currentLang.value === 'th'
        ? a.categoryTh.includes(selectedCategory.value)
        : a.categoryEn.toLowerCase().includes(selectedCategory.value.toLowerCase())
    )
  }
  return list
})

// Total count of activities for current goal
const goalActivitiesTotal = computed<number>(() => {
  return SDG_ACTIVITIES.filter((a) => a.sdgId === selectedGoalId.value).length
})

// Handle selecting a goal with smooth scroll
const selectGoal = (id: number) => {
  selectedGoalId.value = id
  selectedCategory.value = 'all'
  const section = document.getElementById('sdg-activities-section')
  if (section) {
    section.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}
</script>

<template>
  <div class="min-h-screen bg-slate-50/70 pb-24 text-slate-800 antialiased">
    <!-- Top Hero Banner (Minimal Dark Green with subtle organic glow) -->
    <section class="relative bg-[#0E351E] text-white pt-10 sm:pt-14 pb-20 sm:pb-24 overflow-hidden">
      <!-- Minimal subtle ambient gradient -->
      <div class="pointer-events-none absolute inset-0">
        <div class="absolute -top-32 -left-32 w-[32rem] h-[32rem] rounded-full bg-emerald-600/15 blur-3xl" />
        <div class="absolute -bottom-24 right-0 w-[28rem] h-[28rem] rounded-full bg-emerald-400/10 blur-3xl" />
      </div>

      <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
          <!-- Banner Title & Subtitle -->
          <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white mb-2">
              {{ currentLang === 'th' ? 'เป้าหมายการพัฒนาที่ยั่งยืน (SDGs)' : 'Sustainable Development Goals (SDGs)' }}
            </h1>
            <p class="text-emerald-200/90 text-xs sm:text-sm font-medium tracking-wide">
              {{ currentLang === 'th' ? 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์' : 'Faculty of Education, Rajabhat Rajanagarinda University' }}
            </p>
          </div>

          <!-- Minimal Language Switcher Pill (TH | EN) -->
          <div class="self-start sm:self-center shrink-0">
            <div class="inline-flex items-center p-0.5 rounded-full bg-black/25 border border-white/15 backdrop-blur-md">
              <button
                type="button"
                class="px-3.5 py-1 text-xs font-semibold rounded-full transition-all duration-150 cursor-pointer"
                :class="currentLang === 'th' ? 'bg-white text-[#0E351E] shadow-sm font-bold' : 'text-emerald-100/80 hover:text-white'"
                @click="currentLang = 'th'"
              >
                TH
              </button>
              <button
                type="button"
                class="px-3.5 py-1 text-xs font-semibold rounded-full transition-all duration-150 cursor-pointer"
                :class="currentLang === 'en' ? 'bg-white text-[#0E351E] shadow-sm font-bold' : 'text-emerald-100/80 hover:text-white'"
                @click="currentLang = 'en'"
              >
                EN
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content Container -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- White Overview Card ("SDGs คืออะไร?") -->
      <section class="relative -mt-12 sm:-mt-16 mb-12 sm:mb-16 z-20">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg shadow-slate-900/5 border border-slate-100/90 p-6 sm:p-8 lg:p-10 transition-all">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-center">
            <!-- Left: Text Content -->
            <div class="lg:col-span-7">
              <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#0E351E] mb-3.5 tracking-tight leading-snug">
                {{ currentLang === 'th' ? 'เป้าหมายการพัฒนาที่ยั่งยืน (SDGs) คืออะไร?' : 'What are the Sustainable Development Goals (SDGs)?' }}
              </h2>
              <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-normal text-justify">
                {{ currentLang === 'th'
                  ? 'เป้าหมายการพัฒนาที่ยั่งยืน (Sustainable Development Goals: SDGs) เป็นเป้าหมายร่วมกันระดับโลกที่ได้รับการรับรองจากองค์การสหประชาชาติ (UN) เพื่อเป็นแนวทางในการขจัดความยากจน ปกป้องโลก และสร้างหลักประกันว่าทุกคนจะได้รับความสันติสุขและความมั่งคั่งภายในปี 2573 โดยมีเป้าหมายทั้งหมด 17 ข้อ ครอบคลุมทั้งมิติเศรษฐกิจ สังคม และสิ่งแวดล้อมอย่างรอบด้าน'
                  : 'The Sustainable Development Goals (SDGs) are a universal call to action adopted by the United Nations to end poverty, protect the planet, and ensure that by 2030 all people enjoy peace and prosperity. The 17 integrated goals balance social, economic and environmental sustainability.'
                }}
              </p>

              <!-- Minimal Info Tags -->
              <div class="mt-5 flex flex-wrap items-center gap-2 pt-4 border-t border-slate-100 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-600 font-medium border border-slate-200/80">
                  <v-icon icon="mdi-earth" size="14" class="text-emerald-700" />
                  <span>17 Global Goals (2016-2030)</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-600 font-medium border border-slate-200/80">
                  <v-icon icon="mdi-school-outline" size="14" class="text-emerald-700" />
                  <span>Education for Sustainable Future</span>
                </span>
              </div>
            </div>

            <!-- Right: Real Image with Minimal Frame -->
            <div class="lg:col-span-5">
              <div class="relative overflow-hidden rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 group">
                <img
                  :src="sdgsHeroPlant"
                  alt="ภาพสัญลักษณ์ความยั่งยืน มือโอบอุ้มต้นกล้าและพลังงานสะอาด"
                  class="w-full h-56 sm:h-64 object-cover object-center group-hover:scale-102 transition-transform duration-500 ease-out"
                />
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Section Title: "คลิกที่เป้าหมายเพื่อแสดงข่าวสารและกิจกรรมที่เกี่ยวข้อง" -->
      <section class="text-center mb-8 sm:mb-10">
        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800 tracking-tight">
          {{ currentLang === 'th' ? 'คลิกที่เป้าหมายเพื่อแสดงข่าวสารและกิจกรรมที่เกี่ยวข้อง' : 'Click on a goal to explore related news and activities' }}
        </h3>
        <div class="w-12 h-1 bg-[#0E351E] rounded-full mx-auto mt-2.5 opacity-90" />
      </section>

      <!-- 17 Official UN SDG Logos Grid (Arranged cleanly like the mockup) -->
      <section class="mb-14 sm:mb-16">
        <!-- Row 1: Goals 1 to 11 on desktop -->
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-2 sm:gap-2.5 mb-2 sm:mb-2.5">
          <button
            v-for="goal in SDG_GOALS.slice(0, 11)"
            :key="goal.id"
            type="button"
            class="group relative aspect-square rounded-xl sm:rounded-2xl overflow-hidden transition-all duration-200 cursor-pointer shadow-xs hover:shadow-md hover:-translate-y-0.5 select-none border-0 p-0 bg-white"
            :class="[
              selectedGoalId === goal.id
                ? 'ring-4 ring-offset-2 ring-emerald-600 scale-[1.04] z-10 shadow-lg'
                : 'opacity-95 hover:opacity-100'
            ]"
            :aria-label="goal.titleTh"
            @click="selectGoal(goal.id)"
          >
            <!-- Checkmark badge when active -->
            <div
              v-if="selectedGoalId === goal.id"
              class="absolute top-1 right-1 w-5 h-5 sm:w-5.5 sm:h-5.5 rounded-full bg-white text-emerald-700 shadow-md flex items-center justify-center border-2 border-emerald-600 z-20"
            >
              <v-icon icon="mdi-check-bold" size="11" />
            </div>

            <!-- Official UN SDG Logo Image -->
            <img
              :src="sdgLogos[goal.id]"
              :alt="goal.titleEn"
              class="w-full h-full object-cover rounded-xl sm:rounded-2xl"
              loading="lazy"
            />
          </button>
        </div>

        <!-- Row 2: Goals 12 to 17 on desktop -->
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 sm:gap-2.5 max-w-[calc(6/11*100%)] max-lg:max-w-none">
          <button
            v-for="goal in SDG_GOALS.slice(11, 17)"
            :key="goal.id"
            type="button"
            class="group relative aspect-square rounded-xl sm:rounded-2xl overflow-hidden transition-all duration-200 cursor-pointer shadow-xs hover:shadow-md hover:-translate-y-0.5 select-none border-0 p-0 bg-white"
            :class="[
              selectedGoalId === goal.id
                ? 'ring-4 ring-offset-2 ring-emerald-600 scale-[1.04] z-10 shadow-lg'
                : 'opacity-95 hover:opacity-100'
            ]"
            :aria-label="goal.titleTh"
            @click="selectGoal(goal.id)"
          >
            <!-- Checkmark badge when active -->
            <div
              v-if="selectedGoalId === goal.id"
              class="absolute top-1 right-1 w-5 h-5 sm:w-5.5 sm:h-5.5 rounded-full bg-white text-emerald-700 shadow-md flex items-center justify-center border-2 border-emerald-600 z-20"
            >
              <v-icon icon="mdi-check-bold" size="11" />
            </div>

            <!-- Official UN SDG Logo Image -->
            <img
              :src="sdgLogos[goal.id]"
              :alt="goal.titleEn"
              class="w-full h-full object-cover rounded-xl sm:rounded-2xl"
              loading="lazy"
            />
          </button>
        </div>
      </section>

      <!-- Selected Goal News & Activities Section -->
      <section id="sdg-activities-section" class="scroll-mt-28">
        <!-- Minimal Active Goal Summary Card -->
        <div
          v-if="selectedGoal"
          class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-sm p-5 sm:p-6 mb-8 transition-all duration-200"
        >
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
              <!-- Real Official SDG Icon Thumbnail -->
              <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0 rounded-xl overflow-hidden shadow-xs border border-slate-100">
                <img
                  :src="sdgLogos[selectedGoal.id]"
                  :alt="selectedGoal.titleEn"
                  class="w-full h-full object-cover"
                />
              </div>

              <!-- Title & Description -->
              <div class="min-w-0">
                <div class="flex items-center gap-2 mb-1">
                  <span
                    class="px-2.5 py-0.5 rounded-md text-[11px] font-bold text-white uppercase tracking-wider"
                    :style="{ backgroundColor: selectedGoal.color }"
                  >
                    GOAL {{ selectedGoal.numberStr }}
                  </span>
                  <span class="text-xs font-semibold text-slate-400">
                    {{ selectedGoal.titleEn }}
                  </span>
                </div>
                <h4 class="text-lg sm:text-xl font-bold text-slate-900 leading-snug">
                  {{ currentLang === 'th' ? selectedGoal.titleTh : selectedGoal.titleEn }}
                </h4>
                <p class="text-slate-500 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                  {{ currentLang === 'th' ? selectedGoal.descTh : selectedGoal.descEn }}
                </p>
              </div>
            </div>

            <!-- Counter Pill -->
            <div class="shrink-0 flex items-center sm:self-center">
              <div class="px-4 py-2 rounded-xl bg-slate-50 border border-slate-200/80 text-center min-w-[120px]">
                <span class="text-xl font-extrabold text-[#0E351E] block leading-none">
                  {{ goalActivitiesTotal }}
                </span>
                <span class="text-[10px] text-slate-500 font-medium mt-0.5 block">
                  {{ currentLang === 'th' ? 'กิจกรรมที่เกี่ยวข้อง' : 'Activities' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Filter bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200">
          <div class="flex items-center gap-2">
            <span class="text-sm font-bold text-slate-800">
              {{ currentLang === 'th' ? 'ข่าวสารและโครงการที่เกี่ยวข้อง' : 'Related News & Projects' }}
            </span>
            <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">
              {{ filteredActivities.length }} {{ currentLang === 'th' ? 'รายการ' : 'items' }}
            </span>
          </div>

          <!-- Minimal Category Filter Pills -->
          <div class="flex flex-wrap gap-1.5">
            <button
              type="button"
              class="px-3 py-1.5 text-xs font-medium rounded-full transition-all duration-150 cursor-pointer"
              :class="selectedCategory === 'all' ? 'bg-[#0E351E] text-white shadow-xs font-semibold' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/90'"
              @click="selectedCategory = 'all'"
            >
              {{ currentLang === 'th' ? 'ทั้งหมด' : 'All' }}
            </button>
            <button
              type="button"
              class="px-3 py-1.5 text-xs font-medium rounded-full transition-all duration-150 cursor-pointer"
              :class="selectedCategory === 'บริการวิชาการ' ? 'bg-[#0E351E] text-white shadow-xs font-semibold' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/90'"
              @click="selectedCategory = 'บริการวิชาการ'"
            >
              {{ currentLang === 'th' ? 'บริการวิชาการ' : 'Academic Service' }}
            </button>
            <button
              type="button"
              class="px-3 py-1.5 text-xs font-medium rounded-full transition-all duration-150 cursor-pointer"
              :class="selectedCategory === 'วิจัย' ? 'bg-[#0E351E] text-white shadow-xs font-semibold' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/90'"
              @click="selectedCategory = 'วิจัย'"
            >
              {{ currentLang === 'th' ? 'งานวิจัย & นวัตกรรม' : 'Research & Innovation' }}
            </button>
            <button
              type="button"
              class="px-3 py-1.5 text-xs font-medium rounded-full transition-all duration-150 cursor-pointer"
              :class="selectedCategory === 'กิจกรรม' ? 'bg-[#0E351E] text-white shadow-xs font-semibold' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/90'"
              @click="selectedCategory = 'กิจกรรม'"
            >
              {{ currentLang === 'th' ? 'กิจกรรมพัฒนานักศึกษา' : 'Student Activities' }}
            </button>
          </div>
        </div>

        <!-- Activities Grid (Clean & Minimalist cards) -->
        <div v-if="filteredActivities.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
          <article
            v-for="act in filteredActivities"
            :key="act.id"
            class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all duration-200 flex flex-col group"
          >
            <!-- Card Image -->
            <div class="relative h-44 overflow-hidden bg-slate-100">
              <img
                :src="act.image"
                :alt="act.titleTh"
                class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500"
                loading="lazy"
              />
              <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1.5">
                <span
                  class="px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-xs"
                  :style="{ backgroundColor: selectedGoal?.color || '#0E351E' }"
                >
                  SDG {{ selectedGoal?.numberStr }}
                </span>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-black/60 text-white backdrop-blur-md">
                  {{ currentLang === 'th' ? act.categoryTh : act.categoryEn }}
                </span>
              </div>
            </div>

            <!-- Card Body -->
            <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
              <div>
                <div class="flex items-center gap-1.5 text-[11px] text-slate-400 mb-2">
                  <v-icon icon="mdi-calendar-blank-outline" size="13" />
                  <span>{{ act.date }}</span>
                  <span>•</span>
                  <span class="truncate">{{ act.author }}</span>
                </div>

                <h5 class="text-sm sm:text-base font-bold text-slate-800 group-hover:text-emerald-700 transition-colors leading-snug mb-2 line-clamp-2">
                  {{ currentLang === 'th' ? act.titleTh : act.titleEn }}
                </h5>

                <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                  {{ currentLang === 'th' ? act.summaryTh : act.summaryEn }}
                </p>
              </div>

              <!-- Card Action -->
              <div class="pt-3.5 mt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="font-semibold text-emerald-700 flex items-center gap-1 group-hover:gap-1.5 transition-all">
                  {{ currentLang === 'th' ? 'อ่านรายละเอียด' : 'Read more' }}
                  <v-icon icon="mdi-arrow-right" size="13" />
                </span>
                <span class="text-[10px] text-slate-400">คณะครุศาสตร์</span>
              </div>
            </div>
          </article>
        </div>

        <!-- Empty State (Clean Minimalist) -->
        <div
          v-else
          class="bg-white rounded-2xl border border-slate-200/80 p-8 sm:p-10 text-center my-6 shadow-xs"
        >
          <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center mx-auto mb-3">
            <v-icon icon="mdi-leaf" size="24" class="text-emerald-700" />
          </div>
          <h5 class="text-base font-bold text-slate-800 mb-1.5">
            {{ currentLang === 'th' ? 'อยู่ระหว่างการดำเนินโครงการและรวบรวมข้อมูล' : 'Initiatives In Progress' }}
          </h5>
          <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-5 leading-relaxed">
            {{ currentLang === 'th'
              ? 'คณะครุศาสตร์กำลังจัดเตรียมและดำเนินโครงการที่เกี่ยวข้องกับเป้าหมายนี้เพื่อขับเคลื่อนการศึกษาที่ยั่งยืน ท่านสามารถเลือกดูเป้าหมายอื่น ๆ ได้'
              : 'The Faculty of Education is actively conducting projects supporting this goal. Feel free to explore other goals.'
            }}
          </p>
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#0E351E] text-white text-xs font-semibold hover:bg-emerald-800 transition-colors shadow-xs cursor-pointer"
            @click="selectGoal(4)"
          >
            <v-icon icon="mdi-school" size="14" />
            <span>{{ currentLang === 'th' ? 'ดูเป้าหมายที่ 4: การศึกษาที่มีคุณภาพ' : 'View Goal 4: Quality Education' }}</span>
          </button>
        </div>
      </section>
    </div>
  </div>
</template>
