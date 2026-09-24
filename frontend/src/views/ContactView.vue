<script setup lang="ts">
import PageHeroBanner from '@/components/PageHeroBanner.vue'

// Google Maps Embed URLs provided by user
const mapMueangUrl =
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.6827254640707!2d101.07136480000001!3d13.6770464!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d170b646b51df%3A0xa6cdcb6e4d21174d!2z4Lih4Lir4Liy4Lin4Li04LiX4Lii4Liy4Lil4Lix4Lii4Lij4Liy4LiK4Lig4Lix4LiP4Lij4Liy4LiK4LiZ4LiE4Lij4Li04LiZ4LiX4Lij4LmM!5e0!3m2!1sth!2sth!4v1790207948063!5m2!1sth!2sth'

const mapBangkhlaUrl =
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3874.8534255648133!2d101.23501549999999!3d13.787708099999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d1b16cf5d44a9%3A0xda8791dddd3ef46d!2z4Lih4Lir4Liy4Lin4Li04LiX4Lii4Liy4Lil4Lix4Lii4Lij4Liy4LiK4Lig4Lix4LiP4Lij4Liy4LiK4LiE4Lij4Li04LiZ4LiX4Lij4LmMIOC4qOC4ueC4meC4ouC5jOC4muC4suC4h-C4hOC4peC5ieC4sg!5e0!3m2!1sth!2sth!4v1790208030501!5m2!1sth!2sth'
</script>

