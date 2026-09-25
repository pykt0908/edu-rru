<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { api, type CurriculumRecord } from '@/services/api'
import AdminToast from '@/components/admin/AdminToast.vue'

// ─── State ───────────────────────────────────────────────────────────────────
const loading = ref(false)
const curricula = ref<CurriculumRecord[]>([])
const selectedDegreeFilter = ref<string>('all')
const search = ref('')

// Toast Notification State
const toast = ref({
  show: false,
  title: '',
  message: '',
  type: 'success' as 'success' | 'error' | 'warning' | 'info',
})

function showToast(message: string, title = 'สำเร็จ', type: 'success' | 'error' | 'warning' | 'info' = 'success') {
  toast.value = { show: true, title, message, type }
}

// ─── Modal State ─────────────────────────────────────────────────────────────
const showDialog = ref(false)
const isEditing = ref(false)
const saving = ref(false)
const uploadLoading = ref(false)
const tagsInput = ref('')
const dialogTab = ref<'general' | 'careers_elo' | 'fees_structure' | 'gallery'>('general')

// Detail Content Reactive States
const careersList = ref<string[]>([])
const newCareerInput = ref('')

const learningOutcomes = ref<{ year: string; description: string }[]>([
  { year: 'ชั้นปีที่ 1', description: '' },
  { year: 'ชั้นปีที่ 2', description: '' },
  { year: 'ชั้นปีที่ 3', description: '' },
  { year: 'ชั้นปีที่ 4', description: '' },
])

const tuitionFeesList = ref<{ term: string; amount: string; note: string }[]>([
  { term: 'ภาคการศึกษาที่ 1', amount: '13,500 บาท', note: 'รวมค่าธรรมเนียมแรกเข้าและอุปกรณ์' },
  { term: 'ภาคการศึกษาที่ 2', amount: '11,500 บาท', note: 'ค่าลงทะเบียนปกติ' },
  { term: 'รวมตลอดหลักสูตร (8 ภาคการศึกษา)', amount: '94,000 บาท', note: 'เฉลี่ยเทอมละประมาณ 11,750 บาท' },
])

const structuresList = ref<{ category: string; credits: string; highlight?: boolean }[]>([
  { category: '1. หมวดวิชาศึกษาทั่วไป', credits: '30 หน่วยกิต', highlight: false },
  { category: '2. หมวดวิชาเฉพาะ', credits: '84 หน่วยกิต', highlight: false },
  { category: '3. หมวดวิชาเลือกเสรี', credits: '6 หน่วยกิต', highlight: false },
  { category: 'รวมหน่วยกิตตลอดหลักสูตร', credits: '120 หน่วยกิต', highlight: true },
])

const galleryList = ref<{ url: string; title: string; badge: string }[]>([
  { url: '', title: 'บรรยากาศห้องปฏิบัติการและการเรียนการสอน', badge: 'LABORATORY' },
  { url: '', title: 'การจัดการเรียนรู้เชิงรุก Active Learning', badge: 'WORKSHOP' },
  { url: '', title: 'การฝึกประสบการณ์ในสถานศึกษา', badge: 'PRACTICE' },
  { url: '', title: 'นิทรรศการผลงานและการนำเสนอทางวิชาการ', badge: 'EXHIBITION' },
])

const galleryUploadLoading = ref<number | null>(null)

const form = ref<Partial<CurriculumRecord>>({
  id: 0,
  slug: '',
  degree_level: 'bachelor',
  title: '',
  title_en: '',
  degree_title: '',
  degree_title_en: '',
  duration: '4 ปี',
  credits: '120 หน่วยกิต',
  desc: '',
  image: '',
  tags: [],
  highlight: false,
  document_url: '',
  sort_order: 1,
  is_active: true,
})

// Delete Dialog
const showDeleteDialog = ref(false)
const deleteTarget = ref<CurriculumRecord | null>(null)
const deleteLoading = ref(false)

// Degree Levels definitions
const degreeLevels = [
  { value: 'all', label: 'ทั้งหมด', icon: 'mdi-view-grid-outline' },
  { value: 'bachelor', label: 'ปริญญาตรี (4 ปี)', icon: 'mdi-school' },
  { value: 'grad-diploma', label: 'ป.บัณฑิตวิชาชีพครู', icon: 'mdi-certificate-outline' },
  { value: 'master', label: 'ปริญญาโท', icon: 'mdi-book-education-outline' },
]

// ─── Computed ────────────────────────────────────────────────────────────────
const filteredCurricula = computed(() => {
  let list = curricula.value

  if (selectedDegreeFilter.value !== 'all') {
    list = list.filter((c) => c.degree_level === selectedDegreeFilter.value)
  }

  if (search.value.trim()) {
    const q = search.value.toLowerCase().trim()
    list = list.filter((c) => {
      return (
        c.title.toLowerCase().includes(q) ||
        c.title_en?.toLowerCase().includes(q) ||
        c.degree_title.toLowerCase().includes(q) ||
        c.desc?.toLowerCase().includes(q) ||
        c.tags?.some((t) => t.toLowerCase().includes(q))
      )
    })
  }

  return list
})


// ─── Fetch Data ──────────────────────────────────────────────────────────────
async function fetchData() {
  loading.value = true
  try {
    const data = await api.getCurricula()
    curricula.value = data
  } catch (err: any) {
    showToast(err.message || 'ไม่สามารถโหลดข้อมูลหลักสูตรได้', 'เกิดข้อผิดพลาด', 'error')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchData()
})

// ─── Helper Functions ────────────────────────────────────────────────────────
function getDegreeBadgeClass(level: string): { bg: string; text: string; label: string } {
  switch (level) {
    case 'bachelor':
      return { bg: 'bg-emerald-50 border-emerald-200', text: 'text-emerald-800', label: 'ปริญญาตรี' }
    case 'grad-diploma':
      return { bg: 'bg-sky-50 border-sky-200', text: 'text-sky-800', label: 'ป.บัณฑิต' }
    case 'master':
      return { bg: 'bg-purple-50 border-purple-200', text: 'text-purple-800', label: 'ปริญญาโท' }
    default:
      return { bg: 'bg-slate-100 border-slate-200', text: 'text-slate-800', label: level }
  }
}

// ─── Detail Helpers ─────────────────────────────────────────────────────────
function addCareer() {
  const val = newCareerInput.value.trim()
  if (!val) return
  if (!careersList.value.includes(val)) {
    careersList.value.push(val)
  }
  newCareerInput.value = ''
}

