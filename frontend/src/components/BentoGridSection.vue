<script setup lang="ts">
import { ref, computed } from 'vue'

export interface Post {
  id: number | string
  title: string
  desc: string
  thumbnail: string
  date: string
  category: string
  categoryBadgeClass: string
  views: string
  readTime: string
  author: {
    name: string
    role: string
    avatar: string
  }
  featured?: boolean
  gridClass: string // Bento Grid column and row spanning
}

// Active Filter Category
const selectedCategory = ref('ทั้งหมด')
const categories = ['ทั้งหมด', 'ข่าวประชาสัมพันธ์', 'วิชาการ & วิจัย', 'กิจกรรมนิสิต', 'บริการวิชาการ']

// Mock Data for Normal Posts with Thumbnail, Title, Desc, Date
const posts = ref<Post[]>([
  {
    id: 1,
    title: 'เปิดรับสมัครนิสิตใหม่ระดับปริญญาตรี คณะครุศาสตร์ ประจำปีการศึกษา 2569 (รอบ Portfolio & โควตาภาคตะวันออก)',
    desc: 'หลักสูตรครุศาสตรบัณฑิต (ค.บ. 4 ปี) ผ่านการรับรองมาตรฐานวิชาชีพครูจากคุรุสภา มุ่งผลิตครูคุณภาพสู่สังคม มีให้เลือก 8 สาขาวิชาเอก พร้อมทุนการศึกษาและโครงการพัฒนาความเป็นเลิศ',
    thumbnail: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1200&auto=format&fit=crop&q=80',
    date: '24 กันยายน 2569',
    category: 'ข่าวประชาสัมพันธ์',
    categoryBadgeClass: 'bg-emerald-600 text-white',
    views: '4.8K',
    readTime: '3 นาที',
    author: {
      name: 'งานรับสมัครและบริการการศึกษา',
      role: 'คณะครุศาสตร์',
      avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
    },
    featured: true,
    gridClass: 'lg:col-span-8 lg:row-span-2 min-h-[480px] lg:min-h-[540px]',
  },
  {
    id: 2,
    title: 'คณาจารย์ครุศาสตร์คว้ารางวัลชนะเลิศ นวัตกรรมการจัดการเรียนรู้ Active Learning ระดับชาติ 2569',
    desc: 'ผลงานการออกแบบชุดการสอนดิจิทัลและกระบวนการจัดการเรียนรู้เพื่อพัฒนาสมรรถนะผู้เรียนในศตวรรษที่ 21 ได้รับการยกย่องในเวทีประชุมวิชาการครุศาสตร์ระดับประเทศ',
    thumbnail: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&auto=format&fit=crop&q=80',
    date: '22 กันยายน 2569',
    category: 'วิชาการ & วิจัย',
    categoryBadgeClass: 'bg-blue-600 text-white',
    views: '2.3K',
    readTime: '2 นาที',
    author: {
      name: 'ฝ่ายวิจัยและนวัตกรรม',
      role: 'คณะครุศาสตร์',
      avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80',
    },
    gridClass: 'lg:col-span-4 min-h-[255px]',
  },
  {
    id: 3,
    title: 'โครงการเตรียมความพร้อมนิสิตฝึกประสบการณ์วิชาชีพครูในสถานศึกษาเครือข่าย 120 แห่ง',
    desc: 'เสริมสร้างจิตวิญญาณความเป็นครู จริยธรรมวิชาชีพ และทักษะการบริหารจัดการชั้นเรียนยุคใหม่ ก่อนลงปฏิบัติการสอนจริงในโรงเรียน',
    thumbnail: 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=800&auto=format&fit=crop&q=80',
    date: '20 กันยายน 2569',
    category: 'กิจกรรมนิสิต',
    categoryBadgeClass: 'bg-purple-600 text-white',
    views: '3.1K',
    readTime: '2 นาที',
    author: {
      name: 'งานฝึกประสบการณ์วิชาชีพครู',
      role: 'คณะครุศาสตร์',
      avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&auto=format&fit=crop&q=80',
    },
    gridClass: 'lg:col-span-4 min-h-[255px]',
  },
  {
    id: 4,
    title: 'ครุศาสตร์ราชนครินทร์ลงพื้นที่ยกระดับคุณภาพการศึกษาโรงเรียนขนาดเล็ก จังหวัดฉะเชิงเทรา',
    desc: 'ดำเนินโครงการบริการวิชาการเพื่อการพัฒนาท้องถิ่น ถ่ายทอดสื่อนวัตกรรมการอ่านออกเขียนได้ และการใช้เทคโนโลยีส่งเสริมการเรียนรู้สำหรับเด็กในชนบท',
    thumbnail: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&auto=format&fit=crop&q=80',
    date: '18 กันยายน 2569',
    category: 'บริการวิชาการ',
    categoryBadgeClass: 'bg-amber-600 text-white',
    views: '1.9K',
    readTime: '3 นาที',
    author: {
      name: 'ฝ่ายบริการวิชาการและชุมชน',
      role: 'คณะครุศาสตร์',
      avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&auto=format&fit=crop&q=80',
    },
    gridClass: 'lg:col-span-4 min-h-[340px]',
  },
  {
    id: 5,
    title: 'อบรมเชิงปฏิบัติการ: การประยุกต์ใช้ Generative AI สำหรับครูและผู้บริหารสถานศึกษายุคใหม่',
    desc: 'เปิดโลกทัศน์การนำ AI เช่น ChatGPT, Claude และ Copilot มาช่วยร่างแผนการสอน สร้างแบบฝึกหัดที่ตรงจุด และประเมินผลผู้เรียนอย่างสร้างสรรค์และมีจริยธรรม',
    thumbnail: 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800&auto=format&fit=crop&q=80',
    date: '15 กันยายน 2569',
    category: 'วิชาการ & วิจัย',
    categoryBadgeClass: 'bg-blue-600 text-white',
    views: '5.6K',
    readTime: '4 นาที',
    author: {
      name: 'ศูนย์นวัตกรรมและเทคโนโลยีดิจิทัล',
      role: 'คณะครุศาสตร์',
      avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80',
    },
    gridClass: 'lg:col-span-4 min-h-[340px]',
  },
  {
    id: 6,
    title: 'ประกาศผลการสอบคัดเลือกและกำหนดการรายงานตัว นิสิตทุนครูรักษ์ถิ่น ประจำปี 2569',
    desc: 'ขอให้นักศึกษาที่มีรายชื่อผ่านการคัดเลือก ตรวจสอบเอกสารหลักฐาน และดำเนินการรายงานตัวตามวันและเวลาที่กำหนดอย่างเคร่งครัด',
    thumbnail: 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&auto=format&fit=crop&q=80',
    date: '12 กันยายน 2569',
    category: 'ข่าวประชาสัมพันธ์',
    categoryBadgeClass: 'bg-emerald-600 text-white',
    views: '3.8K',
    readTime: '2 นาที',
    author: {
      name: 'งานบริการนิสิตและทุนการศึกษา',
      role: 'คณะครุศาสตร์',
      avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
    },
    gridClass: 'lg:col-span-4 min-h-[340px]',
  },
])

