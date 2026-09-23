<script setup lang="ts">
import PageHeroBanner from '@/components/PageHeroBanner.vue'

const missions = [
  { text: 'ผลิตบัณฑิตครูและบุคลากรทางการศึกษาที่มีคุณภาพตามมาตรฐานวิชาชีพ' },
  { text: 'วิจัยและพัฒนานวัตกรรมทางการศึกษาเพื่อพัฒนาท้องถิ่น' },
  { text: 'บริการวิชาการแก่ชุมชนและสังคม' },
  { text: 'ทำนุบำรุงศิลปวัฒนธรรมและภูมิปัญญาท้องถิ่น' },
  { text: 'บริหารจัดการองค์กรตามหลักธรรมาภิบาล' },
]
</script>

<template>
  <div class="min-h-screen bg-white">
    <!-- Hero Banner -->
    <PageHeroBanner
      badge="Philosophy & Vision"
      badge-icon="mdi-lightbulb-outline"
      title="ปรัชญา วิสัยทัศน์"
      title-highlight="อัตลักษณ์ และพันธกิจ"
      subtitle="รากฐานแนวคิดและทิศทางในการพัฒนาคณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์"
    />

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">

        <!-- ปรัชญา -->
        <div class="group relative bg-gradient-to-br from-emerald-950 to-slate-950 rounded-3xl p-7 sm:p-8 overflow-hidden border border-emerald-800/40 hover:border-emerald-600/60 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-900/30 lg:col-span-2">
          <div class="pointer-events-none absolute top-0 right-0 w-64 h-64 rounded-full bg-emerald-500/10 blur-3xl" />
          <div class="relative">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-4">
              <v-icon icon="mdi-head-lightbulb-outline" size="14" />
              ปรัชญา (Philosophy)
            </div>
            <blockquote class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-snug mb-4">
              "สร้างครูดี มีความรู้<br class="hidden sm:inline" />สู่สังคม"
            </blockquote>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl">
              คณะครุศาสตร์มุ่งมั่นผลิตบัณฑิตครูที่มีคุณภาพ มีความรู้ความสามารถในวิชาชีพ
              และมีคุณธรรมจริยธรรมเป็นแบบอย่างที่ดีแก่สังคม
            </p>
          </div>
        </div>

        <!-- วิสัยทัศน์ -->
        <div class="group bg-white border border-slate-200 hover:border-emerald-300 rounded-3xl p-7 sm:p-8 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-0.5">
          <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-5 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
            <v-icon icon="mdi-telescope" size="26" />
          </div>
          <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider mb-3">
            วิสัยทัศน์ (Vision)
          </div>
          <h2 class="text-lg sm:text-xl font-black text-slate-900 leading-snug mb-3">
            เป็นสถาบันชั้นนำด้านการผลิตครู<br />และบุคลากรทางการศึกษาของภูมิภาคตะวันออก
          </h2>
          <p class="text-slate-500 text-sm leading-relaxed">
            มุ่งสู่ความเป็นเลิศในการจัดการศึกษา การวิจัย และการบริการวิชาการ
            เพื่อพัฒนาท้องถิ่นและสังคม
          </p>
        </div>

        <!-- อัตลักษณ์ -->
        <div class="group bg-white border border-slate-200 hover:border-emerald-300 rounded-3xl p-7 sm:p-8 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-0.5">
          <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-5 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
            <v-icon icon="mdi-star-circle-outline" size="26" />
          </div>
          <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider mb-3">
            อัตลักษณ์ (Identity)
          </div>
          <blockquote class="text-base sm:text-lg font-black text-slate-900 leading-snug mb-3 border-l-4 border-emerald-500 pl-4">
            "บัณฑิตครูมีจิตวิญญาณความเป็นครู<br />มีความรู้คู่คุณธรรม"
          </blockquote>
          <p class="text-slate-500 text-sm leading-relaxed">
            บัณฑิตของคณะครุศาสตร์จะมีความรักในวิชาชีพครู มีจิตสำนึกในการพัฒนาผู้เรียน
            และมีคุณธรรมจริยธรรมในการดำเนินชีวิต
          </p>
        </div>

        <!-- พันธกิจ -->
        <div class="lg:col-span-2 bg-slate-50 border border-slate-200 hover:border-emerald-200 rounded-3xl p-7 sm:p-8 transition-all duration-300 hover:shadow-lg">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
              <v-icon icon="mdi-flag-checkered" size="26" />
            </div>
            <div>
              <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider">
                พันธกิจ (Mission)
              </div>
              <p class="text-slate-500 text-xs mt-0.5">ภารกิจหลักในการขับเคลื่อนคณะ</p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
              v-for="(mission, index) in missions"
              :key="index"
              class="flex items-start gap-3 bg-white rounded-2xl p-4 border border-slate-200 hover:border-emerald-300 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group"
            >
              <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 font-bold text-xs group-hover:scale-110 transition-transform duration-200">
                {{ index + 1 }}
              </div>
              <p class="text-slate-700 text-xs sm:text-sm leading-relaxed font-medium">{{ mission.text }}</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>