function removeCareer(idx: number) {
  careersList.value.splice(idx, 1)
}

function addFeeRow() {
  tuitionFeesList.value.push({ term: '', amount: '', note: '' })
}

function removeFeeRow(idx: number) {
  tuitionFeesList.value.splice(idx, 1)
}

function addStructureRow() {
  structuresList.value.push({ category: '', credits: '', highlight: false })
}

function removeStructureRow(idx: number) {
  structuresList.value.splice(idx, 1)
}

function addGalleryItem() {
  galleryList.value.push({ url: '', title: '', badge: 'PHOTO' })
}

function removeGalleryItem(idx: number) {
  galleryList.value.splice(idx, 1)
}

async function handleGalleryUpload(idx: number, event: Event) {
  const target = event.target as HTMLInputElement
  if (!target.files || !target.files[0]) return
  const file = target.files[0]

  galleryUploadLoading.value = idx
  try {
    const res = await api.uploadFile(file)
    galleryList.value[idx].url = res.url
    showToast(`อัปโหลดรูปภาพที่ ${idx + 1} สำเร็จ`)
  } catch (err: any) {
    showToast(err.response?.data?.message || 'อัปโหลดรูปภาพไม่สำเร็จ', 'ข้อผิดพลาด', 'error')
  } finally {
    galleryUploadLoading.value = null
    target.value = ''
  }
}

