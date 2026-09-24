<script setup lang="ts">
import { ref, computed } from 'vue'
import PageHeroBanner from '@/components/PageHeroBanner.vue'

interface ItaItem {
  id: string
  indicator: string
  components: {
    text: string
    subnotes?: string[]
  }[]
  links: {
    title: string
    url: string
    type?: 'internal' | 'external' | 'pdf'
  }[]
}

const selectedYear = ref('2569')
const years = ['2569', '2568', '2567', '2566']
const searchQuery = ref('')
const activeFilter = ref('2569')

const handleSearch = () => {
  activeFilter.value = selectedYear.value
}

// ITA Data (OIT)
const itaData: Record<string, ItaItem[]> = {
  '2569': [
    {
      id: 'O1',
      indicator: 'โครงสร้างองค์กรและอำนาจหน้าที่',
      components: [
        {
          text: 'แสดงแผนผังโครงสร้างการแบ่งส่วนราชการของหน่วยงาน*',
        },
        {
          text: 'แสดงตำแหน่งที่สำคัญและการแบ่งส่วนงานภายใน ยกตัวอย่างเช่น สำนัก กอง ศูนย์ ฝ่าย ส่วน กลุ่ม เป็นต้น',
          subnotes: [
            '*กรณีองค์กรปกครองส่วนท้องถิ่นและองค์กรปกครองส่วนท้องถิ่นรูปแบบพิเศษ ให้แสดงแผนผังโครงสร้าง ทั้งฝ่ายการเมืองและฝ่ายข้าราชการประจำ',
            '*กรณีจังหวัด จะต้องมีโครงสร้างในส่วนของผู้ว่าราชการจังหวัด รองผู้ว่าราชการจังหวัด สำนักงานจังหวัด และราชการส่วนภูมิภาค',
          ],
        },
        {
          text: 'แสดงข้อมูลเฉพาะที่อธิบายถึงหน้าที่และอำนาจของหน่วยงาน*',
          subnotes: ['*ต้องไม่เป็นการแสดงข้อมูลกฎหมายทั้งฉบับ'],
        },
      ],
      links: [
        {
          title: 'โครงสร้างและผังการบริหารงานคณะครุศาสตร์',
          url: '/about/history',
          type: 'internal',
        },
        {
          title: 'อำนาจหน้าที่ตามพระราชบัญญัติมหาวิทยาลัยราชภัฏ',
          url: '#',
          type: 'pdf',
        },
      ],
    },
    {
      id: 'O2',
      indicator: 'ข้อมูลผู้บริหาร',
      components: [
        {
          text: 'แสดงข้อมูลของผู้บริหารสูงสุด และผู้ดำรงตำแหน่งทางการบริหารของหน่วยงาน อย่างน้อยประกอบด้วย*',
          subnotes: [
            '(1) ผู้บริหารสูงสุด (คณบดี)',
            '(2) รองผู้บริหารสูงสุด (รองคณบดี)',
            '(3) ผู้ช่วยผู้บริหารสูงสุด (ถ้ามี)',
            '(4) หัวหน้าสำนักงานคณบดี',
          ],
        },
        {
          text: 'แสดงข้อมูลของผู้บริหารแต่ละคน อย่างน้อยประกอบด้วย*',
          subnotes: [
            '(1) ชื่อ-นามสกุล',
            '(2) ตำแหน่งทางการบริหาร',
            '(3) รูปถ่ายทางการ',
            '(4) ช่องทางการติดต่อ หรือข้อมูลสังกัด',
          ],
        },
      ],
      links: [
        {
          title: 'ทำเนียบคณะผู้บริหารคณะครุศาสตร์',
          url: '/about/management',
          type: 'internal',
        },
        {
          title: 'รายนามคณะกรรมการประจำคณะครุศาสตร์',
          url: '/about/committee',
          type: 'internal',
        },
      ],
    },
    {
      id: 'O3',
      indicator: 'อำนาจหน้าที่และการดำเนินงาน',
      components: [
        {
          text: 'แสดงข้อมูลอำนาจหน้าที่ของคณะตามที่กฎหมายกำหนด',
        },
        {
          text: 'แสดงวิสัยทัศน์ พันธกิจ ค่านิยม และเป้าหมายการดำเนินงานของคณะ',
        },
      ],
      links: [
        {
          title: 'ปรัชญา วิสัยทัศน์ พันธกิจ และเป้าหมาย',
          url: '/about/philosophy',
          type: 'internal',
        },
      ],
    },
    {
      id: 'O4',
      indicator: 'แผนยุทธศาสตร์และแผนปฏิบัติราชการประจำปี',
      components: [
        {
          text: 'แสดงแผนปฏิบัติราชการประจำปีของคณะครุศาสตร์ พ.ศ. 2569*',
        },
        {
          text: 'แสดงงบประมาณรายจ่ายและโครงการสำคัญประจำปีงบประมาณ*',
        },
      ],
      links: [
        {
          title: 'แผนปฏิบัติราชการประจำปีงบประมาณ พ.ศ. 2569 (อยู่ระหว่างจัดทำ)',
          url: '#',
          type: 'pdf',
        },
        {
          title: 'แผนยุทธศาสตร์การพัฒนาคณะครุศาสตร์ระยะ 5 ปี',
          url: '#',
          type: 'pdf',
        },
      ],
    },
    {
      id: 'O5',
      indicator: 'ข้อมูลการติดต่อและช่องทางร้องเรียน',
      components: [
        {
          text: 'แสดงข้อมูลการติดต่อ ที่อยู่ หมายเลขโทรศัพท์ และอีเมลทางการของคณะ',
        },
        {
          text: 'แสดงช่องทางการรับฟังความคิดเห็นและรับเรื่องร้องเรียนการทุจริตประพฤติมิชอบ',
        },
      ],
      links: [
        {
          title: 'ช่องทางการติดต่อและแผนที่คณะครุศาสตร์',
          url: '/contact',
          type: 'internal',
        },
      ],
    },
    {
      id: 'O6',
      indicator: 'นโยบาย No Gift Policy จากการปฏิบัติหน้าที่',
      components: [
        {
          text: 'แสดงประกาศเจตนารมณ์นโยบายไม่รับของขวัญและของกำนัลทุกชนิดจากการปฏิบัติหน้าที่ (No Gift Policy)',
        },
        {
          text: 'แสดงการขับเคลื่อนกิจกรรมสร้างการรับรู้และความตระหนักแก่บุคลากรในสังกัด',
        },
      ],
      links: [
        {
          title: 'ประกาศนโยบาย No Gift Policy ประจำปีงบประมาณ',
          url: '#',
          type: 'pdf',
        },
      ],
    },
  ],
  '2568': [
    {
      id: 'O1',
      indicator: 'โครงสร้างองค์กรและอำนาจหน้าที่',
      components: [
        {
          text: 'แสดงแผนผังโครงสร้างการแบ่งส่วนราชการของหน่วยงาน ประจำปีงบประมาณ 2568',
        },
      ],
      links: [
        {
          title: 'โครงสร้างและผังการบริหารงานคณะครุศาสตร์ (2568)',
          url: '/about/history',
          type: 'internal',
        },
      ],
    },
    {
      id: 'O2',
      indicator: 'ข้อมูลผู้บริหาร',
      components: [
        {
          text: 'แสดงข้อมูลของผู้บริหารและช่องทางการติดต่อ ประจำปีงบประมาณ 2568',
        },
      ],
      links: [
        {
          title: 'ทำเนียบคณะผู้บริหารคณะครุศาสตร์ (2568)',
          url: '/about/management',
          type: 'internal',
        },
      ],
    },
  ],
  '2567': [],
  '2566': [],
}

