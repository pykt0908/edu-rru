<script setup lang="ts">
import { ref, computed } from 'vue'
import PageHeroBanner from '@/components/PageHeroBanner.vue'

interface LawItem {
  id: number
  title: string
  category: 'act' | 'regulation' | 'rule' | 'announcement'
  categoryName: string
  year: string
  effectiveDate: string
  fileSize: string
  fileUrl: string
  description?: string
}

const categories = [
  { id: 'all', name: 'ทั้งหมด', icon: 'mdi-format-list-bulleted' },
  { id: 'act', name: 'พระราชบัญญัติ', icon: 'mdi-scale-balance' },
  { id: 'regulation', name: 'ข้อบังคับมหาวิทยาลัย', icon: 'mdi-file-document-outline' },
  { id: 'rule', name: 'ระเบียบและแนวปฏิบัติ', icon: 'mdi-clipboard-text-outline' },
  { id: 'announcement', name: 'ประกาศมหาวิทยาลัย/คณะ', icon: 'mdi-bullhorn-outline' },
]

const selectedCategory = ref('all')
const searchQuery = ref('')
const sortOrder = ref<'desc' | 'asc'>('desc')

const lawItems: LawItem[] = [
  // พระราชบัญญัติ (Acts)
  {
    id: 1,
    title: 'พระราชบัญญัติมหาวิทยาลัยราชภัฏ พ.ศ. 2547',
    category: 'act',
    categoryName: 'พระราชบัญญัติ',
    year: '2547',
    effectiveDate: '15 มิ.ย. 2547',
    fileSize: '1.2 MB',
    fileUrl: '#',
    description: 'กฎหมายจัดตั้งและกำหนดโครงสร้างอำนาจหน้าที่ของมหาวิทยาลัยราชภัฏทั่วประเทศ',
  },
  {
    id: 2,
    title: 'พระราชบัญญัติระเบียบข้าราชการพลเรือนในสถาบันอุดมศึกษา พ.ศ. 2547 และที่แก้ไขเพิ่มเติม',
    category: 'act',
    categoryName: 'พระราชบัญญัติ',
    year: '2547',
    effectiveDate: '12 พ.ย. 2547',
    fileSize: '1.8 MB',
    fileUrl: '#',
    description: 'ว่าด้วยการบริหารงานบุคคล สิทธิประโยชน์ วินัย และการพ้นจากตำแหน่งของข้าราชการสายวิชาการและสายสนับสนุน',
  },
  {
    id: 3,
    title: 'พระราชบัญญัติสภาครูและผู้ประกอบวิชาชีพทางการศึกษา พ.ศ. 2546',
    category: 'act',
    categoryName: 'พระราชบัญญัติ',
    year: '2546',
    effectiveDate: '12 มิ.ย. 2546',
    fileSize: '950 KB',
    fileUrl: '#',
    description: 'มาตรฐานวิชาชีพ จรรยาบรรณวิชาชีพครู และการออกใบอนุญาตประกอบวิชาชีพทางการศึกษา',
  },
  {
    id: 4,
    title: 'พระราชบัญญัติการศึกษาแห่งชาติ พ.ศ. 2542 และที่แก้ไขเพิ่มเติม (ฉบับที่ 4) พ.ศ. 2562',
    category: 'act',
    categoryName: 'พระราชบัญญัติ',
    year: '2562',
    effectiveDate: '2 พ.ค. 2562',
    fileSize: '2.1 MB',
    fileUrl: '#',
    description: 'กฎหมายแม่บทในการจัดการศึกษาและการปฏิรูปการศึกษาของชาติ',
  },
  {
    id: 5,
    title: 'พระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA)',
    category: 'act',
    categoryName: 'พระราชบัญญัติ',
    year: '2562',
    effectiveDate: '1 มิ.ย. 2565',
    fileSize: '1.4 MB',
    fileUrl: '#',
    description: 'หลักเกณฑ์การคุ้มครองข้อมูลส่วนบุคคลของผู้เรียน คณาจารย์ และบุคลากรในสถานศึกษา',
  },

  // ข้อบังคับมหาวิทยาลัย (University Regulations)
  {
    id: 6,
    title: 'ข้อบังคับมหาวิทยาลัยราชภัฏราชนครินทร์ ว่าด้วยการบริหารงานบุคคลสำหรับพนักงานมหาวิทยาลัย พ.ศ. 2564',
    category: 'regulation',
    categoryName: 'ข้อบังคับมหาวิทยาลัย',
    year: '2564',
    effectiveDate: '25 ก.พ. 2564',
    fileSize: '880 KB',
    fileUrl: '#',
    description: 'การสรรหา บรรจุ แต่งตั้ง อัตราเงินเดือน และสวัสดิการของพนักงานมหาวิทยาลัย',
  },
  {
    id: 7,
    title: 'ข้อบังคับมหาวิทยาลัยราชภัฏราชนครินทร์ ว่าด้วยจรรยาบรรณและวินัยของบุคลากร พ.ศ. 2561',
    category: 'regulation',
    categoryName: 'ข้อบังคับมหาวิทยาลัย',
    year: '2561',
    effectiveDate: '18 ส.ค. 2561',
    fileSize: '720 KB',
    fileUrl: '#',
    description: 'มาตรฐานทางจริยธรรม วินัย และกระบวนการทางวินัยของคณาจารย์และบุคลากร',
  },
  {
    id: 8,
    title: 'ข้อบังคับมหาวิทยาลัยราชภัฏราชนครินทร์ ว่าด้วยคุณสมบัติ หลักเกณฑ์ และวิธีการแต่งตั้งคณบดี พ.ศ. 2562',
    category: 'regulation',
    categoryName: 'ข้อบังคับมหาวิทยาลัย',
    year: '2562',
    effectiveDate: '10 มี.ค. 2562',
    fileSize: '650 KB',
    fileUrl: '#',
    description: 'การสรรหา คุณสมบัติ และวาระการดำรงตำแหน่งของคณบดี',
  },
  {
    id: 9,
    title: 'ข้อบังคับมหาวิทยาลัยราชภัฏราชนครินทร์ ว่าด้วยหลักเกณฑ์และวิธีการประเมินผลการปฏิบัติราชการ พ.ศ. 2563',
    category: 'regulation',
    categoryName: 'ข้อบังคับมหาวิทยาลัย',
    year: '2563',
    effectiveDate: '15 พ.ย. 2563',
    fileSize: '910 KB',
    fileUrl: '#',
    description: 'หลักเกณฑ์และตัวชี้วัดผลสัมฤทธิ์ของงาน (KPI) และพฤติกรรมการปฏิบัติราชการ (Competency)',
  },
  {
    id: 10,
    title: 'ข้อบังคับมหาวิทยาลัยราชภัฏราชนครินทร์ ว่าด้วยการจัดการศึกษาระดับปริญญาตรี พ.ศ. 2565',
    category: 'regulation',
    categoryName: 'ข้อบังคับมหาวิทยาลัย',
    year: '2565',
    effectiveDate: '1 มิ.ย. 2565',
    fileSize: '1.5 MB',
    fileUrl: '#',
    description: 'การเปิดสอนหลักสูตร การวัดและประเมินผลการศึกษา และการสำเร็จการศึกษา',
  },

  // ระเบียบและแนวปฏิบัติ (Rules & Operational Guidelines)
  {
    id: 11,
    title: 'ระเบียบมหาวิทยาลัยราชภัฏราชนครินทร์ ว่าด้วยการลาของบุคลากร พ.ศ. 2563',
    category: 'rule',
    categoryName: 'ระเบียบและแนวปฏิบัติ',
    year: '2563',
    effectiveDate: '1 ต.ค. 2563',
    fileSize: '620 KB',
    fileUrl: '#',
    description: 'ระเบียบการลาป่วย ลากิจ ลาพักผ่อน ลาคลอดบุตร และการลาไปศึกษาต่อหรือฝึกอบรม',
  },
  {
    id: 12,
    title: 'ระเบียบมหาวิทยาลัยราชภัฏราชนครินทร์ ว่าด้วยการจ่ายเงินรายได้เป็นค่าตอบแทนภาระงานสอน พ.ศ. 2565',
    category: 'rule',
    categoryName: 'ระเบียบและแนวปฏิบัติ',
    year: '2565',
    effectiveDate: '1 ก.ค. 2565',
    fileSize: '750 KB',
    fileUrl: '#',
    description: 'หลักเกณฑ์การคำนวณภาระงานสอนขั้นต่ำและอัตราจ่ายเงินสอนเกินภาระงาน',
  },
  {
    id: 13,
    title: 'แนวปฏิบัติการขอรับทุนอุดหนุนการวิจัยและพัฒนาศักยภาพอาจารย์ คณะครุศาสตร์ พ.ศ. 2566',
    category: 'rule',
    categoryName: 'ระเบียบและแนวปฏิบัติ',
    year: '2566',
    effectiveDate: '15 ม.ค. 2566',
    fileSize: '540 KB',
    fileUrl: '#',
    description: 'เกณฑ์การจัดสรรทุนวิจัยเพื่อพัฒนานวัตกรรมการเรียนรู้และการตีพิมพ์ผลงานวิชาการ',
  },
  {
    id: 14,
    title: 'ระเบียบมหาวิทยาลัยราชภัฏราชนครินทร์ ว่าด้วยการฝึกอบรม การดูงาน และการปฏิบัติงานวิจัย พ.ศ. 2562',
    category: 'rule',
    categoryName: 'ระเบียบและแนวปฏิบัติ',
    year: '2562',
    effectiveDate: '20 ส.ค. 2562',
    fileSize: '680 KB',
    fileUrl: '#',
    description: 'สิทธิการขออนุมัติเดินทางไปราชการ การเบิกค่าใช้จ่ายในการเดินทางและฝึกอบรม',
  },
  {
    id: 15,
    title: 'แนวปฏิบัติการเบิกจ่ายงบประมาณโครงการพัฒนาศักยภาพนักศึกษา คณะครุศาสตร์ พ.ศ. 2566',
    category: 'rule',
    categoryName: 'ระเบียบและแนวปฏิบัติ',
    year: '2566',
    effectiveDate: '1 ต.ค. 2566',
    fileSize: '490 KB',
    fileUrl: '#',
    description: 'ขั้นตอนการเสนอขออนุมัติโครงการ หลักฐานการเงิน และแนวทางการตรวจรับพัสดุ',
  },

  // ประกาศมหาวิทยาลัย/คณะ (Announcements & Notifications)
  {
    id: 16,
    title: 'ประกาศคณะครุศาสตร์ เรื่อง นโยบายไม่รับของขวัญและของกำนัลทุกชนิดจากการปฏิบัติหน้าที่ (No Gift Policy) พ.ศ. 2567',
    category: 'announcement',
    categoryName: 'ประกาศมหาวิทยาลัย/คณะ',
    year: '2567',
    effectiveDate: '2 ม.ค. 2567',
    fileSize: '410 KB',
    fileUrl: '#',
    description: 'การประกาศเจตนารมณ์ต่อต้านการทุจริตและส่งเสริมความโปร่งใสในการดำเนินงาน',
  },
  {
    id: 17,
    title: 'ประกาศมหาวิทยาลัยราชภัฏราชนครินทร์ เรื่อง มาตรการส่งเสริมความโปร่งใสและป้องกันการทุจริต พ.ศ. 2567',
    category: 'announcement',
    categoryName: 'ประกาศมหาวิทยาลัย/คณะ',
    year: '2567',
    effectiveDate: '15 ม.ค. 2567',
    fileSize: '580 KB',
    fileUrl: '#',
    description: 'แนวทางปฏิบัติตามมาตรฐานการประเมินคุณธรรมและความโปร่งใส (ITA)',
  },
  {
    id: 18,
    title: 'ประกาศคณะครุศาสตร์ เรื่อง เกณฑ์การประเมินผลการปฏิบัติงานของอาจารย์ประจำ ประจำปีงบประมาณ 2567',
    category: 'announcement',
    categoryName: 'ประกาศมหาวิทยาลัย/คณะ',
    year: '2567',
    effectiveDate: '1 ต.ค. 2566',
    fileSize: '820 KB',
    fileUrl: '#',
    description: 'เกณฑ์ภาระงานด้านการสอน การวิจัย การบริการวิชาการ และการทำนุบำรุงศิลปวัฒนธรรม',
  },
  {
    id: 19,
    title: 'ประกาศมหาวิทยาลัยราชภัฏราชนครินทร์ เรื่อง การแต่งกายและการประพฤติปฏิบัติตนของบุคลากร พ.ศ. 2565',
    category: 'announcement',
    categoryName: 'ประกาศมหาวิทยาลัย/คณะ',
    year: '2565',
    effectiveDate: '1 มิ.ย. 2565',
    fileSize: '460 KB',
    fileUrl: '#',
    description: 'การแต่งกายสุภาพ ผ้าไทย และการประพฤติตนให้เป็นแบบอย่างที่ดีแก่นักศึกษา',
  },
  {
    id: 20,
    title: 'ประกาศคณะครุศาสตร์ เรื่อง แนวทางการจัดทำผลงานทางวิชาการเพื่อขอกำหนดตำแหน่งทางวิชาการ พ.ศ. 2566',
    category: 'announcement',
    categoryName: 'ประกาศมหาวิทยาลัย/คณะ',
    year: '2566',
    effectiveDate: '1 ธ.ค. 2566',
    fileSize: '1.1 MB',
    fileUrl: '#',
    description: 'คู่มือและแบบฟอร์มการขอกำหนดตำแหน่งผู้ช่วยศาสตราจารย์และรองศาสตราจารย์',
  },
]

