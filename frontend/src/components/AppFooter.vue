<script setup lang="ts">
import { RouterLink } from 'vue-router'
import eduLogo from '@/assets/logos/edu-logo-border-white.png'

const faculties = [
  { label: 'คณะครุศาสตร์', href: '#' },
  { label: 'คณะมนุษยศาสตร์และสังคมศาสตร์', href: '#' },
  { label: 'คณะวิทยาการจัดการ', href: '#' },
  { label: 'คณะวิทยาศาสตร์และเทคโนโลยี', href: '#' },
  { label: 'คณะเทคโนโลยีอุตสาหกรรม', href: '#' },
  { label: 'คณะพยาบาลศาสตร์', href: '#' },
]

const departments = [
  { label: 'สำนักส่งเสริมวิชาการและงานทะเบียน', href: '#' },
  { label: 'สำนักวิทยบริการและเทคโนโลยีสารสนเทศ', href: '#' },
  { label: 'ศูนย์จิตอาสา วัฒนธรรมและท้องถิ่น', href: '#' },
  { label: 'สถาบันเกษตรกรพัฒนา', href: '#' },
  { label: 'สถาบันวิจัยและพัฒนา', href: '#' },
]

const socials = [
  { icon: 'mdi-facebook', href: 'https://facebook.com', label: 'Facebook' },
  { icon: 'mdi-youtube', href: 'https://youtube.com', label: 'YouTube' },
  { icon: 'mdi-instagram', href: 'https://instagram.com', label: 'Instagram' },
  { icon: 'mdi-email-outline', href: 'mailto:edu@rru.ac.th', label: 'อีเมล' },
]

const navLinks = [
  { label: 'หน้าแรก', to: '/' },
  { label: 'เกี่ยวกับคณะ', to: '/about' },
  { label: 'SDGs', to: '/sdgs' },
  { label: 'งานบริการนักศึกษา', to: '/student-services', disabled: true },
  { label: 'งานวางแผน', to: '/planning', disabled: true },
  { label: 'งานกิจกรรมนักศึกษา', to: '/student-activities', disabled: true },
  { label: 'ติดต่อเรา', to: '/contact' },
]
</script>