const currentItems = computed(() => {
  const items = itaData[activeFilter.value] || []
  if (!searchQuery.value.trim()) return items
  const q = searchQuery.value.trim().toLowerCase()
  return items.filter(
    (item) =>
      item.id.toLowerCase().includes(q) ||
      item.indicator.toLowerCase().includes(q) ||
      item.components.some((c) => c.text.toLowerCase().includes(q))
  )
})
</script>

<template>
  <div class="min-h-screen bg-white">
    <!-- Hero Banner with Logo -->
    <PageHeroBanner
      badge="ITA Assessment"
      badge-icon="mdi-shield-check-outline"
      title="การประเมินคุณธรรมและความโปร่งใส"
      title-highlight="(ITA)"
      subtitle="การเปิดเผยข้อมูลสาธารณะ (Open Data Integrity and Transparency Assessment: OIT) คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์"
    />

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-8">
      <!-- Search & Filter Bar (Matching Reference Layout) -->
      <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <!-- Year Select Dropdown -->
        <div class="relative w-44 sm:w-48">
          <select
            v-model="selectedYear"
            class="w-full appearance-none bg-white border border-slate-300 hover:border-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 text-sm font-semibold rounded-lg px-4 py-2.5 pr-9 cursor-pointer transition-all shadow-xs"
          >
            <option v-for="y in years" :key="y" :value="y">
              ปี พ.ศ. {{ y }}
            </option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
            <v-icon icon="mdi-chevron-down" size="18" />
          </div>
        </div>

        <!-- Search Button (Emerald Green matching image) -->
        <button
          type="button"
          class="bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white font-medium text-sm rounded-lg px-5 py-2.5 flex items-center justify-center gap-2 shadow-xs transition-colors cursor-pointer"
          @click="handleSearch"
        >
          <v-icon icon="mdi-magnify" size="18" />
          <span>ค้นหา</span>
        </button>
      </div>

      <!-- ITA Table (Matching image style) -->
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
              <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-900 text-sm font-bold">
                <th scope="col" class="py-4 px-6 text-center w-20">ลำดับ</th>
                <th scope="col" class="py-4 px-6 w-60">ตัวชี้วัด/ประเด็น คำถาม</th>
                <th scope="col" class="py-4 px-6">องค์ประกอบด้านข้อมูล</th>
                <th scope="col" class="py-4 px-6 w-56 text-right">ลิงก์หรือ URL ข้อมูล</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr
                v-for="item in currentItems"
                :key="item.id"
                class="hover:bg-slate-50/50 transition-colors align-top"
              >
                <!-- ลำดับ (O1, O2...) -->
                <td class="py-5 px-6 text-center">
                  <span class="text-base font-bold text-emerald-800">
                    {{ item.id }}
                  </span>
                </td>

                <!-- ตัวชี้วัด/ประเด็น คำถาม -->
                <td class="py-5 px-6">
                  <h3 class="text-sm font-bold text-slate-900 leading-snug">
                    {{ item.indicator }}
                  </h3>
                </td>

                <!-- องค์ประกอบด้านข้อมูล -->
                <td class="py-5 px-6 text-xs sm:text-sm text-slate-700 space-y-2.5">
                  <div
                    v-for="(comp, cIdx) in item.components"
                    :key="cIdx"
                    class="space-y-1"
                  >
                    <!-- Main bullet -->
                    <div class="flex items-start gap-2 leading-relaxed">
                      <span class="text-slate-500 font-bold leading-none mt-1 shrink-0 text-xs">○</span>
                      <span>{{ comp.text }}</span>
                    </div>

                    <!-- Subnotes / Guidelines -->
                    <div
                      v-if="comp.subnotes && comp.subnotes.length"
                      class="pl-4 space-y-0.5 text-xs text-slate-500"
                    >
                      <p
                        v-for="(note, nIdx) in comp.subnotes"
                        :key="nIdx"
                        class="leading-relaxed"
                      >
                        {{ note }}
                      </p>
                    </div>
                  </div>
                </td>

                <!-- ลิงก์หรือ URL ข้อมูล -->
                <td class="py-5 px-6 text-right">
                  <div class="flex flex-col items-end gap-2">
                    <template v-if="item.links && item.links.length">
                      <RouterLink
                        v-for="(link, lIdx) in item.links.filter(l => l.type === 'internal')"
                        :key="'int-' + lIdx"
                        :to="link.url"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 hover:text-emerald-900 hover:underline leading-tight"
                      >
                        <v-icon icon="mdi-link-variant" size="14" />
                        <span>{{ link.title }}</span>
                      </RouterLink>

                      <a
                        v-for="(link, lIdx) in item.links.filter(l => l.type !== 'internal')"
                        :key="'ext-' + lIdx"
                        :href="link.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 hover:text-emerald-700 hover:underline leading-tight"
                      >
                        <v-icon
                          :icon="link.type === 'pdf' ? 'mdi-file-pdf-box' : 'mdi-open-in-new'"
                          size="14"
                          :class="link.type === 'pdf' ? 'text-red-500' : 'text-slate-400'"
                        />
                        <span>{{ link.title }}</span>
                      </a>
                    </template>
                    <span v-else class="text-xs text-slate-400 italic">อยู่ระหว่างจัดเตรียม</span>
                  </div>
                </td>
              </tr>

              <!-- Empty state -->
              <tr v-if="currentItems.length === 0">
                <td colspan="4" class="py-12 text-center text-slate-400">
                  <v-icon icon="mdi-folder-open-outline" size="44" class="text-slate-300 mb-2" />
                  <p class="text-sm">ไม่พบข้อมูลการประเมิน ITA สำหรับปี พ.ศ. {{ activeFilter }}</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Table footer note -->
        <div class="py-3.5 px-6 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
          <span>ข้อมูลการประเมินคุณธรรมและความโปร่งใส ประจำปีงบประมาณ พ.ศ. {{ activeFilter }}</span>
          <span class="text-slate-400">คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์</span>
        </div>
      </div>
    </main>
  </div>
</template>
