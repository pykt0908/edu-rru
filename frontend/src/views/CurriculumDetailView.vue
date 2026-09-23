<script setup lang="ts">
import { ref } from 'vue'
import eduLogo from '@/assets/logos/edu-logo-border-white.png'

// Active tab
const activeTab = ref('details')

const tabs = [
  { id: 'details', label: 'รายละเอียดหลักสูตร' },
  { id: 'fees', label: 'ค่าธรรมเนียมการศึกษา' },
  { id: 'structure', label: 'โครงสร้างหลักสูตร' },
  { id: 'plan', label: 'แผนการเรียน' },
  { id: 'faculty', label: 'อาจารย์ประจำหลักสูตร' },
]

// Current gallery photo index
const activePhotoIndex = ref(0)
const isFullscreen = ref(false)

const photos = [
  {
    url: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1000&q=80',
    title: 'พิธีสำเร็จการศึกษาและการแสดงความยินดี',
    badge: 'CONGRATULATIONS',
  },
  {
    url: 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1000&q=80',
    title: 'การเรียนการสอนเชิงปฏิบัติการและการพัฒนานวัตกรรมข้อมูล',
    badge: 'WORKSHOP & LAB',
  },
  {
    url: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1000&q=80',
    title: 'การจัดนิทรรศการผลงานและการนำเสนอทางวิชาการ',
    badge: 'EXHIBITION',
  },
  {
    url: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1000&q=80',
    title: 'การแข่งขันทักษะและการรับรางวัลระดับประเทศ',
    badge: 'NATIONAL CODING DAY',
  },
]

const nextPhoto = () => {
  activePhotoIndex.value = (activePhotoIndex.value + 1) % photos.length
}

const prevPhoto = () => {
  activePhotoIndex.value = (activePhotoIndex.value - 1 + photos.length) % photos.length
}

