<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

interface Video {
  id: string
  embedUrl: string
  title: string
  desc: string
}

const videos: Video[] = [
  {
    id: 'PaVLW-AxSg4',
    embedUrl: 'https://www.youtube.com/embed/PaVLW-AxSg4?si=cqfRx6UPzfMcgzcl',
    title: 'แนะนำคณะครุศาสตร์ มรภ.ราชนครินทร์',
    desc: 'บรรยากาศการเรียนรู้ สภาพแวดล้อม และวิสัยทัศน์ของคณะ',
  },
  {
    id: 'Q3bJIFJLcXo',
    embedUrl: 'https://www.youtube.com/embed/Q3bJIFJLcXo?si=jbMmvkrTyVa12dqd',
    title: 'กิจกรรมนักศึกษาคณะครุศาสตร์',
    desc: 'ประสบการณ์นอกห้องเรียนที่สร้างความเป็นครูมืออาชีพ',
  },
  {
    id: 'dMStpROdibc',
    embedUrl: 'https://www.youtube.com/embed/dMStpROdibc?si=OUmBTYpEuWIUGodf',
    title: 'การฝึกประสบการณ์วิชาชีพครู',
    desc: 'นักศึกษาฝึกสอนในโรงเรียนเครือข่ายคุณภาพ',
  },
  {
    id: 'fux7KZopA-I',
    embedUrl: 'https://www.youtube.com/embed/fux7KZopA-I?si=ST7x1S6Aa3Q8KQKO',
    title: 'นวัตกรรมการสอนในศตวรรษที่ 21',
    desc: 'เทคโนโลยีและนวัตกรรมการเรียนรู้ยุคใหม่',
  },
  {
    id: 'xIdv2oSBWDE',
    embedUrl: 'https://www.youtube.com/embed/xIdv2oSBWDE?si=pfTZAhc3FyGr2Wn-',
    title: 'ผลงานและรางวัลเกียรติยศ',
    desc: 'ความภาคภูมิใจของนักศึกษาและคณาจารย์',
  },
]

const activeIndex = ref(0)
const playingIndex = ref<number | null>(null)
const isPaused = ref(false)
let timer: ReturnType<typeof setInterval> | null = null

function startAutoScroll() {
  timer = setInterval(() => {
    if (!isPaused.value && playingIndex.value === null) {
      activeIndex.value = (activeIndex.value + 1) % videos.length
    }
  }, 4500)
}

function stopAutoScroll() {
  if (timer) {
    clearInterval(timer)
    timer = null
  }
}

onMounted(() => startAutoScroll())
onUnmounted(() => stopAutoScroll())

function goTo(index: number) {
  if (index === activeIndex.value) return
  playingIndex.value = null
  activeIndex.value = index
}

function prev() {
  playingIndex.value = null
  activeIndex.value = (activeIndex.value - 1 + videos.length) % videos.length
}

function next() {
  playingIndex.value = null
  activeIndex.value = (activeIndex.value + 1) % videos.length
}

function playVideo(index: number) {
  if (index !== activeIndex.value) {
    goTo(index)
    return
  }
  playingIndex.value = index
}

function getCardStyle(index: number) {
  const total = videos.length
  const diff = ((index - activeIndex.value) + total) % total
  const absDiff = diff <= Math.floor(total / 2) ? diff : diff - total

  const absD = Math.abs(absDiff)

  if (absD === 0) {
    return {
      transform: 'translateX(0%) scale(1) rotateY(0deg)',
      zIndex: 50,
      opacity: 1,
      filter: 'brightness(1)',
    }
  } else if (absD === 1) {
    const dir = absDiff > 0 ? 1 : -1
    return {
      transform: `translateX(${dir * 72}%) scale(0.75) rotateY(${dir * -15}deg)`,
      zIndex: 30,
      opacity: 0.85,
      filter: 'brightness(0.7)',
    }
  } else {
    const dir = absDiff > 0 ? 1 : -1
    return {
      transform: `translateX(${dir * 120}%) scale(0.55) rotateY(${dir * -28}deg)`,
      zIndex: 10,
      opacity: 0.6,
      filter: 'brightness(0.5)',
    }
  }
}

function isVisible(index: number): boolean {
  const total = videos.length
  const diff = ((index - activeIndex.value) + total) % total
  const absDiff = diff <= Math.floor(total / 2) ? diff : diff - total
  return Math.abs(absDiff) <= 2
}

function thumbnailUrl(id: string) {
  return `https://img.youtube.com/vi/${id}/hqdefault.jpg`
}
</script>

