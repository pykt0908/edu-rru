<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useDisplay } from 'vuetify'
import { api } from '@/services/api'

const { smAndDown, mdAndDown, lgAndDown } = useDisplay()

const carouselHeight = computed(() => {
  if (smAndDown.value) return 240
  if (mdAndDown.value) return 460
  if (lgAndDown.value) return 540
  return 620
})

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
  loadSlides()
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
        :href="slide.link_url"
        :target="slide.link_url ? slide.target : undefined"
        cover
        height="100%"
        class="border-0 bg-transparent h-full w-full"
      />

      <!-- Natural, Minimalist Glassmorphic Arrow Controls -->
      <template #prev="{ props }">
        <button
          type="button"
          class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 rounded-full bg-black/30 hover:bg-black/55 text-white/90 hover:text-white backdrop-blur-md flex items-center justify-center transition-all duration-300 hover:scale-105 active:scale-95 shadow-md ml-2 sm:ml-4 lg:ml-6 group border border-white/10"
          aria-label="สไลด์ก่อนหน้า"
          @click="props.onClick"
        >
          <v-icon icon="mdi-chevron-left" :size="smAndDown ? 20 : 28" class="transition-transform duration-200 group-hover:-translate-x-0.5" />
        </button>
      </template>

      <template #next="{ props }">
        <button
          type="button"
          class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 rounded-full bg-black/30 hover:bg-black/55 text-white/90 hover:text-white backdrop-blur-md flex items-center justify-center transition-all duration-300 hover:scale-105 active:scale-95 shadow-md mr-2 sm:mr-4 lg:mr-6 group border border-white/10"
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
/* Responsive Carousel Height - tailored to eliminate empty space on iPad Pro and tablets */
.hero-banner-carousel {
  height: 240px !important;
}

@media (min-width: 640px) {
  .hero-banner-carousel {
    height: 360px !important;
  }
}

/* iPad Pro 13" Portrait and 1024px Tablets */
@media (min-width: 1024px) {
  .hero-banner-carousel {
    height: 460px !important;
  }
}

/* iPad Pro 13" Landscape and Standard Laptops */
@media (min-width: 1280px) {
  .hero-banner-carousel {
    height: 540px !important;
  }
}

/* Desktop and Large Screens */
@media (min-width: 1536px) {
  .hero-banner-carousel {
    height: 620px !important;
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
