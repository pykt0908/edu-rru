<script setup lang="ts">
import eduLogo from '@/assets/logos/edu-logo-border-white.png'

const scrollToContent = () => {
  const target = document.getElementById('explore-content')
  if (target) {
    // @ts-expect-error global lenis smooth scroll
    if (window.__lenis) {
      // @ts-expect-error global lenis smooth scroll
      window.__lenis.scrollTo(target, { duration: 1.4, offset: -20 })
    } else {
      target.scrollIntoView({ behavior: 'smooth' })
    }
  }
}
</script>

<template>
  <section class="relative w-full bg-white overflow-hidden pt-0 pb-12 sm:pb-16 lg:pb-20 border-b border-slate-100">
    <!-- Infinite Running Marquee Watermark in Background -->
    <div
      class="absolute top-1/2 -translate-y-1/2 left-0 w-full select-none pointer-events-none opacity-15 overflow-hidden leading-none z-0"
      aria-hidden="true"
    >
      <div class="marquee-track flex whitespace-nowrap">
        <div class="flex shrink-0 items-center">
          <span class="text-[12vw] sm:text-[9vw] font-black tracking-tighter text-slate-500 uppercase mr-8 shrink-0">
            FACULTY OF EDUCATION RAJABHATRAJANAGARINDA •
          </span>
          <span class="text-[12vw] sm:text-[9vw] font-black tracking-tighter text-slate-500 uppercase mr-8 shrink-0">
            FACULTY OF EDUCATION RAJABHATRAJANAGARINDA •
          </span>
        </div>
        <div class="flex shrink-0 items-center" aria-hidden="true">
          <span class="text-[12vw] sm:text-[9vw] font-black tracking-tighter text-slate-500 uppercase mr-8 shrink-0">
            FACULTY OF EDUCATION RAJABHATRAJANAGARINDA •
          </span>
          <span class="text-[12vw] sm:text-[9vw] font-black tracking-tighter text-slate-500 uppercase mr-8 shrink-0">
            FACULTY OF EDUCATION RAJABHATRAJANAGARINDA •
          </span>
        </div>
      </div>
    </div>

    <!-- Main Grid Container -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-1 sm:pt-2">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-8 items-start">
        <!-- Left Column: Giant Typography Stack -->
        <div class="lg:col-span-8 flex flex-col justify-start">
          <!-- Eyebrow Sub-heading -->
          <div class="text-emerald-700 font-bold tracking-widest pt-5 text-xs sm:text-sm uppercase mb-1 sm:mb-2">
            สร้างครูดี มีความรู้ สู่สังคม
          </div>

          <!-- Bold Massive English Headlines -->
          <div class="flex flex-col select-none">
            <h1 class="text-5xl sm:text-7xl md:text-8xl lg:text-7xl xl:text-8xl 2xl:text-[7.5rem] font-black text-slate-900 tracking-tight leading-[0.9] m-0">
              FACULTY
            </h1>
            <h1 class="text-5xl sm:text-7xl md:text-8xl lg:text-7xl xl:text-8xl 2xl:text-[7.5rem] font-black text-slate-900 tracking-tight leading-[0.9] mt-1 sm:mt-2 m-0">
              OF EDUCATION
            </h1>
          </div>
        </div>

        <!-- Right Column: Logo without white background + Description + Down Arrow -->
        <div class="lg:col-span-4 flex flex-col items-center text-center mt-4 lg:mt-0">
          <!-- Logo (No white circle background, just pure logo with drop shadow) -->
          <div class="relative group">
            <img
              :src="eduLogo"
              alt="ตราสัญลักษณ์คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์"
              class="w-36 h-36 sm:w-44 sm:h-44 md:w-56 md:h-56 object-contain drop-shadow-xl transition-transform duration-300 group-hover:scale-105"
            />
          </div>

          <!-- Short Mission Statement -->
          <div class="mt-5 sm:mt-7 max-w-xs sm:max-w-sm">
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
              มุ่งเน้นความเป็นเลิศทางวิชาชีพครูและนวัตกรรมทางการศึกษา<br class="hidden sm:inline" />
              สร้างความพร้อมสู่การเป็นบุคลากรทางการศึกษาชั้นนำระดับสากล
            </p>
          </div>

          <!-- Down Arrow Button & Action Link -->
          <div class="mt-5 sm:mt-6 flex flex-col items-center">
            <button
              type="button"
              class="w-12 h-12 rounded-full bg-slate-950 text-white flex items-center justify-center shadow-lg hover:bg-emerald-700 active:scale-95 transition-all duration-300 group cursor-pointer"
              aria-label="เลื่อนลงดูเนื้อหาเพิ่มเติม"
              @click="scrollToContent"
            >
              <v-icon
                icon="mdi-arrow-down"
                size="22"
                class="transition-transform duration-300 group-hover:translate-y-1"
              />
            </button>

            <button
              type="button"
              class="text-xs font-medium text-slate-500 hover:text-emerald-700 transition-colors mt-2 cursor-pointer"
              @click="scrollToContent"
            >
              สนใจสมัครเรียนกับเรา
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Infinite seamless horizontal marquee animation */
.marquee-track {
  display: flex;
  width: max-content;
  animation: marquee-scroll 45s linear infinite;
  will-change: transform;
}

@keyframes marquee-scroll {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}
</style>
