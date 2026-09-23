<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import PageHeroBanner from '@/components/PageHeroBanner.vue'

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
  highlight?: boolean
  tags: string[]
}

const programsByDegree: Record<'bachelor' | 'grad-diploma' | 'master', ProgramItem[]> = {
  bachelor: [
    {
      id: 'datascience',
      title: 'สาขาวิชาวิทยาการข้อมูล',
      degreeTitle: 'วิทยาศาสตรบัณฑิต (วท.บ.)',
      duration: '4 ปี',
      credits: '120 หน่วยกิต',
      desc: 'มุ่งเน้นการสร้างนักวิทยาศาสตร์ข้อมูลและนักวิเคราะห์ข้อมูลที่มีทักษะการคำนวณขั้นสูง ผสานความรู้ด้านเทคโนโลยีและสารสนเทศเพื่อการพัฒนาการศึกษาและสังคม',
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
      tags: ['จิตวิทยาเด็ก', 'สื่อปฐมวัย', 'นวัตกรรมการเรียนรู้'],
    },
    {
      id: 'elementary',
      title: 'สาขาวิชาการประถมศึกษา',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '132 หน่วยกิต',
      desc: 'ผลิตครูประถมศึกษาที่มีความเชี่ยวชาญการจัดการเรียนรู้บูรณาการกลุ่มสาระต่างๆ พัฒนาทักษะพื้นฐานและคุณธรรมของผู้เรียนระดับประถม',
      tags: ['การจัดการเรียนรู้', 'จิตวิทยาครู', 'การวิจัยชั้นเรียน'],
    },
    {
      id: 'thai',
      title: 'สาขาวิชาภาษาไทย',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '132 หน่วยกิต',
      desc: 'สร้างครูภาษาไทยที่มีความเชี่ยวชาญด้านภาษา วรรณคดีไทย ศิลปะการสื่อสาร และการจัดการเรียนรู้ภาษาไทยอย่างสร้างสรรค์',
      tags: ['ภาษาไทย', 'วรรณคดี', 'วาทศาสตร์'],
    },
    {
      id: 'english',
      title: 'สาขาวิชาภาษาอังกฤษ',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '132 หน่วยกิต',
      desc: 'พัฒนาครูภาษาอังกฤษที่มีทักษะการสื่อสารระดับสากล เชี่ยวชาญการจัดการเรียนรู้ภาษาอังกฤษเป็นภาษาต่างประเทศตามมาตรฐาน CEFR',
      tags: ['CEFR', 'English Teaching', 'Global Communication'],
    },
    {
      id: 'science',
      title: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '136 หน่วยกิต',
      desc: 'มุ่งเน้นการจัดการเรียนรู้วิทยาศาสตร์เชิงสืบเสาะ การทดลอง และสะเต็มศึกษา (STEM Education) สร้างเสริมทักษะกระบวนการทางวิทยาศาสตร์',
      tags: ['STEM Education', 'การทดลอง', 'นวัตกรรมวิทย์'],
    },
    {
      id: 'mathematics',
      title: 'สาขาวิชาคณิตศาสตร์',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '132 หน่วยกิต',
      desc: 'พัฒนาครูคณิตศาสตร์ที่มีทักษะการคิดเชิงตรรกะ การแก้ปัญหา และการนำเทคโนโลยีมาประยุกต์สอนคณิตศาสตร์อย่างเข้าใจง่าย',
      tags: ['Logic & Proof', 'สถิติประยุกต์', 'GeoGebra'],
    },
    {
      id: 'social-studies',
      title: 'สาขาวิชาสังคมศึกษา',
      degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
      duration: '4 ปี',
      credits: '132 หน่วยกิต',
      desc: 'สร้างครูสังคมศึกษาที่มีความรอบรู้ประวัติศาสตร์ ภูมิศาสตร์ เศรษฐศาสตร์ ศาสนา และความเป็นพลเมืองโลก',
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
      tags: ['การพัฒนาหลักสูตร', 'การวิจัยการศึกษา', 'นวัตกรรมการสอน'],
    },
    {
      id: 'educational-admin',
      title: 'สาขาวิชาการบริหารการศึกษา',
      degreeTitle: 'ครุศาสตรมหาบัณฑิต (ค.ม.)',
      duration: '2 ปี',
      credits: '36 หน่วยกิต',
      desc: 'เสริมสร้างภาวะผู้นำทางการศึกษา การบริหารจัดการสถานศึกษาเชิงยุทธศาสตร์ และการประกันคุณภาพการศึกษาตามมาตรฐานสากล',
      tags: ['ภาวะผู้นำทางวิชาการ', 'การบริหารสถานศึกษา', 'การประกันคุณภาพ'],
    },
  ],
}

const currentPrograms = computed(() => {
  return programsByDegree[selectedDegree.value] || []
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="prog in currentPrograms"
            :key="prog.id"
            class="bg-white rounded-2xl p-6 border border-slate-200/90 hover:border-emerald-500/50 shadow-xs hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 flex flex-col justify-between group"
            :class="prog.highlight ? 'ring-1 ring-emerald-500/30' : ''"
          >
            <div class="space-y-3.5">
              <!-- Header Badges -->
              <div class="flex items-center justify-between gap-2">
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                  {{ prog.duration }}
                </span>
                <span class="text-xs text-slate-400 font-medium">
                  {{ prog.credits }}
                </span>
              </div>

              <!-- Title & Degree -->
              <div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug">
                  {{ prog.title }}
                </h3>
                <p class="text-xs text-slate-500 font-medium mt-1">
                  {{ prog.degreeTitle }}
                </p>
              </div>

              <!-- Description -->
              <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
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

            <!-- Action Button -->
            <div class="pt-5 border-t border-slate-100 mt-5">
              <RouterLink
                :to="{ path: '/curriculum/detail', query: { major: prog.id } }"
                class="w-full py-2.5 px-4 rounded-xl bg-slate-50 group-hover:bg-emerald-600 text-slate-700 group-hover:text-white font-bold text-xs text-center transition-all duration-200 flex items-center justify-center gap-2 no-underline"
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
