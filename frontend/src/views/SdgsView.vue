<script setup lang="ts">
import { ref, computed } from 'vue'
import { SDG_GOALS, SDG_ACTIVITIES, type SdgGoal, type SdgActivity } from '@/data/sdgsData'
import sdgsHeroPlant from '@/assets/sdgs_hero_plant.jpg'

// Language toggle state: 'th' | 'en'
const currentLang = ref<'th' | 'en'>('th')

// Selected SDG goal (defaults to 1 like in the screenshot)
const selectedGoalId = ref<number>(1)

// Selected category filter
const selectedCategory = ref<string>('all')

// Selected goal object
const selectedGoal = computed<SdgGoal | undefined>(() => {
  return SDG_GOALS.find((g) => g.id === selectedGoalId.value)
})

// Filtered activities for current goal
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

// All activities count for current goal
const goalActivitiesTotal = computed<number>(() => {
  return SDG_ACTIVITIES.filter((a) => a.sdgId === selectedGoalId.value).length
})

// Handle selecting a goal
const selectGoal = (id: number) => {
  selectedGoalId.value = id
  selectedCategory.value = 'all'
  // Smooth scroll to activities section
  const section = document.getElementById('sdg-activities-section')
  if (section) {
    section.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}
</script>

<template>
  <div class="min-h-screen bg-slate-50/60 pb-20">
    <!-- Top Hero Banner (Deep Forest Green with Subtle Triangle Pattern) -->
    <div class="relative bg-[#104727] text-white pt-10 pb-16 sm:pb-20 overflow-hidden">
      <!-- Subtle geometric / triangular decorative SVG pattern -->
      <div class="absolute inset-0 opacity-[0.07] pointer-events-none" style="background-image: radial-gradient(#fff 1.5px, transparent 1.5px), radial-gradient(#fff 1.5px, #104727 1.5px); background-size: 36px 36px; background-position: 0 0, 18px 18px;" />
      
      <!-- Ambient light glows -->
      <div class="pointer-events-none absolute inset-0">
        <div class="absolute -top-24 -left-20 w-96 h-96 rounded-full bg-emerald-500/20 blur-3xl" />
        <div class="absolute -bottom-20 right-0 w-80 h-80 rounded-full bg-emerald-300/15 blur-3xl" />
      </div>

      <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <!-- Banner Title & Subtitle -->
          <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white mb-1.5 drop-shadow-xs">
              {{ currentLang === 'th' ? 'เป้าหมายการพัฒนาที่ยั่งยืน (SDGs)' : 'Sustainable Development Goals (SDGs)' }}
            </h1>
            <p class="text-emerald-100/85 text-xs sm:text-sm font-medium">
              {{ currentLang === 'th' ? 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์' : 'Faculty of Education, Rajabhat Rajanagarinda University' }}
            </p>
          </div>

          <!-- Language Toggle Pill (TH | EN) -->
          <div class="self-start sm:self-center shrink-0">
            <div class="inline-flex items-center p-1 rounded-full bg-[#0b331c]/80 border border-emerald-600/40 backdrop-blur-md shadow-inner">
              <button
                type="button"
                class="px-3.5 py-1 text-xs font-bold rounded-full transition-all duration-200 cursor-pointer"
                :class="currentLang === 'th' ? 'bg-white text-[#104727] shadow-sm' : 'text-emerald-200/90 hover:text-white'"
                @click="currentLang = 'th'"
              >
                TH
              </button>
              <button
                type="button"
                class="px-3.5 py-1 text-xs font-bold rounded-full transition-all duration-200 cursor-pointer"
                :class="currentLang === 'en' ? 'bg-white text-[#104727] shadow-sm' : 'text-emerald-200/90 hover:text-white'"
                @click="currentLang = 'en'"
              >
                EN
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- White Explanation Card ("SDGs คืออะไร?") -->
      <div class="relative -mt-10 sm:-mt-12 mb-12 sm:mb-16 z-20">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl shadow-slate-900/7 border border-slate-100 p-6 sm:p-8 lg:p-10 transition-all">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-center">
            <!-- Left: Text Content -->
            <div class="lg:col-span-7">
              <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#104727] mb-4 tracking-tight leading-snug">
                {{ currentLang === 'th' ? 'เป้าหมายการพัฒนาที่ยั่งยืน (SDGs) คืออะไร?' : 'What are the Sustainable Development Goals (SDGs)?' }}
              </h2>
              <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-normal text-justify">
                {{ currentLang === 'th'
                  ? 'เป้าหมายการพัฒนาที่ยั่งยืน (Sustainable Development Goals: SDGs) เป็นเป้าหมายร่วมกันระดับโลกที่ได้รับการรับรองจากองค์การสหประชาชาติ (UN) เพื่อเป็นแนวทางในการขจัดความยากจน ปกป้องโลก และสร้างหลักประกันว่าทุกคนจะได้รับความสันติสุขและความมั่งคั่งภายในปี 2573 โดยมีเป้าหมายทั้งหมด 17 ข้อ ครอบคลุมทั้งมิติเศรษฐกิจ สังคม และสิ่งแวดล้อมอย่างรอบด้าน'
                  : 'The Sustainable Development Goals (SDGs) are a universal call to action adopted by the United Nations to end poverty, protect the planet, and ensure that by 2030 all people enjoy peace and prosperity. The 17 SDGs are integrated—they recognize that action in one area will affect outcomes in others, and that development must balance social, economic and environmental sustainability.'
                }}
              </p>

              <div class="mt-5 flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold border border-emerald-100">
                  <v-icon icon="mdi-shield-check" size="14" />
                  <span>17 Goals for Sustainable Future</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold border border-blue-100">
                  <v-icon icon="mdi-school" size="14" />
                  <span>Faculty of Education RRU</span>
                </span>
              </div>
            </div>

            <!-- Right: Image of Hands Holding Seedling with Solar/Windmills -->
            <div class="lg:col-span-5">
              <div class="relative overflow-hidden rounded-xl sm:rounded-2xl shadow-lg group">
                <img
                  :src="sdgsHeroPlant"
                  alt="ภาพสัญลักษณ์ความยั่งยืน มือโอบอุ้มต้นกล้าและพลังงานสะอาด"
                  class="w-full h-56 sm:h-64 lg:h-64 object-cover object-center transform group-hover:scale-105 transition-transform duration-500 ease-out"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-60" />
                <div class="absolute bottom-3 left-3 right-3 text-white text-[11px] font-medium bg-black/40 backdrop-blur-md px-3 py-1.5 rounded-lg border border-white/20">
                  🌱 มุ่งสู่อนาคตที่ยั่งยืนผ่านพลังการศึกษาและการพัฒนาท้องถิ่น
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Section Title: "คลิกที่เป้าหมายเพื่อแสดงข่าวสารและกิจกรรมที่เกี่ยวข้อง" -->
      <div class="text-center mb-8 sm:mb-10">
        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800 tracking-tight">
          {{ currentLang === 'th' ? 'คลิกที่เป้าหมายเพื่อแสดงข่าวสารและกิจกรรมที่เกี่ยวข้อง' : 'Click on a goal to explore related news and activities' }}
        </h3>
        <!-- Green indicator bar -->
        <div class="w-16 h-1 bg-[#104727] rounded-full mx-auto mt-2.5" />
      </div>

      <!-- 17 SDG Tiles Grid (Arranged precisely like the screenshot) -->
      <div class="mb-14 sm:mb-16">
        <!-- Row 1: Goals 1 to 11 on large screen -->
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-2.5 sm:gap-3 mb-2.5 sm:mb-3">
          <button
            v-for="goal in SDG_GOALS.slice(0, 11)"
            :key="goal.id"
            type="button"
            class="group relative aspect-square rounded-xl sm:rounded-2xl p-2 sm:p-2.5 flex flex-col justify-between text-left transition-all duration-200 cursor-pointer shadow-sm hover:shadow-md hover:-translate-y-1 select-none border-0"
            :style="{ backgroundColor: goal.color, color: goal.textColor }"
            :class="[
              selectedGoalId === goal.id
                ? 'ring-4 ring-offset-2 ring-emerald-600 scale-[1.03] z-10 shadow-lg'
                : 'opacity-95 hover:opacity-100'
            ]"
            :aria-label="goal.titleTh"
            @click="selectGoal(goal.id)"
          >
            <!-- Checkmark badge when active -->
            <div
              v-if="selectedGoalId === goal.id"
              class="absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full bg-white text-emerald-700 shadow-md flex items-center justify-center border-2 border-emerald-600 z-20 animate-scale-in"
            >
              <v-icon icon="mdi-check-bold" size="13" />
            </div>

            <!-- Goal Number + Title -->
            <div class="leading-none">
              <span class="text-base sm:text-lg font-black tracking-tight block">
                {{ goal.numberStr }}
              </span>
              <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-tight block mt-0.5 line-clamp-2 leading-tight">
                {{ goal.titleEn }}
              </span>
            </div>

            <!-- Pictogram SVG -->
            <div class="self-center my-auto w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center">
              <svg viewBox="0 0 48 48" class="w-full h-full fill-current" v-html="goal.svgIcon" />
            </div>
          </button>
        </div>

        <!-- Row 2: Goals 12 to 17 on large screen -->
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2.5 sm:gap-3 max-w-[calc(6/11*100%)] max-lg:max-w-none">
          <button
            v-for="goal in SDG_GOALS.slice(11, 17)"
            :key="goal.id"
            type="button"
            class="group relative aspect-square rounded-xl sm:rounded-2xl p-2 sm:p-2.5 flex flex-col justify-between text-left transition-all duration-200 cursor-pointer shadow-sm hover:shadow-md hover:-translate-y-1 select-none border-0"
            :style="{ backgroundColor: goal.color, color: goal.textColor }"
            :class="[
              selectedGoalId === goal.id
                ? 'ring-4 ring-offset-2 ring-emerald-600 scale-[1.03] z-10 shadow-lg'
                : 'opacity-95 hover:opacity-100'
            ]"
            :aria-label="goal.titleTh"
            @click="selectGoal(goal.id)"
          >
            <!-- Checkmark badge when active -->
            <div
              v-if="selectedGoalId === goal.id"
              class="absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full bg-white text-emerald-700 shadow-md flex items-center justify-center border-2 border-emerald-600 z-20 animate-scale-in"
            >
              <v-icon icon="mdi-check-bold" size="13" />
            </div>

            <!-- Goal Number + Title -->
            <div class="leading-none">
              <span class="text-base sm:text-lg font-black tracking-tight block">
                {{ goal.numberStr }}
              </span>
              <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-tight block mt-0.5 line-clamp-2 leading-tight">
                {{ goal.titleEn }}
              </span>
            </div>

            <!-- Pictogram SVG -->
            <div class="self-center my-auto w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center">
              <svg viewBox="0 0 48 48" class="w-full h-full fill-current" v-html="goal.svgIcon" />
            </div>
          </button>
        </div>
      </div>

      <!-- Selected Goal News & Activities Section -->
      <div id="sdg-activities-section" class="scroll-mt-28">
        <!-- Active Goal Banner Card -->
        <div
          v-if="selectedGoal"
          class="rounded-2xl sm:rounded-3xl p-6 sm:p-8 text-white shadow-xl transition-all duration-300 mb-8 relative overflow-hidden"
          :style="{ backgroundColor: selectedGoal.color }"
        >
          <!-- Background decoration -->
          <div class="absolute right-0 bottom-0 opacity-15 pointer-events-none translate-x-8 translate-y-8">
            <svg viewBox="0 0 48 48" class="w-64 h-64 fill-white" v-html="selectedGoal.svgIcon" />
          </div>

          <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-start sm:items-center gap-4">
              <!-- Big Icon -->
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0 p-3 shadow-inner">
                <svg viewBox="0 0 48 48" class="w-full h-full fill-white" v-html="selectedGoal.svgIcon" />
              </div>

              <!-- Titles -->
              <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-bold uppercase tracking-wider mb-1.5">
                  <span>SDG GOAL {{ selectedGoal.numberStr }}</span>
                </div>
                <h4 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight leading-tight">
                  {{ currentLang === 'th' ? selectedGoal.titleTh : selectedGoal.titleEn }}
                </h4>
                <p class="text-white/90 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                  {{ currentLang === 'th' ? selectedGoal.descTh : selectedGoal.descEn }}
                </p>
              </div>
            </div>

            <!-- Stats Badge -->
            <div class="shrink-0 flex items-center gap-3">
              <div class="px-4 py-2 rounded-xl bg-black/20 backdrop-blur-md border border-white/20 text-center">
                <span class="text-xl sm:text-2xl font-black block leading-none">
                  {{ goalActivitiesTotal }}
                </span>
                <span class="text-[10px] text-white/80 uppercase font-semibold">
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
            <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold">
              {{ filteredActivities.length }} รายการ
            </span>
          </div>

          <!-- Category filter buttons -->
          <div class="flex flex-wrap gap-1.5">
            <button
              type="button"
              class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors cursor-pointer"
              :class="selectedCategory === 'all' ? 'bg-[#104727] text-white font-semibold shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
              @click="selectedCategory = 'all'"
            >
              {{ currentLang === 'th' ? 'ทั้งหมด' : 'All' }}
            </button>
            <button
              type="button"
              class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors cursor-pointer"
              :class="selectedCategory === 'บริการวิชาการ' ? 'bg-[#104727] text-white font-semibold shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
              @click="selectedCategory = 'บริการวิชาการ'"
            >
              {{ currentLang === 'th' ? 'บริการวิชาการ' : 'Academic Service' }}
            </button>
            <button
              type="button"
              class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors cursor-pointer"
              :class="selectedCategory === 'วิจัย' ? 'bg-[#104727] text-white font-semibold shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
              @click="selectedCategory = 'วิจัย'"
            >
              {{ currentLang === 'th' ? 'งานวิจัย & นวัตกรรม' : 'Research & Innovation' }}
            </button>
            <button
              type="button"
              class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors cursor-pointer"
              :class="selectedCategory === 'กิจกรรม' ? 'bg-[#104727] text-white font-semibold shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
              @click="selectedCategory = 'กิจกรรม'"
            >
              {{ currentLang === 'th' ? 'กิจกรรมพัฒนานักศึกษา' : 'Student Activities' }}
            </button>
          </div>
        </div>

        <!-- Activities Grid -->
        <div v-if="filteredActivities.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <article
            v-for="act in filteredActivities"
            :key="act.id"
            class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col group"
          >
            <!-- Card Image -->
            <div class="relative h-48 overflow-hidden bg-slate-100">
              <img
                :src="act.image"
                :alt="act.titleTh"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
              />
              <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                <span
                  class="px-2.5 py-1 rounded-md text-[11px] font-bold text-white shadow-xs backdrop-blur-md"
                  :style="{ backgroundColor: selectedGoal?.color || '#104727' }"
                >
                  SDG {{ selectedGoal?.numberStr }}
                </span>
                <span class="px-2.5 py-1 rounded-md text-[11px] font-medium bg-black/60 text-white backdrop-blur-md">
                  {{ currentLang === 'th' ? act.categoryTh : act.categoryEn }}
                </span>
              </div>
            </div>

            <!-- Card Body -->
            <div class="p-5 flex-1 flex flex-col justify-between">
              <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-2.5">
                  <v-icon icon="mdi-calendar-clock" size="14" class="text-slate-400" />
                  <span>{{ act.date }}</span>
                  <span class="text-slate-300">•</span>
                  <span class="truncate">{{ act.author }}</span>
                </div>

                <h5 class="text-base font-bold text-slate-800 group-hover:text-emerald-700 transition-colors leading-snug mb-2 line-clamp-2">
                  {{ currentLang === 'th' ? act.titleTh : act.titleEn }}
                </h5>

                <p class="text-xs sm:text-sm text-slate-600 line-clamp-3 leading-relaxed">
                  {{ currentLang === 'th' ? act.summaryTh : act.summaryEn }}
                </p>
              </div>

              <!-- Card Action -->
              <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="font-semibold text-emerald-700 flex items-center gap-1 group-hover:gap-1.5 transition-all">
                  {{ currentLang === 'th' ? 'อ่านรายละเอียดเพิ่มเติม' : 'Read more' }}
                  <v-icon icon="mdi-arrow-right" size="14" />
                </span>
                <span class="text-[11px] text-slate-400 font-medium">คณะครุศาสตร์</span>
              </div>
            </div>
          </article>
        </div>

        <!-- Empty State if no activities for selected filter/goal -->
        <div
          v-else
          class="bg-white rounded-2xl border border-slate-200/90 p-10 text-center my-6"
        >
          <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto mb-4">
            <v-icon icon="mdi-sprout-outline" size="32" />
          </div>
          <h5 class="text-base sm:text-lg font-bold text-slate-800 mb-2">
            {{ currentLang === 'th' ? 'อยู่ระหว่างการดำเนินโครงการและรวบรวมข้อมูล' : 'Initiatives Underway' }}
          </h5>
          <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto mb-6 leading-relaxed">
            {{ currentLang === 'th'
              ? 'คณะครุศาสตร์กำลังจัดเตรียมและดำเนินโครงการที่เกี่ยวข้องกับเป้าหมายนี้เพื่อขับเคลื่อนการศึกษาที่ยั่งยืน ท่านสามารถเลือกดูเป้าหมายอื่น ๆ หรืออ่านภาพรวม SDGs ได้'
              : 'The Faculty of Education is actively planning and implementing initiatives supporting this goal. Feel free to explore other SDG goals.'
            }}
          </p>
          <button
            type="button"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#104727] text-white text-xs sm:text-sm font-semibold hover:bg-emerald-800 transition-colors shadow-sm cursor-pointer"
            @click="selectGoal(4)"
          >
            <v-icon icon="mdi-school" size="16" />
            <span>{{ currentLang === 'th' ? 'ดูตัวอย่างเป้าหมายที่ 4: การศึกษาที่มีคุณภาพ' : 'View Goal 4: Quality Education' }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes scaleIn {
  0% {
    transform: scale(0);
    opacity: 0;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.animate-scale-in {
  animation: scaleIn 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}
</style>
