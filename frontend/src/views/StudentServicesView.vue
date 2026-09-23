<script setup lang="ts">
import PageHeroBanner from '@/components/PageHeroBanner.vue'
</script>

<template>
  <div class="min-h-screen bg-white">
    <PageHeroBanner
      badge="Student Services"
      badge-icon="mdi-account-school-outline"
      title="งานบริการ"
      title-highlight="นักศึกษา"
      subtitle="บริการด้านการศึกษา คำร้อง ทุนการศึกษา และการให้คำปรึกษาสำหรับนักศึกษา"
    />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
      <v-card class="p-5 sm:p-6 border border-slate-200 hover:shadow-md transition-shadow flex flex-col justify-between">
        <div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4">
            <v-icon icon="mdi-file-document-edit-outline" size="28" />
          </div>
          <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">คำร้องและการลงทะเบียน</h3>
          <p class="text-xs sm:text-sm text-slate-600 mb-4 leading-relaxed">
            แบบฟอร์มคำร้องออนไลน์ คำร้องขอเทียบโอน และระบบตรวจสอบผลการเรียน
          </p>
        </div>
        <v-btn variant="tonal" color="primary" class="w-full sm:w-auto !min-h-[40px]">ดูรายละเอียด</v-btn>
      </v-card>

      <v-card class="p-5 sm:p-6 border border-slate-200 hover:shadow-md transition-shadow flex flex-col justify-between">
        <div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center mb-4">
            <v-icon icon="mdi-cash-multiple" size="28" />
          </div>
          <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">ทุนการศึกษาและเงินกู้ยืม</h3>
          <p class="text-xs sm:text-sm text-slate-600 mb-4 leading-relaxed">
            ข้อมูลทุนการศึกษาประเภทต่างๆ และการกู้ยืม กยศ. / กรอ.
          </p>
        </div>
        <v-btn variant="tonal" color="primary" class="w-full sm:w-auto !min-h-[40px]">ดูรายละเอียด</v-btn>
      </v-card>

      <v-card class="p-5 sm:p-6 border border-slate-200 hover:shadow-md transition-shadow flex flex-col justify-between">
        <div>
          <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center mb-4">
            <v-icon icon="mdi-head-heart-outline" size="28" />
          </div>
          <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">ศูนย์แนะแนวและให้คำปรึกษา</h3>
          <p class="text-xs sm:text-sm text-slate-600 mb-4 leading-relaxed">
            บริการให้คำปรึกษาด้านการเรียน การปรับตัว และสุขภาพจิตสำหรับนักศึกษา
          </p>
        </div>
        <v-btn variant="tonal" color="primary" class="w-full sm:w-auto !min-h-[40px]">ดูรายละเอียด</v-btn>
      </v-card>
    </div>
  </div>
</template>