// ─── CRUD Actions ────────────────────────────────────────────────────────────
function openAddDialog() {
  isEditing.value = false
  dialogTab.value = 'general'
  tagsInput.value = ''
  newCareerInput.value = ''
  const currentDegree = selectedDegreeFilter.value === 'all' ? 'bachelor' : selectedDegreeFilter.value
  const nextOrder = curricula.value.filter((c) => c.degree_level === currentDegree).length + 1

  form.value = {
    id: 0,
    slug: '',
    degree_level: currentDegree as any,
    title: '',
    title_en: '',
    degree_title: currentDegree === 'bachelor' ? 'ครุศาสตรบัณฑิต (ค.บ.)' : currentDegree === 'master' ? 'ครุศาสตรมหาบัณฑิต (ค.ม.)' : 'ประกาศนียบัตรบัณฑิตวิชาชีพครู (ป.บัณฑิต)',
    duration: currentDegree === 'grad-diploma' ? '1 ปี' : currentDegree === 'master' ? '2 ปี' : '4 ปี',
    credits: currentDegree === 'bachelor' ? '132 หน่วยกิต' : currentDegree === 'master' ? '36 หน่วยกิต' : '34 หน่วยกิต',
    desc: '',
    image: '',
    tags: [],
    highlight: false,
    document_url: '',
    sort_order: nextOrder,
    is_active: true,
  }

  careersList.value = [
    'ครูและอาจารย์ผู้สอนในสถานศึกษาของรัฐและเอกชน',
    'นักวิชาการศึกษา และนักออกแบบหลักสูตร',
    'นักวิจัยและพัฒนานวัตกรรมการศึกษา',
    'บุคลากรทางการศึกษาในหน่วยงานภาครัฐและเอกชน',
  ]

  learningOutcomes.value = [
    { year: 'ชั้นปีที่ 1', description: 'สร้างความรู้พื้นฐานและเจตคติที่ดีต่อวิชาชีพและศาสตร์เฉพาะทาง' },
    { year: 'ชั้นปีที่ 2', description: 'พัฒนาทักษะการออกแบบหลักสูตร การสอน และการใช้เทคโนโลยีสมัยใหม่' },
    { year: 'ชั้นปีที่ 3', description: 'วิจัยและพัฒนานวัตกรรมการจัดการเรียนรู้เพื่อแก้ปัญหาจริง' },
    { year: 'ชั้นปีที่ 4', description: 'ฝึกปฏิบัติการสอนในสถานศึกษาเต็มเวลาตามมาตรฐานวิชาชีพ' },
  ]

  tuitionFeesList.value = [
    { term: 'ภาคการศึกษาที่ 1', amount: '13,500 บาท', note: 'รวมค่าธรรมเนียมแรกเข้าและอุปกรณ์' },
    { term: 'ภาคการศึกษาที่ 2', amount: '11,500 บาท', note: 'ค่าลงทะเบียนปกติ' },
    { term: 'รวมตลอดหลักสูตร (8 ภาคการศึกษา)', amount: '94,000 บาท', note: 'เฉลี่ยเทอมละประมาณ 11,750 บาท' },
  ]

  structuresList.value = [
    { category: '1. หมวดวิชาศึกษาทั่วไป', credits: '30 หน่วยกิต', highlight: false },
    { category: '2. หมวดวิชาเฉพาะ', credits: '96 หน่วยกิต', highlight: false },
    { category: '3. หมวดวิชาเลือกเสรี', credits: '6 หน่วยกิต', highlight: false },
    { category: 'รวมหน่วยกิตตลอดหลักสูตร', credits: '132 หน่วยกิต', highlight: true },
  ]

  galleryList.value = [
    { url: '', title: 'ภาพกิจกรรมและการเรียนการสอน', badge: 'FEATURED' },
    { url: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1000&q=80', title: 'การเรียนรู้เชิงปฏิบัติการ คณะครุศาสตร์', badge: 'WORKSHOP' },
    { url: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1000&q=80', title: 'การฝึกประสบการณ์ในสถานศึกษา', badge: 'PRACTICE' },
    { url: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1000&q=80', title: 'นิทรรศการผลงานและการนำเสนอทางวิชาการ', badge: 'EXHIBITION' },
  ]

  showDialog.value = true
}

function openEditDialog(item: CurriculumRecord) {
  isEditing.value = true
  dialogTab.value = 'general'
  newCareerInput.value = ''
  tagsInput.value = (item.tags || []).join(', ')

  form.value = {
    id: item.id,
    slug: item.slug,
    degree_level: item.degree_level,
    title: item.title,
    title_en: item.title_en || '',
    degree_title: item.degree_title,
    degree_title_en: item.degree_title_en || '',
    duration: item.duration,
    credits: item.credits,
    desc: item.desc || '',
    image: item.image || '',
    tags: item.tags || [],
    highlight: !!item.highlight,
    document_url: item.document_url || '',
    sort_order: item.sort_order,
    is_active: item.is_active,
  }

  const dc = item.detail_content || {}

  // Careers
  if (Array.isArray(dc.careers) && dc.careers.length > 0) {
    careersList.value = [...dc.careers]
  } else {
    const t = item.title.replace('สาขาวิชา', '').trim()
    careersList.value = [
      `ครูผู้สอน${t} ในสถานศึกษาของรัฐและเอกชน`,
      `นักวิชาการศึกษา และนักวิจัยด้าน${t}`,
      'ผู้ประกอบการและนักพัฒนานวัตกรรมการจัดการเรียนรู้',
      'บุคลากรทางการศึกษาในหน่วยงานภาครัฐและเอกชน',
    ]
  }

  // Learning Outcomes (ELO)
  if (Array.isArray(dc.learningOutcomes) && dc.learningOutcomes.length > 0) {
    learningOutcomes.value = JSON.parse(JSON.stringify(dc.learningOutcomes))
  } else {
    const t = item.title.replace('สาขาวิชา', '').trim()
    learningOutcomes.value = [
      { year: 'ชั้นปีที่ 1', description: `สร้างความรู้พื้นฐานและเจตคติที่ดีต่อวิชาชีพและศาสตร์ด้าน${t}` },
      { year: 'ชั้นปีที่ 2', description: 'พัฒนาทักษะการออกแบบหลักสูตรและวิธีสอนสมัยใหม่' },
      { year: 'ชั้นปีที่ 3', description: 'วิจัยและพัฒนานวัตกรรมการจัดการเรียนรู้ในชั้นเรียน' },
      { year: 'ชั้นปีที่ 4', description: 'ฝึกปฏิบัติการสอนในสถานศึกษาเต็มเวลาตามมาตรฐานวิชาชีพ' },
    ]
  }

  // Tuition Fees
  if (Array.isArray(dc.tuitionFees) && dc.tuitionFees.length > 0) {
    tuitionFeesList.value = JSON.parse(JSON.stringify(dc.tuitionFees))
  } else {
    tuitionFeesList.value = [
      { term: 'ภาคการศึกษาที่ 1', amount: '13,500 บาท', note: 'รวมค่าธรรมเนียมแรกเข้าและอุปกรณ์' },
      { term: 'ภาคการศึกษาที่ 2', amount: '11,500 บาท', note: 'ค่าลงทะเบียนปกติ' },
      { term: 'รวมตลอดหลักสูตร (8 ภาคการศึกษา)', amount: '94,000 บาท', note: 'เฉลี่ยเทอมละประมาณ 11,750 บาท' },
    ]
  }

  // Structures
  if (Array.isArray(dc.structures) && dc.structures.length > 0) {
    structuresList.value = JSON.parse(JSON.stringify(dc.structures))
  } else {
    structuresList.value = [
      { category: '1. หมวดวิชาศึกษาทั่วไป', credits: '30 หน่วยกิต', highlight: false },
      { category: '2. หมวดวิชาเฉพาะ', credits: '96 หน่วยกิต', highlight: false },
      { category: '3. หมวดวิชาเลือกเสรี', credits: '6 หน่วยกิต', highlight: false },
      { category: 'รวมหน่วยกิตตลอดหลักสูตร', credits: item.credits || '132 หน่วยกิต', highlight: true },
    ]
  }

  // Gallery Photos
  if (Array.isArray(dc.gallery) && dc.gallery.length > 0) {
    galleryList.value = JSON.parse(JSON.stringify(dc.gallery))
  } else {
    galleryList.value = [
      { url: item.image || '', title: `ภาพกิจกรรมและการเรียนการสอน ${item.title}`, badge: 'FEATURED' },
      { url: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1000&q=80', title: 'การเรียนรู้เชิงปฏิบัติการ คณะครุศาสตร์', badge: 'WORKSHOP' },
      { url: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1000&q=80', title: 'การฝึกประสบการณ์ในสถานศึกษา', badge: 'PRACTICE' },
      { url: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1000&q=80', title: 'นิทรรศการผลงานและการนำเสนอทางวิชาการ', badge: 'EXHIBITION' },
    ]
  }

  showDialog.value = true
}

async function handleImageUpload(event: Event) {
  const target = event.target as HTMLInputElement
  if (!target.files || !target.files[0]) return
  const file = target.files[0]

  uploadLoading.value = true
  try {
    const res = await api.uploadFile(file)
    form.value.image = res.url
    showToast('อัปโหลดรูปภาพหลักสูตรสำเร็จ')
  } catch (err: any) {
    showToast(err.response?.data?.message || 'อัปโหลดรูปภาพไม่สำเร็จ', 'ข้อผิดพลาด', 'error')
  } finally {
    uploadLoading.value = false
    target.value = ''
  }
}

async function saveCurriculum() {
  if (!form.value.title?.trim()) {
    showToast('กรุณาระบุชื่อหลักสูตร/สาขาวิชา', 'ข้อมูลไม่ครบ', 'warning')
    return
  }
  if (!form.value.degree_title?.trim()) {
    showToast('กรุณาระบุชื่อวุฒิการศึกษาที่ได้รับ', 'ข้อมูลไม่ครบ', 'warning')
    return
  }

  // Parse tags from comma-separated string
  const tags = tagsInput.value
    .split(',')
    .map((t) => t.trim())
    .filter(Boolean)

  const detail_content = {
    careers: careersList.value.filter((c) => c.trim()),
    learningOutcomes: learningOutcomes.value,
    tuitionFees: tuitionFeesList.value,
    structures: structuresList.value,
    gallery: galleryList.value.filter((g) => g.url.trim()),
  }

  saving.value = true
  try {
    const payload = {
      ...form.value,
      tags,
      detail_content,
    }

    if (isEditing.value && form.value.id) {
      await api.updateCurriculum(form.value.id, payload)
      showToast('บันทึกการแก้ไขหลักสูตรเรียบร้อยแล้ว')
    } else {
      await api.createCurriculum(payload)
      showToast('เพิ่มหลักสูตรใหม่เรียบร้อยแล้ว')
    }

    showDialog.value = false
    await fetchData()
  } catch (err: any) {
    showToast(err.response?.data?.message || err.message || 'บันทึกหลักสูตรไม่สำเร็จ', 'ข้อผิดพลาด', 'error')
  } finally {
    saving.value = false
  }
}

function confirmDelete(item: CurriculumRecord) {
  deleteTarget.value = item
  showDeleteDialog.value = true
}

async function doDelete() {
  if (!deleteTarget.value) return
  deleteLoading.value = true
  try {
    await api.deleteCurriculum(deleteTarget.value.id)
    showToast(`ลบหลักสูตร "${deleteTarget.value.title}" เรียบร้อยแล้ว`)
    showDeleteDialog.value = false
    deleteTarget.value = null
    await fetchData()
  } catch (err: any) {
    showToast(err.response?.data?.message || err.message || 'ลบหลักสูตรไม่สำเร็จ', 'ข้อผิดพลาด', 'error')
  } finally {
    deleteLoading.value = false
  }
}

async function toggleActive(item: CurriculumRecord) {
  try {
    const newStatus = !item.is_active
    await api.updateCurriculum(item.id, { is_active: newStatus })
    item.is_active = newStatus
    showToast(newStatus ? 'เปิดแสดงผลหลักสูตรนี้แล้ว' : 'ปิดแสดงผลหลักสูตรนี้แล้ว')
  } catch (err) {
    showToast('ไม่สามารถเปลี่ยนสถานะได้', 'ข้อผิดพลาด', 'error')
  }
}

async function moveCurriculum(item: CurriculumRecord, direction: 'up' | 'down') {
  const currentList = filteredCurricula.value
  const index = currentList.findIndex((c) => c.id === item.id)
  const targetIndex = direction === 'up' ? index - 1 : index + 1
  if (targetIndex < 0 || targetIndex >= currentList.length) return

  const itemA = currentList[index]
  const itemB = currentList[targetIndex]
  const tempOrder = itemA.sort_order
  itemA.sort_order = itemB.sort_order
  itemB.sort_order = tempOrder

  try {
    const orders = [
      { id: itemA.id, sort_order: itemA.sort_order },
      { id: itemB.id, sort_order: itemB.sort_order },
    ]
    await api.reorderCurricula(orders)
    showToast('ปรับลำดับหลักสูตรเรียบร้อยแล้ว')
    await fetchData()
  } catch (err) {
    showToast('ไม่สามารถบันทึกลำดับได้', 'เกิดข้อผิดพลาด', 'error')
    await fetchData()
  }
}
</script>

<template>
  <div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200/60 flex items-center justify-center shrink-0">
            <v-icon icon="mdi-school-outline" size="24" />
          </div>
          <div>
            <h2 class="text-lg sm:text-xl font-black text-slate-900 leading-tight">
              จัดการหลักสูตรการศึกษา
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
              จัดการข้อมูลหลักสูตร สาขาวิชา รูปภาพหน้าปก วุฒิการศึกษา และหน่วยกิต ของคณะครุศาสตร์
            </p>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-wrap items-center gap-2.5">
        <a
          href="/curriculum/bachelor"
          target="_blank"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors no-underline cursor-pointer"
        >
          <v-icon icon="mdi-open-in-new" size="16" />
          <span>ดูหน้าเว็บจริง</span>
        </a>

        <button
          type="button"
          class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-700/20 transition-all cursor-pointer"
          @click="openAddDialog"
        >
          <v-icon icon="mdi-plus" size="16" />
          <span>เพิ่มหลักสูตรใหม่</span>
        </button>
      </div>
    </div>

    <!-- Filter Bar & Search -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
      <!-- Degree Level Tabs -->
      <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0">
        <button
          v-for="deg in degreeLevels"
          :key="deg.value"
          type="button"
          class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors flex items-center gap-1.5 cursor-pointer"
          :class="selectedDegreeFilter === deg.value
            ? 'bg-emerald-600 text-white shadow-xs'
            : 'text-slate-600 hover:bg-slate-100'"
          @click="selectedDegreeFilter = deg.value"
        >
          <v-icon :icon="deg.icon" size="16" />
          <span>{{ deg.label }}</span>
        </button>
      </div>

      <!-- Search Box -->
      <div class="relative w-full md:w-72">
        <v-icon icon="mdi-magnify" size="18" class="absolute left-3 top-2.5 text-slate-400" />
        <input
          v-model="search"
          type="text"
          placeholder="ค้นหาชื่อหลักสูตร หรือวุฒิ..."
          class="w-full pl-9 pr-3.5 py-1.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
        />
      </div>
    </div>

    <!-- Loading Skeleton -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <div v-for="i in 6" :key="i" class="bg-white rounded-2xl border border-slate-200/80 p-4 animate-pulse space-y-3">
        <div class="h-44 bg-slate-200 rounded-xl" />
        <div class="h-5 bg-slate-200 rounded w-3/4" />
        <div class="h-4 bg-slate-100 rounded w-1/2" />
      </div>
    </div>

    <!-- Empty State -->
    <div
      v-else-if="filteredCurricula.length === 0"
      class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center"
    >
      <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
        <v-icon icon="mdi-school-outline" size="32" />
      </div>
      <h3 class="text-base font-bold text-slate-800">ไม่พบหลักสูตรที่ตรงกับเงื่อนไข</h3>
      <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
        ลองปรับตัวกรอง หรือคลิกปุ่มด้านล่างเพื่อเพิ่มหลักสูตรใหม่
      </p>
      <button
        type="button"
        class="mt-4 px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 cursor-pointer"
        @click="openAddDialog"
      >
        + เพิ่มหลักสูตรใหม่
      </button>
    </div>

    <!-- Curricula Cards Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <div
        v-for="item in filteredCurricula"
        :key="item.id"
        class="bg-white rounded-2xl border border-slate-200/80 hover:border-slate-300 shadow-xs hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col justify-between group"
      >
        <div>
          <!-- Card Image Header -->
          <div class="relative h-44 w-full bg-slate-100 overflow-hidden">
            <img
              v-if="item.image"
              :src="item.image"
              :alt="item.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              @error="($event.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80'"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-100">
              <v-icon icon="mdi-image-outline" size="36" />
            </div>

            <!-- Gradient bottom shade -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent pointer-events-none" />

            <!-- Floating Top Badges -->
            <div class="absolute top-3 left-3 flex items-center gap-1.5 flex-wrap">
              <span
                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border backdrop-blur-md bg-white/90 shadow-xs"
                :class="getDegreeBadgeClass(item.degree_level).text"
              >
                {{ getDegreeBadgeClass(item.degree_level).label }}
              </span>

              <span
                v-if="item.highlight"
                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white shadow-xs inline-flex items-center gap-1"
              >
                <v-icon icon="mdi-star" size="12" />
                <span>แนะนำ</span>
              </span>
            </div>

            <!-- Duration & Credits overlay -->
            <div class="absolute bottom-2.5 inset-x-3 flex items-center justify-between text-white text-[11px] font-semibold">
              <span class="px-2 py-0.5 rounded-md bg-black/40 backdrop-blur-xs">
                {{ item.duration }}
              </span>
              <span class="px-2 py-0.5 rounded-md bg-black/40 backdrop-blur-xs">
                {{ item.credits }}
              </span>
            </div>
          </div>

          <!-- Card Content Body -->
          <div class="p-4 space-y-2.5">
            <div>
              <h3 class="text-base font-bold text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug line-clamp-1">
                {{ item.title }}
              </h3>
              <p class="text-xs font-semibold text-emerald-700 mt-0.5">
                {{ item.degree_title }}
              </p>
            </div>

            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
              {{ item.desc || 'ยังไม่มีคำอธิบายหลักสูตร' }}
            </p>

            <!-- Tags -->
            <div v-if="item.tags && item.tags.length > 0" class="flex flex-wrap gap-1 pt-1">
              <span
                v-for="t in item.tags.slice(0, 3)"
                :key="t"
                class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-600"
              >
                {{ t }}
              </span>
            </div>
          </div>
        </div>

        <!-- Card Footer Actions -->
        <div class="p-4 pt-2 border-t border-slate-100 flex items-center justify-between gap-2 bg-slate-50/50">
          <!-- Active Status Switch -->
          <button
            type="button"
            class="px-2 py-1 rounded-lg text-[11px] font-semibold border transition-colors flex items-center gap-1 cursor-pointer"
            :class="item.is_active
              ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100'
              : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200'"
            @click="toggleActive(item)"
          >
            <span class="w-1.5 h-1.5 rounded-full" :class="item.is_active ? 'bg-emerald-500' : 'bg-slate-400'" />
            <span>{{ item.is_active ? 'แสดงผล' : 'ซ่อน' }}</span>
          </button>

          <!-- Buttons Group -->
          <div class="flex items-center gap-1.5">
            <button
              type="button"
              class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
              title="เลื่อนขึ้น"
              @click="moveCurriculum(item, 'up')"
            >
              <v-icon icon="mdi-chevron-up" size="16" />
            </button>
            <button
              type="button"
              class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
              title="เลื่อนลง"
              @click="moveCurriculum(item, 'down')"
            >
              <v-icon icon="mdi-chevron-down" size="16" />
            </button>

            <!-- Edit Button -->
            <button
              type="button"
              class="w-7 h-7 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
              title="แก้ไขหลักสูตร"
              @click="openEditDialog(item)"
            >
              <v-icon icon="mdi-pencil-outline" size="15" />
            </button>

            <!-- Delete Button -->
            <button
              type="button"
              class="w-7 h-7 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer"
              title="ลบหลักสูตร"
              @click="confirmDelete(item)"
            >
              <v-icon icon="mdi-delete-outline" size="15" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ─── Add / Edit Modal ────────────────────────────────────────────────── -->
    <v-dialog v-model="showDialog" max-width="860px" scrollable>
      <v-card class="rounded-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Dialog Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <v-icon :icon="isEditing ? 'mdi-pencil-outline' : 'mdi-plus'" size="20" />
            </div>
            <div>
              <h3 class="text-base font-bold text-slate-900 leading-tight">
                {{ isEditing ? 'แก้ไขข้อมูลหลักสูตร' : 'เพิ่มหลักสูตรการศึกษาใหม่' }}
              </h3>
              <p class="text-[11px] text-slate-500">จัดการข้อมูลพื้นฐาน, อาชีพ, ผลการเรียนรู้ ELO, ค่าธรรมเนียม, โครงสร้าง และภาพกิจกรรม</p>
            </div>
          </div>
          <button
            type="button"
            class="text-slate-400 hover:text-slate-600 cursor-pointer p-1 rounded-lg hover:bg-slate-100 transition-colors"
            @click="showDialog = false"
          >
            <v-icon icon="mdi-close" size="20" />
          </button>
        </div>

        <!-- Navigation Tab Header -->
        <div class="px-6 pt-3 pb-2 bg-slate-50/80 border-b border-slate-200/80 shrink-0">
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5 p-1 bg-slate-200/60 rounded-xl">
            <button
              type="button"
              class="py-2 px-3 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition-all cursor-pointer"
              :class="dialogTab === 'general' ? 'bg-white text-emerald-800 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
              @click="dialogTab = 'general'"
            >
              <v-icon icon="mdi-information-outline" size="16" />
              <span>1. ข้อมูลทั่วไป</span>
            </button>

            <button
              type="button"
              class="py-2 px-3 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition-all cursor-pointer"
              :class="dialogTab === 'careers_elo' ? 'bg-white text-emerald-800 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
              @click="dialogTab = 'careers_elo'"
            >
              <v-icon icon="mdi-school-outline" size="16" />
              <span>2. อาชีพ & ELO</span>
            </button>

            <button
              type="button"
              class="py-2 px-3 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition-all cursor-pointer"
              :class="dialogTab === 'fees_structure' ? 'bg-white text-emerald-800 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
              @click="dialogTab = 'fees_structure'"
            >
              <v-icon icon="mdi-cash-multiple" size="16" />
              <span>3. ค่าธรรมเนียม & โครงสร้าง</span>
            </button>

            <button
              type="button"
              class="py-2 px-3 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition-all cursor-pointer"
              :class="dialogTab === 'gallery' ? 'bg-white text-emerald-800 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
              @click="dialogTab = 'gallery'"
            >
              <v-icon icon="mdi-image-multiple-outline" size="16" />
              <span>4. ภาพกิจกรรม ({{ galleryList.length }})</span>
            </button>
          </div>
        </div>

        <!-- Dialog Scrollable Content Body -->
        <form class="flex-1 overflow-y-auto p-6 space-y-5" @submit.prevent="saveCurriculum">
          <!-- ═══════════════════════════════════════════════════════════════════ -->
          <!-- TAB 1: ข้อมูลทั่วไป (General)                                      -->
          <!-- ═══════════════════════════════════════════════════════════════════ -->
          <div v-show="dialogTab === 'general'" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Degree Level -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  ระดับการศึกษา <span class="text-rose-500">*</span>
                </label>
                <select
                  v-model="form.degree_level"
                  required
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none bg-white"
                >
                  <option value="bachelor">ปริญญาตรี (Bachelor)</option>
                  <option value="grad-diploma">ประกาศนียบัตรบัณฑิตวิชาชีพครู (Grad Diploma)</option>
                  <option value="master">ปริญญาโท (Master)</option>
                  <option value="doctoral">ปริญญาเอก (Doctoral)</option>
                </select>
              </div>

              <!-- Slug -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  Slug / รหัสอ้างอิง URL
                </label>
                <input
                  v-model="form.slug"
                  type="text"
                  placeholder="เช่น datascience, early-childhood (เว้นว่างเพื่อสร้างอัตโนมัติ)"
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                />
              </div>
            </div>

            <!-- Titles -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  ชื่อสาขาวิชา/หลักสูตร (ภาษาไทย) <span class="text-rose-500">*</span>
                </label>
                <input
                  v-model="form.title"
                  type="text"
                  required
                  placeholder="เช่น สาขาวิชาวิทยาการข้อมูล"
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                />
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  ชื่อหลักสูตร (ภาษาอังกฤษ)
                </label>
                <input
                  v-model="form.title_en"
                  type="text"
                  placeholder="เช่น Data Science"
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                />
              </div>
            </div>

            <!-- Degree Awarded -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  ชื่อวุฒิการศึกษาที่ได้รับ (ภาษาไทย) <span class="text-rose-500">*</span>
                </label>
                <input
                  v-model="form.degree_title"
                  type="text"
                  required
                  placeholder="เช่น วิทยาศาสตรบัณฑิต (วท.บ.) หรือ ครุศาสตรบัณฑิต (ค.บ.)"
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                />
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  ชื่อวุฒิการศึกษาที่ได้รับ (ภาษาอังกฤษ)
                </label>
                <input
                  v-model="form.degree_title_en"
                  type="text"
                  placeholder="เช่น Bachelor of Science (B.Sc.)"
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ระยะเวลาเรียน</label>
                <input
                  v-model="form.duration"
                  type="text"
                  placeholder="เช่น 4 ปี"
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                />
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">จำนวนหน่วยกิตตลอดหลักสูตร</label>
                <input
                  v-model="form.credits"
                  type="text"
                  placeholder="เช่น 120 หน่วยกิต"
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                />
              </div>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">
                จุดเด่น / คำอธิบายย่อหลักสูตร
              </label>
              <textarea
                v-model="form.desc"
                rows="3"
                placeholder="จุดเด่นและวัตถุประสงค์การผลิตบัณฑิตของสาขาวิชานี้..."
                class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none resize-none"
              />
            </div>

            <!-- Image Upload & Preview -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2.5">
              <label class="block text-xs font-bold text-slate-800">
                รูปภาพหน้าปกหลักสูตร (Featured Image)
              </label>

              <div class="flex items-center gap-3">
                <!-- Preview Box -->
                <div class="w-32 h-20 rounded-xl overflow-hidden bg-slate-200 shrink-0 border border-slate-300 flex items-center justify-center">
                  <img
                    v-if="form.image"
                    :src="form.image"
                    alt="Preview"
                    class="w-full h-full object-cover"
                  />
                  <v-icon v-else icon="mdi-image-outline" size="24" class="text-slate-400" />
                </div>

                <!-- Upload & URL input -->
                <div class="flex-1 space-y-2">
                  <input
                    v-model="form.image"
                    type="text"
                    placeholder="ใส่ URL รูปภาพ หรือกดปุ่มอัปโหลดรูปภาพ..."
                    class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none bg-white"
                  />

                  <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-100 cursor-pointer transition-colors shadow-2xs">
                    <v-icon :icon="uploadLoading ? 'mdi-loading' : 'mdi-upload'" :class="{ 'animate-spin': uploadLoading }" size="16" />
                    <span>{{ uploadLoading ? 'กำลังอัปโหลด...' : 'อัปโหลดรูปจากคอมพิวเตอร์' }}</span>
                    <input
                      type="file"
                      accept="image/*"
                      class="hidden"
                      :disabled="uploadLoading"
                      @change="handleImageUpload"
                    />
                  </label>
                </div>
              </div>
            </div>

            <!-- Tags & Document Link -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  แท็กคีย์เวิร์ด (คั่นด้วยจุลภาค ,)
                </label>
                <input
                  v-model="tagsInput"
                  type="text"
                  placeholder="เช่น Data Science, Machine Learning, AI"
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                />
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                  ลิงก์เอกสารเล่มหลักสูตร PDF (ถ้ามี)
                </label>
                <input
                  v-model="form.document_url"
                  type="text"
                  placeholder="https://.../curriculum.pdf"
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                />
              </div>
            </div>

            <!-- Highlight, Active & Sort Order -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
              <div class="flex items-center h-[38px]">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                  <input
                    v-model="form.highlight"
                    type="checkbox"
                    class="w-4 h-4 rounded text-amber-500 focus:ring-amber-500 border-slate-300"
                  />
                  <span class="text-xs font-medium text-slate-700">หลักสูตรแนะนำ (เด่น)</span>
                </label>
              </div>

              <div class="flex items-center h-[38px]">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                  <input
                    v-model="form.is_active"
                    type="checkbox"
                    class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300"
                  />
                  <span class="text-xs font-medium text-slate-700">เปิดแสดงผลหน้าเว็บ</span>
                </label>
              </div>

              <div>
                <input
                  v-model.number="form.sort_order"
                  type="number"
                  min="1"
                  placeholder="ลำดับการแสดงผล"
                  class="w-full px-3.5 py-1.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                />
              </div>
            </div>
          </div>

          <!-- ═══════════════════════════════════════════════════════════════════ -->
          <!-- TAB 2: อาชีพ & ELO (4 ชั้นปี)                                       -->
          <!-- ═══════════════════════════════════════════════════════════════════ -->
          <div v-show="dialogTab === 'careers_elo'" class="space-y-6">
            <!-- 1. Careers List -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                    <v-icon icon="mdi-briefcase-check" size="16" class="text-emerald-600" />
                    <span>อาชีพที่สามารถประกอบได้หลังสำเร็จการศึกษา</span>
                  </h4>
                  <p class="text-[11px] text-slate-500">แสดงผลในหัวข้ออาชีพที่ประกอบได้ในหน้ารายละเอียดหลักสูตร</p>
                </div>
                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                  {{ careersList.length }} อาชีพ
                </span>
              </div>

              <!-- Input to Add Career -->
              <div class="flex items-center gap-2">
                <input
                  v-model="newCareerInput"
                  type="text"
                  placeholder="พิมพ์ชื่ออาชีพ เช่น นักวิชาการคอมพิวเตอร์, ครูผู้สอนวิทยาศาสตร์..."
                  class="flex-1 px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none bg-white"
                  @keyup.enter.prevent="addCareer"
                />
                <button
                  type="button"
                  class="px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shrink-0 flex items-center gap-1 transition-colors cursor-pointer"
                  @click="addCareer"
                >
                  <v-icon icon="mdi-plus" size="16" />
                  <span>เพิ่มอาชีพ</span>
                </button>
              </div>

              <!-- Careers Tag Chips -->
              <div v-if="careersList.length > 0" class="flex flex-wrap gap-2 pt-2">
                <div
                  v-for="(job, idx) in careersList"
                  :key="idx"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-xs text-slate-800 shadow-2xs group hover:border-emerald-300 transition-colors"
                >
                  <v-icon icon="mdi-check-circle" size="14" class="text-emerald-600" />
                  <span>{{ job }}</span>
                  <button
                    type="button"
                    class="text-slate-400 hover:text-rose-500 cursor-pointer ml-1"
                    title="ลบอาชีพนี้"
                    @click="removeCareer(idx)"
                  >
                    <v-icon icon="mdi-close" size="14" />
                  </button>
                </div>
              </div>
              <div v-else class="text-xs text-slate-400 italic text-center py-2">
                ยังไม่มีรายการอาชีพที่ระบุ กรุณาพิมพ์แล้วกดเพิ่มอาชีพ
              </div>
            </div>

            <!-- 2. Learning Outcomes (ELO 4 Years) -->
            <div class="space-y-3">
              <div>
                <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                  <v-icon icon="mdi-target" size="16" class="text-emerald-600" />
                  <span>ผลการเรียนรู้ที่คาดหวังของหลักสูตร (ELO 4 ชั้นปี)</span>
                </h4>
                <p class="text-[11px] text-slate-500">ระบุผลการเรียนรู้และเป้าหมายการพัฒนาสมรรถนะของผู้เรียนในแต่ละชั้นปี</p>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div
                  v-for="(elo, idx) in learningOutcomes"
                  :key="idx"
                  class="p-3.5 rounded-xl border border-slate-200/90 bg-white space-y-1.5 shadow-2xs hover:border-emerald-300 transition-colors"
                >
                  <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-800 flex items-center gap-1">
                      <v-icon :icon="`mdi-numeric-${idx + 1}-circle`" size="16" class="text-emerald-600" />
                      {{ elo.year }}
                    </span>
                  </div>
                  <textarea
                    v-model="elo.description"
                    rows="3"
                    :placeholder="`ระบุผลการเรียนรู้ที่คาดหวังสำหรับ ${elo.year}...`"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none resize-none leading-relaxed"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- ═══════════════════════════════════════════════════════════════════ -->
          <!-- TAB 3: ค่าธรรมเนียม & โครงสร้าง                                     -->
          <!-- ═══════════════════════════════════════════════════════════════════ -->
          <div v-show="dialogTab === 'fees_structure'" class="space-y-6">
            <!-- 1. Tuition Fees -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                    <v-icon icon="mdi-cash-register" size="16" class="text-emerald-600" />
                    <span>ตารางค่าธรรมเนียมการศึกษา</span>
                  </h4>
                  <p class="text-[11px] text-slate-500">กำหนดภาคการศึกษา อัตราค่าเล่าเรียน และหมายเหตุสำหรับผู้เรียน</p>
                </div>
                <button
                  type="button"
                  class="px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-700 bg-white border border-emerald-300 hover:bg-emerald-50 flex items-center gap-1 cursor-pointer transition-colors"
                  @click="addFeeRow"
                >
                  <v-icon icon="mdi-plus" size="14" />
                  <span>เพิ่มแถว</span>
                </button>
              </div>

              <div class="space-y-2">
                <div
                  v-for="(fee, idx) in tuitionFeesList"
                  :key="idx"
                  class="flex items-center gap-2 p-2 bg-white rounded-xl border border-slate-200"
                >
                  <input
                    v-model="fee.term"
                    type="text"
                    placeholder="ภาคการศึกษา เช่น ภาคการศึกษาที่ 1"
                    class="w-1/3 px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                  />
                  <input
                    v-model="fee.amount"
                    type="text"
                    placeholder="อัตรา เช่น 13,500 บาท"
                    class="w-1/4 px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none font-semibold text-emerald-700"
                  />
                  <input
                    v-model="fee.note"
                    type="text"
                    placeholder="หมายเหตุ (ถ้ามี)"
                    class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none text-slate-600"
                  />
                  <button
                    type="button"
                    class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center cursor-pointer shrink-0"
                    title="ลบแถวนี้"
                    @click="removeFeeRow(idx)"
                  >
                    <v-icon icon="mdi-delete-outline" size="16" />
                  </button>
                </div>
              </div>
            </div>

            <!-- 2. Structures Breakdown -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                    <v-icon icon="mdi-format-list-bulleted" size="16" class="text-emerald-600" />
                    <span>โครงสร้างหลักสูตร (หมวดวิชาและหน่วยกิต)</span>
                  </h4>
                  <p class="text-[11px] text-slate-500">จัดกลุ่มหมวดวิชาศึกษาทั่วไป วิชาเฉพาะ และวิชาเลือกเสรี</p>
                </div>
                <button
                  type="button"
                  class="px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-700 bg-white border border-emerald-300 hover:bg-emerald-50 flex items-center gap-1 cursor-pointer transition-colors"
                  @click="addStructureRow"
                >
                  <v-icon icon="mdi-plus" size="14" />
                  <span>เพิ่มหมวดวิชา</span>
                </button>
              </div>

              <div class="space-y-2">
                <div
                  v-for="(st, idx) in structuresList"
                  :key="idx"
                  class="flex items-center gap-2 p-2 bg-white rounded-xl border border-slate-200"
                  :class="{ 'border-emerald-300 bg-emerald-50/30': st.highlight }"
                >
                  <input
                    v-model="st.category"
                    type="text"
                    placeholder="ชื่อหมวดวิชา เช่น 1. หมวดวิชาศึกษาทั่วไป"
                    class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                  />
                  <input
                    v-model="st.credits"
                    type="text"
                    placeholder="หน่วยกิต เช่น 30 หน่วยกิต"
                    class="w-32 px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none font-bold"
                  />
                  <label class="flex items-center gap-1 text-[11px] text-slate-600 shrink-0 px-2 cursor-pointer">
                    <input v-model="st.highlight" type="checkbox" class="w-3.5 h-3.5 rounded text-emerald-600" />
                    <span>แถวสรุป</span>
                  </label>
                  <button
                    type="button"
                    class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center cursor-pointer shrink-0"
                    title="ลบแถวนี้"
                    @click="removeStructureRow(idx)"
                  >
                    <v-icon icon="mdi-delete-outline" size="16" />
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- ═══════════════════════════════════════════════════════════════════ -->
          <!-- TAB 4: แกลเลอรีภาพกิจกรรม (Gallery)                                 -->
          <!-- ═══════════════════════════════════════════════════════════════════ -->
          <div v-show="dialogTab === 'gallery'" class="space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                  <v-icon icon="mdi-camera-outline" size="16" class="text-emerald-600" />
                  <span>ภาพกิจกรรมและบรรยากาศการเรียนรู้ (Gallery Slider)</span>
                </h4>
                <p class="text-[11px] text-slate-500">
                  รูปภาพที่จะแสดงในแถบสไลด์ 4 รูปด้านบนสุดของหน้ารายละเอียดหลักสูตร และเปิด Lightbox ดูภาพขยายได้
                </p>
              </div>

              <button
                type="button"
                class="px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 flex items-center gap-1 transition-colors cursor-pointer"
                @click="addGalleryItem"
              >
                <v-icon icon="mdi-plus" size="15" />
                <span>เพิ่มรูปกิจกรรม</span>
              </button>
            </div>

            <!-- Gallery Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div
                v-for="(photo, idx) in galleryList"
                :key="idx"
                class="p-3.5 rounded-2xl border border-slate-200/90 bg-white space-y-3 shadow-2xs hover:border-emerald-300 transition-colors"
              >
                <!-- Card Header (Photo Index & Delete) -->
                <div class="flex items-center justify-between text-xs">
                  <span class="font-bold text-slate-700 flex items-center gap-1">
                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] flex items-center justify-center font-black">
                      {{ idx + 1 }}
                    </span>
                    <span>ภาพกิจกรรมชุดที่ {{ idx + 1 }}</span>
                  </span>

                  <button
                    type="button"
                    class="text-slate-400 hover:text-rose-500 cursor-pointer p-0.5"
                    title="ลบรูปนี้"
                    @click="removeGalleryItem(idx)"
                  >
                    <v-icon icon="mdi-trash-can-outline" size="16" />
                  </button>
                </div>

                <!-- Preview + Upload Form -->
                <div class="flex items-start gap-3">
                  <!-- Thumbnail preview -->
                  <div class="w-28 h-20 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 relative group flex items-center justify-center">
                    <img
                      v-if="photo.url"
                      :src="photo.url"
                      :alt="photo.title"
                      class="w-full h-full object-cover"
                    />
                    <v-icon v-else icon="mdi-image-outline" size="24" class="text-slate-300" />

                    <!-- Floating Badge Preview -->
                    <span
                      v-if="photo.badge"
                      class="absolute bottom-1 left-1 px-1.5 py-0.2 rounded text-[8px] font-bold bg-black/60 text-white tracking-wider backdrop-blur-xs"
                    >
                      {{ photo.badge }}
                    </span>
                  </div>

                  <!-- Inputs -->
                  <div class="flex-1 space-y-2">
                    <input
                      v-model="photo.url"
                      type="text"
                      placeholder="ใส่ URL รูปภาพ..."
                      class="w-full px-2.5 py-1 rounded-lg border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                    />

                    <!-- Upload Button -->
                    <label class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 cursor-pointer transition-colors">
                      <v-icon
                        :icon="galleryUploadLoading === idx ? 'mdi-loading' : 'mdi-upload'"
                        :class="{ 'animate-spin': galleryUploadLoading === idx }"
                        size="14"
                      />
                      <span>{{ galleryUploadLoading === idx ? 'กำลังอัปโหลด...' : 'อัปโหลดรูป' }}</span>
                      <input
                        type="file"
                        accept="image/*"
                        class="hidden"
                        :disabled="galleryUploadLoading !== null"
                        @change="handleGalleryUpload(idx, $event)"
                      />
                    </label>
                  </div>
                </div>

                <!-- Title & Badge inputs -->
                <div class="grid grid-cols-3 gap-2">
                  <div class="col-span-2">
                    <input
                      v-model="photo.title"
                      type="text"
                      placeholder="คำอธิบายภาพ เช่น ห้องปฏิบัติการ Data Lab"
                      class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
                    />
                  </div>
                  <div>
                    <input
                      v-model="photo.badge"
                      type="text"
                      placeholder="Badge เช่น LAB"
                      class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none uppercase font-semibold text-emerald-800"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Dialog Footer Actions -->
          <div class="flex items-center justify-between pt-4 border-t border-slate-100">
            <span class="text-[11px] text-slate-400">
              * ข้อมูลที่บันทึกจะซิงค์แสดงผลในหน้ารายละเอียดหลักสูตรทันที
            </span>

            <div class="flex items-center gap-2">
              <button
                type="button"
                class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 cursor-pointer transition-colors"
                @click="showDialog = false"
              >
                ยกเลิก
              </button>
              <button
                type="submit"
                class="px-5 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 cursor-pointer shadow-xs transition-colors flex items-center gap-1.5"
                :disabled="saving"
              >
                <v-icon :icon="saving ? 'mdi-loading' : 'mdi-check'" :class="{ 'animate-spin': saving }" size="16" />
                <span>{{ saving ? 'กำลังบันทึก...' : 'บันทึกหลักสูตร' }}</span>
              </button>
            </div>
          </div>
        </form>
      </v-card>
    </v-dialog>

    <!-- ─── Delete Confirmation Dialog ──────────────────────────────────────── -->
    <v-dialog v-model="showDeleteDialog" max-width="420px">
      <v-card class="rounded-2xl p-6 text-center">
        <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 mx-auto flex items-center justify-center mb-3">
          <v-icon icon="mdi-alert-outline" size="26" />
        </div>
        <h3 class="text-base font-bold text-slate-900">ยืนยันการลบหลักสูตร</h3>
        <p class="text-xs text-slate-500 mt-1">
          คุณต้องการลบหลักสูตร <strong class="text-slate-800">"{{ deleteTarget?.title }}"</strong> ออกจากระบบใช่หรือไม่?
        </p>
        <div class="flex items-center justify-center gap-2 mt-5">
          <button
            type="button"
            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 cursor-pointer"
            @click="showDeleteDialog = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 disabled:opacity-50 cursor-pointer"
            :disabled="deleteLoading"
            @click="doDelete"
          >
            {{ deleteLoading ? 'กำลังลบ...' : 'ยืนยันการลบ' }}
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- ─── Toast Feedback ──────────────────────────────────────────────────── -->
    <AdminToast
      v-model="toast.show"
      :message="toast.message"
      :title="toast.title"
      :type="toast.type"
      :duration="3500"
    />
  </div>
</template>
