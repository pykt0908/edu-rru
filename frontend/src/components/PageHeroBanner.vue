<script setup lang="ts">
import eduLogo from '@/assets/logos/edu-logo-border-white.png'

withDefaults(defineProps<{
  badge?: string
  badgeIcon?: string
  title: string
  titleHighlight?: string
  subtitle?: string
}>(), {
  badge: '',
  badgeIcon: 'mdi-school-outline',
  titleHighlight: '',
  subtitle: '',
})
</script>

<template>
  <div class="relative bg-slate-950 overflow-hidden">
    <!-- Background glows -->
    <div class="pointer-events-none absolute inset-0">
      <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-emerald-700/15 blur-3xl" />
      <div class="absolute -bottom-16 right-0 w-72 h-72 rounded-full bg-emerald-600/10 blur-3xl" />
      <!-- Subtle grid pattern -->
      <div
        class="absolute inset-0 opacity-[0.04]"
        style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 40px 40px"
      />
    </div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20">
      <div class="flex flex-col sm:flex-row sm:items-center gap-6 sm:gap-10">
        <!-- Left: Text Content -->
        <div class="flex-1 min-w-0">
          <!-- Badge -->
          <div
            v-if="badge"
            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-xs font-semibold tracking-wider uppercase mb-4"
          >
            <v-icon :icon="badgeIcon" size="14" />
            <span>{{ badge }}</span>
          </div>

          <!-- Title -->
          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-[1.15] mb-3">
            {{ title }}
            <span v-if="titleHighlight" class="text-emerald-400 block sm:inline"><br class="hidden sm:inline" />{{ titleHighlight }}</span>
          </h1>

          <!-- Subtitle -->
          <p v-if="subtitle" class="text-slate-400 text-sm sm:text-base max-w-xl leading-relaxed">
            {{ subtitle }}
          </p>

          <!-- Extra slot -->
          <slot name="extra" />
        </div>

        <!-- Right: Logo -->
        <div class="shrink-0 flex items-center justify-start sm:justify-end">
          <div class="relative">
            <!-- Glow ring behind logo -->
            <div class="absolute inset-0 rounded-full bg-emerald-500/20 blur-xl scale-125" />
            <img
              :src="eduLogo"
              alt="โลโก้คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์"
              class="relative w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 object-contain drop-shadow-2xl"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
