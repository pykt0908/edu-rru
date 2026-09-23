<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { getPersonById } from '@/data/personnelData'

const route = useRoute()
const router = useRouter()

const personId = computed(() => route.params.id as string)
const person = computed(() => getPersonById(personId.value))
</script>

<template>
  <div class="min-h-screen bg-slate-50/50 pb-20">
    <!-- Top Breadcrumb & Actions Bar -->
    <div class="bg-white border-b border-slate-200/80 sticky top-16 z-20 shadow-xs">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 sm:py-4">
        <div class="flex items-center justify-between gap-4">
          <!-- Breadcrumb Links -->
          <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 sm:gap-2 text-xs text-slate-500 overflow-x-auto scrollbar-none whitespace-nowrap">
            <RouterLink to="/" class="hover:text-emerald-700 transition-colors no-underline">หน้าแรก</RouterLink>
            <span>/</span>
            <RouterLink to="/about/personnel" class="hover:text-emerald-700 transition-colors no-underline">บุคลากรประจำสาขาวิชา</RouterLink>
            <template v-if="person">
              <span>/</span>
              <span class="text-slate-500 hidden sm:inline">{{ person.departmentName }}</span>
              <span class="hidden sm:inline">/</span>
              <span class="text-emerald-800 font-semibold truncate max-w-[200px]">{{ person.name }}</span>
            </template>
          </nav>

          <!-- Back Button -->
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors cursor-pointer shrink-0"
            @click="router.push('/about/personnel')"
          >
            <v-icon icon="mdi-arrow-left" size="14" />
            <span>กลับหน้ารายชื่อ</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-10">
      <!-- 404 Not Found State -->
      <div v-if="!person" class="bg-white rounded-3xl p-12 text-center border border-slate-200/90 shadow-xs max-w-lg mx-auto space-y-4">
        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
          <v-icon icon="mdi-account-question-outline" size="36" />
        </div>
        <h2 class="text-xl font-bold text-slate-800">ไม่พบข้อมูลคณาจารย์ท่านนี้</h2>
        <p class="text-xs sm:text-sm text-slate-500">
          รหัสคณาจารย์อาจไม่ถูกต้อง หรือข้อมูลถูกย้ายไปยังหมวดหมู่อื่น
        </p>
        <RouterLink
          to="/about/personnel"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold transition-colors no-underline shadow-xs"
        >
          <v-icon icon="mdi-arrow-left" size="16" />
          <span>กลับไปยังหน้ารายชื่อคณาจารย์</span>
        </RouterLink>
      </div>

      <!-- Personnel Profile Content -->
      <template v-else>
        <!-- Top Profile Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 lg:p-10 border border-slate-200/90 shadow-xs">
          <div class="flex flex-col md:flex-row gap-8 lg:gap-10 items-start">
            <!-- Left Portrait Container (Matching Reference UI: Rounded 2xl, #DDE2E6 background) -->
            <div class="w-full md:w-64 lg:w-72 shrink-0">
              <div class="relative w-full aspect-[3/4] rounded-2xl overflow-hidden bg-[#DDE2E6] shadow-sm">
                <img
                  :src="person.avatar"
                  :alt="person.name"
                  class="w-full h-full object-cover object-top"
                />
              </div>


            </div>

            <!-- Right Profile Info -->
            <div class="flex-1 space-y-6">
              <!-- Name & Title -->
              <div class="space-y-1.5 border-b border-slate-100 pb-5">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                  {{ person.name }}
                </h1>

                <p v-if="person.nameEn" class="text-sm sm:text-base text-slate-500 font-medium">
                  {{ person.nameEn }}
                </p>
              </div>

              <!-- Education Credentials inside Profile Card -->
              <div class="space-y-3">
                <div class="flex items-center gap-2 pb-1.5 border-b border-slate-100">
                  <v-icon icon="mdi-school-outline" size="18" class="text-emerald-700" />
                  <h2 class="text-sm sm:text-base font-bold text-slate-900">
                    คุณวุฒิการศึกษา
                  </h2>
                </div>

                <div v-if="person.educationHistory && person.educationHistory.length > 0" class="space-y-2.5">
                  <div
                    v-for="(edu, idx) in person.educationHistory"
                    :key="idx"
                    class="p-3 rounded-xl bg-slate-50/80 border border-slate-100/90"
                  >
                    <div class="flex flex-wrap items-center justify-between gap-1.5">
                      <span class="text-xs sm:text-[13px] font-bold text-slate-800">
                        {{ edu.degree }} ({{ edu.field }})
                      </span>
                      <span v-if="edu.year" class="text-[10px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        พ.ศ. {{ edu.year }}
                      </span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                      {{ edu.institution }}
                    </p>
                  </div>
                </div>

                <div v-else class="text-xs sm:text-sm text-slate-700 p-3 rounded-xl bg-slate-50 border border-slate-100">
                  {{ person.degrees || 'ข้อมูลอยู่ระหว่างการปรับปรุง' }}
                </div>
              </div>


            </div>
          </div>
        </div>

        <!-- Detailed Information Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column (2 Cols): Experience, Publications, Study Visits -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Work Experience -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-5">
              <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center">
                  <v-icon icon="mdi-briefcase-outline" size="18" />
                </div>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900">
                  ประสบการณ์การทำงาน
                </h2>
              </div>

              <div v-if="person.workExperience && person.workExperience.length > 0" class="space-y-3.5">
                <div
                  v-for="(exp, idx) in person.workExperience"
                  :key="idx"
                  class="p-4 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-1.5 hover:bg-slate-50 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <span
                      v-if="exp.period"
                      class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-teal-50 text-teal-800 border border-teal-200/80"
                    >
                      {{ exp.period }}
                    </span>
                  </div>

                  <h3 class="text-xs sm:text-sm font-bold text-slate-800 leading-snug">
                    {{ exp.position }}
                  </h3>

                  <p v-if="exp.organization" class="text-xs text-slate-600 font-medium">
                    {{ exp.organization }}
                  </p>

                  <p v-if="exp.description" class="text-xs text-slate-500 font-normal">
                    {{ exp.description }}
                  </p>
                </div>
              </div>

              <div v-else class="text-center py-6 text-xs text-slate-400">
                ไม่มีข้อมูลประสบการณ์การทำงานที่แสดงในขณะนี้
              </div>
            </div>

            <!-- Academic Publications & Research -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-5">
              <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center">
                  <v-icon icon="mdi-book-open-page-variant-outline" size="18" />
                </div>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900">
                  ผลงานทางวิชาการและงานวิจัย
                </h2>
              </div>

              <div v-if="person.publications && person.publications.length > 0" class="space-y-3.5">
                <div
                  v-for="(pub, idx) in person.publications"
                  :key="idx"
                  class="p-4 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-1.5 hover:bg-slate-50 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <span
                      class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                      :class="{
                        'bg-purple-100 text-purple-800': pub.type === 'journal',
                        'bg-blue-100 text-blue-800': pub.type === 'conference',
                        'bg-emerald-100 text-emerald-800': pub.type === 'book',
                        'bg-amber-100 text-amber-800': pub.type === 'research',
                      }"
                    >
                      {{ pub.type === 'journal' ? 'วารสารวิชาการ' : pub.type === 'conference' ? 'การประชุมวิชาการ' : pub.type === 'book' ? 'หนังสือ/ตำรา' : 'โครงการวิจัย' }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">ปี {{ pub.year }}</span>
                  </div>

                  <h3 class="text-xs sm:text-sm font-bold text-slate-800 leading-snug">
                    {{ pub.title }}
                  </h3>

                  <p class="text-xs text-slate-500 font-normal">
                    {{ pub.source }}
                  </p>
                </div>
              </div>

              <div v-else class="text-center py-6 text-xs text-slate-400">
                ไม่มีข้อมูลผลงานทางวิชาการที่แสดงในขณะนี้
              </div>
            </div>

            <!-- Study Visits -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-5">
              <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                  <v-icon icon="mdi-bus-school" size="18" />
                </div>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900">
                  การศึกษาดูงาน
                </h2>
              </div>

              <div v-if="person.studyVisits && person.studyVisits.length > 0" class="space-y-3.5">
                <div
                  v-for="(visit, idx) in person.studyVisits"
                  :key="idx"
                  class="p-4 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-1.5 hover:bg-slate-50 transition-colors"
                >
                  <div class="flex items-center gap-2 flex-wrap">
                    <span
                      v-if="visit.year"
                      class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-200/80"
                    >
                      พ.ศ. {{ visit.year }}
                    </span>
                    <span v-if="visit.location" class="text-xs text-slate-500 font-medium flex items-center gap-1">
                      <v-icon icon="mdi-map-marker-outline" size="14" class="text-slate-400 shrink-0" />
                      {{ visit.location }}
                    </span>
                  </div>

                  <h3 class="text-xs sm:text-sm font-bold text-slate-800 leading-snug">
                    {{ visit.topic }}
                  </h3>

                  <p v-if="visit.organization" class="text-xs text-slate-500 font-normal">
                    {{ visit.organization }}
                  </p>
                </div>
              </div>

              <div v-else class="text-center py-6 text-xs text-slate-400">
                ไม่มีข้อมูลการศึกษาดูงานที่แสดงในขณะนี้
              </div>
            </div>
          </div>

          <!-- Right Column (1 Col): Expertise & Department Info -->
          <div class="space-y-8">
            <!-- Expertise Chips Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xs space-y-4">
              <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                <v-icon icon="mdi-lightbulb-on-outline" size="18" class="text-emerald-700" />
                <h2 class="text-base sm:text-lg font-bold text-slate-900">
                  สาขาวิชาที่เชี่ยวชาญ
                </h2>
              </div>

              <div v-if="person.expertise && person.expertise.length > 0" class="flex flex-wrap gap-2">
                <span
                  v-for="(exp, idx) in person.expertise"
                  :key="idx"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-900 border border-emerald-100 leading-snug"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 shrink-0" />
                  <span>{{ exp }}</span>
                </span>
              </div>

              <div v-else class="text-xs text-slate-400 py-3 text-center">
                สาขาวิชาการตามกลุ่มสาระการเรียนรู้
              </div>
            </div>

          </div>
        </div>
      </template>
    </div>
  </div>
</template>
