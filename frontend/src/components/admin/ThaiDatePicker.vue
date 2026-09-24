<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

const props = withDefaults(
  defineProps<{
    modelValue: string
    placeholder?: string
  }>(),
  {
    modelValue: '',
    placeholder: 'เลือกวันที่ประกาศ (เช่น 24 กันยายน 2569)',
  }
)

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const isOpen = ref(false)
const containerRef = ref<HTMLElement | null>(null)

const THAI_MONTHS = [
  'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน',
  'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
  'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม',
]

const THAI_DAYS = ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส']

// Current viewing month & year (in Gregorian for Date math)
const viewDate = ref(new Date())

// Selected day, month, year (Gregorian)
const selectedDate = ref<Date | null>(null)

// Parse incoming modelValue string (supports Thai date "24 กันยายน 2569" or "2026-09-24")
const parseThaiDate = (val: string): Date | null => {
  if (!val) return null
  const trimmed = val.trim()

  // Match ISO YYYY-MM-DD
  const isoMatch = trimmed.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/)
  if (isoMatch) {
    const y = parseInt(isoMatch[1], 10)
    const m = parseInt(isoMatch[2], 10) - 1
    const d = parseInt(isoMatch[3], 10)
    return new Date(y, m, d)
  }

  // Match Thai text: "24 กันยายน 2569"
  for (let mIdx = 0; mIdx < THAI_MONTHS.length; mIdx++) {
    const monthName = THAI_MONTHS[mIdx]
    if (trimmed.includes(monthName)) {
      const parts = trimmed.split(monthName).map((s) => s.trim())
      const day = parseInt(parts[0], 10)
      let year = parseInt(parts[1], 10)
      if (year > 2400) year -= 543 // Convert B.E. to C.E.
      if (!isNaN(day) && !isNaN(year)) {
        return new Date(year, mIdx, day)
      }
    }
  }

  const fallback = new Date(trimmed)
  return isNaN(fallback.getTime()) ? null : fallback
}

// Format Date object to Thai string "24 กันยายน 2569"
const formatThaiDate = (d: Date): string => {
  const day = d.getDate()
  const month = THAI_MONTHS[d.getMonth()]
  const thaiYear = d.getFullYear() + 543
  return `${day} ${month} ${thaiYear}`
}

// Initialize from props
const initFromProp = () => {
  const parsed = parseThaiDate(props.modelValue)
  if (parsed) {
    selectedDate.value = parsed
    viewDate.value = new Date(parsed.getFullYear(), parsed.getMonth(), 1)
  } else {
    viewDate.value = new Date()
  }
}

onMounted(() => {
  initFromProp()
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})

const handleClickOutside = (e: MouseEvent) => {
  if (containerRef.value && !containerRef.value.contains(e.target as Node)) {
    isOpen.value = false
  }
}

// Calendar Navigation
const prevMonth = () => {
  viewDate.value = new Date(viewDate.value.getFullYear(), viewDate.value.getMonth() - 1, 1)
}

const nextMonth = () => {
  viewDate.value = new Date(viewDate.value.getFullYear(), viewDate.value.getMonth() + 1, 1)
}

const currentMonthIndex = computed({
  get: () => viewDate.value.getMonth(),
  set: (val: number) => {
    viewDate.value = new Date(viewDate.value.getFullYear(), val, 1)
  },
})

const currentYearBE = computed({
  get: () => viewDate.value.getFullYear() + 543,
  set: (beYear: number) => {
    viewDate.value = new Date(beYear - 543, viewDate.value.getMonth(), 1)
  },
})

// Year options: range of 15 years around current
const yearOptions = computed(() => {
  const currentBE = new Date().getFullYear() + 543
  const years = []
  for (let y = currentBE - 6; y <= currentBE + 6; y++) {
    years.push(y)
  }
  return years
})

// Days in month grid
interface CalendarDay {
  date: Date
  dayNumber: number
  isCurrentMonth: boolean
  isToday: boolean
  isSelected: boolean
}

const calendarDays = computed<CalendarDay[]>(() => {
  const year = viewDate.value.getFullYear()
  const month = viewDate.value.getMonth()

  // First day of month (0 = Sun, 6 = Sat)
  const firstDay = new Date(year, month, 1)
  const startingDay = firstDay.getDay()

  // Total days in current month
  const totalDays = new Date(year, month + 1, 0).getDate()

  // Total days in previous month
  const prevMonthTotalDays = new Date(year, month, 0).getDate()

  const today = new Date()
  const days: CalendarDay[] = []

  // Prev month padding
  for (let i = startingDay - 1; i >= 0; i--) {
    const d = new Date(year, month - 1, prevMonthTotalDays - i)
    days.push({
      date: d,
      dayNumber: d.getDate(),
      isCurrentMonth: false,
      isToday: false,
      isSelected: false,
    })
  }

  // Current month days
  for (let day = 1; day <= totalDays; day++) {
    const d = new Date(year, month, day)
    const isToday =
      d.getDate() === today.getDate() &&
      d.getMonth() === today.getMonth() &&
      d.getFullYear() === today.getFullYear()
    const isSelected =
      !!selectedDate.value &&
      d.getDate() === selectedDate.value.getDate() &&
      d.getMonth() === selectedDate.value.getMonth() &&
      d.getFullYear() === selectedDate.value.getFullYear()

    days.push({
      date: d,
      dayNumber: day,
      isCurrentMonth: true,
      isToday,
      isSelected,
    })
  }

  // Next month padding to complete 42 or 35 cells
  const remaining = 42 - days.length
  if (remaining < 7) {
    for (let day = 1; day <= remaining; day++) {
      const d = new Date(year, month + 1, day)
      days.push({
        date: d,
        dayNumber: day,
        isCurrentMonth: false,
        isToday: false,
        isSelected: false,
      })
    }
  }

  return days
})

