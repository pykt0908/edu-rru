<script setup lang="ts">
import PageHeroBanner from '@/components/PageHeroBanner.vue'
</script>

<template>
  <div class="min-h-screen bg-white">
    <PageHeroBanner
      badge="Sustainable Development Goals"
      badge-icon="mdi-earth"
      title="SDGs เป้าหมายการพัฒนา"
      title-highlight="ที่ยั่งยืน"
      subtitle="การดำเนินงานและโครงการขับเคลื่อนเป้าหมายการพัฒนาที่ยั่งยืนของคณะ"
    />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
      <v-card class="p-5 sm:p-6 border border-slate-200 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-bold text-lg mb-4">
          SDG 4
        </div>
        <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">Quality Education</h3>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
          การศึกษาที่เท่าเทียมและมีคุณภาพ ส่งเสริมโอกาสการเรียนรู้ตลอดชีวิตสำหรับทุกคน
        </p>
      </v-card>

      <v-card class="p-5 sm:p-6 border border-slate-200 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-lg mb-4">
          SDG 5
        </div>
        <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">Gender Equality</h3>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
          ความเท่าเทียมทางเพศและเสริมสร้างความเข้มแข็งแก่ผู้หญิงและเยาวชนทุกคน
        </p>
      </v-card>

      <v-card class="p-5 sm:p-6 border border-slate-200 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-lg mb-4">
          SDG 17
        </div>
        <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">Partnerships for the Goals</h3>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
          ความร่วมมือเพื่อการพัฒนาที่ยั่งยืน สร้างเครือข่ายระหว่างชุมชน องค์กร และสถานศึกษา
        </p>
      </v-card>
    </div>
  </div>
</template>

