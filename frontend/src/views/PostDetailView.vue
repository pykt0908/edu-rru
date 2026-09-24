<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getPostById, POSTS, type Post } from '@/data/postsData'

const route = useRoute()
const router = useRouter()

// Get post by route param ID
const post = computed<Post | undefined>(() => {
  const id = route.params.id as string
  return getPostById(id)
})

// Related posts (excluding current post)
const relatedPosts = computed<Post[]>(() => {
  if (!post.value) return []
  return POSTS.filter((p) => p.id !== post.value?.id).slice(0, 3)
})

// Copy link feedback state
const copyFeedback = ref(false)

const copyCurrentUrl = async () => {
  try {
    await navigator.clipboard.writeText(window.location.href)
    copyFeedback.value = true
    setTimeout(() => {
      copyFeedback.value = false
    }, 2000)
  } catch {
    // Fallback if clipboard API is restricted
    copyFeedback.value = true
    setTimeout(() => {
      copyFeedback.value = false
    }, 2000)
  }
}

const shareToFacebook = () => {
  const url = encodeURIComponent(window.location.href)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400')
}

const shareToLine = () => {
  const url = encodeURIComponent(window.location.href)
  window.open(`https://social-plugins.line.me/lineit/share?url=${url}`, '_blank', 'width=600,height=500')
}
</script>

