<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useDisplay } from 'vuetify'
import { api } from '@/services/api'

const router = useRouter()
const { smAndDown } = useDisplay()

const windowWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1920)

const onResize = () => {
  if (typeof window !== 'undefined') {
    windowWidth.value = window.innerWidth
  }
}

// Exactly match 1920 x 800 (2.4 : 1) aspect ratio
const carouselHeight = computed(() => {
  const h = Math.round(windowWidth.value * (800 / 1920))
  return Math.min(Math.max(h, 180), 800)
})

const handleSlideClick = (e: MouseEvent, slide: SlideItem) => {
  if (!slide.link_url) return
  // If it is an internal router path (e.g. /posts/1, /about/history)
  if (slide.link_url.startsWith('/') && !slide.link_url.startsWith('//')) {
    e.preventDefault()
    router.push(slide.link_url)
  }
}

interface SlideItem {
  id?: number
  src: string
  alt: string
  link_url?: string
  target?: string
}

const defaultSlides: SlideItem[] = [
  {
    src: 'https://placehold.co/1920x800/0F5132/ffffff?text=Banner+Slide+1+(1920x800)',
    alt: 'Banner Slide 1',
  },
  {
    src: 'https://placehold.co/1920x800/157347/ffffff?text=Banner+Slide+2+(1920x800)',
    alt: 'Banner Slide 2',
  },
  {
    src: 'https://placehold.co/1920x800/0A3622/ffffff?text=Banner+Slide+3+(1920x800)',
    alt: 'Banner Slide 3',
  },
  {
    src: 'https://placehold.co/1920x800/1e293b/ffffff?text=Banner+Slide+4+(1920x800)',
    alt: 'Banner Slide 4',
  },
]

const slides = ref<SlideItem[]>(defaultSlides)

const loadSlides = async () => {
  try {
    const data = await api.getCarouselSlides({ active_only: true })
    if (data && data.length > 0) {
      slides.value = data.map((item) => ({
        id: item.id,
        src: item.image_url,
        alt: item.alt_text || item.title || 'แบนเนอร์คณะครุศาสตร์',
        link_url: item.link_url || undefined,
        target: item.target || '_self',
      }))
    }
  } catch (err) {
    console.warn('Failed to load carousel slides from API, using default slides:', err)
  }
}

onMounted(() => {
  if (typeof window !== 'undefined') {
    window.addEventListener('resize', onResize)
    onResize()
  }
  loadSlides()
})

onUnmounted(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('resize', onResize)
  }
})
</script>

<template>
  <div class="w-full flex flex-col overflow-hidden bg-white border-0 p-0 m-0 leading-none">
    <v-carousel
      cycle
      :interval="5000"
      hide-delimiters
      hide-delimiter-background
      show-arrows="hover"
      :height="carouselHeight"
      class="hero-banner-carousel w-full border-0 shadow-none bg-white !m-0 !p-0"
    >
      <v-carousel-item
        v-for="(slide, i) in slides"
        :key="slide.id || i"
        :src="slide.src"
        :alt="slide.alt"
        cover
        height="100%"
        class="border-0 bg-transparent h-full w-full relative"
      >
        <!-- Full Clickable Overlay Anchor if slide has link_url -->
        <a
          v-if="slide.link_url"
          :href="slide.link_url"
          :target="slide.target || '_self'"
          :rel="slide.target === '_blank' ? 'noopener noreferrer' : undefined"
          class="absolute inset-0 z-10 w-full h-full block cursor-pointer select-none"
          :title="slide.alt || 'คลิกเพื่อดูรายละเอียด'"
          @click="handleSlideClick($event, slide)"
        />
      </v-carousel-item>

      <!-- Natural, Minimalist Glassmorphic Arrow Controls -->
      <template #prev="{ props }">
        <button
          type="button"
          class="relative z-20 w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 rounded-full bg-black/30 hover:bg-black/55 text-white/90 hover:text-white backdrop-blur-md flex items-center justify-center transition-all duration-300 hover:scale-105 active:scale-95 shadow-md ml-2 sm:ml-4 lg:ml-6 group border border-white/10"
          aria-label="สไลด์ก่อนหน้า"
          @click="props.onClick"
        >
          <v-icon icon="mdi-chevron-left" :size="smAndDown ? 20 : 28" class="transition-transform duration-200 group-hover:-translate-x-0.5" />
        </button>
      </template>

      <template #next="{ props }">
        <button
          type="button"
          class="relative z-20 w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 rounded-full bg-black/30 hover:bg-black/55 text-white/90 hover:text-white backdrop-blur-md flex items-center justify-center transition-all duration-300 hover:scale-105 active:scale-95 shadow-md mr-2 sm:mr-4 lg:mr-6 group border border-white/10"
          aria-label="สไลด์ถัดไป"
          @click="props.onClick"
        >
          <v-icon icon="mdi-chevron-right" :size="smAndDown ? 20 : 28" class="transition-transform duration-200 group-hover:translate-x-0.5" />
        </button>
      </template>
    </v-carousel>
  </div>
</template>

<style>
/* Responsive Carousel Height - exact 1920x800 (2.4 : 1) aspect ratio */
.hero-banner-carousel {
  width: 100% !important;
  aspect-ratio: 1920 / 800 !important;
  height: calc(100vw * 800 / 1920) !important;
  max-height: 800px !important;
}

@media (max-width: 480px) {
  .hero-banner-carousel {
    min-height: 180px !important;
  }
}

@media (min-width: 1920px) {
  .hero-banner-carousel {
    height: 800px !important;
  }
}

/* Remove all borders, backgrounds, and black bars */
.hero-banner-carousel,
.hero-banner-carousel .v-window,
.hero-banner-carousel .v-window__container,
.hero-banner-carousel .v-window-item,
.hero-banner-carousel .v-carousel-item,
.hero-banner-carousel .v-responsive,
.hero-banner-carousel .v-img,
.hero-banner-carousel .v-img__img,
.hero-banner-carousel img {
  background-color: transparent !important;
  border: none !important;
  box-shadow: none !important;
  outline: none !important;
  margin: 0 !important;
  padding: 0 !important;
}

/* Force 100% height and remove the intrinsic aspect-ratio sizer padding that creates the bottom blank gap */
.hero-banner-carousel .v-window__container,
.hero-banner-carousel .v-window-item,
.hero-banner-carousel .v-carousel-item,
.hero-banner-carousel .v-responsive,
.hero-banner-carousel .v-img {
  height: 100% !important;
  max-height: 100% !important;
}

.hero-banner-carousel .v-img__img,
.hero-banner-carousel img {
  height: 100% !important;
  width: 100% !important;
  object-fit: cover !important;
  object-position: center !important;
}

.hero-banner-carousel .v-responsive__sizer {
  display: none !important;
  padding-bottom: 0 !important;
}

.hero-banner-carousel .v-carousel__progress,
.hero-banner-carousel .v-carousel__controls,
.v-carousel__controls,
.v-carousel__progress {
  display: none !important;
  height: 0 !important;
  min-height: 0 !important;
  max-height: 0 !important;
  opacity: 0 !important;
  visibility: hidden !important;
  background: transparent !important;
  border: none !important;
  pointer-events: none !important;
}
</style>