// Filtered Posts computed property
const filteredPosts = computed(() => {
  if (selectedCategory.value === 'ทั้งหมด') {
    return posts.value
  }
  return posts.value.filter((p) => p.category === selectedCategory.value)
})
</script>

<template>
  <section class="w-full bg-slate-50/70 border-t border-slate-200/80 py-12 sm:py-16 lg:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Section Header -->
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 sm:mb-12">

        <!-- Filter Category Tabs -->
        <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
          <button
            v-for="cat in categories"
            :key="cat"
            type="button"
            class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 cursor-pointer"
            :class="[
              selectedCategory === cat
                ? 'bg-slate-900 text-white shadow-sm'
                : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
            ]"
            @click="selectedCategory = cat"
          >
            {{ cat }}
          </button>
        </div>
      </div>

      <!-- Bento Grid Posts Container -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 sm:gap-6">
        
        <article
          v-for="post in filteredPosts"
          :key="post.id"
          :class="[
            post.gridClass,
            post.featured
              ? 'relative bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col justify-end p-6 sm:p-8 lg:p-10'
              : 'relative bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col'
          ]"
        >
          <!-- ================= FEATURED HERO POST (Big Card Layout) ================= -->
          <template v-if="post.featured">
            <!-- Background Image with Dark Vignette Gradient -->
            <div class="absolute inset-0 z-0 overflow-hidden">
              <img
                :src="post.thumbnail"
                :alt="post.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                loading="lazy"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent" />
            </div>

            <!-- Content Overlay at Bottom -->
            <div class="relative z-10 text-white flex flex-col justify-end">
              <div class="flex items-center gap-2 mb-3">
                <span :class="['px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase', post.categoryBadgeClass]">
                  {{ post.category }}
                </span>
                <span class="text-xs text-slate-300 flex items-center gap-1">
                  <v-icon icon="mdi-calendar-blank-outline" size="14" />
                  {{ post.date }}
                </span>
                <span class="text-xs text-slate-300 flex items-center gap-1 ml-auto">
                  <v-icon icon="mdi-eye-outline" size="14" />
                  {{ post.views }}
                </span>
              </div>

              <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white tracking-tight leading-snug group-hover:text-emerald-300 transition-colors">
                {{ post.title }}
              </h3>

              <p class="text-xs sm:text-sm text-slate-200/90 font-normal mt-2.5 line-clamp-3 leading-relaxed max-w-3xl">
                {{ post.desc }}
              </p>

              <!-- Footer with Author & Action Button -->
              <div class="flex items-center justify-between gap-4 mt-6 pt-5 border-t border-white/20">
                <div class="flex items-center gap-2.5 min-w-0">
                  <img
                    :src="post.author.avatar"
                    :alt="post.author.name"
                    class="w-9 h-9 rounded-full object-cover border border-white/40 shrink-0"
                  />
                  <div class="min-w-0">
                    <p class="text-xs font-bold text-white truncate">{{ post.author.name }}</p>
                    <p class="text-[11px] text-slate-300 truncate">{{ post.author.role }}</p>
                  </div>
                </div>

                <button
                  type="button"
                  class="px-4 py-2 rounded-full bg-white text-slate-950 hover:bg-emerald-600 hover:text-white font-bold text-xs flex items-center gap-1.5 transition-all duration-200 cursor-pointer shrink-0 shadow-md"
                >
                  <span>อ่านรายละเอียด</span>
                  <v-icon icon="mdi-arrow-right" size="16" />
                </button>
              </div>
            </div>
          </template>

          <!-- ================= STANDARD POSTS (Card Layout with Thumbnail on Top) ================= -->
          <template v-else>
            <!-- Thumbnail Image -->
            <div class="relative w-full h-44 sm:h-48 overflow-hidden shrink-0 bg-slate-100">
              <img
                :src="post.thumbnail"
                :alt="post.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                loading="lazy"
              />
              <div class="absolute top-3 left-3">
                <span :class="['px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide shadow-sm', post.categoryBadgeClass]">
                  {{ post.category }}
                </span>
              </div>
              <div class="absolute bottom-2 right-2 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-xs text-[10px] text-white font-medium flex items-center gap-1">
                <v-icon icon="mdi-clock-outline" size="12" />
                <span>{{ post.readTime }}</span>
              </div>
            </div>

            <!-- Text Content -->
            <div class="p-5 flex-1 flex flex-col justify-between">
              <div>
                <!-- Date & Views Metadata -->
                <div class="flex items-center justify-between text-[11px] text-slate-400 mb-2">
                  <span class="flex items-center gap-1">
                    <v-icon icon="mdi-calendar-blank-outline" size="13" />
                    {{ post.date }}
                  </span>
                  <span class="flex items-center gap-1">
                    <v-icon icon="mdi-eye-outline" size="13" />
                    {{ post.views }}
                  </span>
                </div>

                <!-- Post Title -->
                <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight leading-snug line-clamp-2 group-hover:text-emerald-700 transition-colors">
                  {{ post.title }}
                </h3>

                <!-- Post Description -->
                <p class="text-xs sm:text-sm text-slate-500 font-normal mt-2 line-clamp-2 leading-relaxed">
                  {{ post.desc }}
                </p>
              </div>

              <!-- Author & Read More Link -->
              <div class="flex items-center justify-between pt-4 mt-4 border-t border-slate-100">
                <div class="flex items-center gap-2 min-w-0">
                  <img
                    :src="post.author.avatar"
                    :alt="post.author.name"
                    class="w-7 h-7 rounded-full object-cover shrink-0"
                  />
                  <span class="text-xs font-semibold text-slate-700 truncate max-w-[120px]">
                    {{ post.author.name }}
                  </span>
                </div>

                <span class="text-xs font-bold text-emerald-700 group-hover:translate-x-1 transition-transform flex items-center gap-0.5">
                  อ่านต่อ
                  <v-icon icon="mdi-chevron-right" size="16" />
                </span>
              </div>
            </div>
          </template>

        </article>

      </div>

      <!-- Bottom Call To Action for More Posts -->
      <div class="mt-10 sm:mt-14 text-center">
        <button
          type="button"
          class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white hover:bg-slate-100 border border-slate-300 text-slate-800 text-sm font-bold shadow-xs hover:shadow-md transition-all duration-200 cursor-pointer"
        >
          <v-icon icon="mdi-newspaper-variant-multiple-outline" size="18" class="text-emerald-700" />
          <span>ดูข่าวสารและกิจกรรมทั้งหมดของคณะครุศาสตร์</span>
          <v-icon icon="mdi-arrow-right" size="16" />
        </button>
      </div>

    </div>
  </section>
</template>

<style scoped>
/* Typography smooth rendering */
article {
  will-change: transform, box-shadow;
}
</style>