<template>
  <div class="min-h-screen bg-slate-50/50 pb-20">
    <!-- If Post Found -->
    <template v-if="post">
      <!-- Breadcrumb Bar -->
      <div class="bg-white border-b border-slate-200/80">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
          <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap scrollbar-none">
            <RouterLink to="/" class="hover:text-emerald-700 transition-colors flex items-center gap-1 no-underline">
              <v-icon icon="mdi-home-outline" size="14" />
              <span>หน้าแรก</span>
            </RouterLink>
            <span class="text-slate-300">/</span>
            <RouterLink to="/#explore-content" class="hover:text-emerald-700 transition-colors no-underline">
              <span>ข่าวและกิจกรรม</span>
            </RouterLink>
            <span class="text-slate-300">/</span>
            <span class="text-slate-800 font-medium truncate max-w-xs sm:max-w-md">
              {{ post.title }}
            </span>
          </nav>
        </div>
      </div>

      <!-- Main Article Container -->
      <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12">
        <article class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-10 lg:p-12 overflow-hidden">
          <!-- Category & Meta Header -->
          <div class="mb-6">
            <div class="flex flex-wrap items-center gap-2.5 mb-4">
              <span :class="['px-3 py-1 rounded-full text-xs font-bold tracking-wide shadow-xs', post.categoryBadgeClass]">
                {{ post.category }}
              </span>
              <span class="text-xs text-slate-400 flex items-center gap-1">
                <v-icon icon="mdi-clock-outline" size="14" />
                <span>ใช้เวลาอ่าน {{ post.readTime }}</span>
              </span>
            </div>

            <!-- Main Title -->
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-snug sm:leading-tight mb-6">
              {{ post.title }}
            </h1>

            <!-- Author Info & Publishing Metadata -->
            <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-slate-100">
              <div class="flex items-center gap-3">
                <img
                  :src="post.author.avatar"
                  :alt="post.author.name"
                  class="w-11 h-11 rounded-full object-cover border border-slate-200 shadow-xs"
                />
                <div>
                  <h3 class="text-sm font-bold text-slate-900 leading-tight">
                    {{ post.author.name }}
                  </h3>
                  <p class="text-xs text-slate-500 mt-0.5 leading-tight">
                    {{ post.author.role }}
                  </p>
                </div>
              </div>

              <!-- Metadata & Share buttons -->
              <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                  <p class="text-xs font-medium text-slate-700 flex items-center gap-1 justify-end">
                    <v-icon icon="mdi-calendar-blank-outline" size="14" class="text-slate-400" />
                    <span>{{ post.date }}</span>
                  </p>
                  <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1 justify-end">
                    <v-icon icon="mdi-eye-outline" size="13" />
                    <span>เข้าชม {{ post.views }} ครั้ง</span>
                  </p>
                </div>

                <div class="flex items-center gap-1.5 pl-3 border-l border-slate-200">
                  <!-- Facebook share -->
                  <button
                    type="button"
                    class="w-8 h-8 rounded-full bg-[#1877F2]/10 text-[#1877F2] hover:bg-[#1877F2] hover:text-white transition-all flex items-center justify-center cursor-pointer"
                    title="แชร์ไปยัง Facebook"
                    @click="shareToFacebook"
                  >
                    <v-icon icon="mdi-facebook" size="16" />
                  </button>

                  <!-- LINE share -->
                  <button
                    type="button"
                    class="w-8 h-8 rounded-full bg-[#06C755]/10 text-[#06C755] hover:bg-[#06C755] hover:text-white transition-all flex items-center justify-center cursor-pointer"
                    title="แชร์ไปยัง LINE"
                    @click="shareToLine"
                  >
                    <v-icon icon="mdi-chat" size="16" />
                  </button>

                  <!-- Copy Link -->
                  <button
                    type="button"
                    class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all flex items-center justify-center cursor-pointer relative"
                    title="คัดลอกลิงก์"
                    @click="copyCurrentUrl"
                  >
                    <v-icon :icon="copyFeedback ? 'mdi-check' : 'mdi-link-variant'" size="16" />
                  </button>
                </div>
              </div>
            </div>

            <!-- Toast notification for copy -->
            <transition
              enter-active-class="transition duration-200 ease-out"
              enter-from-class="opacity-0 -translate-y-1"
              enter-to-class="opacity-100 translate-y-0"
              leave-active-class="transition duration-150 ease-in"
              leave-from-class="opacity-100 translate-y-0"
              leave-to-class="opacity-0 -translate-y-1"
            >
              <div
                v-if="copyFeedback"
                class="mt-2.5 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold"
              >
                <v-icon icon="mdi-check-circle" size="14" />
                <span>คัดลอกลิงก์เรียบร้อยแล้ว</span>
              </div>
            </transition>
          </div>

          <!-- Featured Hero Image -->
          <div class="relative overflow-hidden rounded-2xl shadow-sm border border-slate-100 mb-8 sm:mb-10">
            <img
              :src="post.thumbnail"
              :alt="post.title"
              class="w-full h-[280px] sm:h-[420px] lg:h-[480px] object-cover"
            />
          </div>

          <!-- Key Highlights Callout (if available) -->
          <div
            v-if="post.keyHighlights && post.keyHighlights.length > 0"
            class="bg-emerald-50/70 border-l-4 border-emerald-600 rounded-r-2xl p-5 sm:p-6 mb-8"
          >
            <h4 class="text-sm font-bold text-emerald-900 uppercase tracking-wider flex items-center gap-2 mb-3">
              <v-icon icon="mdi-star-four-points" size="16" class="text-emerald-700" />
              <span>ประเด็นสำคัญของข่าวนี้</span>
            </h4>
            <ul class="space-y-2 text-sm text-emerald-950">
              <li v-for="(hl, idx) in post.keyHighlights" :key="idx" class="flex items-start gap-2.5">
                <v-icon icon="mdi-check-circle" size="16" class="text-emerald-600 shrink-0 mt-0.5" />
                <span class="leading-relaxed">{{ hl }}</span>
              </li>
            </ul>
          </div>

          <!-- Article Paragraphs -->
          <div class="prose max-w-none text-slate-700 text-base sm:text-lg leading-relaxed space-y-6 font-normal">
            <p v-for="(paragraph, pIndex) in post.content" :key="pIndex" class="text-justify leading-relaxed">
              {{ paragraph }}
            </p>
          </div>

          <!-- Quote Box (if available) -->
          <div
            v-if="post.quote"
            class="my-8 sm:my-10 p-6 sm:p-8 rounded-2xl bg-slate-50 border border-slate-200/90 relative overflow-hidden"
          >
            <v-icon
              icon="mdi-format-quote-open"
              size="48"
              class="text-emerald-700/15 absolute -top-1 left-2 pointer-events-none"
            />
            <p class="text-base sm:text-lg font-medium text-slate-800 italic relative z-10 leading-relaxed mb-3">
              “{{ post.quote.text }}”
            </p>
            <p class="text-xs sm:text-sm font-bold text-emerald-800">
              — {{ post.quote.by }}
            </p>
          </div>

          <!-- Photo Gallery (if available) -->
          <div v-if="post.gallery && post.gallery.length > 0" class="my-8 sm:my-10 pt-6 border-t border-slate-100">
            <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
              <v-icon icon="mdi-image-multiple-outline" size="20" class="text-emerald-700" />
              <span>ภาพบรรยากาศและกิจกรรม</span>
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              <div
                v-for="(photo, pIdx) in post.gallery"
                :key="pIdx"
                class="rounded-xl overflow-hidden shadow-xs border border-slate-100 group aspect-4/3 bg-slate-100"
              >
                <img
                  :src="photo"
                  :alt="post.title + ' ' + (pIdx + 1)"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  loading="lazy"
                />
              </div>
            </div>
          </div>

          <!-- Downloadable Attachments (if available) -->
          <div v-if="post.attachments && post.attachments.length > 0" class="my-8 sm:my-10 p-6 rounded-2xl bg-slate-50 border border-slate-200/90">
            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2 mb-3.5">
              <v-icon icon="mdi-file-document-outline" size="18" class="text-emerald-700" />
              <span>เอกสารแนบที่เกี่ยวข้อง</span>
            </h4>
            <div class="space-y-2.5">
              <div
                v-for="(att, aIdx) in post.attachments"
                :key="aIdx"
                class="flex items-center justify-between p-3.5 bg-white rounded-xl border border-slate-200 hover:border-emerald-300 transition-colors"
              >
                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-9 h-9 rounded-lg bg-red-50 text-red-600 flex items-center justify-center font-bold text-xs shrink-0">
                    PDF
                  </div>
                  <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-bold text-slate-800 truncate">
                      {{ att.name }}
                    </p>
                    <span class="text-[11px] text-slate-400 font-medium">ขนาดไฟล์ {{ att.size }}</span>
                  </div>
                </div>

                <a
                  :href="att.url"
                  download
                  class="px-3.5 py-1.5 rounded-lg bg-slate-900 hover:bg-emerald-700 text-white text-xs font-semibold flex items-center gap-1.5 transition-colors no-underline shrink-0"
                >
                  <v-icon icon="mdi-download" size="14" />
                  <span class="hidden sm:inline">ดาวน์โหลด</span>
                </a>
              </div>
            </div>
          </div>

          <!-- Tags Footer -->
          <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-1.5">
              <span class="text-xs text-slate-400 font-medium mr-1">แท็ก:</span>
              <span
                v-for="tag in post.tags"
                :key="tag"
                class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors"
              >
                #{{ tag }}
              </span>
            </div>

            <button
              type="button"
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:text-emerald-800 cursor-pointer"
              @click="router.push('/#explore-content')"
            >
              <v-icon icon="mdi-arrow-left" size="14" />
              <span>กลับสู่หน้าข่าวทั้งหมด</span>
            </button>
          </div>
        </article>

        <!-- Related News / Other Articles -->
        <section v-if="relatedPosts.length > 0" class="mt-14 sm:mt-16">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg sm:text-xl font-bold text-slate-900">
              ข่าวสารและกิจกรรมอื่น ๆ
            </h3>
            <RouterLink to="/#explore-content" class="text-xs font-semibold text-emerald-700 hover:underline">
              ดูข่าวทั้งหมด
            </RouterLink>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <RouterLink
              v-for="rel in relatedPosts"
              :key="rel.id"
              :to="'/posts/' + rel.id"
              class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-md transition-all duration-200 flex flex-col no-underline group"
            >
              <div class="relative h-44 overflow-hidden bg-slate-100">
                <img
                  :src="rel.thumbnail"
                  :alt="rel.title"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  loading="lazy"
                />
                <div class="absolute top-2.5 left-2.5">
                  <span :class="['px-2.5 py-0.5 rounded-full text-[10px] font-bold shadow-xs', rel.categoryBadgeClass]">
                    {{ rel.category }}
                  </span>
                </div>
              </div>

              <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                <div>
                  <span class="text-[11px] text-slate-400 block mb-1.5">{{ rel.date }}</span>
                  <h4 class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 leading-snug">
                    {{ rel.title }}
                  </h4>
                </div>

                <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-xs text-emerald-700 font-semibold">
                  <span>อ่านต่อ</span>
                  <v-icon icon="mdi-arrow-right" size="14" class="group-hover:translate-x-1 transition-transform" />
                </div>
              </div>
            </RouterLink>
          </div>
        </section>
      </main>
    </template>

    <!-- Not Found State -->
    <template v-else>
      <div class="max-w-xl mx-auto px-4 py-24 text-center">
        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
          <v-icon icon="mdi-file-question-outline" size="32" />
        </div>
        <h2 class="text-xl font-bold text-slate-800 mb-2">ไม่พบบทความหรือข่าวสารที่ต้องการ</h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-6">
          ข่าวสารนี้อาจถูกลบหรือย้ายที่อยู่แล้ว สามารถกลับไปตรวจสอบข่าวสารอื่น ๆ ได้ที่หน้าแรก
        </p>
        <RouterLink
          to="/"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-700 text-white text-xs font-semibold shadow-xs hover:bg-emerald-800 transition-colors no-underline"
        >
          <v-icon icon="mdi-home" size="14" />
          <span>กลับสู่หน้าแรก</span>
        </RouterLink>
      </div>
    </template>
  </div>
</template>
