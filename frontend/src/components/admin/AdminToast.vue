<script setup lang="ts">
import { ref, watch, onBeforeUnmount } from 'vue'

const props = withDefaults(
  defineProps<{
    modelValue: boolean
    message: string
    title?: string
    type?: 'success' | 'error' | 'warning' | 'info'
    duration?: number
  }>(),
  {
    title: '',
    type: 'success',
    duration: 3500,
  }
)

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()

let timer: ReturnType<typeof setTimeout> | null = null
const progress = ref(100)
let progressInterval: ReturnType<typeof setInterval> | null = null

const startTimer = () => {
  clearTimers()
  progress.value = 100

  const startTime = Date.now()
  const duration = props.duration

  progressInterval = setInterval(() => {
    const elapsed = Date.now() - startTime
    const remaining = Math.max(0, 100 - (elapsed / duration) * 100)
    progress.value = remaining
    if (remaining <= 0 && progressInterval) {
      clearInterval(progressInterval)
    }
  }, 30)

  timer = setTimeout(() => {
    close()
  }, duration)
}

const clearTimers = () => {
  if (timer) {
    clearTimeout(timer)
    timer = null
  }
  if (progressInterval) {
    clearInterval(progressInterval)
    progressInterval = null
  }
}

const close = () => {
  clearTimers()
  emit('update:modelValue', false)
}

watch(
  () => props.modelValue,
  (val) => {
    if (val) {
      startTimer()
    } else {
      clearTimers()
    }
  },
  { immediate: true }
)

onBeforeUnmount(() => {
  clearTimers()
})

const defaultTitle = (t: string) => {
  switch (t) {
    case 'success':
      return 'บันทึกสำเร็จ'
    case 'error':
      return 'เกิดข้อผิดพลาด'
    case 'warning':
      return 'แจ้งเตือน'
    case 'info':
      return 'ข้อมูล'
    default:
      return 'แจ้งเตือน'
  }
}
</script>

<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-300 ease-out transform"
      enter-from-class="translate-y-[-16px] opacity-0 scale-95"
      enter-to-class="translate-y-0 opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in transform"
      leave-from-class="translate-y-0 opacity-100 scale-100"
      leave-to-class="translate-y-[-16px] opacity-0 scale-95"
    >
      <div
        v-if="modelValue"
        class="fixed top-5 right-5 z-[99999] pointer-events-auto flex flex-col overflow-hidden rounded-2xl bg-white/95 backdrop-blur-md border shadow-2xl transition-all min-w-[320px] max-w-sm sm:max-w-md"
        :class="{
          'border-emerald-200/90 shadow-emerald-950/10': type === 'success',
          'border-rose-200/90 shadow-rose-950/10': type === 'error',
          'border-amber-200/90 shadow-amber-950/10': type === 'warning',
          'border-sky-200/90 shadow-sky-950/10': type === 'info',
        }"
      >
        <div class="flex items-start gap-3.5 p-4">
          <!-- Icon container -->
          <div
            class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-xs"
            :class="{
              'bg-emerald-50 text-emerald-600 ring-4 ring-emerald-50/60': type === 'success',
              'bg-rose-50 text-rose-600 ring-4 ring-rose-50/60': type === 'error',
              'bg-amber-50 text-amber-600 ring-4 ring-amber-50/60': type === 'warning',
              'bg-sky-50 text-sky-600 ring-4 ring-sky-50/60': type === 'info',
            }"
          >
            <v-icon
              :icon="
                type === 'success'
                  ? 'mdi-check-circle'
                  : type === 'error'
                  ? 'mdi-alert-circle'
                  : type === 'warning'
                  ? 'mdi-alert'
                  : 'mdi-information'
              "
              size="22"
            />
          </div>

          <!-- Message block -->
          <div class="flex-1 min-w-0 pt-0.5">
            <h4
              class="text-xs font-black leading-tight tracking-wide"
              :class="{
                'text-emerald-950': type === 'success',
                'text-rose-950': type === 'error',
                'text-amber-950': type === 'warning',
                'text-sky-950': type === 'info',
              }"
            >
              {{ title || defaultTitle(type) }}
            </h4>
            <p class="text-xs text-slate-600 mt-1 font-medium leading-relaxed break-words">
              {{ message }}
            </p>
          </div>

          <!-- Close button -->
          <button
            type="button"
            class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg p-1 transition-colors cursor-pointer shrink-0 -mr-1 -mt-1"
            title="ปิด"
            @click="close"
          >
            <v-icon icon="mdi-close" size="16" />
          </button>
        </div>

        <!-- Subtle progress bar -->
        <div class="h-1 w-full bg-slate-100/80 overflow-hidden">
          <div
            class="h-full transition-all duration-75 ease-linear rounded-full"
            :class="{
              'bg-emerald-500': type === 'success',
              'bg-rose-500': type === 'error',
              'bg-amber-500': type === 'warning',
              'bg-sky-500': type === 'info',
            }"
            :style="{ width: `${progress}%` }"
          />
        </div>
      </div>
    </Transition>
  </Teleport>
</template>
