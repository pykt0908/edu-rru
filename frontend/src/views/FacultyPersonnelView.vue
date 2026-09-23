<script setup lang="ts">
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'
import PageHeroBanner from '@/components/PageHeroBanner.vue'
import { departments, type Department } from '@/data/personnelData'

const selectedDepartment = ref('all')
const searchQuery = ref('')

const isDepartmentDropdownOpen = ref(false)

const currentDepartment = computed(() => {
  if (selectedDepartment.value === 'all') {
    return { id: 'all', name: 'ทุกสาขาวิชา (ทั้งหมด)', count: totalPersonnelCount.value }
  }
  const found = departments.find((d) => d.id === selectedDepartment.value)
  return found
    ? { id: found.id, name: found.name, count: found.members.length }
    : { id: 'all', name: 'ทุกสาขาวิชา (ทั้งหมด)', count: totalPersonnelCount.value }
})

const totalPersonnelCount = computed(() => {
  return departments.reduce((acc, d) => acc + d.members.length, 0)
})

const filteredPersonnelCount = computed(() => {
  return filteredDepartments.value.reduce((acc, d) => acc + d.members.length, 0)
})

const selectDepartment = (id: string) => {
  selectedDepartment.value = id
  isDepartmentDropdownOpen.value = false
}

const clearFilters = () => {
  selectedDepartment.value = 'all'
  searchQuery.value = ''
  isDepartmentDropdownOpen.value = false
}

// Filtered departments based on selection and search
const filteredDepartments = computed(() => {
  let list = departments

  // Department filter
  if (selectedDepartment.value !== 'all') {
    list = list.filter((dept) => dept.id === selectedDepartment.value)
  }

  // Search filter
  const query = searchQuery.value.trim().toLowerCase()
  if (!query) return list

  return list
    .map((dept) => {
      const deptMatches = dept.name.toLowerCase().includes(query)
      const matchedMembers = dept.members.filter(
        (m) =>
          m.name.toLowerCase().includes(query) ||
          m.roleTitle.toLowerCase().includes(query) ||
          (m.degrees && m.degrees.toLowerCase().includes(query))
      )

      if (deptMatches || matchedMembers.length > 0) {
        return {
          ...dept,
          members: deptMatches ? dept.members : matchedMembers,
        }
      }
      return null
    })
    .filter((dept): dept is Department => dept !== null)
})
</script>