// Filter and search
const filteredLaws = computed(() => {
  let list = lawItems

  // Category filter
  if (selectedCategory.value !== 'all') {
    list = list.filter((item) => item.category === selectedCategory.value)
  }

  // Search filter
  const query = searchQuery.value.trim().toLowerCase()
  if (query) {
    list = list.filter(
      (item) =>
        item.title.toLowerCase().includes(query) ||
        (item.description && item.description.toLowerCase().includes(query)) ||
        item.year.includes(query) ||
        item.categoryName.toLowerCase().includes(query)
    )
  }

  // Sort by year
  return [...list].sort((a, b) => {
    const numA = parseInt(a.year, 10)
    const numB = parseInt(b.year, 10)
    return sortOrder.value === 'desc' ? numB - numA : numA - numB
  })
})

const getCategoryCount = (catId: string) => {
  if (catId === 'all') return lawItems.length
  return lawItems.filter((item) => item.category === catId).length
}

const getCategoryBadgeClass = (category: string) => {
  switch (category) {
    case 'act':
      return 'bg-purple-50 text-purple-700 border-purple-200'
    case 'regulation':
      return 'bg-blue-50 text-blue-700 border-blue-200'
    case 'rule':
      return 'bg-emerald-50 text-emerald-800 border-emerald-200'
    case 'announcement':
      return 'bg-amber-50 text-amber-800 border-amber-200'
    default:
      return 'bg-slate-50 text-slate-700 border-slate-200'
  }
}
</script>

