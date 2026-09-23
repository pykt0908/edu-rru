<script setup lang="ts">
import PageHeroBanner from '@/components/PageHeroBanner.vue'
</script>

<template>
  <div class="min-h-screen bg-white">
    <PageHeroBanner
      badge="Student Activities"
      badge-icon="mdi-account-group-outline"
      title="งานกิจกรรม"
      title-highlight="นักศึกษา"
      subtitle="กิจกรรมส่งเสริมทักษะความเป็นครู กิจกรรมจิตอาสา ชมรม และสโมสรนักศึกษา"
    />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
      <v-card class="p-5 sm:p-6 border border-slate-200 hover:shadow-md transition-shadow flex flex-col justify-between">
        <div>
          <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center mb-4">
            <v-icon icon="mdi-trophy-outline" size="28" />
          </div>
          <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">สโมสรนักศึกษา</h3>
          <p class="text-xs sm:text-sm text-slate-600 mb-4 leading-relaxed">
            องค์กรตัวแทนนักศึกษาเพื่อพัฒนาศักยภาพความเป็นผู้นำและการจัดกิจกรรมสร้างสรรค์
          </p>
        </div>
        <v-btn variant="tonal" color="primary" class="w-full sm:w-auto !min-h-[40px]">ข้อมูลสโมสร</v-btn>
      </v-card>

      <v-card class="p-5 sm:p-6 border border-slate-200 hover:shadow-md transition-shadow flex flex-col justify-between">
        <div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4">
            <v-icon icon="mdi-hand-heart-outline" size="28" />
          </div>
          <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">ชมรมและจิตอาสา</h3>
          <p class="text-xs sm:text-sm text-slate-600 mb-4 leading-relaxed">
            รวมกลุ่มทำกิจกรรมพัฒนาชุมชน โรงเรียนในพื้นที่ห่างไกล และกิจกรรมสาธารณประโยชน์
          </p>
        </div>
        <v-btn variant="tonal" color="primary" class="w-full sm:w-auto !min-h-[40px]">ดูรายชื่อชมรม</v-btn>
      </v-card>

      <v-card class="p-5 sm:p-6 border border-slate-200 hover:shadow-md transition-shadow flex flex-col justify-between">
        <div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center mb-4">
            <v-icon icon="mdi-calendar-star" size="28" />
          </div>
          <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">ปฏิทินกิจกรรม</h3>
          <p class="text-xs sm:text-sm text-slate-600 mb-4 leading-relaxed">
            ติดตามกำหนดการกิจกรรมรับน้อง พิธีไหว้ครู และกิจกรรมวิชาการตลอดทั้งปี
          </p>
        </div>
        <v-btn variant="tonal" color="primary" class="w-full sm:w-auto !min-h-[40px]">ดูปฏิทิน</v-btn>
      </v-card>
    </div>
  </div>
</template>