<template>
  <div class="min-h-screen bg-white pb-24 relative">
    <!-- Click-away backdrop for dropdown -->
    <div
      v-if="isDepartmentDropdownOpen"
      class="fixed inset-0 z-20"
      @click="isDepartmentDropdownOpen = false"
    />

    <!-- Hero Banner (Follows rule.md Section 13) -->
    <PageHeroBanner
      badge="คณาจารย์และบุคลากร"
      badge-icon="mdi-account-group"
      title="คณาจารย์ประจำสาขาวิชา"
      title-highlight="คณะครุศาสตร์"
      subtitle="ทำเนียบคณาจารย์ แยกตามสาขาวิชา ประกอบด้วยประธานสาขาวิชาและอาจารย์ประจำสาขาวิชา มหาวิทยาลัยราชภัฏราชนครินทร์"
    />

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-10">
      <!-- Filter & Search Toolbar Card (Dropdown + Search) -->
      <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs relative z-30">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4 items-end">
          <!-- 1. Department Dropdown Selector (md:col-span-6 lg:col-span-5) -->
          <div class="relative md:col-span-6 lg:col-span-5">
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
              เลือกสาขาวิชา
            </label>
            <div class="relative">
              <button
                type="button"
                class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl border transition-all duration-150 cursor-pointer text-left min-h-[44px]"
                :class="
                  isDepartmentDropdownOpen
                    ? 'border-emerald-600 ring-2 ring-emerald-500/20 bg-emerald-50/20'
                    : 'border-slate-200 hover:border-slate-300 bg-slate-50/70 hover:bg-slate-50'
                "
                @click="isDepartmentDropdownOpen = !isDepartmentDropdownOpen"
              >
                <div class="flex items-center gap-2.5 min-w-0">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 bg-emerald-100 text-emerald-800 border border-emerald-200/60">
                    <v-icon icon="mdi-school" size="16" />
                  </div>
                  <div class="min-w-0">
                    <span class="block text-xs sm:text-sm font-bold text-slate-800 truncate">
                      {{ currentDepartment.name }}
                    </span>
                  </div>
                </div>

                <div class="flex items-center shrink-0">
                  <v-icon
                    icon="mdi-chevron-down"
                    size="18"
                    class="text-slate-400 transition-transform duration-200"
                    :class="isDepartmentDropdownOpen ? 'rotate-180 text-emerald-700' : ''"
                  />
                </div>
              </button>

              <!-- Department Dropdown Popover Menu -->
              <transition
                enter-active-class="transition ease-out duration-150"
                enter-from-class="opacity-0 translate-y-1"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-1"
              >
                <div
                  v-if="isDepartmentDropdownOpen"
                  class="absolute top-full left-0 right-0 mt-1.5 z-40 bg-white rounded-2xl shadow-xl shadow-slate-900/12 border border-slate-200/90 py-1.5 overflow-hidden max-h-96 overflow-y-auto"
                >
                  <!-- Option: All Departments -->
                  <button
                    type="button"
                    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 text-left text-xs sm:text-sm transition-colors cursor-pointer border-b border-slate-100"
                    :class="
                      selectedDepartment === 'all'
                        ? 'bg-emerald-50 text-emerald-900 font-bold'
                        : 'text-slate-700 hover:bg-slate-50'
                    "
                    @click="selectDepartment('all')"
                  >
                    <div class="flex items-center gap-2.5 min-w-0">
                      <div
                        class="w-6 h-6 rounded-md flex items-center justify-center shrink-0"
                        :class="selectedDepartment === 'all' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500'"
                      >
                        <v-icon icon="mdi-view-grid" size="14" />
                      </div>
                      <span class="truncate">ทุกสาขาวิชา (ทั้งหมด)</span>
                    </div>
                    <div class="flex items-center shrink-0">
                      <v-icon
                        v-if="selectedDepartment === 'all'"
                        icon="mdi-check"
                        size="16"
                        class="text-emerald-700"
                      />
                    </div>
                  </button>

                  <!-- List of Departments -->
                  <div class="py-1">
                    <button
                      v-for="dept in departments"
                      :key="dept.id"
                      type="button"
                      class="w-full flex items-center justify-between gap-3 px-3.5 py-2 text-left text-xs sm:text-sm transition-colors cursor-pointer"
                      :class="
                        selectedDepartment === dept.id
                          ? 'bg-emerald-50 text-emerald-900 font-bold'
                          : 'text-slate-700 hover:bg-slate-50'
                      "
                      @click="selectDepartment(dept.id)"
                    >
                      <div class="flex items-center gap-2.5 min-w-0">
                        <div
                          class="w-6 h-6 rounded-md flex items-center justify-center shrink-0"
                          :class="selectedDepartment === dept.id ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500'"
                        >
                          <v-icon icon="mdi-school" size="14" />
                        </div>
                        <span class="truncate">{{ dept.name }}</span>
                      </div>
                      <div class="flex items-center shrink-0">
                        <v-icon
                          v-if="selectedDepartment === dept.id"
                          icon="mdi-check"
                          size="16"
                          class="text-emerald-700"
                        />
                      </div>
                    </button>
                  </div>
                </div>
              </transition>
            </div>
          </div>

          <!-- 2. Search Input Box (md:col-span-6 lg:col-span-7) -->
          <div class="md:col-span-6 lg:col-span-7">
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
              ค้นหาคณาจารย์
            </label>
            <div class="relative">
              <v-icon
                icon="mdi-magnify"
                size="18"
                class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"
              />
              <input
                v-model="searchQuery"
                type="text"
                placeholder="ค้นหาชื่อ-นามสกุล, ตำแหน่ง, คุณวุฒิ หรือสาขาวิชา..."
                class="w-full pl-10 pr-9 py-2.5 rounded-xl text-xs sm:text-sm bg-slate-50/70 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 focus:bg-white transition-all min-h-[44px]"
              />
              <button
                v-if="searchQuery"
                type="button"
                aria-label="ล้างคำค้นหา"
                class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 cursor-pointer rounded-full"
                @click="searchQuery = ''"
              >
                <v-icon icon="mdi-close-circle" size="16" />
              </button>
            </div>
          </div>
        </div>

        <!-- Active Filter Summary & Quick Clear -->
        <div
          v-if="selectedDepartment !== 'all' || searchQuery"
          class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs"
        >
          <div class="flex flex-wrap items-center gap-1.5 text-slate-600">
            <span class="text-slate-400 font-medium">ผลการกรอง:</span>
            <!-- Selected Department Chip -->
            <span
              v-if="selectedDepartment !== 'all'"
              class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-medium"
            >
              <v-icon icon="mdi-school" size="12" />
              <span>{{ currentDepartment.name }}</span>
              <button
                type="button"
                class="hover:text-emerald-950 cursor-pointer ml-0.5"
                @click="selectedDepartment = 'all'"
              >
                <v-icon icon="mdi-close" size="12" />
              </button>
            </span>

            <!-- Search Query Chip -->
            <span
              v-if="searchQuery"
              class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800 border border-blue-200 font-medium"
            >
              <v-icon icon="mdi-magnify" size="12" />
              <span>"{{ searchQuery }}"</span>
              <button
                type="button"
                class="hover:text-blue-950 cursor-pointer ml-0.5"
                @click="searchQuery = ''"
              >
                <v-icon icon="mdi-close" size="12" />
              </button>
            </span>

            <span class="text-slate-500 ml-1">
              (พบ {{ filteredPersonnelCount }} ท่าน ใน {{ filteredDepartments.length }} สาขาวิชา)
            </span>
          </div>

          <button
            type="button"
            class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:underline cursor-pointer flex items-center gap-1"
            @click="clearFilters"
          >
            <v-icon icon="mdi-refresh" size="14" />
            <span>ล้างตัวกรองทั้งหมด</span>
          </button>
        </div>
      </div>

      <!-- Department Sections (Matching User's Reference UI) -->
      <div v-if="filteredDepartments.length > 0" class="space-y-16">
        <section
          v-for="dept in filteredDepartments"
          :key="dept.id"
          class="space-y-6"
        >
          <!-- Section Title with Graduation Cap & Green Divider (Exact match to reference) -->
          <div>
            <div class="flex items-center gap-2.5">
              <v-icon icon="mdi-school" size="26" class="text-emerald-700 shrink-0" />
              <h2 class="text-xl sm:text-2xl font-bold text-emerald-800 tracking-tight leading-tight">
                {{ dept.name }}
              </h2>
            </div>
            <!-- Green Divider: Dark thick bar on the left, faint line spanning across -->
            <div class="mt-2.5 flex items-center">
              <div class="h-1 w-28 bg-emerald-700 rounded-full shrink-0" />
              <div class="h-px flex-1 bg-emerald-100/90" />
            </div>
          </div>

          <!-- Cards Grid (Exact match to reference: 4 rounded cards, grey background, text below) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-7 items-start">
            <RouterLink
              v-for="person in dept.members"
              :key="person.id"
              :to="'/about/personnel/' + person.id"
              class="flex flex-col group cursor-pointer no-underline"
            >
              <!-- Photo Container: Rounded Rectangle, Soft Grey Background -->
              <div class="relative w-full aspect-[3/4] rounded-2xl overflow-hidden bg-[#DDE2E6] shadow-xs group-hover:shadow-md transition-all duration-300">
                <img
                  :src="person.avatar"
                  :alt="person.name"
                  class="w-full h-full object-cover object-top group-hover:scale-103 transition-transform duration-300"
                />
                <!-- Subtle hover overlay indicating clickability -->
                <div class="absolute inset-0 bg-slate-950/15 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-end justify-center pb-3">
                  <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/95 backdrop-blur-xs text-emerald-900 text-[11px] font-bold shadow-xs">
                    <span>ดูประวัติและผลงาน</span>
                    <v-icon icon="mdi-arrow-right" size="12" />
                  </span>
                </div>
              </div>

              <!-- Personnel Information: Centered below the container -->
              <div class="mt-3.5 text-center space-y-1 px-1">
                <h3 class="text-sm sm:text-base font-bold text-slate-800 leading-snug group-hover:text-emerald-800 transition-colors">
                  {{ person.name }}
                </h3>
                <p class="text-xs sm:text-[13px] text-slate-500 font-normal leading-normal">
                  {{ person.roleTitle }}
                </p>
              </div>
            </RouterLink>
          </div>
        </section>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 max-w-sm mx-auto space-y-3">
        <v-icon icon="mdi-account-search-outline" size="52" class="text-slate-300" />
        <h3 class="text-base font-bold text-slate-700">ไม่พบข้อมูลคณาจารย์</h3>
        <p class="text-xs text-slate-400">
          ไม่พบคณาจารย์ที่ตรงกับคำค้นหา "{{ searchQuery }}"
        </p>
        <button
          type="button"
          class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold cursor-pointer shadow-xs"
          @click="selectedDepartment = 'all'; searchQuery = ''"
        >
          <v-icon icon="mdi-refresh" size="14" />
          <span>แสดงคณาจารย์ทั้งหมด</span>
        </button>
      </div>
    </div>
  </div>
</template>