<template>
  <footer class="relative bg-slate-950 text-white overflow-hidden">
    <!-- Subtle emerald gradient glow top-left -->
    <div class="pointer-events-none absolute -top-32 -left-32 w-[480px] h-[480px] rounded-full bg-emerald-700/10 blur-3xl" />
    <div class="pointer-events-none absolute -bottom-24 right-0 w-[320px] h-[320px] rounded-full bg-emerald-600/8 blur-3xl" />

    <!-- Main footer body -->
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 sm:pt-16 pb-10">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8">

        <!-- Col 1: Brand / Logo (spans 3 cols) -->
        <div class="lg:col-span-3 flex flex-col gap-5">
          <!-- Logo + Name -->
          <RouterLink to="/" class="flex items-center gap-3 no-underline group w-fit">
            <div class="w-14 h-14 rounded-2xl bg-white/5 ring-1 ring-white/10 flex items-center justify-center shrink-0 group-hover:ring-emerald-500/50 transition-all duration-300">
              <img
                :src="eduLogo"
                alt="โลโก้คณะครุศาสตร์"
                class="w-11 h-11 object-contain drop-shadow"
              />
            </div>
            <div>
              <p class="font-bold text-white text-sm leading-snug">คณะครุศาสตร์</p>
              <p class="text-slate-400 text-[11px] leading-snug mt-0.5">มหาวิทยาลัยราชภัฏราชนครินทร์</p>
            </div>
          </RouterLink>

          <!-- Motto -->
          <div class="border-l-2 border-emerald-500 pl-3">
            <p class="text-emerald-400 text-xs font-semibold leading-relaxed italic">"สร้างครูดี มีความรู้ สู่สังคม"</p>
            <p class="text-slate-400 text-[11px] mt-1 leading-relaxed">มุ่งมั่นผลิตครูและบุคลากรทางการศึกษาที่มีคุณภาพ</p>
          </div>

          <!-- Social icons -->
          <div class="flex items-center gap-2">
            <a
              v-for="s in socials"
              :key="s.label"
              :href="s.href"
              :aria-label="s.label"
              target="_blank"
              rel="noopener noreferrer"
              class="w-9 h-9 rounded-xl bg-white/5 hover:bg-emerald-600 ring-1 ring-white/10 hover:ring-emerald-500 flex items-center justify-center text-slate-400 hover:text-white transition-all duration-200 active:scale-90"
            >
              <v-icon :icon="s.icon" size="18" />
            </a>
          </div>
        </div>

        <!-- Col 2: คณะอื่น ๆ (spans 3 cols) -->
        <div class="lg:col-span-3">
          <h3 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full bg-emerald-500 shrink-0" />
            คณะอื่น ๆ
          </h3>
          <ul class="space-y-2.5">
            <li v-for="f in faculties" :key="f.label">
              <a
                :href="f.href"
                class="text-slate-400 hover:text-emerald-400 text-xs leading-snug transition-colors duration-150 flex items-start gap-1.5 no-underline group"
              >
                <v-icon icon="mdi-chevron-right" size="14" class="shrink-0 mt-0.5 text-slate-600 group-hover:text-emerald-500 transition-colors" />
                {{ f.label }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Col 3: หน่วยงาน (spans 3 cols) -->
        <div class="lg:col-span-3">
          <h3 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full bg-emerald-500 shrink-0" />
            หน่วยงาน
          </h3>
          <ul class="space-y-2.5 mb-6">
            <li v-for="d in departments" :key="d.label">
              <a
                :href="d.href"
                class="text-slate-400 hover:text-emerald-400 text-xs leading-snug transition-colors duration-150 flex items-start gap-1.5 no-underline group"
              >
                <v-icon icon="mdi-chevron-right" size="14" class="shrink-0 mt-0.5 text-slate-600 group-hover:text-emerald-500 transition-colors" />
                {{ d.label }}
              </a>
            </li>
          </ul>

          <h3 class="text-white font-bold text-sm mb-3 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full bg-emerald-500 shrink-0" />
            เมนูหลัก
          </h3>
          <div class="flex flex-wrap gap-x-4 gap-y-2">
            <template v-for="n in navLinks" :key="n.label">
              <span
                v-if="n.disabled"
                class="text-slate-600 cursor-not-allowed text-xs select-none pointer-events-none"
                title="ยังไม่มีเนื้อหา"
              >
                {{ n.label }}
              </span>
              <RouterLink
                v-else
                :to="n.to"
                class="text-slate-400 hover:text-emerald-400 text-xs transition-colors duration-150 no-underline"
              >
                {{ n.label }}
              </RouterLink>
            </template>
          </div>
        </div>

        <!-- Col 4: ติดต่อเรา (spans 3 cols) -->
        <div class="lg:col-span-3">
          <h3 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full bg-emerald-500 shrink-0" />
            ติดต่อเรา
          </h3>

          <ul class="space-y-3.5">
            <!-- Address -->
            <li class="flex items-start gap-3">
              <div class="w-7 h-7 rounded-lg bg-emerald-900/50 flex items-center justify-center shrink-0 mt-0.5">
                <v-icon icon="mdi-map-marker-outline" size="16" class="text-emerald-400" />
              </div>
              <p class="text-slate-400 text-xs leading-relaxed">
                คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์<br />
                422 ถ.มรุพงษ์ ต.หน้าเมือง อ.เมือง<br />
                จ.ฉะเชิงเทรา 24000
              </p>
            </li>

            <!-- Phone -->
            <li class="flex items-center gap-3">
              <div class="w-7 h-7 rounded-lg bg-emerald-900/50 flex items-center justify-center shrink-0">
                <v-icon icon="mdi-phone-outline" size="16" class="text-emerald-400" />
              </div>
              <a href="tel:038511140" class="text-slate-400 hover:text-emerald-400 text-xs transition-colors no-underline">
                038-511-140 ต่อ 1234
              </a>
            </li>

            <!-- Email -->
            <li class="flex items-center gap-3">
              <div class="w-7 h-7 rounded-lg bg-emerald-900/50 flex items-center justify-center shrink-0">
                <v-icon icon="mdi-email-outline" size="16" class="text-emerald-400" />
              </div>
              <a href="mailto:edu@rru.ac.th" class="text-slate-400 hover:text-emerald-400 text-xs transition-colors no-underline">
                edu@rru.ac.th
              </a>
            </li>

            <!-- Hours -->
            <li class="flex items-center gap-3">
              <div class="w-7 h-7 rounded-lg bg-emerald-900/50 flex items-center justify-center shrink-0">
                <v-icon icon="mdi-clock-outline" size="16" class="text-emerald-400" />
              </div>
              <p class="text-slate-400 text-xs">จันทร์ – ศุกร์: 08:30 – 16:30 น.</p>
            </li>
          </ul>
        </div>

      </div>

      <!-- Divider -->
      <div class="mt-12 pt-6 border-t border-white/8 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-slate-500 text-xs text-center sm:text-left">
          © 2569 คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์ สงวนลิขสิทธิ์
        </p>
        <div class="flex items-center gap-4">
          <a href="#" class="text-slate-500 hover:text-slate-300 text-xs transition-colors no-underline">นโยบายความเป็นส่วนตัว</a>
          <span class="text-slate-700 text-xs">|</span>
          <a href="#" class="text-slate-500 hover:text-slate-300 text-xs transition-colors no-underline">เงื่อนไขการใช้งาน</a>
          <span class="text-slate-700 text-xs">|</span>
          <RouterLink to="/admin" class="text-emerald-400 hover:text-emerald-300 text-xs font-medium transition-colors no-underline flex items-center gap-1">
            <v-icon icon="mdi-shield-lock-outline" size="13" />
            <span>เข้าสู่ระบบจัดการหลังบ้าน</span>
          </RouterLink>
        </div>
      </div>
    </div>
  </footer>
</template>