// Curriculum Data
const program = {
  nameTh: 'หลักสูตรวิทยาศาสตรบัณฑิต สาขาวิชาวิทยาการข้อมูล',
  nameEn: 'Bachelor of Science Program in Data Science',
  degreeFullTh: 'วิทยาศาสตรบัณฑิต (วิทยาการข้อมูล)',
  degreeFullEn: 'Bachelor of Science (Data Science)',
  degreeShortTh: 'วท.บ. (วิทยาการข้อมูล)',
  degreeShortEn: 'B.Sc. (Data Science)',
  credits: '120 หน่วยกิต',
  format: 'หลักสูตรปริญญาตรี 4 ปี',
  type: 'ปริญญาตรีทางวิชาการ',
  language: 'ภาษาไทย/ภาษาอังกฤษ',
  location: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
  learningOutcomes: [
    {
      year: 'ชั้นปีที่ 1',
      description:
        'อธิบายหลักการด้านวิทยาการข้อมูล วิธีการในการจัดการข้อมูล หลักการออกแบบวิธีการแก้ปัญหา แนวทางพัฒนาระบบสารสนเทศด้านการประมวลผลข้อมูล และจรรยาบรรณทางวิทยาการข้อมูล ใช้เครื่องมือวิเคราะห์ข้อมูล เขียนโปรแกรมคอมพิวเตอร์เบื้องต้น ออกแบบวิธีการแก้ปัญหาเกี่ยวกับการบริหารจัดการข้อมูล พัฒนาระบบสารสนเทศและแบบจำลองข้อมูล',
    },
    {
      year: 'ชั้นปีที่ 2',
      description:
        'ประยุกต์ใช้องค์ความรู้ด้านวิทยาการข้อมูล วิเคราะห์และบริหารจัดการข้อมูล ใช้โปรแกรมทางคณิตศาสตร์และสถิติในการแก้ปัญหาต่างๆ',
    },
    {
      year: 'ชั้นปีที่ 3',
      description:
        'วิเคราะห์ออกแบบวิธีการแก้ปัญหาเกี่ยวกับการบริหารจัดการข้อมูล พัฒนาระบบสารสนเทศและแบบจำลองข้อมูล',
    },
    {
      year: 'ชั้นปีที่ 4',
      description:
        'บูรณาการองค์ความรู้ด้านวิทยาการข้อมูลเพื่อแก้ปัญหาอย่างเป็นระบบและมีเหตุผล',
    },
  ],
  careers: [
    'นักวิทยาศาสตร์ข้อมูล (Data Scientist)',
    'นักวิเคราะห์และวางแผนข้อมูล (Data Analyst)',
    'วิศวกรข้อมูล (Data Engineer)',
    'นักวิเคราะห์สถิติ (Statistical Analyst)',
    'นักวิชาการด้านวิทยาการข้อมูล (Academic Staff)',
    'วิศวกรด้านการเรียนรู้ของเครื่อง (Machine Learning Engineer)',
    'ครูและอาจารย์ผู้สอนด้านวิทยาการคำนวณและเทคโนโลยีดิจิทัล',
  ],
  tuitionFees: [
    { term: 'ภาคการศึกษาที่ 1', amount: '13,500 บาท', note: 'รวมค่าธรรมเนียมแรกเข้าและอุปกรณ์' },
    { term: 'ภาคการศึกษาที่ 2', amount: '11,500 บาท', note: 'ค่าลงทะเบียนปกติ' },
    { term: 'รวมตลอดหลักสูตร (8 ภาคการศึกษา)', amount: '94,000 บาท', note: 'เฉลี่ยเทอมละประมาณ 11,750 บาท' },
  ],
  structures: [
    { category: '1. หมวดวิชาศึกษาทั่วไป', credits: '30 หน่วยกิต' },
    { category: '2. หมวดวิชาเฉพาะ (วิชาแกน, วิชาเอกบังคับ, วิชาเอกเลือก)', credits: '84 หน่วยกิต' },
    { category: '3. หมวดวิชาเลือกเสรี', credits: '6 หน่วยกิต' },
    { category: 'รวมหน่วยกิตตลอดหลักสูตร', credits: '120 หน่วยกิต', highlight: true },
  ],
  studyPlan: [
    {
      year: 'ชั้นปีที่ 1',
      semesters: [
        {
          name: 'ภาคเรียนที่ 1',
          courses: [
            'การคิดเชิงคำนวณและการแก้ปัญหา (3 นก.)',
            'สถิติเบื้องต้นสำหรับวิทยาการข้อมูล (3 นก.)',
            'ภาษาอังกฤษเพื่อการสื่อสารทางวิชาการ (3 นก.)',
            'การรู้ดิจิทัลและการรู้สารสนเทศ (3 นก.)',
            'วิชาศึกษาทั่วไปเลือก (3 นก.)',
          ],
        },
        {
          name: 'ภาคเรียนที่ 2',
          courses: [
            'การโปรแกรมคอมพิวเตอร์ด้วยไพธอน (3 นก.)',
            'พีชคณิตเชิงเส้นประยุกต์ (3 นก.)',
            'โครงสร้างข้อมูลและขั้นตอนวิธี (3 นก.)',
            'ทักษะชีวิตและความเป็นพลเมือง (3 นก.)',
            'วิชาศึกษาทั่วไปเลือก (3 นก.)',
          ],
        },
      ],
    },
    {
      year: 'ชั้นปีที่ 2',
      semesters: [
        {
          name: 'ภาคเรียนที่ 1',
          courses: [
            'การจัดการและจัดเตรียมข้อมูล (3 นก.)',
            'ระบบฐานข้อมูลและการจัดการ (3 นก.)',
            'การวิเคราะห์ข้อมูลเชิงสำรวจและการนำเสนอ (3 นก.)',
            'สถิติเชิงอนุมานสำหรับการวิจัย (3 นก.)',
          ],
        },
        {
          name: 'ภาคเรียนที่ 2',
          courses: [
            'การเรียนรู้ของเครื่องเบื้องต้น (3 นก.)',
            'ปัญญาประดิษฐ์และระบบผู้เชี่ยวชาญ (3 นก.)',
            'วิศวกรรมข้อมูลขนาดใหญ่ (3 นก.)',
            'การเขียนโปรแกรมเชิงวัตถุ (3 นก.)',
          ],
        },
      ],
    },
  ],
  facultyInstructors: [
    {
      name: 'ผู้ช่วยศาสตราจารย์ ดร. สมชาย ใจดี',
      position: 'ประธานอาจารย์ประจำหลักสูตร',
      degree: 'ปร.ด. (วิทยาการคอมพิวเตอร์), วท.ม. (เทคโนโลยีสารสนเทศ)',
    },
    {
      name: 'อาจารย์ ดร. วราภรณ์ วงศ์วัฒนา',
      position: 'อาจารย์ประจำหลักสูตร',
      degree: 'Ph.D. (Data Science & AI), วท.บ. (วิทยาการคอมพิวเตอร์)',
    },
    {
      name: 'อาจารย์ ณัฐพล ทองคำ',
      position: 'อาจารย์ประจำหลักสูตร / เลขานุการหลักสูตร',
      degree: 'วท.ม. (วิทยาการข้อมูล), วศ.บ. (วิศวกรรมคอมพิวเตอร์)',
    },
  ],
}
</script>

