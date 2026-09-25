<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router'
import eduLogo from '@/assets/logos/edu-logo-border-white.png'

const router = useRouter()
const route = useRoute()

// Collapse / Rail state (default false = open)
const rail = ref(false)
const mobileMenuOpen = ref(false)

const navMenuItems = [
  {
    title: 'แดชบอร์ดภาพรวม',
    icon: 'mdi-view-dashboard-outline',
    to: '/admin',
    exact: true,
  },
  {
    title: 'จัดการข่าวสาร & ประกาศ',
    icon: 'mdi-newspaper-variant-outline',
    to: '/admin/posts',
  },
  {
    title: 'จัดการบุคลากร & อาจารย์',
    icon: 'mdi-account-group-outline',
    to: '/admin/personnel',
  },
  {
    title: 'จัดการคณะผู้บริหาร',
    icon: 'mdi-account-tie-outline',
    to: '/admin/executives',
  },
  {
    title: 'จัดการหลักสูตรการศึกษา',
    icon: 'mdi-school-outline',
    to: '/admin/curricula',
  },
  {
    title: 'จัดการข้อมูลและนโยบาย',
    icon: 'mdi-book-cog-outline',
    to: '/admin/content',
  },
]

const currentTitle = computed(() => {
  if (route.path === '/admin') return 'แดชบอร์ดภาพรวม (Dashboard Overview)'
  if (route.path === '/admin/posts/create') return 'สร้างข่าวสารใหม่'
  if (route.path.match(/\/admin\/posts\/\d+\/edit/)) return 'แก้ไขข่าวสาร'
  if (route.path.startsWith('/admin/posts')) return 'จัดการข่าวสารและกิจกรรม (News & Posts)'
  if (route.path.startsWith('/admin/personnel')) return 'จัดการบุคลากรและคณาจารย์ (Personnel)'
  if (route.path.startsWith('/admin/executives')) return 'จัดการคณะผู้บริหาร (Faculty Management)'
  if (route.path.startsWith('/admin/curricula')) return 'จัดการหลักสูตรการศึกษา (Curriculum Management)'
  if (route.path.startsWith('/admin/content')) return 'จัดการข้อมูลและนโยบาย (Content & Policy)'
  return 'ระบบจัดการหลังบ้าน'
})
</script>

