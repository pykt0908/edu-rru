<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import PageHeroBanner from '@/components/PageHeroBanner.vue'
import { api, type CurriculumRecord } from '@/services/api'

const props = withDefaults(
  defineProps<{
    defaultDegree?: 'bachelor' | 'grad-diploma' | 'master'
  }>(),
  {
    defaultDegree: 'bachelor',
  }
)

const route = useRoute()
const selectedDegree = ref<'bachelor' | 'grad-diploma' | 'master'>(props.defaultDegree)
const loading = ref(true)
const dbCurricula = ref<CurriculumRecord[]>([])

watch(
  () => route.path,
  (newPath) => {
    if (newPath.includes('grad-diploma')) {
      selectedDegree.value = 'grad-diploma'
    } else if (newPath.includes('master')) {
      selectedDegree.value = 'master'
    } else {
      selectedDegree.value = 'bachelor'
    }
  },
  { immediate: true }
)

const degreeTabs = [
  {
    id: 'bachelor' as const,
    label: 'ปริญญาตรี',
    subtitle: 'หลักสูตรครุศาสตรบัณฑิต 4 ปี',
    icon: 'mdi-school',
  },
  {
    id: 'grad-diploma' as const,
    label: 'ประกาศนียบัตรบัณฑิตวิชาชีพครู',
    subtitle: 'หลักสูตร ป.บัณฑิต 1 ปี',
    icon: 'mdi-certificate-outline',
  },
  {
    id: 'master' as const,
    label: 'ปริญญาโท',
    subtitle: 'หลักสูตรครุศาสตรมหาบัณฑิต 2 ปี',
    icon: 'mdi-book-education-outline',
  },
]

interface ProgramItem {
  id: string
  title: string
  degreeTitle: string
  duration: string
  credits: string
  desc: string
  image?: string
  highlight?: boolean
  tags: string[]
}

