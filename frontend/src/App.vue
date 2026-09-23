<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { RouterLink, RouterView, useRoute } from 'vue-router'
import Lenis from 'lenis'
import 'lenis/dist/lenis.css'
import eduLogo from '@/assets/logos/edu-logo-border-white.png'
import AppFooter from '@/components/AppFooter.vue'

const route = useRoute()
const isAboutActive = computed(() => route.path.startsWith('/about') || route.path.startsWith('/curriculum'))

const drawer = ref(false)
const isScrolled = ref(false)
const aboutOpen = ref(false)
const mobileAboutOpen = ref(false)

let lenis: Lenis | null = null

const handleScroll = () => {
  isScrolled.value = window.scrollY > 40
}

onMounted(() => {
  // Initialize buttery-smooth inertia scrolling across the entire site
  lenis = new Lenis({
    autoRaf: true,
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    smoothWheel: true,
  })

  // Synchronize navbar pill state with Lenis scroll
  lenis.on('scroll', (e: { scroll: number }) => {
    isScrolled.value = e.scroll > 40
  })

  // Expose lenis globally for smooth anchor scrolling
  // @ts-expect-error global lenis instance
  window.__lenis = lenis

  window.addEventListener('scroll', handleScroll, { passive: true })
  handleScroll()
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  if (lenis) {
    lenis.destroy()
    lenis = null
    // @ts-expect-error global lenis instance
    window.__lenis = null
  }
})

const navItems = [
  { label: 'SDGs', to: '/sdgs', icon: 'mdi-earth' },
  { label: 'งานบริการนักศึกษา', to: '/student-services', icon: 'mdi-account-school-outline' },
  { label: 'งานวางแผน', to: '/planning', icon: 'mdi-chart-timeline-variant' },
  { label: 'งานกิจกรรมนักศึกษา', to: '/student-activities', icon: 'mdi-account-group-outline' },
  { label: 'ติดต่อ', to: '/contact', icon: 'mdi-phone-outline' },
]

const aboutSubMenu = [
  { label: 'ประวัติคณะ', to: '/about/history', icon: 'mdi-book-open-page-variant-outline', desc: 'ความเป็นมาตั้งแต่ พ.ศ. 2483' },
  { label: 'ปรัชญา วิสัยทัศน์ และพันธกิจ', to: '/about/philosophy', icon: 'mdi-lightbulb-outline', desc: 'อัตลักษณ์และทิศทางของคณะ' },
  { label: 'คณะผู้บริหาร', to: '/about/management', icon: 'mdi-account-tie-outline', desc: 'คณบดี รองคณบดี และผู้ช่วยคณบดี' },
  { label: 'คณะกรรมการคณะครุศาสตร์', to: '/about/committee', icon: 'mdi-account-group-outline', desc: 'คณะกรรมการประจำคณะครุศาสตร์' },
  { label: 'การประเมินคุณธรรมและความโปร่งใส (ITA)', to: '/about/ita', icon: 'mdi-shield-check-outline', desc: 'การประเมินคุณธรรมและความโปร่งใส' },
]

const curriculumPrograms = [
  { label: 'ปริญญาตรี', to: '/curriculum/bachelor', icon: 'mdi-school', desc: 'หลักสูตรครุศาสตรบัณฑิต' },
  { label: 'ประกาศนียบัตรบัณฑิตวิชาชีพครู', to: '/curriculum/grad-diploma', icon: 'mdi-certificate-outline', desc: 'หลักสูตร ป.บัณฑิตวิชาชีพครู' },
  { label: 'ปริญญาโท', to: '/curriculum/master', icon: 'mdi-book-education-outline', desc: 'หลักสูตรครุศาสตรมหาบัณฑิต' },
]
</script>