<template>
  <div class="admin-wrapper min-h-screen bg-slate-50 text-slate-800">
    <!-- Mobile Backdrop Overlay -->
    <div
      v-if="mobileMenuOpen"
      class="fixed inset-0 z-40 bg-black/50 backdrop-blur-xs lg:hidden transition-opacity"
      @click="mobileMenuOpen = false"
    />

    <!-- Admin Sidebar Navigation -->
    <aside
      class="fixed top-0 bottom-0 left-0 z-50 bg-[#0f172a] border-r border-slate-800 flex flex-col transition-all duration-300 ease-in-out select-none shadow-2xl"
      :class="[
        rail ? 'w-[72px]' : 'w-[260px]',
        mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
      ]"
    >
      <!-- Brand Header -->
      <div class="h-16 px-4 flex items-center justify-between border-b border-slate-800/80 bg-slate-950/40">
        <div class="flex items-center gap-3 overflow-hidden">
          <img :src="eduLogo" alt="EDU RRU Logo" class="w-9 h-9 object-contain drop-shadow-md shrink-0" />
          <div v-if="!rail" class="overflow-hidden whitespace-nowrap">
            <div class="text-sm font-bold text-white tracking-wide leading-tight truncate">
              EDU RRU ADMIN
            </div>
            <div class="text-[11px] text-emerald-400 font-medium">
              ระบบจัดการหลังบ้าน
            </div>
          </div>
        </div>

        <button
          v-if="!rail"
          type="button"
          class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800/60 hidden lg:block transition-colors cursor-pointer"
          title="ย่อเมนูด้านข้าง"
          @click="rail = true"
        >
          <v-icon icon="mdi-chevron-left" size="18" />
        </button>
      </div>

      <!-- Navigation Menu Items -->
      <nav class="flex-1 px-2.5 py-4 space-y-1.5 overflow-y-auto">
        <RouterLink
          v-for="item in navMenuItems"
          :key="item.to"
          :to="item.to"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all no-underline group"
          :class="[
            (item.exact ? route.path === item.to : route.path.startsWith(item.to))
              ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30'
              : 'text-slate-300 hover:text-white hover:bg-slate-800/70',
            rail ? 'justify-center px-0' : '',
          ]"
          :title="rail ? item.title : undefined"
          @click="mobileMenuOpen = false"
        >
          <v-icon
            :icon="item.icon"
            size="20"
            class="shrink-0"
            :class="(item.exact ? route.path === item.to : route.path.startsWith(item.to)) ? 'text-white' : 'text-slate-400 group-hover:text-emerald-400'"
          />
          <span v-if="!rail" class="truncate">{{ item.title }}</span>
        </RouterLink>
      </nav>

      <!-- Bottom External Link & Profile -->
      <div class="p-3 border-t border-slate-800/80 bg-slate-950/50 space-y-2">
        <RouterLink
          to="/"
          target="_blank"
          class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-emerald-400 hover:bg-slate-800/60 transition-colors no-underline"
          :class="rail ? 'justify-center px-0' : ''"
          :title="rail ? 'ดูหน้าเว็บไซต์หลัก' : undefined"
        >
          <v-icon icon="mdi-open-in-new" size="18" class="shrink-0" />
          <span v-if="!rail" class="truncate">ดูหน้าเว็บไซต์หลัก</span>
        </RouterLink>

        <!-- Rail toggle button when collapsed -->
        <button
          v-if="rail"
          type="button"
          class="w-full flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors cursor-pointer"
          title="ขยายเมนูด้านข้าง"
          @click="rail = false"
        >
          <v-icon icon="mdi-chevron-right" size="18" />
        </button>

        <!-- User Profile Card -->
        <div v-if="!rail" class="pt-2 border-t border-slate-800/60 flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white shrink-0 shadow-sm">
            <v-icon icon="mdi-shield-account" size="18" />
          </div>
          <div class="min-w-0 flex-1">
            <div class="text-xs font-bold text-white truncate">ผู้ดูแลระบบ (Admin)</div>
            <div class="text-[10px] text-slate-400 truncate">admin@rru.ac.th</div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content Container (Smoothly shifts with rail state) -->
    <div
      class="min-h-screen flex flex-col transition-all duration-300 ease-in-out"
      :class="[
        rail ? 'lg:pl-[72px]' : 'lg:pl-[260px]',
        'pl-0',
      ]"
    >
      <!-- Top App Bar -->
      <header class="h-16 bg-white border-b border-slate-200/90 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <div class="flex items-center gap-3 min-w-0">
          <!-- Toggle sidebar button for desktop and mobile -->
          <button
            type="button"
            class="p-2 rounded-lg text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
            aria-label="ย่อ/ขยายเมนูนำทาง"
            @click="rail ? (rail = false) : (rail = true); mobileMenuOpen = !mobileMenuOpen"
          >
            <v-icon icon="mdi-menu" size="20" />
          </button>

          <div class="min-w-0">
            <h1 class="text-sm sm:text-base font-bold text-slate-900 leading-tight truncate">
              {{ currentTitle }}
            </h1>
            <div class="text-[11px] text-slate-500 hidden sm:block truncate">
              คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์ (EDU RRU)
            </div>
          </div>
        </div>

        <!-- Right Quick Actions -->
        <div class="flex items-center gap-2 shrink-0">
          <div class="items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold hidden md:flex">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
            <span>MySQL: nedu (Connected)</span>
          </div>

          <RouterLink
            to="/admin/posts/create"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs transition-colors no-underline"
          >
            <v-icon icon="mdi-plus" size="16" />
            <span class="hidden sm:inline">สร้างข่าวใหม่</span>
          </RouterLink>

          <!-- Profile menu dropdown -->
          <v-menu location="bottom end">
            <template #activator="{ props }">
              <button
                type="button"
                v-bind="props"
                class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 border border-slate-200 flex items-center justify-center text-slate-700 transition-colors cursor-pointer"
              >
                <v-icon icon="mdi-account" size="18" />
              </button>
            </template>
            <v-list density="compact" class="w-48 shadow-xl rounded-xl border border-slate-100 p-1">
              <v-list-item title="ผู้ดูแลระบบ (Admin)" subtitle="admin@rru.ac.th" />
              <v-divider class="my-1" />
              <v-list-item
                prepend-icon="mdi-open-in-new"
                title="หน้าเว็บหลัก"
                to="/"
                target="_blank"
              />
              <v-list-item
                prepend-icon="mdi-logout"
                title="ออกจากระบบ"
                class="text-rose-600"
                @click="router.push('/')"
              />
            </v-list>
          </v-menu>
        </div>
      </header>

      <!-- View Main Page Content -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-[1600px] mx-auto">
        <router-view />
      </main>
    </div>
  </div>
</template>

<style scoped>
.admin-wrapper {
  font-family: 'LINE Seed Sans TH', system-ui, sans-serif;
}
</style>
