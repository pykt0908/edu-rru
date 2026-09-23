<script setup lang="ts">
import PageHeroBanner from '@/components/PageHeroBanner.vue'
</script>

<template>
  <div class="min-h-screen bg-white">
    <PageHeroBanner
      badge="Planning & Strategy"
      badge-icon="mdi-chart-timeline-variant"
      title="งานวางแผน"
      title-highlight="และยุทธศาสตร์"
      subtitle="แผนปฏิบัติราชการ แผนยุทธศาสตร์การพัฒนาคณะ และการประกันคุณภาพการศึกษา"
    />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
      <v-card class="p-5 sm:p-6 border border-slate-200 hover:shadow-md transition-shadow flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-3 mb-3">
            <v-icon icon="mdi-clipboard-list-outline" color="primary" size="24" />
            <h3 class="text-base sm:text-lg font-semibold text-slate-800">แผนปฏิบัติราชการประจำปี</h3>
          </div>
          <p class="text-xs sm:text-sm text-slate-600 mb-4 leading-relaxed">
            การบริหารจัดการงบประมาณ การติดตามผลการดำเนินงานตามแผนยุทธศาสตร์ และตัวชี้วัดความสำเร็จ
          </p>
        </div>
        <v-btn variant="outlined" color="primary" class="w-full sm:w-auto !min-h-[40px]">ดาวน์โหลดเอกสาร</v-btn>
      </v-card>

      <v-card class="p-5 sm:p-6 border border-slate-200 hover:shadow-md transition-shadow flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-3 mb-3">
            <v-icon icon="mdi-certificate-outline" color="primary" size="24" />
            <h3 class="text-base sm:text-lg font-semibold text-slate-800">การประกันคุณภาพการศึกษา</h3>
          </div>
          <p class="text-xs sm:text-sm text-slate-600 mb-4 leading-relaxed">
            การประเมินคุณภาพระดับหลักสูตรและระดับคณะตามเกณฑ์มาตรฐานการศึกษา (AUN-QA / EdPEx)
          </p>
        </div>
        <v-btn variant="outlined" color="primary" class="w-full sm:w-auto !min-h-[40px]">ดูรายงานการประเมิน</v-btn>
      </v-card>
    </div>
  </div>
</template>