<template>
  <section class="w-full bg-white py-10 sm:py-14 lg:py-16 border-t border-slate-100 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Section Header -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-start mb-10 sm:mb-14">
        <!-- Left: Title -->
        <div class="lg:col-span-6 flex flex-col justify-start">
          <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-[1.2]">
            วิดีทัศน์<br class="hidden sm:inline" />
            <span class="text-emerald-700">คณะครุศาสตร์</span>
          </h2>
        </div>

        <!-- Right: Desc & Buttons -->
        <div class="lg:col-span-6 flex flex-col justify-between h-full pt-1">
          <p class="text-xs sm:text-sm lg:text-base text-slate-600 leading-relaxed">
            ร่วมสัมผัสบรรยากาศการเรียนรู้ที่ทันสมัย นวัตกรรมการสอนในศตวรรษที่ 21 และจิตวิญญาณความเป็นครูมืออาชีพ
            คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์
          </p>
          <div class="flex items-center gap-3 mt-4 sm:mt-5 flex-wrap">
            <a
              href="https://admission.rru.ac.th"
              target="_blank"
              rel="noopener noreferrer"
              class="px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-slate-950 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all duration-200 active:scale-95 no-underline flex items-center gap-2 cursor-pointer"
            >
              <v-icon icon="mdi-account-plus-outline" size="18" />
              <span>สมัครเรียน</span>
            </a>
            <RouterLink
              to="/about"
              class="px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-300 hover:border-slate-800 text-slate-800 text-xs sm:text-sm font-bold shadow-xs hover:shadow-md transition-all duration-200 active:scale-95 no-underline flex items-center gap-2 group"
            >
              <span>ดูหลักสูตร</span>
              <v-icon icon="mdi-arrow-right" size="18" class="transition-transform duration-200 group-hover:translate-x-1" />
            </RouterLink>
          </div>
        </div>
      </div>

      <!-- 3D Fan Carousel -->
      <div
        class="relative w-full"
        style="perspective: 1200px;"
        @mouseenter="isPaused = true"
        @mouseleave="isPaused = false"
      >
        <!-- Track -->
        <div class="relative h-[220px] sm:h-[300px] lg:h-[380px] flex items-center justify-center">
          <div
            v-for="(video, index) in videos"
            v-show="isVisible(index)"
            :key="video.id"
            class="absolute w-[72%] sm:w-[60%] lg:w-[55%] cursor-pointer"
            :style="{
              ...getCardStyle(index),
              transition: 'transform 0.55s cubic-bezier(0.4,0,0.2,1), opacity 0.55s ease, filter 0.55s ease',
            }"
            @click="playVideo(index)"
          >
            <!-- Card -->
            <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-slate-950 shadow-2xl ring-1 ring-slate-200/60">
              <!-- Playing Iframe -->
              <iframe
                v-if="playingIndex === index"
                :src="video.embedUrl + '&autoplay=1'"
                :title="video.title"
                class="w-full h-full border-0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen
              />
              <!-- Thumbnail Poster -->
              <template v-else>
                <img
                  :src="thumbnailUrl(video.id)"
                  :alt="video.title"
                  class="w-full h-full object-cover"
                  @error="($event.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&auto=format&fit=crop&q=80'"
                />
                <!-- Dark overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent" />

                <!-- Play button (center) — only show for active card -->
                <div
                  v-if="index === activeIndex"
                  class="absolute inset-0 flex items-center justify-center"
                >
                  <button
                    type="button"
                    class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-white flex items-center justify-center shadow-2xl hover:scale-110 active:scale-95 transition-transform duration-200 cursor-pointer"
                    aria-label="เล่นวิดีโอ"
                  >
                    <span class="absolute inset-0 rounded-full bg-white/50 animate-ping" />
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 ml-0.5 fill-emerald-700" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z" />
                    </svg>
                  </button>
                </div>

                <!-- Play icon (small) — side cards -->
                <div
                  v-else
                  class="absolute inset-0 flex items-center justify-center"
                >
                  <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <svg class="w-5 h-5 ml-0.5 fill-white" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z" />
                    </svg>
                  </div>
                </div>

                <!-- Bottom info (active card only) -->
                <div
                  v-if="index === activeIndex"
                  class="absolute bottom-3 sm:bottom-4 left-3 sm:left-5 right-3 sm:right-5 text-white pointer-events-none"
                >
                  <p class="text-xs sm:text-sm font-bold drop-shadow-md line-clamp-1">{{ video.title }}</p>
                  <p class="text-[10px] sm:text-xs text-slate-300 drop-shadow-md mt-0.5 line-clamp-1">{{ video.desc }}</p>
                </div>
              </template>
            </div>
          </div>
        </div>

        <!-- Prev / Next Arrows -->
        <button
          type="button"
          class="absolute left-0 sm:left-2 lg:-left-4 top-1/2 -translate-y-1/2 z-[60] w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white hover:bg-emerald-700 hover:text-white text-slate-700 border border-slate-200 hover:border-emerald-700 shadow-lg flex items-center justify-center transition-all duration-200 active:scale-90"
          aria-label="วิดีโอก่อนหน้า"
          @click="prev"
        >
          <v-icon icon="mdi-chevron-left" size="24" />
        </button>
        <button
          type="button"
          class="absolute right-0 sm:right-2 lg:-right-4 top-1/2 -translate-y-1/2 z-[60] w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white hover:bg-emerald-700 hover:text-white text-slate-700 border border-slate-200 hover:border-emerald-700 shadow-lg flex items-center justify-center transition-all duration-200 active:scale-90"
          aria-label="วิดีโอถัดไป"
          @click="next"
        >
          <v-icon icon="mdi-chevron-right" size="24" />
        </button>
      </div>

      <!-- Dot Indicators -->
      <div class="flex items-center justify-center gap-2.5 mt-6 sm:mt-8">
        <button
          v-for="(video, index) in videos"
          :key="video.id"
          type="button"
          class="rounded-full transition-all duration-300"
          :class="index === activeIndex
            ? 'w-6 h-2.5 bg-emerald-600'
            : 'w-2.5 h-2.5 bg-slate-300 hover:bg-slate-400'"
          :aria-label="`วิดีโอที่ ${index + 1}`"
          @click="goTo(index)"
        />
      </div>

    </div>
  </section>
</template>

<style scoped>
iframe {
  aspect-ratio: 16 / 9;
}
</style>