<template>
  <div class="min-h-screen bg-slate-50/50 pb-20">
    <!-- Top Breadcrumb & Program Title -->
    <div class="bg-white border-b border-slate-200/80">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-2">
              <RouterLink to="/" class="hover:text-emerald-700">หน้าแรก</RouterLink>
              <span>/</span>
              <RouterLink to="/curriculum/bachelor" class="hover:text-emerald-700">หลักสูตรปริญญาตรี</RouterLink>
              <span>/</span>
              <span class="text-slate-700 font-medium">วิทยาการข้อมูล</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">
              สาขาวิชาวิทยาการข้อมูล
            </h1>
            <p class="text-sm sm:text-base text-slate-500 font-medium tracking-wide lowercase mt-1">
              data science
            </p>
          </div>

          <!-- Back to Programs Link -->
          <div>
            <RouterLink
              to="/curriculum/bachelor"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold transition-colors no-underline"
            >
              <v-icon icon="mdi-arrow-left" size="16" />
              <span>ดูหลักสูตรทั้งหมด</span>
            </RouterLink>
          </div>
        </div>
      </div>
    </div>

    <!-- Gallery / Photo Strip (Matching Reference Image) -->
    <div class="relative bg-slate-950 overflow-hidden select-none">
      <div class="max-w-6xl mx-auto relative group">
        <!-- 4-Image Flex Strip on Desktop / Single Slider on Mobile -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-1 sm:gap-2 p-1 sm:p-2">
          <div
            v-for="(photo, idx) in photos"
            :key="idx"
            class="relative aspect-[4/3] overflow-hidden bg-slate-900 rounded-lg group/item cursor-pointer"
            :class="idx === activePhotoIndex ? 'ring-2 ring-emerald-500' : 'opacity-90 hover:opacity-100'"
            @click="activePhotoIndex = idx"
          >
            <img
              :src="photo.url"
              :alt="photo.title"
              class="w-full h-full object-cover transition-transform duration-500 group-hover/item:scale-105"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-60 group-hover/item:opacity-80 transition-opacity" />
            <div class="absolute bottom-2 left-2 right-2">
              <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold bg-black/60 text-white tracking-wider backdrop-blur-xs">
                {{ photo.badge }}
              </span>
            </div>
          </div>
        </div>

        <!-- Fullscreen Button matching reference image -->
        <button
          type="button"
          aria-label="ดูภาพขนาดเต็ม"
          class="absolute right-14 top-3 w-8 h-8 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center backdrop-blur-xs transition-all shadow-md cursor-pointer"
          @click="isFullscreen = true"
        >
          <v-icon icon="mdi-fullscreen" size="18" />
        </button>

        <!-- Arrow Controls -->
        <button
          type="button"
          aria-label="รูปก่อนหน้า"
          class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center backdrop-blur-xs transition-all shadow-lg cursor-pointer"
          @click="prevPhoto"
        >
          <v-icon icon="mdi-chevron-left" size="22" />
        </button>
        <button
          type="button"
          aria-label="รูปถัดไป"
          class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center backdrop-blur-xs transition-all shadow-lg cursor-pointer"
          @click="nextPhoto"
        >
          <v-icon icon="mdi-chevron-right" size="22" />
        </button>
      </div>

      <!-- Fullscreen Modal -->
      <div
        v-if="isFullscreen"
        class="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex flex-col items-center justify-center p-4"
        @click.self="isFullscreen = false"
      >
        <button
          type="button"
          class="absolute top-5 right-5 text-white/80 hover:text-white p-2 cursor-pointer"
          @click="isFullscreen = false"
        >
          <v-icon icon="mdi-close" size="32" />
        </button>
        <img
          :src="photos[activePhotoIndex]?.url"
          :alt="photos[activePhotoIndex]?.title"
          class="max-w-4xl max-h-[80vh] object-contain rounded-xl shadow-2xl"
        />
        <p class="text-white text-sm mt-4 font-medium text-center">
          {{ photos[activePhotoIndex]?.title }}
        </p>
      </div>
    </div>

    <!-- Main Container -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
      <!-- Navigation Tabs Container (Matching Reference Image) -->
      <div class="bg-indigo-50/70 p-1.5 rounded-2xl flex items-center gap-1 overflow-x-auto scrollbar-none border border-indigo-100/80">
        <button
          v-for="t in tabs"
          :key="t.id"
          type="button"
          class="flex-1 py-2.5 px-3 sm:px-4 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200 cursor-pointer text-center"
          :class="
            activeTab === t.id
              ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20'
              : 'text-indigo-900/80 hover:bg-indigo-100/70 hover:text-indigo-950'
          "
          @click="activeTab = t.id"
        >
          {{ t.label }}
        </button>
      </div>

      <!-- Main Two-Column Layout (Matching Reference Image) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- LEFT COLUMN: Main Details (~68% width / col-span-8) -->
        <div class="lg:col-span-8 space-y-8">
          <!-- TAB 1: รายละเอียดหลักสูตร -->
          <div v-if="activeTab === 'details'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-8">
            <!-- 1. ชื่อหลักสูตร -->
            <section class="space-y-4">
              <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                ชื่อหลักสูตร
              </h2>
              <div class="border-t border-slate-100 divide-y divide-slate-100 text-xs sm:text-sm">
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ชื่อภาษาไทย</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">{{ program.nameTh }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ชื่อภาษาอังกฤษ</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">{{ program.nameEn }}</span>
                </div>
              </div>
            </section>

            <!-- 2. ชื่อปริญญาและสาขาวิชา -->
            <section class="space-y-4">
              <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                ชื่อปริญญาและสาขาวิชา
              </h2>
              <div class="border-t border-slate-100 divide-y divide-slate-100 text-xs sm:text-sm">
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ชื่อเต็ม (ภาษาไทย)</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">{{ program.degreeFullTh }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ชื่อเต็ม (ภาษาอังกฤษ)</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">{{ program.degreeFullEn }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ชื่อย่อ (ภาษาไทย)</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">{{ program.degreeShortTh }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ชื่อย่อ (ภาษาอังกฤษ)</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">{{ program.degreeShortEn }}</span>
                </div>
              </div>
            </section>

            <!-- 3. จำนวนหน่วยกิต รูปแบบ และประเภทของหลักสูตร -->
            <section class="space-y-4">
              <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                จำนวนหน่วยกิต รูปแบบ และประเภทของหลักสูตร
              </h2>
              <div class="border-t border-slate-100 divide-y divide-slate-100 text-xs sm:text-sm">
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">จำนวนหน่วยกิตที่เรียนตลอดหลักสูตร</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">{{ program.credits }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">รูปแบบของหลักสูตร</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">{{ program.format }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ประเภทของหลักสูตร</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">{{ program.type }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ภาษาที่ใช้</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">{{ program.language }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">สถานที่จัดการเรียนการสอน</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">{{ program.location }}</span>
                </div>
              </div>
            </section>

            <!-- 4. ความคาดหวังของผลลัพธ์การเรียนรู้เมื่อสิ้นปีการศึกษา -->
            <section class="space-y-4">
              <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                ความคาดหวังของผลลัพธ์การเรียนรู้เมื่อสิ้นปีการศึกษา
              </h2>
              <div class="border-t border-slate-100 divide-y divide-slate-100 text-xs sm:text-sm">
                <div
                  v-for="outcome in program.learningOutcomes"
                  :key="outcome.year"
                  class="grid grid-cols-1 sm:grid-cols-4 py-3.5 gap-2 sm:gap-4"
                >
                  <span class="font-bold text-slate-800 shrink-0">{{ outcome.year }}</span>
                  <span class="sm:col-span-3 text-slate-600 leading-relaxed">{{ outcome.description }}</span>
                </div>
              </div>
            </section>

            <!-- 5. อาชีพที่สามารถประกอบได้หลังสำเร็จการศึกษา -->
            <section class="space-y-4">
              <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                อาชีพที่สามารถประกอบได้หลังสำเร็จการศึกษา
              </h2>
              <ul class="space-y-2 text-xs sm:text-sm text-slate-700 pl-2">
                <li
                  v-for="(job, jIdx) in program.careers"
                  :key="jIdx"
                  class="flex items-start gap-2.5"
                >
                  <span class="text-indigo-600 font-bold leading-none mt-1">▸</span>
                  <span>{{ job }}</span>
                </li>
              </ul>
            </section>
          </div>

          <!-- TAB 2: ค่าธรรมเนียมการศึกษา -->
          <div v-else-if="activeTab === 'fees'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-6">
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
              ค่าธรรมเนียมการศึกษา
            </h2>
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                  <tr class="border-b border-slate-200 bg-slate-50 text-slate-700 font-bold">
                    <th class="py-3 px-4">รายการ</th>
                    <th class="py-3 px-4 text-right">จำนวนเงิน</th>
                    <th class="py-3 px-4">หมายเหตุ</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="(fee, fIdx) in program.tuitionFees" :key="fIdx">
                    <td class="py-3.5 px-4 font-medium text-slate-800">{{ fee.term }}</td>
                    <td class="py-3.5 px-4 font-bold text-indigo-700 text-right">{{ fee.amount }}</td>
                    <td class="py-3.5 px-4 text-slate-500 text-xs">{{ fee.note }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200/80 text-xs text-amber-800 flex items-start gap-2">
              <v-icon icon="mdi-information-outline" size="16" class="text-amber-600 mt-0.5 shrink-0" />
              <span>อัตราค่าธรรมเนียมการศึกษาอาจมีการเปลี่ยนแปลงตามประกาศของมหาวิทยาลัยราชภัฏราชนครินทร์</span>
            </div>
          </div>

          <!-- TAB 3: โครงสร้างหลักสูตร -->
          <div v-else-if="activeTab === 'structure'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-6">
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
              โครงสร้างหลักสูตร
            </h2>
            <div class="border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100 text-xs sm:text-sm">
              <div
                v-for="(st, sIdx) in program.structures"
                :key="sIdx"
                class="flex items-center justify-between p-4"
                :class="st.highlight ? 'bg-indigo-50 font-bold text-indigo-900' : 'text-slate-800'"
              >
                <span>{{ st.category }}</span>
                <span class="font-bold shrink-0">{{ st.credits }}</span>
              </div>
            </div>
          </div>

          <!-- TAB 4: แผนการเรียน -->
          <div v-else-if="activeTab === 'plan'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-6">
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
              แผนการเรียน (ตัวอย่าง)
            </h2>
            <div class="space-y-6">
              <div v-for="yp in program.studyPlan" :key="yp.year" class="space-y-3">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-indigo-600" />
                  <span>{{ yp.year }}</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div
                    v-for="sem in yp.semesters"
                    :key="sem.name"
                    class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-2"
                  >
                    <span class="font-bold text-xs text-indigo-700 block">{{ sem.name }}</span>
                    <ul class="text-xs text-slate-600 space-y-1.5 pl-3">
                      <li v-for="(c, cIdx) in sem.courses" :key="cIdx" class="list-disc">
                        {{ c }}
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 5: อาจารย์ประจำหลักสูตร -->
          <div v-else-if="activeTab === 'faculty'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-6">
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
              อาจารย์ประจำหลักสูตร
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div
                v-for="prof in program.facultyInstructors"
                :key="prof.name"
                class="p-4 rounded-2xl border border-slate-200 bg-slate-50/40 text-center space-y-3"
              >
                <div class="w-20 h-20 rounded-full mx-auto bg-indigo-100 flex items-center justify-center text-indigo-700 shadow-inner">
                  <v-icon icon="mdi-account" size="40" />
                </div>
                <div>
                  <h4 class="text-sm font-bold text-slate-900">{{ prof.name }}</h4>
                  <p class="text-xs font-semibold text-indigo-700 mt-0.5">{{ prof.position }}</p>
                  <p class="text-[11px] text-slate-500 mt-1 leading-snug">{{ prof.degree }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN: Sidebar Widgets (~32% width / col-span-4) (Matching Reference Image) -->
        <div class="lg:col-span-4 space-y-6">
          <!-- Widget 1: เอกสารหลักสูตร (Curriculum Booklet & Actions) -->
          <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs space-y-5">
            <h3 class="text-base font-bold text-slate-900">
              เอกสารหลักสูตร
            </h3>

            <!-- Booklet Cover Preview (Document Mock matching screenshot) -->
            <div class="relative mx-auto w-48 sm:w-52 aspect-[3/4] bg-white border border-slate-200 shadow-md rounded-lg p-5 flex flex-col items-center justify-between text-center select-none">
              <!-- Top Emblem -->
              <div class="w-12 h-12 rounded-full border border-slate-300 p-1 flex items-center justify-center bg-slate-50">
                <img :src="eduLogo" alt="ตราสัญลักษณ์" class="w-8 h-8 object-contain" />
              </div>

              <!-- Title on cover -->
              <div class="space-y-1.5 my-auto">
                <span class="text-[10px] text-slate-500 uppercase tracking-widest block font-bold">มคอ. 2</span>
                <p class="text-xs font-bold text-slate-800 leading-snug">หลักสูตรวิทยาศาสตรบัณฑิต</p>
                <p class="text-[11px] text-slate-600">สาขาวิชาวิทยาการข้อมูล</p>
                <p class="text-[9px] text-slate-400">หลักสูตรปรับปรุง พ.ศ. 2568</p>
              </div>

              <!-- Bottom affiliation -->
              <div class="text-[8px] text-slate-500 leading-tight border-t border-slate-100 pt-2 w-full">
                <p class="font-medium">คณะครุศาสตร์</p>
                <p>มหาวิทยาลัยราชภัฏราชนครินทร์</p>
              </div>
            </div>

            <!-- Action Buttons (Solid Purple & Outlined matching image) -->
            <div class="space-y-2.5 pt-2">
              <button
                type="button"
                class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold text-sm shadow-sm transition-colors flex items-center justify-center gap-2 cursor-pointer"
              >
                <v-icon icon="mdi-download" size="18" />
                <span>ดาวน์โหลด</span>
              </button>

              <button
                type="button"
                class="w-full py-2.5 px-4 rounded-xl bg-white hover:bg-indigo-50 active:bg-indigo-100 text-indigo-700 font-bold text-sm border-2 border-indigo-600 transition-colors flex items-center justify-center gap-2 cursor-pointer"
              >
                <v-icon icon="mdi-account-plus-outline" size="18" />
                <span>สมัครเรียน</span>
              </button>
            </div>
          </div>

          <!-- Widget 2: Facebook / Social Contact Box (Matching Reference Image) -->
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900">
              Facebook
            </h3>

            <!-- Facebook Page Header Simulation -->
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70 space-y-3">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                  <v-icon icon="mdi-school" size="20" />
                </div>
                <div class="min-w-0">
                  <h4 class="text-xs font-bold text-slate-900 truncate">
                    หลักสูตรวิทยาการข้อมูล...
                  </h4>
                  <p class="text-[11px] text-slate-500">388 ผู้ติดตาม</p>
                </div>
              </div>

              <!-- Follow & Share Buttons -->
              <div class="flex items-center gap-2 text-xs">
                <button
                  type="button"
                  class="flex-1 py-1.5 px-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold flex items-center justify-center gap-1.5 transition-colors cursor-pointer"
                >
                  <v-icon icon="mdi-facebook" size="14" />
                  <span>ติดตามเพจ</span>
                </button>
                <button
                  type="button"
                  class="py-1.5 px-3 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold flex items-center justify-center gap-1 transition-colors cursor-pointer"
                >
                  <v-icon icon="mdi-share-variant-outline" size="14" />
                  <span>แชร์</span>
                </button>
              </div>

              <!-- Post Preview Image -->
              <div class="relative rounded-lg overflow-hidden border border-slate-200 aspect-[16/10] bg-slate-200">
                <img
                  src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80"
                  alt="โพสต์ล่าสุด"
                  class="w-full h-full object-cover"
                />
                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 to-transparent p-2 text-white">
                  <p class="text-[10px] font-bold line-clamp-1">เปิดรับสมัครนักศึกษาใหม่ ประจำปีการศึกษา 2569</p>
                  <p class="text-[9px] text-slate-300">เริ่มแล้ว ... งานแฟร์สุดยิ่งใหญ่</p>
                </div>
              </div>
            </div>

            <!-- Contact Information List -->
            <div class="space-y-2 text-xs text-slate-700 pt-1">
              <a
                href="https://edu.rru.ac.th"
                target="_blank"
                class="flex items-center gap-2 text-slate-700 hover:text-indigo-700 transition-colors"
              >
                <v-icon icon="mdi-link-variant" size="15" class="text-slate-400 shrink-0" />
                <span class="truncate">edu.rru.ac.th</span>
              </a>

              <a
                href="tel:038511170"
                class="flex items-center gap-2 text-slate-700 hover:text-indigo-700 transition-colors"
              >
                <v-icon icon="mdi-phone" size="15" class="text-slate-400 shrink-0" />
                <span>038-511170, 09-2265-8433</span>
              </a>

              <a
                href="mailto:educ@rru.ac.th"
                class="flex items-center gap-2 text-slate-700 hover:text-indigo-700 transition-colors"
              >
                <v-icon icon="mdi-email-outline" size="15" class="text-slate-400 shrink-0" />
                <span class="truncate">educ@rru.ac.th</span>
              </a>
            </div>

            <!-- Facebook Page Direct Button (Purple button matching image) -->
            <a
              href="https://facebook.com"
              target="_blank"
              rel="noopener noreferrer"
              class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold text-xs text-center transition-colors flex items-center justify-center gap-2 no-underline shadow-xs"
            >
              <v-icon icon="mdi-facebook" size="16" />
              <span>facebook page</span>
            </a>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
