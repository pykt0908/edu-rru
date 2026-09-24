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
      subtitle="แผนปฏิบัติราชการ แผนยุทธศาสตร์การพัฒนาคณะ และการประกันคุณภาพการศึกษา คณะครุศาสตร์"
    />

    <!-- Blank content container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
      <div class="max-w-md mx-auto py-12 px-6 rounded-3xl bg-slate-50 border border-slate-100 space-y-3">
        <div class="w-14 h-14 rounded-2xl bg-slate-200/60 text-slate-400 flex items-center justify-center mx-auto">
          <v-icon icon="mdi-file-document-outline" size="28" />
        </div>
        <h2 class="text-base sm:text-lg font-bold text-slate-700">
          อยู่ระหว่างการจัดเตรียมข้อมูล
        </h2>
        <p class="text-xs sm:text-sm text-slate-400">
          หน้านี้ยังไม่มีเนื้อหาในขณะนี้
        </p>
      </div>
    </div>
  </div>
</template>