<template>
  <div class="min-h-screen bg-slate-50/50 pb-20">
    <!-- Hero Banner (Follows rule.md Section 13) -->
    <PageHeroBanner
      badge="ติดต่อสอบถาม"
      badge-icon="mdi-phone-in-talk"
      title="ติดต่อ"
      title-highlight="คณะครุศาสตร์"
      subtitle="ช่องทางการติดต่อคณะ ข้อมูลสถานที่ตั้ง แผนที่การเดินทาง และการให้บริการ มหาวิทยาลัยราชภัฏราชนครินทร์"
    />

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
        <!-- Left Column: Contact Information Card -->
        <div class="lg:col-span-4">
          <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xs space-y-6">
            <!-- Faculty Title Header -->
            <div class="flex items-center gap-3 pb-2 border-b border-slate-100">
              <v-icon icon="mdi-cellphone" size="28" class="text-emerald-800 shrink-0" />
              <div>
                <h2 class="text-lg sm:text-xl font-bold text-emerald-950 leading-tight">
                  คณะครุศาสตร์
                </h2>
                <p class="text-xs text-slate-500 font-medium">
                  มหาวิทยาลัยราชภัฏราชนครินทร์
                </p>
              </div>
            </div>

            <!-- Contact List Items -->
            <div class="space-y-4">
              <!-- 1. ที่อยู่ -->
              <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#1d5b3a] text-white flex items-center justify-center shrink-0 shadow-xs">
                  <v-icon icon="mdi-map-marker" size="20" />
                </div>
                <div class="space-y-0.5 text-xs sm:text-[13px]">
                  <span class="font-bold text-slate-800 block">ที่อยู่</span>
                  <p class="text-slate-600 leading-relaxed font-normal">
                    422 ถนนมรุพงษ์ ตำบลหน้าเมือง<br />
                    อำเภอเมือง จังหวัดฉะเชิงเทรา 24000
                  </p>
                </div>
              </div>

              <!-- 2. โทรศัพท์ -->
              <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#1d5b3a] text-white flex items-center justify-center shrink-0 shadow-xs">
                  <v-icon icon="mdi-phone" size="20" />
                </div>
                <div class="space-y-0.5 text-xs sm:text-[13px]">
                  <span class="font-bold text-slate-800 block">โทรศัพท์</span>
                  <a
                    href="tel:038515826"
                    class="text-slate-700 hover:text-emerald-700 font-normal transition-colors no-underline block"
                  >
                    038-515826
                  </a>
                </div>
              </div>

              <!-- 3. อีเมล -->
              <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#1d5b3a] text-white flex items-center justify-center shrink-0 shadow-xs">
                  <v-icon icon="mdi-email" size="20" />
                </div>
                <div class="space-y-0.5 text-xs sm:text-[13px]">
                  <span class="font-bold text-slate-800 block">อีเมล</span>
                  <a
                    href="mailto:edu@rru.ac.th"
                    class="text-slate-700 hover:text-emerald-700 font-normal transition-colors no-underline block"
                  >
                    edu@rru.ac.th
                  </a>
                </div>
              </div>

              <!-- 4. เวลาทำการ -->
              <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#1d5b3a] text-white flex items-center justify-center shrink-0 shadow-xs">
                  <v-icon icon="mdi-clock" size="20" />
                </div>
                <div class="space-y-0.5 text-xs sm:text-[13px]">
                  <span class="font-bold text-slate-800 block">เวลาทำการ</span>
                  <p class="text-slate-600 font-normal leading-relaxed">
                    วันจันทร์ - วันศุกร์<br />
                    08.30 - 16.30 น.
                  </p>
                </div>
              </div>
            </div>

            <!-- Follow Us Section -->
            <div class="pt-4 border-t border-slate-100 space-y-3">
              <h3 class="text-xs sm:text-sm font-bold text-slate-800">
                ติดตามเรา
              </h3>
              <div class="flex items-center gap-3">
                <!-- Facebook -->
                <a
                  href="https://www.facebook.com/edurru"
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label="Facebook คณะครุศาสตร์"
                  class="w-9 h-9 rounded-full bg-[#1877F2] text-white flex items-center justify-center shadow-xs hover:scale-110 hover:shadow-md transition-all duration-200 no-underline"
                >
                  <v-icon icon="mdi-facebook" size="20" />
                </a>

                <!-- LINE -->
                <a
                  href="https://line.me"
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label="LINE Official คณะครุศาสตร์"
                  class="w-9 h-9 rounded-full bg-[#06C755] text-white flex items-center justify-center shadow-xs hover:scale-110 hover:shadow-md transition-all duration-200 no-underline"
                >
                  <svg viewBox="0 0 24 24" class="w-4 h-4 fill-white">
                    <path
                      d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.477.254l2.482 3.364V8.108c0-.345.282-.63.63-.63.345 0 .627.285.627.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.627-.63.349 0 .631.285.631.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"
                    />
                  </svg>
                </a>

                <!-- YouTube -->
                <a
                  href="https://www.youtube.com"
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label="YouTube Channel คณะครุศาสตร์"
                  class="w-9 h-9 rounded-full bg-[#FF0000] text-white flex items-center justify-center shadow-xs hover:scale-110 hover:shadow-md transition-all duration-200 no-underline"
                >
                  <v-icon icon="mdi-youtube" size="20" />
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Campus Maps (ศูนย์หน้าเมือง & ศูนย์บางคล้า) -->
        <div class="lg:col-span-8 space-y-6 sm:space-y-8">
          <!-- 1. ศูนย์หน้าเมือง -->
          <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xs space-y-4">
            <!-- Header -->
            <div class="space-y-1">
              <div class="flex items-center gap-2">
                <v-icon icon="mdi-map-marker-radius-outline" size="22" class="text-slate-800" />
                <h3 class="text-base sm:text-lg font-bold text-slate-900">
                  ศูนย์หน้าเมือง
                </h3>
              </div>
              <p class="text-xs sm:text-[13px] text-slate-500">
                422 ถนนมรุพงษ์ ตำบลหน้าเมือง อำเภอเมือง จังหวัดฉะเชิงเทรา 24000
              </p>
            </div>

            <!-- Map Iframe -->
            <div class="w-full rounded-2xl overflow-hidden border border-slate-200/80 bg-slate-100 shadow-inner">
              <iframe
                :src="mapMueangUrl"
                width="100%"
                height="280"
                style="border:0; display:block;"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="แผนที่ศูนย์หน้าเมือง มหาวิทยาลัยราชภัฏราชนครินทร์"
              />
            </div>

            <!-- Action Button -->
            <div class="pt-1">
              <a
                href="https://www.google.com/maps/search/?api=1&query=13.6770464,101.0713648"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border border-blue-300 text-blue-600 hover:bg-blue-50 text-xs font-semibold transition-colors no-underline"
              >
                <v-icon icon="mdi-navigation-variant-outline" size="14" class="text-blue-600" />
                <span>เปิดใน Google Maps</span>
              </a>
            </div>
          </div>

          <!-- 2. ศูนย์บางคล้า -->
          <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xs space-y-4">
            <!-- Header -->
            <div class="space-y-1">
              <div class="flex items-center gap-2">
                <v-icon icon="mdi-map-marker-radius-outline" size="22" class="text-slate-800" />
                <h3 class="text-base sm:text-lg font-bold text-slate-900">
                  ศูนย์บางคล้า
                </h3>
              </div>
              <p class="text-xs sm:text-[13px] text-slate-500">
                6 หมู่ 4 ตำบลหัวไทร อำเภอบางคล้า จังหวัดฉะเชิงเทรา 24110
              </p>
            </div>

            <!-- Map Iframe -->
            <div class="w-full rounded-2xl overflow-hidden border border-slate-200/80 bg-slate-100 shadow-inner">
              <iframe
                :src="mapBangkhlaUrl"
                width="100%"
                height="280"
                style="border:0; display:block;"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="แผนที่ศูนย์บางคล้า มหาวิทยาลัยราชภัฏราชนครินทร์"
              />
            </div>

            <!-- Action Button -->
            <div class="pt-1">
              <a
                href="https://www.google.com/maps/search/?api=1&query=13.7877081,101.2350155"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border border-blue-300 text-blue-600 hover:bg-blue-50 text-xs font-semibold transition-colors no-underline"
              >
                <v-icon icon="mdi-navigation-variant-outline" size="14" class="text-blue-600" />
                <span>เปิดใน Google Maps</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