<template>
  <div class="min-h-screen bg-slate-50/60 pb-20">
    <!-- Hero Banner (Follows rule.md Section 13) -->
    <PageHeroBanner
      badge="ระเบียบและกฎหมาย"
      badge-icon="mdi-gavel"
      title="ข้อกฎหมายสำหรับบุคลากร"
      title-highlight="คณะครุศาสตร์"
      subtitle="รวบรวมพระราชบัญญัติ ระเบียบ ข้อบังคับ และประกาศที่เกี่ยวข้องกับการปฏิบัติงานของบุคลากร"
    />

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-6">
      <!-- Filter & Search Card -->
      <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 shadow-xs space-y-4">
        <!-- Category Filter Tabs -->
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">
            เลือกหมวดหมู่ข้อกฎหมายและระเบียบ
          </label>
          <div class="flex flex-wrap gap-2">
            <button
              v-for="cat in categories"
              :key="cat.id"
              type="button"
              class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-150 cursor-pointer min-h-[40px] border"
              :class="
                selectedCategory === cat.id
                  ? 'bg-emerald-700 text-white border-emerald-700 shadow-sm shadow-emerald-700/20'
                  : 'bg-slate-50 text-slate-600 border-slate-200/80 hover:bg-slate-100 hover:text-slate-900'
              "
              @click="selectedCategory = cat.id"
            >
              <v-icon :icon="cat.icon" size="16" />
              <span>{{ cat.name }}</span>
              <span
                class="px-1.5 py-0.5 rounded-full text-[11px] font-bold"
                :class="selectedCategory === cat.id ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
              >
                {{ getCategoryCount(cat.id) }}
              </span>
            </button>
          </div>
        </div>

        <!-- Search Bar and Sort Controls -->
        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
          <!-- Search input -->
          <div class="relative flex-1 max-w-md">
            <v-icon
              icon="mdi-magnify"
              size="18"
              class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"
            />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="ค้นหาชื่อกฎหมาย ระเบียบ หรือคำสำคัญ..."
              class="w-full pl-10 pr-9 py-2 rounded-xl text-xs sm:text-sm bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600 transition-all min-h-[42px]"
            />
            <button
              v-if="searchQuery"
              type="button"
              aria-label="ล้างคำค้นหา"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer"
              @click="searchQuery = ''"
            >
              <v-icon icon="mdi-close-circle" size="16" />
            </button>
          </div>

          <!-- Sort order toggle and result count -->
          <div class="flex items-center justify-between sm:justify-end gap-3 text-xs sm:text-sm">
            <span class="text-slate-500 font-medium">
              พบ <strong class="text-emerald-700 font-bold">{{ filteredLaws.length }}</strong> รายการ
            </span>

            <button
              type="button"
              class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold cursor-pointer transition-colors"
              @click="sortOrder = sortOrder === 'desc' ? 'asc' : 'desc'"
            >
              <v-icon :icon="sortOrder === 'desc' ? 'mdi-sort-clock-descending-outline' : 'mdi-sort-clock-ascending-outline'" size="16" />
              <span>เรียงตามปี: {{ sortOrder === 'desc' ? 'ล่าสุดก่อน' : 'เก่าสุดก่อน' }}</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Regulations Table Card -->
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full border-collapse text-left">
            <thead>
              <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] sm:text-xs font-bold text-slate-600 uppercase tracking-wider">
                <th scope="col" class="py-4 px-4 sm:px-6 w-16 text-center">ลำดับ</th>
                <th scope="col" class="py-4 px-4 sm:px-6">ชื่อกฎหมาย / ระเบียบ / ประกาศ</th>
                <th scope="col" class="py-4 px-4 sm:px-6 w-44 hidden md:table-cell">หมวดหมู่</th>
                <th scope="col" class="py-4 px-4 sm:px-6 w-28 text-center hidden sm:table-cell">ปี พ.ศ.</th>
                <th scope="col" class="py-4 px-4 sm:px-6 w-36 text-center">ดาวน์โหลด</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700 text-xs sm:text-sm">
              <tr
                v-for="(item, idx) in filteredLaws"
                :key="item.id"
                class="hover:bg-slate-50/80 transition-colors group"
              >
                <!-- ลำดับ -->
                <td class="py-4 px-4 sm:px-6 text-center font-bold text-slate-400 group-hover:text-emerald-700">
                  {{ idx + 1 }}
                </td>

                <!-- ชื่อข้อกฎหมายและคำอธิบาย -->
                <td class="py-4 px-4 sm:px-6">
                  <div class="space-y-1">
                    <div class="flex items-start gap-2.5">
                      <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                        <v-icon icon="mdi-file-document-outline" size="14" />
                      </div>
                      <div>
                        <h2 class="font-bold text-slate-900 group-hover:text-emerald-800 transition-colors leading-snug text-xs sm:text-sm">
                          {{ item.title }}
                        </h2>
                        <p v-if="item.description" class="text-xs text-slate-500 leading-relaxed mt-0.5">
                          {{ item.description }}
                        </p>
                      </div>
                    </div>

                    <!-- Mobile meta badges -->
                    <div class="flex flex-wrap items-center gap-2 pt-1 pl-8 sm:hidden">
                      <span
                        class="px-2 py-0.5 rounded text-[10px] font-semibold border"
                        :class="getCategoryBadgeClass(item.category)"
                      >
                        {{ item.categoryName }}
                      </span>
                      <span class="text-[10px] text-slate-500">
                        พ.ศ. {{ item.year }}
                      </span>
                    </div>
                  </div>
                </td>

                <!-- หมวดหมู่ -->
                <td class="py-4 px-4 sm:px-6 hidden md:table-cell">
                  <span
                    class="inline-block px-2.5 py-1 rounded-lg text-xs font-semibold border whitespace-nowrap"
                    :class="getCategoryBadgeClass(item.category)"
                  >
                    {{ item.categoryName }}
                  </span>
                </td>

                <!-- ปี พ.ศ. -->
                <td class="py-4 px-4 sm:px-6 text-center hidden sm:table-cell">
                  <span class="font-bold text-slate-700">
                    {{ item.year }}
                  </span>
                </td>

                <!-- ดาวน์โหลด PDF -->
                <td class="py-4 px-4 sm:px-6 text-center">
                  <a
                    :href="item.fileUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-700 text-slate-700 hover:text-white font-semibold text-xs transition-all duration-150 no-underline shadow-xs hover:shadow-md min-h-[36px]"
                    :title="'ดาวน์โหลด ' + item.title"
                  >
                    <v-icon icon="mdi-file-pdf-box" size="16" class="text-red-600 group-hover:text-white" />
                    <span>PDF</span>
                  </a>
                </td>
              </tr>

              <!-- Empty state -->
              <tr v-if="filteredLaws.length === 0">
                <td colspan="5" class="py-14 text-center text-slate-400">
                  <div class="max-w-xs mx-auto space-y-2">
                    <v-icon icon="mdi-folder-search-outline" size="48" class="text-slate-300" />
                    <p class="text-sm font-semibold text-slate-700">ไม่พบข้อกฎหมายที่ตรงกับเงื่อนไข</p>
                    <p class="text-xs text-slate-400">กรุณาลองเปลี่ยนหมวดหมู่หรือคำค้นหาใหม่</p>
                    <button
                      type="button"
                      class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 hover:underline cursor-pointer"
                      @click="selectedCategory = 'all'; searchQuery = ''"
                    >
                      <v-icon icon="mdi-refresh" size="14" />
                      <span>ล้างตัวกรองทั้งหมด</span>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Table Footer Info -->
        <div class="py-3.5 px-6 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
          <div class="flex items-center gap-2">
            <v-icon icon="mdi-information-outline" size="14" class="text-slate-400" />
            <span>บุคลากรสามารถสอบถามข้อมูลระเบียบเพิ่มเติมได้ที่ สำนักงานคณบดี คณะครุศาสตร์</span>
          </div>
          <span class="text-slate-400">อัปเดตข้อมูลล่าสุด: 2567</span>
        </div>
      </div>
    </div>
  </div>
</template>