const fallbackProgramsByDegree: Record<'bachelor' | 'grad-diploma' | 'master', ProgramItem[]> = {
  bachelor: [
    {
      id: 'datascience',
      title: 'สาขาวิชาวิทยาการข้อมูล',
      degreeTitle: 'วิทยาศาสตรบัณฑิต (วท.บ.)',
      duration: '4 ปี',
      credits: '120 หน่วยกิต',
      desc: 'มุ่งเน้นการสร้างนักวิทยาศาสตร์ข้อมูลและนักวิเคราะห์ข้อมูลที่มีทักษะการคำนวณขั้นสูง ผสานความรู้ด้านเทคโนโลยีและสารสนเทศเพื่อการพัฒนาการศึกษาและสังคม',
      image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80',
      highlight: true,
      tags: ['Data Science', 'Machine Learning', 'Big Data'],
    },
    {
      id: 'early-childhood',
      title: 'สาขาวิชาการศึกษาปฐมวัย',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '132 หน่วยกิต',
      desc: 'ผลิตครูปฐมวัยที่มีความรู้ลึกซึ้งด้านพัฒนาการเด็ก มีทักษะการจัดประสบการณ์การเรียนรู้ และจิตวิญญาณความเป็นครูอย่างมืออาชีพ',
      image: 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=800&q=80',
      tags: ['จิตวิทยาเด็ก', 'สื่อปฐมวัย', 'นวัตกรรมการเรียนรู้'],
    },
    {
      id: 'elementary',
      title: 'สาขาวิชาการประถมศึกษา',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '132 หน่วยกิต',
      desc: 'ผลิตครูประถมศึกษาที่มีความเชี่ยวชาญการจัดการเรียนรู้บูรณาการกลุ่มสาระต่างๆ พัฒนาทักษะพื้นฐานและคุณธรรมของผู้เรียนระดับประถม',
      image: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=800&q=80',
      tags: ['การจัดการเรียนรู้', 'จิตวิทยาครู', 'การวิจัยชั้นเรียน'],
    },
    {
      id: 'thai',
      title: 'สาขาวิชาภาษาไทย',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '132 หน่วยกิต',
      desc: 'สร้างครูภาษาไทยที่มีความเชี่ยวชาญด้านภาษา วรรณคดีไทย ศิลปะการสื่อสาร และการจัดการเรียนรู้ภาษาไทยอย่างสร้างสรรค์',
      image: 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?auto=format&fit=crop&w=800&q=80',
      tags: ['ภาษาไทย', 'วรรณคดี', 'วาทศาสตร์'],
    },
    {
      id: 'english',
      title: 'สาขาวิชาภาษาอังกฤษ',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '132 หน่วยกิต',
      desc: 'พัฒนาครูภาษาอังกฤษที่มีทักษะการสื่อสารระดับสากล เชี่ยวชาญการจัดการเรียนรู้ภาษาอังกฤษเป็นภาษาต่างประเทศตามมาตรฐาน CEFR',
      image: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80',
      tags: ['CEFR', 'English Teaching', 'Global Communication'],
    },
    {
      id: 'science',
      title: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '136 หน่วยกิต',
      desc: 'มุ่งเน้นการจัดการเรียนรู้วิทยาศาสตร์เชิงสืบเสาะ การทดลอง และสะเต็มศึกษา (STEM Education) สร้างเสริมทักษะกระบวนการทางวิทยาศาสตร์',
      image: 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=800&q=80',
      tags: ['STEM Education', 'การทดลอง', 'นวัตกรรมวิทย์'],
    },
    {
      id: 'mathematics',
      title: 'สาขาวิชาคณิตศาสตร์',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '132 หน่วยกิต',
      desc: 'พัฒนาครูคณิตศาสตร์ที่มีทักษะการคิดเชิงตรรกะ การแก้ปัญหา และการนำเทคโนโลยีมาประยุกต์สอนคณิตศาสตร์อย่างเข้าใจง่าย',
      image: 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?auto=format&fit=crop&w=800&q=80',
      tags: ['Logic & Proof', 'สถิติประยุกต์', 'GeoGebra'],
    },
    {
      id: 'social-studies',
      title: 'สาขาวิชาสังคมศึกษา',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '132 หน่วยกิต',
      desc: 'สร้างครูสังคมศึกษาที่มีความรอบรู้ประวัติศาสตร์ ภูมิศาสตร์ เศรษฐศาสตร์ ศาสนา และความเป็นพลเมืองโลก',
      image: 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?auto=format&fit=crop&w=800&q=80',
      tags: ['ประวัติศาสตร์', 'ภูมิศาสตร์', 'ความเป็นพลเมือง'],
    },
  ],
  'grad-diploma': [
    {
      id: 'grad-dip-teaching',
      title: 'หลักสูตรประกาศนียบัตรบัณฑิต สาขาวิชาชีพครู',
      degreeTitle: 'ประกาศนียบัตรบัณฑิตวิชาชีพครู (ป.บัณฑิต)',
      duration: '1 ปี (3 ภาคการศึกษา)',
      credits: '34 หน่วยกิต',
      desc: 'หลักสูตรสำหรับผู้สำเร็จการศึกษาระดับปริญญาตรีทุกสาขาวิชาที่ต้องการพัฒนาสมรรถนะวิชาชีพครูตามมาตรฐานคุรุสภา พร้อมฝึกประสบการณ์วิชาชีพในสถานศึกษาจริง',
      image: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80',
      highlight: true,
      tags: ['มาตรฐานคุรุสภา', 'ฝึกสอนในโรงเรียน', 'วิชาชีพครู'],
    },
  ],
  master: [
    {
      id: 'curriculum-instruction',
      title: 'สาขาวิชาหลักสูตรและการสอน',
      degreeTitle: 'ครุศาสตรมหาบัณฑิต (ค.ม.)',
      duration: '2 ปี',
      credits: '36 หน่วยกิต',
      desc: 'พัฒนาผู้เชี่ยวชาญด้านการพัฒนาหลักสูตร การออกแบบนวัตกรรมการจัดการเรียนรู้ขั้นสูง และการวิจัยเพื่อพัฒนาการศึกษาในยุคดิจิทัล',
      image: 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=800&q=80',
      tags: ['การพัฒนาหลักสูตร', 'การวิจัยการศึกษา', 'นวัตกรรมการสอน'],
    },
    {
      id: 'educational-admin',
      title: 'สาขาวิชาการบริหารการศึกษา',
      degreeTitle: 'ครุศาสตรมหาบัณฑิต (ค.ม.)',
      duration: '2 ปี',
      credits: '36 หน่วยกิต',
      desc: 'เสริมสร้างภาวะผู้นำทางการศึกษา การบริหารจัดการสถานศึกษาเชิงยุทธศาสตร์ และการประกันคุณภาพการศึกษาตามมาตรฐานสากล',
      image: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
      tags: ['ภาวะผู้นำทางวิชาการ', 'การบริหารสถานศึกษา', 'การประกันคุณภาพ'],
    },
  ],
}

