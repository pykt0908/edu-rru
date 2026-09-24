<script setup lang="ts">
import { ref, onMounted } from 'vue'
import PageHeroBanner from '@/components/PageHeroBanner.vue'
import { api } from '@/services/api'

interface CommitteeDisplayItem {
  id?: number
  no: number
  name: string
  position: string
}

const defaultMembers: CommitteeDisplayItem[] = [
  { no: 1, name: 'ผู้ช่วยศาสตราจารย์ ดร.ลินดา นาคโปย', position: 'คณบดี' },
  { no: 2, name: 'ผู้ช่วยศาสตราจารย์ ดร.ทัศนีย์ รอดมั่นคง', position: 'รองคณบดี' },
  { no: 3, name: 'อาจารย์วรุตม์ กิจเจริญ', position: 'รองคณบดี' },
  { no: 4, name: 'รองศาสตราจารย์ ดร. มนตรี แย้มกสิกร', position: 'ผู้ทรงคุณวุฒิภายนอก' },
  { no: 5, name: 'ผู้ช่วยศาสตราจารย์ ดร.ดวงใจ ชนะสิทธิ์', position: 'ผู้ทรงคุณวุฒิภายนอก' },
  { no: 6, name: 'นางสาวสุทธิษา สมนา', position: 'ผู้แทนประธานสาขาวิชา' },
  { no: 7, name: 'อาจารย์ชาญณรงค์ คำเพชร', position: 'ผู้แทนประธานสาขาวิชา' },
  { no: 8, name: 'อาจารย์ ดร.คทาวุธ กุลศิริรัตน์', position: 'ผู้แทนประธานสาขาวิชา' },
  { no: 9, name: 'ผู้ช่วยศาสตราจารย์ ดร.อังคณา กุลนภาดล', position: 'ผู้แทนคณาจารย์' },
  { no: 10, name: 'นางวัลยา วงศ์ณรัตน์', position: 'เลขานุการ' },
  { no: 11, name: 'ผู้ช่วยศาสตราจารย์ ดร.อดิเรก เยาว์วงค์', position: 'ผู้แทนคณาจารย์' },
  { no: 12, name: 'ผู้ช่วยศาสตราจารย์ ดร.จิราภรณ์ พจนาอารีย์วงศ์', position: 'ผู้แทนคณาจารย์' },
  { no: 13, name: 'ผู้ช่วยศาสตราจารย์ ดร.อังคณา กรัณยาธิกุล', position: 'ผู้ทรงคุณวุฒิภายนอก' },
  { no: 14, name: 'นางสาวปิยนันต์ ต่อแสงธรรม', position: 'ผู้ช่วยเลขานุการ' },
]

const members = ref<CommitteeDisplayItem[]>(defaultMembers)
const loading = ref(true)

const loadMembers = async () => {
  loading.value = true
  try {
    const data = await api.getCommitteeMembers({ active_only: true })
    if (data && data.length > 0) {
      members.value = data.map((item, idx) => ({
        id: item.id,
        no: item.sort_order || idx + 1,
        name: item.name,
        position: item.position,
      }))
    }
  } catch (err) {
    console.warn('Failed to load committee members from API, using default list:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadMembers()
})
</script>

<template>
  <div class="min-h-screen bg-white">
    <!-- Hero Banner with Logo -->
    <PageHeroBanner
      badge="Faculty Committee"
      badge-icon="mdi-account-group-outline"
      title="คณะกรรมการ"
      title-highlight="คณะครุศาสตร์"
      subtitle="คณะกรรมการประจำคณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์"
    />

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
      <!-- Clean Minimal Table (Matching Reference) -->
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-200 bg-slate-50/60">
                <th scope="col" class="py-4 sm:py-5 px-4 sm:px-8 font-bold text-slate-900 text-sm sm:text-base text-center w-16 sm:w-20">
                  ที่
                </th>
                <th scope="col" class="py-4 sm:py-5 px-4 sm:px-8 font-bold text-slate-900 text-sm sm:text-base">
                  ชื่อ นามสกุล
                </th>
                <th scope="col" class="py-4 sm:py-5 px-4 sm:px-8 font-bold text-slate-900 text-sm sm:text-base text-right">
                  ตำแหน่ง
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr
                v-for="item in members"
                :key="item.id || item.no"
                class="hover:bg-slate-50/70 transition-colors"
              >
                <!-- ลำดับที่ -->
                <td class="py-4 sm:py-5 px-4 sm:px-8 text-center font-bold text-slate-800 text-sm sm:text-base">
                  {{ item.no }}
                </td>

                <!-- ชื่อ นามสกุล -->
                <td class="py-4 sm:py-5 px-4 sm:px-8 font-bold text-slate-900 text-sm sm:text-base">
                  {{ item.name }}
                </td>

                <!-- ตำแหน่ง -->
                <td class="py-4 sm:py-5 px-4 sm:px-8 text-right text-slate-700 text-sm sm:text-base whitespace-nowrap">
                  {{ item.position }}
                </td>
              </tr>

              <tr v-if="members.length === 0 && !loading">
                <td colspan="3" class="py-12 text-center text-slate-400 text-sm">
                  ไม่พบข้อมูลคณะกรรมการในขณะนี้
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>
