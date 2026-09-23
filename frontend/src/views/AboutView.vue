<script setup lang="ts">
import PageHeroBanner from '@/components/PageHeroBanner.vue'
</script>

<template>
  <div class="min-h-screen bg-white">
    <PageHeroBanner
      badge="About Faculty"
      badge-icon="mdi-school-outline"
      title="เกี่ยวกับ"
      title-highlight="คณะครุศาสตร์"
      subtitle="มุ่งมั่นผลิตบัณฑิตครูและบุคลากรทางการศึกษาที่มีคุณภาพ คุณธรรม และความเป็นเลิศทางวิชาการ"
    />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-8">
        <v-card class="p-5 sm:p-6 border border-slate-200">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
              <v-icon icon="mdi-eye-outline" size="24" />
            </div>
            <h3 class="text-lg sm:text-xl font-bold text-slate-800">วิสัยทัศน์ (Vision)</h3>
          </div>
          <p class="text-slate-600 leading-relaxed text-sm">
            เป็นองค์กรชั้นนำในการผลิตและพัฒนาครูมืออาชีพ สร้างสรรค์นวัตกรรมการศึกษาเพื่อการพัฒนาท้องถิ่นสู่สากลอย่างยั่งยืน
          </p>
        </v-card>

        <v-card class="p-5 sm:p-6 border border-slate-200">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
              <v-icon icon="mdi-bullseye-arrow" size="24" />
            </div>
            <h3 class="text-lg sm:text-xl font-bold text-slate-800">พันธกิจ (Mission)</h3>
          </div>
          <ul class="text-slate-600 space-y-2.5 text-sm">
            <li class="flex items-start gap-2">
              <v-icon icon="mdi-check" color="success" size="18" class="mt-0.5 shrink-0" />
              <span>จัดการศึกษาเพื่อผลิตครูที่มีสมรรถนะสูงและมีจิตวิญญาณความเป็นครู</span>
            </li>
            <li class="flex items-start gap-2">
              <v-icon icon="mdi-check" color="success" size="18" class="mt-0.5 shrink-0" />
              <span>วิจัยและพัฒนานวัตกรรมการจัดการเรียนรู้ที่ตอบสนองความต้องการของสังคม</span>
            </li>
            <li class="flex items-start gap-2">
              <v-icon icon="mdi-check" color="success" size="18" class="mt-0.5 shrink-0" />
              <span>บริการวิชาการและส่งเสริมการเรียนรู้ตลอดชีวิตเพื่อชุมชนท้องถิ่น</span>
            </li>
          </ul>
        </v-card>
      </div>
    </div>
  </div>
</template>
