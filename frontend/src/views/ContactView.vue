<script setup lang="ts">
import PageHeroBanner from '@/components/PageHeroBanner.vue'
</script>

<template>
  <div class="min-h-screen bg-white">
    <PageHeroBanner
      badge="Contact Us"
      badge-icon="mdi-phone-in-talk"
      title="ติดต่อ"
      title-highlight="เรา"
      subtitle="ช่องทางการติดต่อคณะ ข้อมูลที่อยู่ แผนที่ และเบอร์โทรศัพท์สำหรับสอบถามข้อมูล"
    />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <v-card class="p-5 sm:p-6 border border-slate-200 lg:col-span-1">
        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
          <v-icon icon="mdi-map-marker" color="primary" />
          ที่อยู่และการติดต่อ
        </h3>
        <div class="space-y-4 text-sm text-slate-600">
          <div>
            <strong class="block text-slate-800 mb-1">ที่ตั้ง:</strong>
            คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์<br />
            422 ถนนมรุพงษ์ ตำบลหน้าเมือง อำเภอเมือง จังหวัดฉะเชิงเทรา 24000
          </div>
          <v-divider />
          <div>
            <strong class="block text-slate-800 mb-1">เบอร์โทรศัพท์:</strong>
            <a href="tel:038511140" class="text-emerald-700 hover:underline">038-511-140 ต่อ 1234</a>
          </div>
          <div>
            <strong class="block text-slate-800 mb-1">อีเมล:</strong>
            <a href="mailto:edu@rru.ac.th" class="text-emerald-700 hover:underline">edu@rru.ac.th</a>
          </div>
          <div>
            <strong class="block text-slate-800 mb-1">เวลาทำการ:</strong>
            วันจันทร์ - วันศุกร์ เวลา 08:30 - 16:30 น.
          </div>
        </div>
      </v-card>

      <v-card class="p-5 sm:p-6 border border-slate-200 lg:col-span-2">
        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
          <v-icon icon="mdi-email-fast-outline" color="primary" />
          ส่งข้อความติดต่อสอบถาม
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <v-text-field label="ชื่อ - นามสกุล" variant="outlined" density="comfortable" hide-details />
          <v-text-field label="อีเมลหรือเบอร์โทรศัพท์" variant="outlined" density="comfortable" hide-details />
        </div>
        <v-text-field label="หัวข้อเรื่อง" variant="outlined" density="comfortable" class="mb-4" hide-details />
        <v-textarea label="รายละเอียดข้อความ" variant="outlined" rows="4" density="comfortable" class="mb-4" hide-details />
        <v-btn color="primary" prepend-icon="mdi-send" class="w-full sm:w-auto !min-h-[44px]">
          ส่งข้อความ
        </v-btn>
      </v-card>
    </div>
  </div>
</template>