// Select a day
const selectDay = (day: CalendarDay) => {
  selectedDate.value = day.date
  viewDate.value = new Date(day.date.getFullYear(), day.date.getMonth(), 1)
  const formatted = formatThaiDate(day.date)
  emit('update:modelValue', formatted)
  isOpen.value = false
}

// Quick action: Today
const selectToday = () => {
  const today = new Date()
  selectedDate.value = today
  viewDate.value = new Date(today.getFullYear(), today.getMonth(), 1)
  const formatted = formatThaiDate(today)
  emit('update:modelValue', formatted)
  isOpen.value = false
}

// Clear
const clearDate = () => {
  selectedDate.value = null
  emit('update:modelValue', '')
  isOpen.value = false
}
</script>

<template>
  <div ref="containerRef" class="relative">
    <!-- ── Date Input Field ─────────────────────────────────────────────── -->
    <div
      class="flex items-center gap-2 px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl focus-within:border-emerald-600 focus-within:ring-2 focus-within:ring-emerald-500/20 transition-all cursor-pointer shadow-2xs group"
      @click="isOpen = !isOpen"
    >
      <v-icon icon="mdi-calendar-month-outline" size="18" class="text-slate-400 group-hover:text-emerald-700 transition-colors shrink-0" />
      <input
        :value="modelValue"
        type="text"
        :placeholder="placeholder"
        readonly
        class="w-full text-xs font-semibold text-slate-800 bg-transparent outline-none cursor-pointer placeholder:text-slate-400 placeholder:font-normal"
      />
      <button
        v-if="modelValue"
        type="button"
        class="p-0.5 text-slate-400 hover:text-rose-600 rounded-md transition-colors cursor-pointer shrink-0"
        title="ล้างวันที่"
        @click.stop="clearDate"
      >
        <v-icon icon="mdi-close-circle" size="16" />
      </button>
    </div>

    <!-- ── Calendar Popup Popover ───────────────────────────────────────── -->
    <transition
      enter-active-class="transition duration-150 ease-out"
      enter-from-class="opacity-0 translate-y-1 scale-98"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition duration-100 ease-in"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-1 scale-98"
    >
      <div
        v-if="isOpen"
        class="absolute right-0 top-full mt-2 z-50 w-72 sm:w-80 bg-white border border-slate-200 rounded-2xl shadow-xl p-4 select-none"
      >
        <!-- Header: Month & Year Selector -->
        <div class="flex items-center justify-between gap-1 mb-3">
          <button
            type="button"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-colors cursor-pointer"
            title="เดือนก่อนหน้า"
            @click="prevMonth"
          >
            <v-icon icon="mdi-chevron-left" size="20" />
          </button>

          <div class="flex items-center gap-1.5 min-w-0">
            <!-- Month Dropdown -->
            <select
              v-model="currentMonthIndex"
              class="px-2 py-1 text-xs font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-lg outline-none cursor-pointer focus:border-emerald-600"
            >
              <option v-for="(m, idx) in THAI_MONTHS" :key="idx" :value="idx">
                {{ m }}
              </option>
            </select>

            <!-- Year Dropdown (พ.ศ.) -->
            <select
              v-model="currentYearBE"
              class="px-2 py-1 text-xs font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-lg outline-none cursor-pointer focus:border-emerald-600"
            >
              <option v-for="y in yearOptions" :key="y" :value="y">
                {{ y }}
              </option>
            </select>
          </div>

          <button
            type="button"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-colors cursor-pointer"
            title="เดือนถัดไป"
            @click="nextMonth"
          >
            <v-icon icon="mdi-chevron-right" size="20" />
          </button>
        </div>

        <!-- Weekday Headers -->
        <div class="grid grid-cols-7 gap-1 text-center mb-1.5">
          <div
            v-for="(day, dIdx) in THAI_DAYS"
            :key="day"
            class="text-[11px] font-bold py-1"
            :class="dIdx === 0 ? 'text-rose-500' : 'text-slate-400'"
          >
            {{ day }}
          </div>
        </div>

        <!-- Days Grid -->
        <div class="grid grid-cols-7 gap-1 text-center">
          <button
            v-for="(cell, cIdx) in calendarDays"
            :key="cIdx"
            type="button"
            class="h-8 rounded-lg text-xs font-semibold flex items-center justify-center transition-all cursor-pointer relative"
            :class="[
              cell.isSelected
                ? 'bg-emerald-600 text-white font-bold shadow-xs'
                : cell.isCurrentMonth
                ? cell.isToday
                  ? 'bg-emerald-50 text-emerald-800 font-bold border border-emerald-300 hover:bg-emerald-100'
                  : 'text-slate-700 hover:bg-slate-100'
                : 'text-slate-300 hover:text-slate-400',
            ]"
            @click="selectDay(cell)"
          >
            {{ cell.dayNumber }}
          </button>
        </div>

        <!-- Quick Actions Footer -->
        <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs">
          <button
            type="button"
            class="text-emerald-700 hover:text-emerald-800 font-semibold cursor-pointer py-1 px-2 rounded hover:bg-emerald-50 transition-colors"
            @click="selectToday"
          >
            วันนี้
          </button>
          <button
            type="button"
            class="text-slate-400 hover:text-slate-700 cursor-pointer py-1 px-2 rounded hover:bg-slate-100 transition-colors"
            @click="isOpen = false"
          >
            ปิด
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>
