<script setup lang="ts">
import HeroCarousel from '@/components/HeroCarousel.vue'
import FacultyHeroSection from '@/components/FacultyHeroSection.vue'
import BentoGridSection from '@/components/BentoGridSection.vue'
import FacultyVideoSection from '@/components/FacultyVideoSection.vue'
</script>

<template>
  <div class="bg-white">
    <!-- Fullscreen Hero Carousel with Placeholders -->
    <HeroCarousel />

    <!-- Big Typography Faculty Hero Section -->
    <FacultyHeroSection />

    <!-- Bento Grid Showcase Section (Matching Reference Style) -->
    <div id="explore-content">
      <BentoGridSection />
    </div>

    <!-- Video Introduction Section -->
    <FacultyVideoSection />
  </div>
</template>