async function loadCurricula() {
  loading.value = true
  try {
    const data = await api.getCurricula({ active_only: true })
    if (data && data.length > 0) {
      dbCurricula.value = data
    }
  } catch (err) {
    console.warn('Failed to load curricula from API, using fallback:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadCurricula()
})

const currentPrograms = computed<ProgramItem[]>(() => {
  if (dbCurricula.value.length > 0) {
    const list = dbCurricula.value.filter((c) => c.degree_level === selectedDegree.value)
    if (list.length > 0) {
      return list.map((c) => ({
        id: c.slug,
        title: c.title,
        degreeTitle: c.degree_title,
        duration: c.duration,
        credits: c.credits,
        desc: c.desc || '',
        image: c.image || 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80',
        highlight: c.highlight,
        tags: c.tags || [],
      }))
    }
  }
  return fallbackProgramsByDegree[selectedDegree.value] || []
})

const getHeroTitle = computed(() => {
  if (selectedDegree.value === 'grad-diploma') {
    return 'ประกาศนียบัตรบัณฑิต'
  }
  if (selectedDegree.value === 'master') {
    return 'หลักสูตรปริญญาโท'
  }
  return 'หลักสูตรปริญญาตรี'
})

const getHeroHighlight = computed(() => {
  if (selectedDegree.value === 'grad-diploma') {
    return 'วิชาชีพครู'
  }
  if (selectedDegree.value === 'master') {
    return 'ครุศาสตรมหาบัณฑิต'
  }
  return 'คณะครุศาสตร์'
})
</script>

<template>
  <div class="min-h-screen bg-slate-50/50">
    <!-- Hero Banner with Logo -->
    <PageHeroBanner
      badge="Curriculum Programs"
      badge-icon="mdi-school"
      :title="getHeroTitle"
      :title-highlight="getHeroHighlight"
      subtitle="หลักสูตรที่มุ่งเน้นการผลิตบัณฑิตครูและนักวิชาชีพที่มีความเป็นเลิศ เพียบพร้อมด้วยคุณธรรมและสมรรถนะแห่งศตวรรษที่ 21"
    />

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-10">
      <!-- Degree Level Navigation Tabs -->
      <div class="bg-white p-2 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
          <button
            v-for="deg in degreeTabs"
            :key="deg.id"
            type="button"
            class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 text-left cursor-pointer"
            :class="
              selectedDegree === deg.id
                ? 'bg-emerald-700 text-white shadow-md shadow-emerald-700/20'
                : 'hover:bg-slate-100/80 text-slate-700'
            "
            @click="selectedDegree = deg.id"
          >
            <div
              class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 transition-colors"
              :class="selectedDegree === deg.id ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-700'"
            >
              <v-icon :icon="deg.icon" size="20" />
            </div>
            <div class="min-w-0">
              <span class="text-sm font-bold block truncate">{{ deg.label }}</span>
              <span
                class="text-xs block truncate mt-0.5"
                :class="selectedDegree === deg.id ? 'text-emerald-100' : 'text-slate-500'"
              >
                {{ deg.subtitle }}
              </span>
            </div>
          </button>
        </div>
      </div>

      <!-- Programs Grid -->
      <div class="space-y-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <span class="w-1 h-6 rounded-full bg-emerald-600" />
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
              สาขาวิชาที่เปิดสอน ({{ currentPrograms.length }} หลักสูตร)
            </h2>
          </div>
          <span class="text-xs text-slate-500 font-medium">ปีการศึกษา 2569</span>
        </div>

        <!-- Skeleton Loader -->
        <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="i in 6" :key="i" class="bg-white rounded-2xl p-4 border border-slate-200 animate-pulse space-y-3">
            <div class="h-48 bg-slate-200 rounded-xl" />
            <div class="h-5 bg-slate-200 rounded w-3/4" />
            <div class="h-4 bg-slate-100 rounded w-1/2" />
          </div>
        </div>

        <!-- Real Cards with High Quality Images -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="prog in currentPrograms"
            :key="prog.id"
            class="bg-white rounded-2xl border border-slate-200/90 hover:border-emerald-500/50 shadow-xs hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 flex flex-col justify-between group overflow-hidden"
            :class="prog.highlight ? 'ring-1 ring-emerald-500/40' : ''"
          >
            <div>
              <!-- Featured Image with Zoom & Floating Badges -->
              <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
                <img
                  v-if="prog.image"
                  :src="prog.image"
                  :alt="prog.title"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  @error="($event.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80'"
                />
                <div v-else class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-100">
                  <v-icon icon="mdi-image-outline" size="36" />
                </div>

                <!-- Bottom gradient for text legibility -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-transparent to-transparent pointer-events-none" />

                <!-- Floating Top Badges -->
                <div class="absolute top-3 left-3 flex items-center gap-1.5">
                  <span
                    v-if="prog.highlight"
                    class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white shadow-sm inline-flex items-center gap-1"
                  >
                    <v-icon icon="mdi-star" size="12" />
                    <span>แนะนำ</span>
                  </span>
                </div>

                <!-- Floating Bottom Duration & Credits -->
                <div class="absolute bottom-2.5 inset-x-3 flex items-center justify-between text-white text-[11px] font-semibold">
                  <span class="px-2 py-0.5 rounded-md bg-black/40 backdrop-blur-xs flex items-center gap-1">
                    <v-icon icon="mdi-clock-outline" size="12" />
                    <span>{{ prog.duration }}</span>
                  </span>
                  <span class="px-2 py-0.5 rounded-md bg-black/40 backdrop-blur-xs flex items-center gap-1">
                    <v-icon icon="mdi-school-outline" size="12" />
                    <span>{{ prog.credits }}</span>
                  </span>
                </div>
              </div>

              <!-- Card Content -->
              <div class="p-5 space-y-3">
                <!-- Title & Degree -->
                <div>
                  <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug">
                    {{ prog.title }}
                  </h3>
                  <p class="text-xs text-emerald-700 font-semibold mt-1">
                    {{ prog.degreeTitle }}
                  </p>
                </div>

                <!-- Description -->
                <p class="text-xs text-slate-600 leading-relaxed line-clamp-2">
                  {{ prog.desc }}
                </p>

                <!-- Tags -->
                <div class="flex flex-wrap gap-1.5 pt-1">
                  <span
                    v-for="t in prog.tags"
                    :key="t"
                    class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-600"
                  >
                    {{ t }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Action Button -->
            <div class="p-5 pt-0">
              <RouterLink
                :to="{ path: '/curriculum/detail', query: { major: prog.id } }"
                class="w-full py-2.5 px-4 rounded-xl bg-slate-50 group-hover:bg-emerald-600 text-slate-700 group-hover:text-white font-bold text-xs text-center transition-all duration-200 flex items-center justify-center gap-2 no-underline shadow-2xs group-hover:shadow-md group-hover:shadow-emerald-600/20"
              >
                <span>ดูรายละเอียดหลักสูตร</span>
                <v-icon icon="mdi-arrow-right" size="14" class="group-hover:translate-x-1 transition-transform" />
              </RouterLink>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