<template>
  <v-app>
    <!-- Mobile Navigation Drawer -->
    <v-navigation-drawer
      v-model="drawer"
      temporary
      location="right"
      width="300"
      class="lg:hidden"
    >
      <div class="p-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-2.5 min-w-0">
          <img :src="eduLogo" alt="โลโก้คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์" class="w-10 h-10 object-contain shrink-0 drop-shadow-xs" />
          <div class="min-w-0">
            <span class="font-bold text-slate-800 text-xs block leading-tight truncate">คณะครุศาสตร์</span>
            <span class="text-[10px] text-slate-500 block leading-tight mt-0.5 truncate">มหาวิทยาลัยราชภัฏราชนครินทร์</span>
          </div>
        </div>
        <v-btn icon="mdi-close" variant="text" size="small" aria-label="ปิดเมนู" @click="drawer = false" />
      </div>

      <v-list nav density="comfortable" class="p-3 space-y-1">
        <!-- หน้าแรก -->
        <v-list-item
          to="/"
          prepend-icon="mdi-home-outline"
          title="หน้าแรก"
          rounded="lg"
          active-color="primary"
          class="!min-h-[44px] text-slate-700"
          @click="drawer = false"
        />

        <!-- เกี่ยวกับคณะ accordion -->
        <div>
          <button
            type="button"
            class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 text-sm font-medium min-h-[44px] cursor-pointer"
            @click="mobileAboutOpen = !mobileAboutOpen"
          >
            <v-icon icon="mdi-school-outline" size="20" />
            <span class="flex-1 text-left">เกี่ยวกับคณะ</span>
            <v-icon
              icon="mdi-chevron-down"
              size="18"
              class="transition-transform duration-200"
              :class="mobileAboutOpen ? 'rotate-180 text-emerald-600' : 'text-slate-400'"
            />
          </button>
          <div v-show="mobileAboutOpen" class="pl-4 mt-1 space-y-1">
            <RouterLink
              v-for="sub in aboutSubMenu"
              :key="sub.to"
              :to="sub.to"
              class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 no-underline min-h-[40px] transition-colors"
              @click="drawer = false; mobileAboutOpen = false"
            >
              <v-icon :icon="sub.icon" size="18" />
              <span>{{ sub.label }}</span>
            </RouterLink>

            <!-- Submenu หลักสูตร -->
            <div class="pt-3 pb-1 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-600" />
              <span>หลักสูตรที่เปิดสอน</span>
            </div>
            <RouterLink
              v-for="sub in curriculumPrograms"
              :key="sub.to"
              :to="sub.to"
              class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 no-underline min-h-[40px] transition-colors"
              @click="drawer = false; mobileAboutOpen = false"
            >
              <v-icon :icon="sub.icon" size="18" />
              <span>{{ sub.label }}</span>
            </RouterLink>
          </div>
        </div>

        <!-- Other nav items -->
        <v-list-item
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          :prepend-icon="item.icon"
          :title="item.label"
          rounded="lg"
          active-color="primary"
          class="!min-h-[44px] text-slate-700"
          @click="drawer = false"
        />
      </v-list>

      <template #append>
        <div class="p-4 border-t border-slate-100 text-xs text-slate-500 space-y-1">
          <div class="flex items-center gap-2">
            <v-icon icon="mdi-phone" size="14" color="primary" />
            <span>038-511-140 ต่อ 1234</span>
          </div>
          <div class="flex items-center gap-2">
            <v-icon icon="mdi-email" size="14" color="primary" />
            <span>edu@rru.ac.th</span>
          </div>
        </div>
      </template>
    </v-navigation-drawer>

    <!-- Fixed Top Navigation Bar (Morphs to Pill on Scroll) -->
    <header
      class="fixed top-0 inset-x-0 z-50 w-full transition-[padding,background-color] duration-300 border-0"
      :class="[
        isScrolled
          ? 'pt-2.5 sm:pt-4 px-3 sm:px-6 pointer-events-none bg-transparent shadow-none'
          : 'pt-0 px-0 pointer-events-auto bg-white/95 backdrop-blur-md shadow-xs'
      ]"
    >
      <div
        class="transition-[max-width,height,padding,border-radius,box-shadow,background-color,border-color] duration-300 ease-out flex items-center justify-between gap-3 sm:gap-4 pointer-events-auto border"
        :class="[
          isScrolled
            ? 'max-w-5xl xl:max-w-6xl mx-auto px-4 sm:px-6 h-14 sm:h-16 rounded-full bg-white/92 backdrop-blur-xl border-slate-200/90 shadow-xl shadow-slate-900/10'
            : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 sm:h-20 rounded-none bg-transparent border-transparent'
        ]"
      >
        <!-- Logo / Brand -->
        <RouterLink to="/" class="flex items-center gap-2.5 sm:gap-3 shrink-0 no-underline group py-1">
          <img
            :src="eduLogo"
            alt="โลโก้คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์"
            class="shrink-0 object-contain drop-shadow-sm transition-all duration-300 group-hover:scale-105"
            :class="isScrolled ? 'w-9 h-9 sm:w-11 sm:h-11' : 'w-11 h-11 sm:w-14 sm:h-14 lg:w-16 lg:h-16'"
          />
          <div class="shrink-0">
            <span
              class="font-bold text-slate-800 tracking-tight block leading-tight whitespace-nowrap transition-all duration-300"
              :class="isScrolled ? 'text-xs sm:text-sm' : 'text-xs sm:text-sm md:text-base xl:text-lg'"
            >
              คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์
            </span>
            <span
              class="text-slate-500 leading-tight whitespace-nowrap transition-all duration-300"
              :class="isScrolled ? 'hidden xl:block text-[10px] mt-0.5' : 'text-[10px] sm:text-[11px] xl:text-xs block mt-0.5'"
            >
              Faculty of Education, Rajabhatrajanagarinda University
            </span>
          </div>
        </RouterLink>

        <!-- Desktop Navigation Links -->
        <nav class="hidden lg:flex items-center gap-0.5 xl:gap-1.5 h-full shrink-0">
          <!-- Home -->
          <RouterLink
            to="/"
            class="relative h-full px-2 xl:px-2.5 font-medium text-slate-600 hover:text-emerald-700 transition-all flex items-center gap-1 xl:gap-1.5 shrink-0 whitespace-nowrap"
            :class="isScrolled ? 'text-xs xl:text-xs py-1' : 'text-xs xl:text-sm'"
            active-class="!text-emerald-700 font-semibold"
          >
            <v-icon icon="mdi-home-outline" :size="isScrolled ? 16 : 18" />
            <span>หน้าแรก</span>
          </RouterLink>

          <!-- เกี่ยวกับคณะ dropdown -->
          <div
            class="relative h-full flex items-center"
            @mouseenter="aboutOpen = true"
            @mouseleave="aboutOpen = false"
          >
            <button
              type="button"
              class="relative h-full px-2 xl:px-2.5 font-medium text-slate-600 hover:text-emerald-700 transition-all flex items-center gap-1 xl:gap-1.5 shrink-0 whitespace-nowrap cursor-pointer select-none border-0"
              :class="[
                isScrolled ? 'text-xs xl:text-xs py-1' : 'text-xs xl:text-sm',
                aboutOpen || isAboutActive ? '!text-emerald-700 font-semibold' : ''
              ]"
            >
              <v-icon icon="mdi-school-outline" :size="isScrolled ? 16 : 18" />
              <span>เกี่ยวกับคณะ</span>
              <v-icon
                icon="mdi-chevron-down"
                :size="isScrolled ? 14 : 16"
                class="transition-transform duration-200"
                :class="aboutOpen ? 'rotate-180' : ''"
              />
            </button>

            <!-- Megamenu panel -->
            <transition
              enter-active-class="transition ease-out duration-200"
              enter-from-class="opacity-0 translate-y-2 scale-98"
              enter-to-class="opacity-100 translate-y-0 scale-100"
              leave-active-class="transition ease-in duration-150"
              leave-from-class="opacity-100 translate-y-0 scale-100"
              leave-to-class="opacity-0 translate-y-2 scale-98"
            >
              <div
                v-show="aboutOpen"
                class="absolute top-full left-1/2 -translate-x-1/2 pt-2 z-50 pointer-events-auto"
              >
                <!-- Container Card -->
                <div class="w-[720px] max-w-[calc(100vw-2rem)] bg-white/98 backdrop-blur-xl rounded-2xl shadow-2xl shadow-slate-900/15 border border-slate-200/90 overflow-hidden text-slate-800">
                  <!-- 2-Column Grid -->
                  <div class="grid grid-cols-12 divide-x divide-slate-100">
                    <!-- Column 1: ข้อมูลและโครงสร้างคณะ (col-span-6) -->
                    <div class="col-span-6 p-5 space-y-3">
                      <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <div class="w-6 h-6 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center">
                          <v-icon icon="mdi-domain" size="14" />
                        </div>
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">ข้อมูลและโครงสร้างคณะ</span>
                      </div>

                      <div class="space-y-1">
                        <RouterLink
                          v-for="sub in aboutSubMenu"
                          :key="sub.to"
                          :to="sub.to"
                          class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-emerald-50/80 transition-all duration-150 no-underline group"
                          @click="aboutOpen = false"
                        >
                          <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100/80 text-emerald-700 flex items-center justify-center shrink-0 group-hover:bg-emerald-700 group-hover:text-white group-hover:border-emerald-700 transition-all duration-200">
                            <v-icon :icon="sub.icon" size="16" />
                          </div>
                          <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 transition-colors leading-snug truncate">
                              {{ sub.label }}
                            </p>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-snug line-clamp-1">
                              {{ sub.desc }}
                            </p>
                          </div>
                        </RouterLink>
                      </div>
                    </div>

                    <!-- Column 2: หลักสูตรที่เปิดสอน (col-span-6) -->
                    <div class="col-span-6 p-5 flex flex-col justify-start bg-slate-50/50">
                      <div>
                        <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                          <div class="w-6 h-6 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <v-icon icon="mdi-school" size="14" />
                          </div>
                          <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">หลักสูตรที่เปิดสอน</span>
                        </div>

                        <div class="mt-3 space-y-1">
                          <RouterLink
                            v-for="sub in curriculumPrograms"
                            :key="sub.to"
                            :to="sub.to"
                            class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-emerald-50/80 transition-all duration-150 no-underline group"
                            @click="aboutOpen = false"
                          >
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100/80 text-emerald-700 flex items-center justify-center shrink-0 group-hover:bg-emerald-700 group-hover:text-white group-hover:border-emerald-700 transition-all duration-200">
                              <v-icon :icon="sub.icon" size="16" />
                            </div>
                            <div class="min-w-0 flex-1">
                              <p class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 transition-colors leading-snug truncate">
                                {{ sub.label }}
                              </p>
                              <p class="text-[11px] text-slate-500 mt-0.5 leading-snug line-clamp-1">
                                {{ sub.desc }}
                              </p>
                            </div>
                          </RouterLink>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Megamenu Footer -->
                  <div class="bg-slate-100/80 px-5 py-2.5 border-t border-slate-200/70 flex items-center justify-between text-[11px] text-slate-500">
                    <div class="flex items-center gap-2">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-600" />
                      <span>คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์</span>
                    </div>
                    <RouterLink
                      to="/contact"
                      class="text-emerald-700 hover:text-emerald-800 font-semibold no-underline flex items-center gap-1"
                      @click="aboutOpen = false"
                    >
                      <span>ติดต่อสอบถาม</span>
                      <v-icon icon="mdi-chevron-right" size="14" />
                    </RouterLink>
                  </div>
                </div>
              </div>
            </transition>
          </div>

          <!-- Other nav items -->
          <RouterLink
            v-for="item in navItems"
            :key="item.to"
            :to="item.to"
            class="relative h-full px-2 xl:px-2.5 font-medium text-slate-600 hover:text-emerald-700 transition-all flex items-center gap-1 xl:gap-1.5 shrink-0 whitespace-nowrap"
            :class="isScrolled ? 'text-xs xl:text-xs py-1' : 'text-xs xl:text-sm'"
            active-class="!text-emerald-700 font-semibold"
          >
            <v-icon :icon="item.icon" :size="isScrolled ? 16 : 18" />
            <span>{{ item.label }}</span>
          </RouterLink>
        </nav>

        <!-- Mobile Menu Toggle Button -->
        <div class="flex items-center lg:hidden shrink-0">
          <v-btn
            icon="mdi-menu"
            variant="text"
            color="slate-700"
            :size="isScrolled ? 'small' : 'default'"
            aria-label="เปิดเมนูนำทาง"
            class="!min-w-[40px] !min-h-[40px]"
            @click="drawer = !drawer"
          />
        </div>
      </div>
    </header>

    <!-- Header Spacer to preserve document layout flow -->
    <div class="h-18 sm:h-20 w-full shrink-0" aria-hidden="true" />

    <!-- Main Content -->
    <v-main class="bg-white min-h-screen">
      <RouterView />
    </v-main>

    <!-- Footer -->
    <AppFooter />
  </v-app>
</template>

<style>
/* Vuetify typography inheritance */
.v-application {
  font-family: inherit !important;
}

/* Remove background overlay on drawer menu items */
.v-list-item--nav .v-list-item__overlay {
  display: none !important;
}
</style>
