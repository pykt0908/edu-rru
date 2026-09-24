<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import {
  api,
  type HistoryItem,
  type Mission,
  type ItaItem,
  type ItaLink,
  type ItaYear,
  type RegulationCategory,
  type RegulationItem,
  type CarouselSlide,
  type CommitteeMember,
} from '@/services/api'
import AdminToast from '@/components/admin/AdminToast.vue'
import RichTextEditor from '@/components/admin/RichTextEditor.vue'

// ─── Toast Feedback ────────────────────────────────────────────────────────────
const toast = ref({
  show: false,
  message: '',
  title: '',
  type: 'success' as 'success' | 'error' | 'warning' | 'info',
})

const showToast = (
  msg: string,
  type: 'success' | 'error' | 'warning' | 'info' = 'success',
  title?: string
) => {
  toast.value = {
    show: true,
    message: msg,
    title: title || (type === 'success' ? 'บันทึกสำเร็จ' : type === 'warning' ? 'แจ้งเตือน' : 'เกิดข้อผิดพลาด'),
    type,
  }
}

// ─── Delete Confirmation Modal ────────────────────────────────────────────────
const showDeleteConfirm = ref(false)
const deleteLoading = ref(false)
const deleteTarget = ref({
  title: '',
  description: '',
  action: async () => {},
})

const openDeleteConfirm = (title: string, description: string, action: () => Promise<void>) => {
  deleteTarget.value = { title, description, action }
  showDeleteConfirm.value = true
}

const handleConfirmDelete = async () => {
  deleteLoading.value = true
  try {
    await deleteTarget.value.action()
    showDeleteConfirm.value = false
  } catch (err) {
    console.error('Delete action failed:', err)
  } finally {
    deleteLoading.value = false
  }
}

// ─── Active Tab ────────────────────────────────────────────────────────────────
const activeTab = ref('carousel')
const tabs = [
  { id: 'carousel', label: 'แบนเนอร์ภาพสไลด์', icon: 'mdi-view-carousel-outline' },
  { id: 'committee', label: 'คณะกรรมการคณะ', icon: 'mdi-account-group-outline' },
  { id: 'history', label: 'ประวัติคณะ', icon: 'mdi-book-open-page-variant-outline' },
  { id: 'philosophy', label: 'ปรัชญาวิสัยทัศน์', icon: 'mdi-lightbulb-outline' },
  { id: 'ita', label: 'ITA', icon: 'mdi-shield-check-outline' },
  { id: 'regulations', label: 'ข้อกฎหมาย', icon: 'mdi-scale-balance' },
]

// ─── 0. ภาพสไลด์แบนเนอร์ (Hero Carousel) ───────────────────────────────────────
const carouselSlides = ref<CarouselSlide[]>([])
const carouselFetching = ref(false)
const editingSlide = ref<CarouselSlide | null>(null)
const showCarouselDialog = ref(false)
const carouselLoading = ref(false)
const uploadCarouselLoading = ref(false)

const carouselForm = ref({
  id: 0,
  title: '',
  image_url: '',
  alt_text: '',
  link_url: '',
  target: '_self' as '_self' | '_blank',
  sort_order: 1,
  is_active: true,
})

const loadCarouselSlides = async () => {
  carouselFetching.value = true
  try {
    const data = await api.getCarouselSlides()
    carouselSlides.value = data
  } catch (err) {
    console.error('Failed to load carousel slides:', err)
    showToast('ไม่สามารถโหลดภาพสไลด์แบนเนอร์ได้', 'error')
  } finally {
    carouselFetching.value = false
  }
}

const openCreateSlide = () => {
  editingSlide.value = null
  carouselForm.value = {
    id: 0,
    title: '',
    image_url: '',
    alt_text: '',
    link_url: '',
    target: '_self',
    sort_order: carouselSlides.value.length + 1,
    is_active: true,
  }
  showCarouselDialog.value = true
}

const openEditSlide = (slide: CarouselSlide) => {
  editingSlide.value = slide
  carouselForm.value = {
    id: slide.id,
    title: slide.title || '',
    image_url: slide.image_url,
    alt_text: slide.alt_text || '',
    link_url: slide.link_url || '',
    target: (slide.target as '_self' | '_blank') || '_self',
    sort_order: slide.sort_order ?? 1,
    is_active: slide.is_active ?? true,
  }
  showCarouselDialog.value = true
}

const handleCarouselFileUpload = async (e: Event) => {
  const target = e.target as HTMLInputElement
  if (!target.files?.length) return
  uploadCarouselLoading.value = true
  try {
    const file = target.files[0]
    const res = await api.uploadFile(file)
    carouselForm.value.image_url = res.url
    if (!carouselForm.value.title) {
      carouselForm.value.title = file.name.replace(/\.[^/.]+$/, '')
    }
    if (!carouselForm.value.alt_text) {
      carouselForm.value.alt_text = carouselForm.value.title
    }
    showToast(`อัปโหลดภาพ ${file.name} เรียบร้อยแล้ว`, 'success')
  } catch (err) {
    console.error('Carousel image upload error:', err)
    showToast('เกิดข้อผิดพลาดในการอัปโหลดภาพ', 'error')
  } finally {
    uploadCarouselLoading.value = false
    target.value = ''
  }
}

const saveSlide = async () => {
  if (!carouselForm.value.image_url.trim()) {
    showToast('กรุณาระบุ URL ภาพ หรืออัปโหลดไฟล์ภาพแบนเนอร์', 'warning', 'ข้อมูลไม่ครบถ้วน')
    return
  }

  carouselLoading.value = true
  try {
    const payload = {
      title: carouselForm.value.title.trim() || undefined,
      image_url: carouselForm.value.image_url.trim(),
      alt_text: carouselForm.value.alt_text.trim() || undefined,
      link_url: carouselForm.value.link_url.trim() || undefined,
      target: carouselForm.value.target || '_self',
      sort_order: Number(carouselForm.value.sort_order) || 1,
      is_active: carouselForm.value.is_active,
    }

    if (editingSlide.value) {
      await api.updateCarouselSlide(editingSlide.value.id, payload)
      showToast('อัปเดตภาพสไลด์แบนเนอร์สำเร็จ', 'success', 'บันทึกสำเร็จ')
    } else {
      await api.createCarouselSlide(payload)
      showToast('เพิ่มภาพสไลด์แบนเนอร์สำเร็จ', 'success', 'เพิ่มสำเร็จ')
    }

    showCarouselDialog.value = false
    await loadCarouselSlides()
  } catch (err: any) {
    console.error('Failed to save carousel slide:', err)
    const errorMsg = err.response?.data?.message || 'เกิดข้อผิดพลาดในการบันทึกภาพสไลด์'
    showToast(errorMsg, 'error')
  } finally {
    carouselLoading.value = false
  }
}

const toggleSlideActive = async (slide: CarouselSlide) => {
  try {
    await api.updateCarouselSlide(slide.id, { is_active: !slide.is_active })
    slide.is_active = !slide.is_active
    showToast(`${slide.is_active ? 'เปิด' : 'ปิด'}การแสดงผลสไลด์เรียบร้อย`, 'success')
  } catch (err) {
    console.error('Failed to toggle slide status:', err)
    showToast('ไม่สามารถเปลี่ยนสถานะสไลด์ได้', 'error')
  }
}

const deleteSlide = (slide: CarouselSlide) => {
  openDeleteConfirm(
    'ลบภาพสไลด์แบนเนอร์',
    `คุณต้องการลบภาพสไลด์ "${slide.title || 'แบนเนอร์สไลด์'}" ใช่หรือไม่?`,
    async () => {
      try {
        await api.deleteCarouselSlide(slide.id)
        showToast('ลบภาพสไลด์แบนเนอร์เรียบร้อยแล้ว', 'success', 'ลบสำเร็จ')
        await loadCarouselSlides()
      } catch (err) {
        console.error('Failed to delete slide:', err)
        showToast('เกิดข้อผิดพลาดในการลบภาพสไลด์', 'error')
      }
    }
  )
}

const moveSlideOrder = async (index: number, direction: 'up' | 'down') => {
  const targetIndex = direction === 'up' ? index - 1 : index + 1
  if (targetIndex < 0 || targetIndex >= carouselSlides.value.length) return

  const currentList = [...carouselSlides.value]
  const temp = currentList[index]
  currentList[index] = currentList[targetIndex]
  currentList[targetIndex] = temp

  const orders = currentList.map((item, idx) => ({
    id: item.id,
    sort_order: idx + 1,
  }))

  try {
    await api.reorderCarouselSlides(orders)
    await loadCarouselSlides()
    showToast('ปรับเปลี่ยนลำดับการแสดงผลสำเร็จ', 'success')
  } catch (err) {
    console.error('Failed to reorder slides:', err)
    showToast('เกิดข้อผิดพลาดในการจัดลำดับภาพสไลด์', 'error')
  }
}

// ─── 1. ประวัติคณะ ─────────────────────────────────────────────────────────────
const historyItems = ref<HistoryItem[]>([])
const historyFetching = ref(false)
const editingHistory = ref<HistoryItem | null>(null)
const showHistoryDialog = ref(false)
const historyForm = ref({ id: 0, year: '', title: '', detail: '', sort_order: 0 })
const historyLoading = ref(false)

const loadHistory = async () => {
  historyFetching.value = true
  try {
    const data = await api.getHistory()
    historyItems.value = data
  } catch (err) {
    console.error('Failed to load history:', err)
    showToast('ไม่สามารถโหลดข้อมูลประวัติได้', 'error')
  } finally {
    historyFetching.value = false
  }
}

const openCreateHistory = () => {
  historyForm.value = {
    id: 0,
    year: '',
    title: '',
    detail: '',
    sort_order: historyItems.value.length + 1,
  }
  editingHistory.value = null
  showHistoryDialog.value = true
}

const openEditHistory = (item: HistoryItem) => {
  historyForm.value = { ...item, sort_order: item.sort_order ?? 0 }
  editingHistory.value = item
  showHistoryDialog.value = true
}

const saveHistory = async () => {
  if (!historyForm.value.year || !historyForm.value.title) {
    showToast('กรุณากรอกปี พ.ศ. และชื่อเหตุการณ์ให้ครบถ้วน', 'warning', 'ข้อมูลไม่ครบถ้วน')
    return
  }
  historyLoading.value = true
  try {
    if (editingHistory.value) {
      await api.updateHistory(editingHistory.value.id, historyForm.value)
      showToast('อัปเดตข้อมูลประวัติคณะเรียบร้อยแล้ว', 'success', 'บันทึกสำเร็จ')
    } else {
      await api.createHistory(historyForm.value)
      showToast('เพิ่มประวัติคณะเรียบร้อยแล้ว', 'success', 'เพิ่มข้อมูลสำเร็จ')
    }
    showHistoryDialog.value = false
    await loadHistory()
  } catch (err) {
    console.error('Failed to save history:', err)
    showToast('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error')
  } finally {
    historyLoading.value = false
  }
}

const deleteHistory = (id: number) => {
  openDeleteConfirm(
    'ลบประวัติคณะ',
    'คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลประวัตินี้? ข้อมูลจะถูกลบออกจากระบบอย่างถาวร',
    async () => {
      try {
        await api.deleteHistory(id)
        showToast('ลบข้อมูลประวัติคณะเรียบร้อยแล้ว', 'success', 'ลบสำเร็จ')
        await loadHistory()
      } catch (err) {
        console.error('Failed to delete history:', err)
        showToast('เกิดข้อผิดพลาดในการลบข้อมูล', 'error')
      }
    }
  )
}

const sortedHistory = computed(() =>
  [...historyItems.value].sort((a, b) => parseInt(a.year) - parseInt(b.year))
)

// ─── 2. ปรัชญาวิสัยทัศน์ ──────────────────────────────────────────────────────
const philosophyData = ref({
  philosophy: '',
  philosophy_detail: '',
  vision: '',
  identity: '',
})
const missions = ref<Mission[]>([])
const philosophyFetching = ref(false)
const philosophySaving = ref(false)
const newMissionText = ref('')

const loadPhilosophy = async () => {
  philosophyFetching.value = true
  try {
    const data = await api.getPhilosophy()
    philosophyData.value = {
      philosophy: data.philosophy || '',
      philosophy_detail: data.philosophy_detail || '',
      vision: data.vision || '',
      identity: data.identity || '',
    }
    missions.value = data.missions || []
  } catch (err) {
    console.error('Failed to load philosophy:', err)
    showToast('ไม่สามารถโหลดข้อมูลปรัชญาวิสัยทัศน์ได้', 'error')
  } finally {
    philosophyFetching.value = false
  }
}

const savePhilosophy = async () => {
  philosophySaving.value = true
  try {
    await api.updatePhilosophy({
      ...philosophyData.value,
      missions: missions.value.map((m, idx) => ({ text: m.text, sort_order: idx + 1 })),
    })
    showToast('บันทึกปรัชญา วิสัยทัศน์ และอัตลักษณ์เรียบร้อยแล้ว', 'success', 'บันทึกสำเร็จ')
    await loadPhilosophy()
  } catch (err) {
    console.error('Failed to save philosophy:', err)
    showToast('เกิดข้อผิดพลาดในการบันทึก', 'error')
  } finally {
    philosophySaving.value = false
  }
}

const addMission = async () => {
  if (!newMissionText.value.trim()) return
  try {
    await api.createMission({
      text: newMissionText.value.trim(),
      sort_order: missions.value.length + 1,
    })
    newMissionText.value = ''
    showToast('เพิ่มพันธกิจเรียบร้อยแล้ว', 'success', 'เพิ่มสำเร็จ')
    await loadPhilosophy()
  } catch (err) {
    console.error('Failed to add mission:', err)
    showToast('ไม่สามารถเพิ่มพันธกิจได้', 'error')
  }
}

const deleteMission = (id?: number) => {
  if (!id) return
  openDeleteConfirm(
    'ลบพันธกิจ',
    'คุณต้องการลบข้อพันธกิจนี้ใช่หรือไม่?',
    async () => {
      try {
        await api.deleteMission(id)
        showToast('ลบพันธกิจเรียบร้อยแล้ว', 'success', 'ลบสำเร็จ')
        await loadPhilosophy()
      } catch (err) {
        console.error('Failed to delete mission:', err)
        showToast('ไม่สามารถลบพันธกิจได้', 'error')
      }
    }
  )
}

// ─── 3. ITA ───────────────────────────────────────────────────────────────────
const itaYear = ref('2569')
const itaYears = ref<ItaYear[]>([])
const itaYearsFetching = ref(false)
const showManageYearsDialog = ref(false)
const editingYear = ref<ItaYear | null>(null)
const yearForm = ref({ id: 0, year: '', title: '', is_active: true, sort_order: 0 })
const yearLoading = ref(false)

const itaItems = ref<ItaItem[]>([])
const itaFetching = ref(false)
const editingIta = ref<ItaItem | null>(null)
const showItaDialog = ref(false)
const itaForm = ref<{
  id: number
  code: string
  indicator: string
  year: string
  components: string
  links: ItaLink[]
}>({ id: 0, code: '', indicator: '', year: '2569', components: '', links: [] })
const itaLoading = ref(false)
const newItaLink = ref<{ title: string; url: string; type: 'internal' | 'external' | 'pdf' }>({
  title: '',
  url: '',
  type: 'internal',
})

const loadItaYears = async () => {
  itaYearsFetching.value = true
  try {
    const data = await api.getItaYears()
    itaYears.value = data
    if (data.length > 0 && !data.some((y) => y.year === itaYear.value)) {
      itaYear.value = data[0].year
    }
  } catch (err) {
    console.error('Failed to load ITA years:', err)
  } finally {
    itaYearsFetching.value = false
  }
}

const openManageYears = () => {
  cancelEditYear()
  showManageYearsDialog.value = true
}

const openEditYear = (y: ItaYear) => {
  editingYear.value = y
  yearForm.value = {
    id: y.id,
    year: y.year,
    title: y.title || '',
    is_active: y.is_active,
    sort_order: y.sort_order ?? 0,
  }
}

const cancelEditYear = () => {
  editingYear.value = null
  yearForm.value = {
    id: 0,
    year: '',
    title: '',
    is_active: true,
    sort_order: itaYears.value.length + 1,
  }
}

const saveYear = async () => {
  const yStr = yearForm.value.year.trim()
  if (!yStr) {
    showToast('กรุณาระบุปี พ.ศ. เช่น 2570', 'warning', 'ข้อมูลไม่ครบถ้วน')
    return
  }
  yearLoading.value = true
  try {
    const payload = {
      year: yStr,
      title: yearForm.value.title.trim() || `ปีงบประมาณ พ.ศ. ${yStr}`,
      is_active: yearForm.value.is_active,
      sort_order: yearForm.value.sort_order,
    }
    if (editingYear.value) {
      await api.updateItaYear(editingYear.value.id, payload)
      showToast('อัปเดตปีประเมิน ITA สำเร็จ', 'success', 'บันทึกสำเร็จ')
      if (itaYear.value === editingYear.value.year) {
        itaYear.value = yStr
      }
    } else {
      await api.createItaYear(payload)
      showToast('เพิ่มปีประเมิน ITA เรียบร้อยแล้ว', 'success', 'เพิ่มปีสำเร็จ')
      itaYear.value = yStr
    }
    cancelEditYear()
    await loadItaYears()
    await loadIta()
  } catch (err: any) {
    console.error('Failed to save ITA year:', err)
    const errorMsg = err.response?.data?.message || 'เกิดข้อผิดพลาดในการบันทึกปีประเมิน'
    showToast(errorMsg, 'error')
  } finally {
    yearLoading.value = false
  }
}

const deleteYear = (y: ItaYear) => {
  const count = y.items_count || 0
  const desc =
    count > 0
      ? `ปี ${y.year} มีตัวชี้วัดอยู่ ${count} รายการ หากยืนยันการลบ ข้อมูลตัวชี้วัดทั้งหมดในปีนี้จะถูกลบไปด้วยอย่างถาวร!`
      : `คุณต้องการลบปีประเมิน ${y.year} ใช่หรือไม่?`

  openDeleteConfirm(`ลบปีประเมิน ${y.year}`, desc, async () => {
    try {
      await api.deleteItaYear(y.id, true)
      showToast(`ลบปีประเมิน ${y.year} เรียบร้อยแล้ว`, 'success', 'ลบสำเร็จ')
      await loadItaYears()
      if (itaYear.value === y.year) {
        if (itaYears.value.length > 0) {
          itaYear.value = itaYears.value[0].year
        }
      }
      await loadIta()
    } catch (err: any) {
      console.error('Failed to delete year:', err)
      const errorMsg = err.response?.data?.message || 'เกิดข้อผิดพลาดในการลบปี'
      showToast(errorMsg, 'error')
    }
  })
}

const loadIta = async () => {
  itaFetching.value = true
  try {
    const data = await api.getIta({ year: itaYear.value })
    itaItems.value = data
  } catch (err) {
    console.error('Failed to load ITA items:', err)
    showToast('ไม่สามารถโหลดข้อมูล ITA ได้', 'error')
  } finally {
    itaFetching.value = false
  }
}

// ─── ITA Drag & Drop Reordering ──────────────────────────────────────────────
const draggedItaIndex = ref<number | null>(null)
const dragOverItaIndex = ref<number | null>(null)
const canDragIta = ref(false)
const isSavingItaOrder = ref(false)

const onItaDragStart = (index: number, e: DragEvent) => {
  draggedItaIndex.value = index
  if (e.dataTransfer) {
    e.dataTransfer.effectAllowed = 'move'
    e.dataTransfer.setData('text/plain', String(index))
  }
}

const onItaDragOver = (index: number, e: DragEvent) => {
  e.preventDefault()
  if (draggedItaIndex.value === null || draggedItaIndex.value === index) return
  dragOverItaIndex.value = index
}

const onItaDragLeave = (index: number) => {
  if (dragOverItaIndex.value === index) {
    dragOverItaIndex.value = null
  }
}

const onItaDrop = async (dropIndex: number, e: DragEvent) => {
  e.preventDefault()
  if (draggedItaIndex.value === null || draggedItaIndex.value === dropIndex) {
    onItaDragEnd()
    return
  }

  const fromIndex = draggedItaIndex.value
  const toIndex = dropIndex

  // Reorder array in local state
  const items = [...itaItems.value]
  const [movedItem] = items.splice(fromIndex, 1)
  items.splice(toIndex, 0, movedItem)
  itaItems.value = items

  onItaDragEnd()

  // Save new order to backend
  isSavingItaOrder.value = true
  try {
    const ids = itaItems.value.map((item) => item.id)
    await api.reorderIta(ids)
    showToast('จัดลำดับตัวชี้วัดสำเร็จ', 'success')
  } catch (err) {
    console.error('Failed to reorder ITA items:', err)
    showToast('เกิดข้อผิดพลาดในการบันทึกลำดับ', 'error')
    await loadIta() // Revert to server state on error
  } finally {
    isSavingItaOrder.value = false
  }
}

const onItaDragEnd = () => {
  draggedItaIndex.value = null
  dragOverItaIndex.value = null
  canDragIta.value = false
}

const moveItaItem = async (index: number, direction: -1 | 1) => {
  const targetIndex = index + direction
  if (targetIndex < 0 || targetIndex >= itaItems.value.length) return

  const items = [...itaItems.value]
  const [movedItem] = items.splice(index, 1)
  items.splice(targetIndex, 0, movedItem)
  itaItems.value = items

  isSavingItaOrder.value = true
  try {
    const ids = itaItems.value.map((item) => item.id)
    await api.reorderIta(ids)
    showToast('จัดลำดับตัวชี้วัดสำเร็จ', 'success')
  } catch (err) {
    console.error('Failed to reorder ITA items:', err)
    showToast('เกิดข้อผิดพลาดในการบันทึกลำดับ', 'error')
    await loadIta()
  } finally {
    isSavingItaOrder.value = false
  }
}

const openCreateIta = () => {
  itaForm.value = {
    id: 0,
    code: `O${itaItems.value.length + 1}`,
    indicator: '',
    year: itaYear.value,
    components: '',
    links: [],
  }
  newItaLink.value = { title: '', url: '', type: 'internal' }
  editingIta.value = null
  showItaDialog.value = true
}

const openEditIta = (item: ItaItem) => {
  let compHtml = ''
  if (typeof item.components === 'string') {
    compHtml = item.components
  } else if (Array.isArray(item.components)) {
    compHtml = item.components
      .map(
        (c) =>
          `<p><strong>${c.text}</strong></p>` +
          (c.subnotes?.length
            ? `<ul>${c.subnotes.map((s) => `<li>${s}</li>`).join('')}</ul>`
            : '')
      )
      .join('')
  }

  itaForm.value = {
    id: item.id,
    code: item.code,
    indicator: item.indicator,
    year: item.year || itaYear.value,
    components: compHtml,
    links: item.links ? item.links.map((l) => ({ ...l })) : [],
  }
  newItaLink.value = { title: '', url: '', type: 'internal' }
  editingIta.value = item
  showItaDialog.value = true
}

const uploadItaFileLoading = ref(false)

const handleItaFileUpload = async (e: Event) => {
  const target = e.target as HTMLInputElement
  if (!target.files?.length) return
  uploadItaFileLoading.value = true
  try {
    for (let i = 0; i < target.files.length; i++) {
      const file = target.files[i]
      const res = await api.uploadFile(file)
      const ext = (res.extension || file.name.split('.').pop() || '').toLowerCase()
      const isPdf = ext === 'pdf'
      itaForm.value.links.push({
        id: Date.now() + i,
        title: res.name || file.name,
        url: res.url,
        type: isPdf ? 'pdf' : (['jpg', 'jpeg', 'png', 'webp'].includes(ext) ? 'internal' : 'pdf'),
      })
    }
    showToast(`อัปโหลดไฟล์เอกสาร ${target.files.length} รายการสำเร็จ`, 'success')
  } catch (err) {
    console.error('ITA file upload error:', err)
    showToast('เกิดข้อผิดพลาดในการอัปโหลดไฟล์', 'error')
  } finally {
    uploadItaFileLoading.value = false
    target.value = ''
  }
}

const addItaLink = () => {
  if (!newItaLink.value.title || !newItaLink.value.url) {
    showToast('กรุณากรอกชื่อลิงก์และ URL ให้ครบถ้วน', 'warning', 'ข้อมูลลิงก์ไม่ครบ')
    return
  }
  itaForm.value.links.push({ ...newItaLink.value, id: Date.now() })
  newItaLink.value = { title: '', url: '', type: 'internal' }
}

const removeItaLink = (idx: number) => {
  itaForm.value.links.splice(idx, 1)
}

const saveIta = async () => {
  if (!itaForm.value.code || !itaForm.value.indicator) {
    showToast('กรุณากรอกรหัสตัวชี้วัดและชื่อตัวชี้วัด', 'warning', 'ข้อมูลไม่ครบถ้วน')
    return
  }
  itaLoading.value = true
  try {
    const payload = {
      code: itaForm.value.code,
      indicator: itaForm.value.indicator,
      year: itaForm.value.year || itaYear.value,
      links: itaForm.value.links,
      components: itaForm.value.components,
    }
    if (editingIta.value) {
      await api.updateIta(editingIta.value.id, payload)
      showToast('อัปเดตตัวชี้วัด ITA เรียบร้อยแล้ว', 'success', 'บันทึกสำเร็จ')
    } else {
      await api.createIta(payload)
      showToast('เพิ่มตัวชี้วัด ITA เรียบร้อยแล้ว', 'success', 'เพิ่มข้อมูลสำเร็จ')
    }
    showItaDialog.value = false
    await loadIta()
    await loadItaYears()
  } catch (err) {
    console.error('Failed to save ITA:', err)
    showToast('เกิดข้อผิดพลาดในการบันทึก ITA', 'error')
  } finally {
    itaLoading.value = false
  }
}

const deleteIta = (id: number) => {
  openDeleteConfirm(
    'ลบตัวชี้วัด ITA',
    'คุณแน่ใจหรือไม่ว่าต้องการลบตัวชี้วัดนี้พร้อมลิงก์ที่เกี่ยวข้องทั้งหมด?',
    async () => {
      try {
        await api.deleteIta(id)
        showToast('ลบตัวชี้วัด ITA เรียบร้อยแล้ว', 'success', 'ลบสำเร็จ')
        await loadIta()
        await loadItaYears()
      } catch (err) {
        console.error('Failed to delete ITA:', err)
        showToast('เกิดข้อผิดพลาดในการลบ ITA', 'error')
      }
    }
  )
}

const linkTypeLabel = (type?: string) =>
  ({ internal: 'ภายใน', external: 'ภายนอก', pdf: 'PDF' }[type || 'internal'] || type)
const linkTypeColor = (type?: string) =>
  ({
    internal: 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
    external: 'bg-sky-50 text-sky-700 border border-sky-200/60',
    pdf: 'bg-rose-50 text-rose-700 border border-rose-200/60',
  }[type || 'internal'] || 'bg-slate-100 text-slate-600')

// ─── 4. ข้อกฎหมาย ─────────────────────────────────────────────────────────────
const lawCategories = ref<RegulationCategory[]>([])
const lawCategoriesFetching = ref(false)
const showManageLawCategoriesDialog = ref(false)
const editingLawCategory = ref<RegulationCategory | null>(null)
const lawCategoryLoading = ref(false)

const lawCategoryColorPresets = [
  { label: 'สีม่วง (Violet)', color: 'bg-violet-100 text-violet-700', badge_class: 'bg-purple-50 text-purple-700 border-purple-200', dot: 'bg-purple-500' },
  { label: 'สีฟ้า (Sky)', color: 'bg-sky-100 text-sky-700', badge_class: 'bg-blue-50 text-blue-700 border-blue-200', dot: 'bg-sky-500' },
  { label: 'สีส้ม/ทอง (Amber)', color: 'bg-amber-100 text-amber-700', badge_class: 'bg-amber-50 text-amber-800 border-amber-200', dot: 'bg-amber-500' },
  { label: 'สีแดง/ชมพู (Rose)', color: 'bg-rose-100 text-rose-700', badge_class: 'bg-rose-50 text-rose-700 border-rose-200', dot: 'bg-rose-500' },
  { label: 'สีเขียว (Emerald)', color: 'bg-emerald-100 text-emerald-800', badge_class: 'bg-emerald-50 text-emerald-800 border-emerald-200', dot: 'bg-emerald-500' },
  { label: 'สีคราม (Indigo)', color: 'bg-indigo-100 text-indigo-700', badge_class: 'bg-indigo-50 text-indigo-700 border-indigo-200', dot: 'bg-indigo-500' },
  { label: 'สีเทา (Slate)', color: 'bg-slate-100 text-slate-700', badge_class: 'bg-slate-100 text-slate-700 border-slate-200', dot: 'bg-slate-500' },
]

const lawCategoryIconPresets = [
  'mdi-scale-balance',
  'mdi-file-document-outline',
  'mdi-clipboard-text-outline',
  'mdi-bullhorn-outline',
  'mdi-gavel',
  'mdi-book-open-outline',
  'mdi-shield-check-outline',
  'mdi-certificate-outline',
  'mdi-school-outline',
  'mdi-briefcase-outline',
]

const lawCategoryForm = ref({
  id: 0,
  key: '',
  name: '',
  short_name: '',
  description: '',
  icon: 'mdi-file-document-outline',
  color: 'bg-sky-100 text-sky-700',
  badge_class: 'bg-blue-50 text-blue-700 border-blue-200',
  sort_order: 1,
  is_active: true,
})

const loadLawCategories = async () => {
  lawCategoriesFetching.value = true
  try {
    const data = await api.getRegulationCategories()
    lawCategories.value = data
  } catch (err) {
    console.error('Failed to load regulation categories:', err)
  } finally {
    lawCategoriesFetching.value = false
  }
}

const openManageLawCategories = () => {
  cancelEditLawCategory()
  showManageLawCategoriesDialog.value = true
}

const openEditLawCategory = (cat: RegulationCategory) => {
  editingLawCategory.value = cat
  lawCategoryForm.value = {
    id: cat.id,
    key: cat.key,
    name: cat.name,
    short_name: cat.short_name || cat.name,
    description: cat.description || '',
    icon: cat.icon || 'mdi-file-document-outline',
    color: cat.color || 'bg-sky-100 text-sky-700',
    badge_class: cat.badge_class || 'bg-blue-50 text-blue-700 border-blue-200',
    sort_order: cat.sort_order ?? 0,
    is_active: cat.is_active ?? true,
  }
}

const cancelEditLawCategory = () => {
  editingLawCategory.value = null
  lawCategoryForm.value = {
    id: 0,
    key: '',
    name: '',
    short_name: '',
    description: '',
    icon: 'mdi-file-document-outline',
    color: 'bg-sky-100 text-sky-700',
    badge_class: 'bg-blue-50 text-blue-700 border-blue-200',
    sort_order: lawCategories.value.length + 1,
    is_active: true,
  }
}

const selectCategoryPreset = (preset: (typeof lawCategoryColorPresets)[0]) => {
  lawCategoryForm.value.color = preset.color
  lawCategoryForm.value.badge_class = preset.badge_class
}

const saveLawCategory = async () => {
  const name = lawCategoryForm.value.name.trim()
  if (!name) {
    showToast('กรุณากรอกชื่อหมวดหมู่', 'warning', 'ข้อมูลไม่ครบถ้วน')
    return
  }

  lawCategoryLoading.value = true
  try {
    const payload: Partial<RegulationCategory> = {
      name,
      short_name: lawCategoryForm.value.short_name.trim() || name,
      key: lawCategoryForm.value.key.trim() || undefined,
      description: lawCategoryForm.value.description.trim() || undefined,
      icon: lawCategoryForm.value.icon || 'mdi-file-document-outline',
      color: lawCategoryForm.value.color || 'bg-sky-100 text-sky-700',
      badge_class: lawCategoryForm.value.badge_class || 'bg-blue-50 text-blue-700 border-blue-200',
      sort_order: Number(lawCategoryForm.value.sort_order) || 1,
      is_active: lawCategoryForm.value.is_active,
    }

    if (editingLawCategory.value) {
      await api.updateRegulationCategory(editingLawCategory.value.id, payload)
      showToast('อัปเดตหมวดหมู่ข้อกฎหมายสำเร็จ', 'success', 'บันทึกสำเร็จ')
    } else {
      await api.createRegulationCategory(payload)
      showToast('เพิ่มหมวดหมู่ข้อกฎหมายเรียบร้อยแล้ว', 'success', 'เพิ่มหมวดหมู่สำเร็จ')
    }

    cancelEditLawCategory()
    await loadLawCategories()
    await loadLaws()
  } catch (err: any) {
    console.error('Failed to save regulation category:', err)
    const errorMsg = err.response?.data?.message || 'เกิดข้อผิดพลาดในการบันทึกหมวดหมู่'
    showToast(errorMsg, 'error')
  } finally {
    lawCategoryLoading.value = false
  }
}

const deleteLawCategory = (cat: RegulationCategory) => {
  const count = cat.items_count || 0
  const desc =
    count > 0
      ? `หมวดหมู่ "${cat.name}" มีข้อกฎหมายอยู่ ${count} รายการ หากยืนยันการลบ ข้อมูลข้อกฎหมายทั้งหมดในหมวดหมู่นี้จะถูกลบไปด้วยอย่างถาวร!`
      : `คุณต้องการลบหมวดหมู่ "${cat.name}" ใช่หรือไม่?`

  openDeleteConfirm(`ลบหมวดหมู่ ${cat.name}`, desc, async () => {
    try {
      await api.deleteRegulationCategory(cat.id, true)
      showToast(`ลบหมวดหมู่ "${cat.name}" เรียบร้อยแล้ว`, 'success', 'ลบสำเร็จ')
      await loadLawCategories()
      if (lawCatFilter.value === cat.key) {
        lawCatFilter.value = 'all'
      }
      await loadLaws()
    } catch (err: any) {
      console.error('Failed to delete category:', err)
      const errorMsg = err.response?.data?.message || 'เกิดข้อผิดพลาดในการลบหมวดหมู่'
      showToast(errorMsg, 'error')
    }
  })
}

const lawItems = ref<RegulationItem[]>([])
const lawFetching = ref(false)
const lawSearch = ref('')
const lawCatFilter = ref('all')
const editingLaw = ref<RegulationItem | null>(null)
const showLawDialog = ref(false)
const lawForm = ref({
  id: 0,
  title: '',
  category: 'act',
  year: '',
  effective_date: '',
  file_size: '',
  file_url: '',
  description: '',
})
const lawLoading = ref(false)

const loadLaws = async () => {
  lawFetching.value = true
  try {
    const data = await api.getRegulations()
    lawItems.value = data
  } catch (err) {
    console.error('Failed to load regulations:', err)
    showToast('ไม่สามารถโหลดข้อกฎหมายได้', 'error')
  } finally {
    lawFetching.value = false
  }
}

const filteredLaws = computed(() =>
  lawItems.value.filter(
    (l) =>
      (lawCatFilter.value === 'all' || l.category === lawCatFilter.value) &&
      (!lawSearch.value ||
        l.title.toLowerCase().includes(lawSearch.value.toLowerCase()) ||
        (l.description && l.description.toLowerCase().includes(lawSearch.value.toLowerCase())))
  )
)

const openCreateLaw = () => {
  lawForm.value = {
    id: 0,
    title: '',
    category: lawCategories.value[0]?.key || 'act',
    year: '2567',
    effective_date: '',
    file_size: '',
    file_url: '',
    description: '',
  }
  editingLaw.value = null
  showLawDialog.value = true
}

const openEditLaw = (item: RegulationItem) => {
  lawForm.value = {
    id: item.id,
    title: item.title,
    category: item.category,
    year: item.year || '',
    effective_date: item.effective_date || '',
    file_size: item.file_size || '',
    file_url: item.file_url || '',
    description: item.description || '',
  }
  editingLaw.value = item
  showLawDialog.value = true
}

const uploadLawFileLoading = ref(false)

const handleLawFileUpload = async (e: Event) => {
  const target = e.target as HTMLInputElement
  if (!target.files?.length) return
  uploadLawFileLoading.value = true
  try {
    const file = target.files[0]
    const res = await api.uploadFile(file)
    lawForm.value.file_url = res.url
    lawForm.value.file_size =
      res.file_size ||
      (file.size > 1024 * 1024
        ? (file.size / (1024 * 1024)).toFixed(1) + ' MB'
        : (file.size / 1024).toFixed(0) + ' KB')
    if (!lawForm.value.title.trim()) {
      lawForm.value.title = file.name.replace(/\.[^/.]+$/, '')
    }
    showToast(`อัปโหลดไฟล์ ${file.name} เรียบร้อยแล้ว`, 'success')
  } catch (err) {
    console.error('Law file upload error:', err)
    showToast('เกิดข้อผิดพลาดในการอัปโหลดไฟล์', 'error')
  } finally {
    uploadLawFileLoading.value = false
    target.value = ''
  }
}

const saveLaw = async () => {
  if (!lawForm.value.title) {
    showToast('กรุณากรอกชื่อกฎหมายหรือระเบียบ', 'warning', 'ข้อมูลไม่ครบถ้วน')
    return
  }
  lawLoading.value = true
  try {
    if (editingLaw.value) {
      await api.updateRegulation(editingLaw.value.id, lawForm.value)
      showToast('อัปเดตข้อกฎหมายเรียบร้อยแล้ว', 'success', 'บันทึกสำเร็จ')
    } else {
      await api.createRegulation(lawForm.value)
      showToast('เพิ่มข้อกฎหมายเรียบร้อยแล้ว', 'success', 'เพิ่มข้อมูลสำเร็จ')
    }
    showLawDialog.value = false
    await loadLaws()
  } catch (err) {
    console.error('Failed to save regulation:', err)
    showToast('เกิดข้อผิดพลาดในการบันทึกข้อกฎหมาย', 'error')
  } finally {
    lawLoading.value = false
  }
}

const deleteLaw = (id: number) => {
  openDeleteConfirm(
    'ลบข้อกฎหมาย',
    'คุณต้องการลบข้อกฎหมาย/ระเบียบนี้ใช่หรือไม่? ข้อมูลจะถูกลบออกจากฐานข้อมูลทันที',
    async () => {
      try {
        await api.deleteRegulation(id)
        showToast('ลบข้อกฎหมายเรียบร้อยแล้ว', 'success', 'ลบสำเร็จ')
        await loadLaws()
      } catch (err) {
        console.error('Failed to delete regulation:', err)
        showToast('เกิดข้อผิดพลาดในการลบข้อกฎหมาย', 'error')
      }
    }
  )
}

const getCat = (catKey: string) => {
  const found = lawCategories.value.find((c) => c.key === catKey || c.id?.toString() === catKey)
  if (found) {
    return {
      id: found.id,
      key: found.key,
      name: found.name,
      short_name: found.short_name || found.name,
      icon: found.icon || 'mdi-file-document-outline',
      color: found.color || 'bg-sky-100 text-sky-700',
      badge_class: found.badge_class || 'bg-blue-50 text-blue-700 border-blue-200',
    }
  }
  return {
    id: 0,
    key: catKey,
    name: catKey,
    short_name: catKey,
    icon: 'mdi-file-document-outline',
    color: 'bg-slate-100 text-slate-700',
    badge_class: 'bg-slate-100 text-slate-700 border-slate-200',
  }
}

// ─── 5. คณะกรรมการประจำคณะครุศาสตร์ ──────────────────────────────────────────
const committeeMembers = ref<CommitteeMember[]>([])
const committeeFetching = ref(false)
const committeeSearch = ref('')
const committeeFilter = ref('all') // 'all', 'active', 'inactive'
const editingCommittee = ref<CommitteeMember | null>(null)
const showCommitteeDialog = ref(false)
const committeeLoading = ref(false)

const committeeForm = ref({
  id: 0,
  name: '',
  position: '',
  sort_order: 1,
  is_active: true,
})

const positionPresets = [
  'คณบดี',
  'รองคณบดี',
  'ผู้ทรงคุณวุฒิภายนอก',
  'ผู้แทนประธานสาขาวิชา',
  'ผู้แทนคณาจารย์',
  'เลขานุการ',
  'ผู้ช่วยเลขานุการ',
]

const filteredCommitteeMembers = computed(() => {
  let list = [...committeeMembers.value]
  if (committeeFilter.value === 'active') {
    list = list.filter((m) => m.is_active)
  } else if (committeeFilter.value === 'inactive') {
    list = list.filter((m) => !m.is_active)
  }
  if (committeeSearch.value.trim()) {
    const q = committeeSearch.value.toLowerCase().trim()
    list = list.filter(
      (m) => m.name.toLowerCase().includes(q) || m.position.toLowerCase().includes(q)
    )
  }
  return list
})

const loadCommitteeMembers = async () => {
  committeeFetching.value = true
  try {
    const data = await api.getCommitteeMembers()
    committeeMembers.value = data
  } catch (err) {
    console.error('Failed to load committee members:', err)
    showToast('ไม่สามารถโหลดข้อมูลคณะกรรมการได้', 'error')
  } finally {
    committeeFetching.value = false
  }
}

const openCreateCommittee = () => {
  editingCommittee.value = null
  const maxOrder = committeeMembers.value.reduce((max, m) => Math.max(max, m.sort_order || 0), 0)
  committeeForm.value = {
    id: 0,
    name: '',
    position: '',
    sort_order: maxOrder + 1,
    is_active: true,
  }
  showCommitteeDialog.value = true
}

const openEditCommittee = (member: CommitteeMember) => {
  editingCommittee.value = member
  committeeForm.value = {
    id: member.id,
    name: member.name,
    position: member.position,
    sort_order: member.sort_order,
    is_active: member.is_active,
  }
  showCommitteeDialog.value = true
}

const saveCommitteeMember = async () => {
  if (!committeeForm.value.name.trim()) {
    showToast('กรุณากรอกชื่อ - นามสกุล', 'warning', 'ข้อมูลไม่ครบถ้วน')
    return
  }
  if (!committeeForm.value.position.trim()) {
    showToast('กรุณากรอกตำแหน่งในคณะกรรมการ', 'warning', 'ข้อมูลไม่ครบถ้วน')
    return
  }

  committeeLoading.value = true
  try {
    if (editingCommittee.value) {
      await api.updateCommitteeMember(editingCommittee.value.id, {
        name: committeeForm.value.name.trim(),
        position: committeeForm.value.position.trim(),
        sort_order: Number(committeeForm.value.sort_order) || 1,
        is_active: committeeForm.value.is_active,
      })
      showToast('อัปเดตข้อมูลกรรมการเรียบร้อยแล้ว', 'success', 'บันทึกสำเร็จ')
    } else {
      await api.createCommitteeMember({
        name: committeeForm.value.name.trim(),
        position: committeeForm.value.position.trim(),
        sort_order: Number(committeeForm.value.sort_order) || (committeeMembers.value.length + 1),
        is_active: committeeForm.value.is_active,
      })
      showToast('เพิ่มรายชื่อกรรมการเรียบร้อยแล้ว', 'success', 'เพิ่มสำเร็จ')
    }
    showCommitteeDialog.value = false
    await loadCommitteeMembers()
  } catch (err) {
    console.error('Failed to save committee member:', err)
    showToast('เกิดข้อผิดพลาดในการบันทึกข้อมูลกรรมการ', 'error')
  } finally {
    committeeLoading.value = false
  }
}

const toggleCommitteeActive = async (member: CommitteeMember) => {
  try {
    await api.updateCommitteeMember(member.id, { is_active: !member.is_active })
    member.is_active = !member.is_active
    showToast(`${member.is_active ? 'เปิด' : 'ปิด'}การแสดงผล ${member.name} เรียบร้อย`, 'success')
  } catch (err) {
    console.error('Failed to toggle committee status:', err)
    showToast('ไม่สามารถเปลี่ยนสถานะกรรมการได้', 'error')
  }
}

const deleteCommitteeMember = (member: CommitteeMember) => {
  openDeleteConfirm(
    'ลบรายชื่อกรรมการ',
    `คุณต้องการลบ "${member.name}" (${member.position}) ใช่หรือไม่? ข้อมูลจะถูกลบออกจากฐานข้อมูลทันที`,
    async () => {
      try {
        await api.deleteCommitteeMember(member.id)
        showToast('ลบรายชื่อกรรมการเรียบร้อยแล้ว', 'success', 'ลบสำเร็จ')
        await loadCommitteeMembers()
      } catch (err) {
        console.error('Failed to delete committee member:', err)
        showToast('เกิดข้อผิดพลาดในการลบข้อมูลกรรมการ', 'error')
      }
    }
  )
}

const moveCommitteeOrder = async (index: number, direction: 'up' | 'down') => {
  const targetIndex = direction === 'up' ? index - 1 : index + 1
  if (targetIndex < 0 || targetIndex >= committeeMembers.value.length) return

  const currentList = [...committeeMembers.value]
  const temp = currentList[index]
  currentList[index] = currentList[targetIndex]
  currentList[targetIndex] = temp

  const orders = currentList.map((item, idx) => ({
    id: item.id,
    sort_order: idx + 1,
  }))

  try {
    await api.reorderCommitteeMembers(orders)
    await loadCommitteeMembers()
    showToast('ปรับเปลี่ยนลำดับกรรมการเรียบร้อย', 'success')
  } catch (err) {
    console.error('Failed to reorder committee members:', err)
    showToast('เกิดข้อผิดพลาดในการจัดลำดับ', 'error')
  }
}

// ─── Initial Load & Tab Watch ──────────────────────────────────────────────────
onMounted(async () => {
  loadCarouselSlides()
  loadCommitteeMembers()
  loadHistory()
  loadPhilosophy()
  await loadItaYears()
  loadIta()
  await loadLawCategories()
  loadLaws()
})

watch(itaYear, () => {
  loadIta()
})
</script>

<template>
  <div class="space-y-6 pb-16">
    <!-- Page Header -->
    <div class="mb-6">
      <div class="flex items-center gap-1.5 text-xs text-slate-400 mb-1">
        <v-icon icon="mdi-home-outline" size="13" />
        <span>/</span><span>Admin</span><span>/</span>
        <span class="text-slate-700 font-semibold">จัดการข้อมูลและนโยบาย</span>
      </div>
      <h1 class="text-xl font-black text-slate-900">จัดการข้อมูลและนโยบาย</h1>
      <p class="text-xs text-slate-500 mt-0.5">
        จัดการเนื้อหาหน้าเว็บ ประวัติคณะ ปรัชญา ITA และกฎหมาย (เชื่อมต่อฐานข้อมูล)
      </p>
    </div>

    <!-- Tab Navigation -->
    <div class="flex gap-1.5 mb-6 bg-slate-100/90 p-1.5 rounded-2xl border border-slate-200/60 shadow-2xs">
      <button
        v-for="tab in tabs"
        :key="tab.id"
        type="button"
        class="flex items-center gap-2 flex-1 justify-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer"
        :class="
          activeTab === tab.id
            ? 'bg-white text-emerald-700 shadow-xs ring-1 ring-slate-900/5'
            : 'text-slate-500 hover:text-slate-800 hover:bg-slate-200/50'
        "
        @click="activeTab = tab.id"
      >
        <v-icon :icon="tab.icon" size="16" />
        <span class="hidden sm:inline">{{ tab.label }}</span>
      </button>
    </div>

    <!-- ══ TAB 0: ภาพสไลด์แบนเนอร์ (Carousel) ═══════════════════════════════ -->
    <div v-if="activeTab === 'carousel'">
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <!-- Tab Toolbar Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <v-icon icon="mdi-view-carousel-outline" size="18" />
            </div>
            <div>
              <h2 class="text-sm font-bold text-slate-800">ภาพสไลด์แบนเนอร์หน้าแรก (Hero Carousel)</h2>
              <p class="text-[11px] text-slate-400">
                {{ carouselSlides.length }} ภาพสไลด์ทั้งหมด · ขนาดที่แนะนำ 1920 × 800 พิกเซล
              </p>
            </div>
          </div>
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
            @click="openCreateSlide"
          >
            <v-icon icon="mdi-plus" size="16" />
            <span>เพิ่มภาพสไลด์</span>
          </button>
        </div>

        <!-- Loading State -->
        <div v-if="carouselFetching" class="py-16 text-center text-slate-400">
          <v-icon icon="mdi-loading" size="32" class="animate-spin text-emerald-600 mb-2" />
          <p class="text-xs font-medium">กำลังโหลดภาพสไลด์แบนเนอร์...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="!carouselSlides.length" class="py-16 text-center text-slate-400">
          <v-icon icon="mdi-image-multiple-outline" size="48" class="text-slate-300 mb-2" />
          <p class="text-sm font-bold text-slate-700">ยังไม่มีภาพสไลด์แบนเนอร์</p>
          <p class="text-xs text-slate-400 mt-1 mb-4">คลิกปุ่มด้านล่างเพื่อเพิ่มภาพสไลด์แบนเนอร์ชุดแรก</p>
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
            @click="openCreateSlide"
          >
            <v-icon icon="mdi-plus" size="16" />
            <span>เพิ่มภาพสไลด์แบนเนอร์</span>
          </button>
        </div>

        <!-- Slides Grid -->
        <div v-else class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div
            v-for="(slide, idx) in carouselSlides"
            :key="slide.id"
            class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs hover:shadow-md transition-all overflow-hidden flex flex-col group"
          >
            <!-- Banner Preview Box with 24:10 aspect ratio -->
            <div class="relative w-full aspect-[24/10] bg-slate-900 overflow-hidden">
              <img
                :src="slide.image_url"
                :alt="slide.alt_text || slide.title || 'Banner Slide'"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-102"
                @error="($event.target as HTMLImageElement).src = 'https://placehold.co/1920x800/e2e8f0/64748b?text=Image+Load+Error'"
              />

              <!-- Overlay Top Bar -->
              <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between gap-2 pointer-events-none">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-slate-900/80 text-white backdrop-blur-md border border-white/20 shadow-xs pointer-events-auto">
                  <v-icon icon="mdi-numeric" size="14" />
                  <span>ลำดับที่ {{ slide.sort_order }}</span>
                </span>

                <button
                  type="button"
                  class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold backdrop-blur-md transition-all shadow-xs cursor-pointer pointer-events-auto border"
                  :class="
                    slide.is_active
                      ? 'bg-emerald-600/90 hover:bg-emerald-600 text-white border-emerald-400/40'
                      : 'bg-slate-800/80 hover:bg-slate-800 text-slate-300 border-white/20'
                  "
                  :title="slide.is_active ? 'คลิกเพื่อปิดใช้งาน' : 'คลิกเพื่อเปิดใช้งาน'"
                  @click="toggleSlideActive(slide)"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="slide.is_active ? 'bg-emerald-300 animate-pulse' : 'bg-slate-400'" />
                  <span>{{ slide.is_active ? 'เปิดแสดงผล' : 'ปิดใช้งาน' }}</span>
                </button>
              </div>
            </div>

            <!-- Slide Meta & Actions -->
            <div class="p-4 flex-1 flex flex-col justify-between gap-3">
              <div>
                <h3 class="text-xs font-bold text-slate-900 line-clamp-1">
                  {{ slide.title || 'ไม่มีชื่อสไลด์' }}
                </h3>
                <p v-if="slide.alt_text" class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                  คำอธิบาย: {{ slide.alt_text }}
                </p>
                <div v-if="slide.link_url" class="flex items-center gap-1.5 mt-1.5 text-[11px] text-emerald-700 font-mono truncate">
                  <v-icon icon="mdi-link-variant" size="13" class="shrink-0" />
                  <a :href="slide.link_url" target="_blank" class="truncate hover:underline text-emerald-700">
                    {{ slide.link_url }}
                  </a>
                  <span v-if="slide.target === '_blank'" class="text-[9px] px-1 bg-slate-100 text-slate-500 rounded border">
                    new tab
                  </span>
                </div>
              </div>

              <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between gap-2">
                <!-- Move Reorder Buttons -->
                <div class="flex items-center gap-1">
                  <button
                    type="button"
                    :disabled="idx === 0"
                    class="p-1.5 rounded-lg border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100 disabled:opacity-30 disabled:pointer-events-none cursor-pointer transition-all"
                    title="เลื่อนขึ้น"
                    @click="moveSlideOrder(idx, 'up')"
                  >
                    <v-icon icon="mdi-arrow-up" size="15" />
                  </button>
                  <button
                    type="button"
                    :disabled="idx === carouselSlides.length - 1"
                    class="p-1.5 rounded-lg border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100 disabled:opacity-30 disabled:pointer-events-none cursor-pointer transition-all"
                    title="เลื่อนลง"
                    @click="moveSlideOrder(idx, 'down')"
                  >
                    <v-icon icon="mdi-arrow-down" size="15" />
                  </button>
                </div>

                <!-- Edit and Delete Actions -->
                <div class="flex items-center gap-1.5">
                  <button
                    type="button"
                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl border border-slate-200 hover:border-emerald-300 text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 text-xs font-bold transition-all cursor-pointer"
                    @click="openEditSlide(slide)"
                  >
                    <v-icon icon="mdi-pencil-outline" size="14" />
                    <span>แก้ไข</span>
                  </button>
                  <button
                    type="button"
                    class="p-1.5 rounded-xl border border-slate-200 hover:border-rose-300 text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all cursor-pointer"
                    title="ลบสไลด์"
                    @click="deleteSlide(slide)"
                  >
                    <v-icon icon="mdi-trash-can-outline" size="15" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ══ TAB: คณะกรรมการคณะครุศาสตร์ ═══════════════════════════════════════ -->
    <div v-if="activeTab === 'committee'">
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <!-- Tab Toolbar Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <v-icon icon="mdi-account-group-outline" size="18" />
            </div>
            <div>
              <h2 class="text-sm font-bold text-slate-800">คณะกรรมการประจำคณะครุศาสตร์</h2>
              <p class="text-[11px] text-slate-400">
                {{ committeeMembers.length }} ท่านในระบบ · จัดการรายชื่อ ลำดับ และตำแหน่งในคณะกรรมการ
              </p>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <button
              type="button"
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
              @click="openCreateCommittee"
            >
              <v-icon icon="mdi-plus" size="16" />
              <span>เพิ่มรายชื่อกรรมการ</span>
            </button>
          </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="px-6 py-3 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-center justify-between gap-3">
          <div class="flex flex-wrap items-center gap-2 flex-1">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
              <v-icon icon="mdi-magnify" size="16" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
              <input
                v-model="committeeSearch"
                type="text"
                placeholder="ค้นหาชื่อ-นามสกุล หรือ ตำแหน่ง..."
                class="w-full pl-9 pr-3.5 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 transition-all shadow-2xs"
              />
            </div>

            <div class="relative">
              <select
                v-model="committeeFilter"
                class="px-3.5 py-2 pr-8 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 cursor-pointer appearance-none shadow-2xs"
              >
                <option value="all">สถานะทั้งหมด</option>
                <option value="active">เปิดใช้งาน (แสดงผล)</option>
                <option value="inactive">ซ่อนการแสดงผล</option>
              </select>
              <v-icon icon="mdi-chevron-down" size="15" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
            </div>
          </div>

          <div class="text-[11px] text-slate-400 font-medium">
            แสดง {{ filteredCommitteeMembers.length }} จาก {{ committeeMembers.length }} ท่าน
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="committeeFetching" class="py-16 text-center text-slate-400">
          <v-icon icon="mdi-loading" size="32" class="animate-spin text-emerald-600 mb-2" />
          <p class="text-xs font-medium">กำลังโหลดรายชื่อคณะกรรมการ...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="!committeeMembers.length" class="py-16 text-center text-slate-400">
          <v-icon icon="mdi-account-group-outline" size="48" class="text-slate-300 mb-2" />
          <p class="text-sm font-bold text-slate-700">ยังไม่มีรายชื่อคณะกรรมการ</p>
          <p class="text-xs text-slate-400 mt-1 mb-4">คลิกปุ่มด้านล่างเพื่อเพิ่มรายชื่อกรรมการท่านแรก</p>
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
            @click="openCreateCommittee"
          >
            <v-icon icon="mdi-plus" size="16" />
            <span>เพิ่มกรรมการท่านแรก</span>
          </button>
        </div>

        <!-- Table View: รูปแบบตารางเหมือนเดิมตามที่ผู้ใช้ร้องขอ -->
        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-800 text-xs">
                <th scope="col" class="py-3.5 px-4 font-bold text-center w-28">
                  ลำดับ
                </th>
                <th scope="col" class="py-3.5 px-4 font-bold">
                  ชื่อ - นามสกุล
                </th>
                <th scope="col" class="py-3.5 px-4 font-bold">
                  ตำแหน่งในคณะกรรมการ
                </th>
                <th scope="col" class="py-3.5 px-4 font-bold text-center w-28">
                  สถานะ
                </th>
                <th scope="col" class="py-3.5 px-4 font-bold text-right w-36">
                  การจัดการ
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
              <tr
                v-for="(member, idx) in filteredCommitteeMembers"
                :key="member.id"
                class="hover:bg-slate-50/80 transition-colors group"
                :class="!member.is_active ? 'opacity-60 bg-slate-50/40' : ''"
              >
                <!-- ลำดับที่ + ปุ่มสลับขึ้นลง -->
                <td class="py-3.5 px-4 text-center">
                  <div class="flex items-center justify-center gap-1.5">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-700 font-bold text-xs">
                      {{ member.sort_order }}
                    </span>
                    <div class="inline-flex flex-col gap-0.5 opacity-60 group-hover:opacity-100 transition-opacity">
                      <button
                        type="button"
                        :disabled="idx === 0"
                        class="p-0.5 rounded text-slate-400 hover:text-slate-800 hover:bg-slate-200/70 disabled:opacity-20 disabled:pointer-events-none cursor-pointer transition-all"
                        title="เลื่อนขึ้น"
                        @click="moveCommitteeOrder(idx, 'up')"
                      >
                        <v-icon icon="mdi-chevron-up" size="14" />
                      </button>
                      <button
                        type="button"
                        :disabled="idx === filteredCommitteeMembers.length - 1"
                        class="p-0.5 rounded text-slate-400 hover:text-slate-800 hover:bg-slate-200/70 disabled:opacity-20 disabled:pointer-events-none cursor-pointer transition-all"
                        title="เลื่อนลง"
                        @click="moveCommitteeOrder(idx, 'down')"
                      >
                        <v-icon icon="mdi-chevron-down" size="14" />
                      </button>
                    </div>
                  </div>
                </td>

                <!-- ชื่อ - นามสกุล -->
                <td class="py-3.5 px-4">
                  <div class="font-bold text-slate-900 text-[13px]">
                    {{ member.name }}
                  </div>
                </td>

                <!-- ตำแหน่ง -->
                <td class="py-3.5 px-4">
                  <span
                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold"
                    :class="
                      member.position.includes('คณบดี') && !member.position.includes('รอง')
                        ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/60 font-bold'
                        : member.position.includes('รองคณบดี')
                        ? 'bg-teal-50 text-teal-800 border border-teal-200/60'
                        : member.position.includes('ผู้ทรงคุณวุฒิ')
                        ? 'bg-amber-50 text-amber-800 border border-amber-200/60'
                        : member.position.includes('เลขานุการ')
                        ? 'bg-purple-50 text-purple-800 border border-purple-200/60'
                        : 'bg-slate-100 text-slate-800 border border-slate-200/60'
                    "
                  >
                    {{ member.position }}
                  </span>
                </td>

                <!-- สถานะ เปิด/ปิด -->
                <td class="py-3.5 px-4 text-center">
                  <button
                    type="button"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold transition-all cursor-pointer"
                    :class="
                      member.is_active
                        ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100'
                        : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200'
                    "
                    @click="toggleCommitteeActive(member)"
                  >
                    <span
                      class="w-1.5 h-1.5 rounded-full"
                      :class="member.is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"
                    />
                    <span>{{ member.is_active ? 'เปิดแสดง' : 'ซ่อน' }}</span>
                  </button>
                </td>

                <!-- การจัดการ -->
                <td class="py-3.5 px-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button
                      type="button"
                      class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl border border-slate-200 hover:border-emerald-300 text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 text-xs font-bold transition-all cursor-pointer"
                      @click="openEditCommittee(member)"
                    >
                      <v-icon icon="mdi-pencil-outline" size="14" />
                      <span>แก้ไข</span>
                    </button>
                    <button
                      type="button"
                      class="p-1.5 rounded-xl border border-slate-200 hover:border-rose-300 text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all cursor-pointer"
                      title="ลบรายชื่อ"
                      @click="deleteCommitteeMember(member)"
                    >
                      <v-icon icon="mdi-trash-can-outline" size="15" />
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="!filteredCommitteeMembers.length">
                <td colspan="5" class="py-12 text-center text-slate-400 text-xs">
                  <v-icon icon="mdi-account-search-outline" size="36" class="mb-1 opacity-50" />
                  <p>ไม่พบรายชื่อกรรมการที่ตรงกับคำค้นหา "{{ committeeSearch }}"</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ══ TAB 1: ประวัติคณะ ══════════════════════════════════════════════════ -->
    <div v-if="activeTab === 'history'">
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <v-icon icon="mdi-book-open-page-variant-outline" size="18" />
            </div>
            <div>
              <h2 class="text-sm font-bold text-slate-800">ไทม์ไลน์ประวัติคณะ</h2>
              <p class="text-[11px] text-slate-400">{{ historyItems.length }} รายการ (จากฐานข้อมูล)</p>
            </div>
          </div>
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
            @click="openCreateHistory"
          >
            <v-icon icon="mdi-plus" size="16" />
            <span>เพิ่มเหตุการณ์</span>
          </button>
        </div>

        <div v-if="historyFetching" class="py-16 text-center text-slate-400">
          <v-icon icon="mdi-loading" size="32" class="animate-spin text-emerald-600 mb-2" />
          <p class="text-xs font-medium">กำลังโหลดข้อมูลประวัติคณะ...</p>
        </div>

        <div v-else class="divide-y divide-slate-100">
          <div
            v-for="item in sortedHistory"
            :key="item.id"
            class="flex items-start gap-4 px-6 py-4 hover:bg-slate-50/60 transition-colors group"
          >
            <div class="shrink-0 w-16 pt-0.5 text-center">
              <span class="inline-block px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-black">
                พ.ศ. {{ item.year }}
              </span>
            </div>
            <div class="w-px self-stretch bg-emerald-100 shrink-0" />
            <div class="flex-1 min-w-0">
              <div class="text-sm font-bold text-slate-800">{{ item.title }}</div>
              <div class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ item.detail }}</div>
            </div>
            <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
              <button
                type="button"
                class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                title="แก้ไข"
                @click="openEditHistory(item)"
              >
                <v-icon icon="mdi-pencil-outline" size="15" />
              </button>
              <button
                type="button"
                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                title="ลบ"
                @click="deleteHistory(item.id)"
              >
                <v-icon icon="mdi-trash-can-outline" size="15" />
              </button>
            </div>
          </div>
          <div v-if="!historyItems.length" class="py-16 text-center text-slate-400">
            <v-icon icon="mdi-timeline-outline" size="40" class="mb-2 opacity-40" />
            <p class="text-sm">ยังไม่มีข้อมูลประวัติ</p>
          </div>
        </div>
      </div>
    </div>

    <!-- ══ TAB 2: ปรัชญาวิสัยทัศน์ ═══════════════════════════════════════════ -->
    <div v-if="activeTab === 'philosophy'" class="space-y-6">
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <v-icon icon="mdi-lightbulb-outline" size="18" />
            </div>
            <div>
              <h2 class="text-sm font-bold text-slate-800">ปรัชญา วิสัยทัศน์ และอัตลักษณ์</h2>
              <p class="text-[11px] text-slate-400">ข้อมูลหลักสำหรับการแสดงผลบนหน้าเว็บและเอกสารแนะนำคณะ</p>
            </div>
          </div>
          <button
            type="button"
            :disabled="philosophySaving"
            class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-all disabled:opacity-50 cursor-pointer"
            @click="savePhilosophy"
          >
            <v-icon
              :icon="philosophySaving ? 'mdi-loading' : 'mdi-content-save-check-outline'"
              size="16"
              :class="philosophySaving ? 'animate-spin' : ''"
            />
            <span>{{ philosophySaving ? 'กำลังบันทึก...' : 'บันทึกการเปลี่ยนแปลง' }}</span>
          </button>
        </div>

        <div class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">ปรัชญา (Philosophy)</label>
            <input
              v-model="philosophyData.philosophy"
              type="text"
              class="w-full px-3.5 py-2.5 text-sm font-bold text-slate-900 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs"
            />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">รายละเอียดปรัชญา</label>
            <textarea
              v-model="philosophyData.philosophy_detail"
              rows="3"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all leading-relaxed resize-y shadow-2xs"
            />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">วิสัยทัศน์ (Vision)</label>
            <textarea
              v-model="philosophyData.vision"
              rows="3"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all leading-relaxed resize-y shadow-2xs"
            />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">อัตลักษณ์ (Identity)</label>
            <input
              v-model="philosophyData.identity"
              type="text"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs"
            />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <v-icon icon="mdi-flag-outline" size="18" />
            </div>
            <div>
              <h2 class="text-sm font-bold text-slate-800">พันธกิจ (Missions)</h2>
              <p class="text-[11px] text-slate-400">รายการพันธกิจ 5 ประการของคณะ</p>
            </div>
          </div>
          <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
            {{ missions.length }} ข้อ
          </span>
        </div>
        <div class="p-6 space-y-3">
          <div v-for="(mission, idx) in missions" :key="mission.id" class="flex items-center gap-3 group">
            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold shrink-0">
              {{ idx + 1 }}
            </div>
            <input
              v-model="mission.text"
              class="flex-1 text-xs text-slate-800 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-emerald-600 focus:bg-white transition-all shadow-2xs"
            />
            <button
              type="button"
              class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer opacity-0 group-hover:opacity-100"
              title="ลบข้อนี้"
              @click="deleteMission(mission.id)"
            >
              <v-icon icon="mdi-trash-can-outline" size="15" />
            </button>
          </div>
          <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
            <input
              v-model="newMissionText"
              type="text"
              placeholder="พิมพ์พันธกิจใหม่แล้วกดเพิ่ม หรือ Enter..."
              class="flex-1 px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs"
              @keydown.enter.prevent="addMission"
            />
            <button
              type="button"
              class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shrink-0 transition-all shadow-xs cursor-pointer"
              @click="addMission"
            >
              <v-icon icon="mdi-plus" size="16" />
              <span>เพิ่มพันธกิจ</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ══ TAB 3: ITA ══════════════════════════════════════════════════════════ -->
    <div v-if="activeTab === 'ita'">
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
          <div class="flex flex-wrap items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <v-icon icon="mdi-shield-check-outline" size="18" />
            </div>
            <div>
              <div class="flex items-center gap-2">
                <h2 class="text-sm font-bold text-slate-800">ตัวชี้วัด ITA (OIT)</h2>
                <span
                  v-if="isSavingItaOrder"
                  class="inline-flex items-center gap-1 text-[11px] text-emerald-600 font-semibold animate-pulse"
                >
                  <v-icon icon="mdi-loading" size="12" class="animate-spin" />
                  กำลังบันทึกลำดับ...
                </span>
              </div>
              <p class="text-[11px] text-slate-400 flex items-center gap-1.5">
                <span>{{ itaItems.length }} ตัวชี้วัด ประจำปี {{ itaYear }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-emerald-700/90 font-medium flex items-center gap-0.5">
                  <v-icon icon="mdi-drag-vertical" size="13" />
                  ลากเพื่อจัดลำดับ
                </span>
              </p>
            </div>
            <div class="flex items-center gap-2 ml-2">
              <div class="relative min-w-[160px] sm:min-w-[190px]">
                <select
                  v-model="itaYear"
                  class="w-full appearance-none bg-white border border-slate-300 hover:border-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 text-xs font-bold rounded-xl px-3 py-1.5 pr-8 cursor-pointer transition-all shadow-2xs"
                >
                  <option
                    v-for="yr in itaYears"
                    :key="yr.id"
                    :value="yr.year"
                  >
                    ปี พ.ศ. {{ yr.year }}{{ yr.items_count !== undefined ? ` (${yr.items_count} ตัวชี้วัด)` : '' }}
                  </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                  <v-icon icon="mdi-chevron-down" size="16" />
                </div>
              </div>

              <button
                type="button"
                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:text-emerald-700 hover:border-emerald-300 transition-all cursor-pointer shadow-2xs shrink-0"
                title="จัดการปีการประเมิน ITA (เพิ่ม ลบ แก้ไข)"
                @click="openManageYears"
              >
                <v-icon icon="mdi-calendar-edit-outline" size="14" class="text-emerald-600" />
                <span>จัดการปี</span>
              </button>
            </div>
          </div>
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
            @click="openCreateIta"
          >
            <v-icon icon="mdi-plus" size="16" />
            <span>เพิ่มตัวชี้วัด</span>
          </button>
        </div>

        <div v-if="itaFetching" class="py-16 text-center text-slate-400">
          <v-icon icon="mdi-loading" size="32" class="animate-spin text-emerald-600 mb-2" />
          <p class="text-xs">กำลังโหลดตัวชี้วัด ITA...</p>
        </div>

        <div v-else class="divide-y divide-slate-100">
          <div
            v-for="(item, index) in itaItems"
            :key="item.id"
            class="group transition-all duration-150 relative"
            :class="[
              draggedItaIndex === index ? 'opacity-35 bg-emerald-50/60 ring-2 ring-dashed ring-emerald-400 rounded-xl my-0.5 shadow-inner' : '',
              dragOverItaIndex === index && draggedItaIndex !== index ? 'bg-emerald-50/70 ring-2 ring-emerald-500 ring-inset rounded-xl transition-all' : '',
            ]"
            :draggable="canDragIta"
            @dragstart="onItaDragStart(index, $event)"
            @dragover="onItaDragOver(index, $event)"
            @dragleave="onItaDragLeave(index)"
            @drop="onItaDrop(index, $event)"
            @dragend="onItaDragEnd"
          >
            <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-slate-50/60 transition-colors">
              <!-- Drag Handle -->
              <div
                class="cursor-grab active:cursor-grabbing p-1.5 -ml-2 text-slate-300 group-hover:text-slate-500 hover:text-emerald-700 hover:bg-emerald-50/80 rounded-lg transition-colors flex items-center justify-center shrink-0 select-none"
                title="กดค้างแล้วลากเพื่อสลับลำดับ"
                @mousedown="canDragIta = true"
                @mouseup="canDragIta = false"
                @mouseleave="!isSavingItaOrder && draggedItaIndex === null && (canDragIta = false)"
              >
                <v-icon icon="mdi-drag-vertical" size="20" />
              </div>

              <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200/60 text-emerald-800 flex items-center justify-center text-xs font-black shrink-0 select-none">
                {{ item.code }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-bold text-slate-800">{{ item.indicator }}</div>
                <div
                  v-if="item.components"
                  class="text-[11px] text-slate-500 line-clamp-1 mt-0.5"
                >
                  <span class="font-bold text-slate-400">องค์ประกอบ: </span>
                  <span>{{ typeof item.components === 'string' ? item.components.replace(/<[^>]*>/g, ' ').trim() : '' }}</span>
                </div>
                <div class="text-[11px] text-slate-400 mt-0.5">{{ item.links?.length || 0 }} ลิงก์/เอกสารแนบ</div>
              </div>
              <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                <!-- Move Up / Move Down buttons -->
                <button
                  type="button"
                  :disabled="index === 0"
                  class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 disabled:opacity-20 disabled:hover:bg-transparent disabled:cursor-not-allowed rounded-lg transition-colors cursor-pointer"
                  title="เลื่อนขึ้น"
                  @click.stop="moveItaItem(index, -1)"
                >
                  <v-icon icon="mdi-arrow-up" size="15" />
                </button>
                <button
                  type="button"
                  :disabled="index === itaItems.length - 1"
                  class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 disabled:opacity-20 disabled:hover:bg-transparent disabled:cursor-not-allowed rounded-lg transition-colors cursor-pointer"
                  title="เลื่อนลง"
                  @click.stop="moveItaItem(index, 1)"
                >
                  <v-icon icon="mdi-arrow-down" size="15" />
                </button>

                <!-- Edit -->
                <button
                  type="button"
                  class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                  title="แก้ไข"
                  @click="openEditIta(item)"
                >
                  <v-icon icon="mdi-pencil-outline" size="15" />
                </button>

                <!-- Delete -->
                <button
                  type="button"
                  class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                  title="ลบ"
                  @click="deleteIta(item.id)"
                >
                  <v-icon icon="mdi-trash-can-outline" size="15" />
                </button>
              </div>
            </div>
            <div v-if="item.links?.length" class="px-6 pb-3 pl-16 flex flex-wrap gap-1.5">
              <span
                v-for="(link, lIdx) in item.links"
                :key="lIdx"
                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold"
                :class="linkTypeColor(link.type)"
              >
                <v-icon :icon="link.type === 'pdf' ? 'mdi-file-pdf-box' : 'mdi-link'" size="11" />
                {{ link.title }}
              </span>
            </div>
          </div>
          <div v-if="!itaItems.length" class="py-16 text-center text-slate-400">
            <v-icon icon="mdi-shield-outline" size="40" class="mb-2 opacity-40" />
            <p class="text-sm">ยังไม่มีตัวชี้วัด ITA สำหรับปี {{ itaYear }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- ══ TAB 4: ข้อกฎหมาย ══════════════════════════════════════════════════ -->
    <div v-if="activeTab === 'regulations'">
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 space-y-3">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                <v-icon icon="mdi-scale-balance" size="18" />
              </div>
              <div>
                <h2 class="text-sm font-bold text-slate-800">ข้อกฎหมาย กฎเกณฑ์ และประกาศ</h2>
                <p class="text-[11px] text-slate-400">{{ lawItems.length }} รายการทั้งหมด (จากฐานข้อมูล)</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button
                type="button"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer border border-slate-200 shadow-2xs"
                @click="openManageLawCategories"
              >
                <v-icon icon="mdi-folder-cog-outline" size="16" class="text-slate-600" />
                <span>จัดการหมวดหมู่ ({{ lawCategories.length }})</span>
              </button>
              <button
                type="button"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
                @click="openCreateLaw"
              >
                <v-icon icon="mdi-plus" size="16" />
                <span>เพิ่มข้อกฎหมาย</span>
              </button>
            </div>
          </div>
          <div class="flex flex-wrap gap-2">
            <input
              v-model="lawSearch"
              type="text"
              placeholder="ค้นหาชื่อกฎหมาย..."
              class="flex-1 min-w-[180px] px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 transition-all shadow-2xs"
            />
            <div class="relative">
              <select
                v-model="lawCatFilter"
                class="px-3.5 py-2 pr-8 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 cursor-pointer appearance-none shadow-2xs"
              >
                <option value="all">ทุกหมวดหมู่</option>
                <option v-for="c in lawCategories" :key="c.id" :value="c.key">{{ c.name }}</option>
              </select>
              <v-icon icon="mdi-chevron-down" size="15" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
            </div>
          </div>
        </div>

        <div v-if="lawFetching" class="py-16 text-center text-slate-400">
          <v-icon icon="mdi-loading" size="32" class="animate-spin text-emerald-600 mb-2" />
          <p class="text-xs">กำลังโหลดข้อกฎหมาย...</p>
        </div>

        <div v-else class="divide-y divide-slate-100">
          <div
            v-for="item in filteredLaws"
            :key="item.id"
            class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/60 transition-colors group"
          >
            <div
              class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
              :class="getCat(item.category).color"
            >
              <v-icon :icon="getCat(item.category).icon" size="18" />
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-sm font-bold text-slate-800 truncate">{{ item.title }}</div>
              <div class="flex items-center gap-2 mt-0.5">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" :class="getCat(item.category).color">
                  {{ getCat(item.category).name }}
                </span>
                <span v-if="item.year" class="text-[11px] text-slate-400">พ.ศ. {{ item.year }}</span>
                <span v-if="item.effective_date" class="text-[11px] text-slate-400">· บังคับใช้: {{ item.effective_date }}</span>
                <span v-if="item.file_size" class="text-[11px] text-slate-400">· {{ item.file_size }}</span>
              </div>
            </div>
            <a
              v-if="item.file_url && item.file_url !== '#'"
              :href="item.file_url"
              target="_blank"
              class="p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors no-underline"
              title="ดาวน์โหลด"
            >
              <v-icon icon="mdi-download-outline" size="16" />
            </a>
            <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
              <button
                type="button"
                class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                title="แก้ไข"
                @click="openEditLaw(item)"
              >
                <v-icon icon="mdi-pencil-outline" size="15" />
              </button>
              <button
                type="button"
                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                title="ลบ"
                @click="deleteLaw(item.id)"
              >
                <v-icon icon="mdi-trash-can-outline" size="15" />
              </button>
            </div>
          </div>
          <div v-if="!filteredLaws.length" class="py-16 text-center text-slate-400">
            <v-icon icon="mdi-file-search-outline" size="40" class="mb-2 opacity-40" />
            <p class="text-sm">ไม่พบข้อมูลที่ค้นหา</p>
          </div>
        </div>
      </div>
    </div>

    <!-- ══ DIALOG: ประวัติคณะ ════════════════════════════════════════════════ -->
    <v-dialog v-model="showHistoryDialog" max-width="520" persistent>
      <v-card class="!rounded-3xl p-0 bg-white overflow-hidden shadow-2xl border border-slate-100">
        <!-- Dialog Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs">
              <v-icon icon="mdi-book-open-page-variant-outline" size="20" />
            </div>
            <div>
              <h3 class="text-sm font-bold text-slate-900 leading-tight">
                {{ editingHistory ? 'แก้ไขประวัติคณะ' : 'เพิ่มประวัติคณะ' }}
              </h3>
              <p class="text-[11px] text-slate-500 mt-0.5">ระบุปี พ.ศ. และเหตุการณ์สำคัญของคณะ</p>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors cursor-pointer"
            @click="showHistoryDialog = false"
          >
            <v-icon icon="mdi-close" size="18" />
          </button>
        </div>

        <!-- Dialog Body -->
        <div class="px-6 py-5 space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              ปีพุทธศักราช (พ.ศ.) <span class="text-rose-500">*</span>
            </label>
            <input
              v-model="historyForm.year"
              type="text"
              placeholder="เช่น 2483"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs font-semibold"
            />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              หัวข้อ / ชื่อเหตุการณ์ <span class="text-rose-500">*</span>
            </label>
            <input
              v-model="historyForm.title"
              type="text"
              placeholder="เช่น ก่อตั้งโรงเรียนฝึกหัดครูประกาศนียบัตรจังหวัด"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs font-medium"
            />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">รายละเอียดเหตุการณ์</label>
            <textarea
              v-model="historyForm.detail"
              rows="3"
              placeholder="กรอกรายละเอียด หรือความเป็นมาของเหตุการณ์..."
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all resize-y shadow-2xs leading-relaxed"
            />
          </div>
        </div>

        <!-- Dialog Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2.5">
          <button
            type="button"
            class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs cursor-pointer transition-all shadow-2xs"
            @click="showHistoryDialog = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            :disabled="historyLoading"
            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs disabled:opacity-50 cursor-pointer transition-all shadow-xs"
            @click="saveHistory"
          >
            <v-icon
              :icon="historyLoading ? 'mdi-loading' : 'mdi-content-save-check-outline'"
              size="15"
              :class="historyLoading ? 'animate-spin' : ''"
            />
            <span>{{ historyLoading ? 'กำลังบันทึก...' : 'บันทึกข้อมูล' }}</span>
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══ DIALOG: ITA ════════════════════════════════════════════════════════ -->
    <v-dialog v-model="showItaDialog" max-width="720" persistent>
      <v-card class="!rounded-3xl p-0 bg-white overflow-hidden shadow-2xl border border-slate-100">
        <!-- Dialog Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs">
              <v-icon icon="mdi-shield-check-outline" size="20" />
            </div>
            <div>
              <h3 class="text-sm font-bold text-slate-900 leading-tight">
                {{ editingIta ? 'แก้ไขตัวชี้วัด ITA' : 'เพิ่มตัวชี้วัด ITA' }}
              </h3>
              <p class="text-[11px] text-slate-500 mt-0.5">ระบุปีประเมิน, รหัส OIT, ตัวชี้วัด, องค์ประกอบด้านข้อมูล และลิงก์อ้างอิง</p>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors cursor-pointer"
            @click="showItaDialog = false"
          >
            <v-icon icon="mdi-close" size="18" />
          </button>
        </div>

        <!-- Dialog Body -->
        <div class="px-6 py-5 space-y-4 max-h-[75vh] overflow-y-auto">
          <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">
                ปีประเมิน <span class="text-rose-500">*</span>
              </label>
              <div class="relative">
                <select
                  v-model="itaForm.year"
                  class="w-full px-3 py-2 text-xs font-bold text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 appearance-none pr-7 shadow-2xs cursor-pointer"
                >
                  <option v-for="y in itaYears" :key="y.id" :value="y.year">
                    พ.ศ. {{ y.year }}
                  </option>
                </select>
                <v-icon
                  icon="mdi-chevron-down"
                  size="16"
                  class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"
                />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">
                รหัส (Code) <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="itaForm.code"
                type="text"
                placeholder="เช่น O1"
                class="w-full px-3.5 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs font-bold"
              />
            </div>

            <div class="sm:col-span-2">
              <label class="block text-xs font-bold text-slate-700 mb-1.5">
                ตัวชี้วัด / ประเด็นคำถาม <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="itaForm.indicator"
                type="text"
                placeholder="เช่น โครงสร้างองค์กรและอำนาจหน้าที่"
                class="w-full px-3.5 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs font-medium"
              />
            </div>
          </div>

          <!-- องค์ประกอบด้านข้อมูล (RichText) -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-xs font-bold text-slate-700">
                องค์ประกอบด้านข้อมูล (Data Components)
              </label>
              <span class="text-[11px] text-slate-400">รองรับข้อความย่อหน้า หัวข้อ รายการจุด (Bullet) และตัวหนา</span>
            </div>
            <RichTextEditor
              v-model="itaForm.components"
              placeholder="ระบุองค์ประกอบด้านข้อมูลที่ต้องเปิดเผย เช่น แผนผังโครงสร้างการแบ่งส่วนราชการ, ตำแหน่งที่สำคัญ..."
              min-height="min-h-[160px]"
            />
          </div>

          <!-- ลิงก์และเอกสารอ้างอิง -->
          <div>
            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
              <label class="block text-xs font-bold text-slate-700">
                ลิงก์และเอกสารอ้างอิง ({{ itaForm.links.length }})
              </label>
              <label
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-200/80 cursor-pointer transition-all shadow-2xs"
                :class="uploadItaFileLoading ? 'opacity-60 pointer-events-none' : ''"
              >
                <v-icon
                  :icon="uploadItaFileLoading ? 'mdi-loading' : 'mdi-cloud-upload-outline'"
                  size="15"
                  :class="uploadItaFileLoading ? 'animate-spin' : 'text-emerald-700'"
                />
                <span>{{ uploadItaFileLoading ? 'กำลังอัปโหลด...' : 'อัปโหลดไฟล์เอกสาร (PDF/DOC)' }}</span>
                <input
                  type="file"
                  multiple
                  accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,image/*"
                  class="hidden"
                  @change="handleItaFileUpload"
                />
              </label>
            </div>

            <div class="space-y-2 mb-3">
              <div
                v-for="(link, idx) in itaForm.links"
                :key="idx"
                class="flex items-center gap-2 p-2.5 bg-slate-50 border border-slate-200/80 rounded-xl"
              >
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="linkTypeColor(link.type)">
                  {{ linkTypeLabel(link.type) }}
                </span>
                <span class="flex-1 text-xs text-slate-700 truncate font-medium">{{ link.title }}</span>
                <a
                  v-if="link.url && link.url !== '#'"
                  :href="link.url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="p-1 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                  title="เปิดดูไฟล์/ลิงก์"
                >
                  <v-icon icon="mdi-open-in-new" size="14" />
                </a>
                <button
                  type="button"
                  class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                  title="ลบลิงก์"
                  @click="removeItaLink(idx)"
                >
                  <v-icon icon="mdi-close" size="14" />
                </button>
              </div>
              <div v-if="!itaForm.links.length" class="text-xs text-slate-400 italic py-2 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                ยังไม่มีลิงก์หรือไฟล์แนบ (สามารถคลิก "อัปโหลดไฟล์เอกสาร" ด้านบน หรือระบุลิงก์ด้านล่าง)
              </div>
            </div>

            <div class="p-3.5 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-2.5">
              <div class="text-[11px] font-bold text-slate-700">เพิ่มลิงก์อ้างอิงใหม่</div>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                <div class="relative">
                  <select
                    v-model="newItaLink.type"
                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 cursor-pointer appearance-none pr-7 shadow-2xs"
                  >
                    <option value="internal">ภายใน</option>
                    <option value="external">ภายนอก</option>
                    <option value="pdf">PDF</option>
                  </select>
                  <v-icon icon="mdi-chevron-down" size="15" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
                </div>
                <input
                  v-model="newItaLink.title"
                  type="text"
                  placeholder="ชื่อลิงก์..."
                  class="px-3 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 transition-all shadow-2xs"
                />
                <input
                  v-model="newItaLink.url"
                  type="text"
                  placeholder="URL หรือลิงก์..."
                  class="px-3 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 transition-all shadow-2xs"
                />
              </div>
              <button
                type="button"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold cursor-pointer transition-all shadow-2xs"
                @click="addItaLink"
              >
                <v-icon icon="mdi-plus" size="14" />
                <span>เพิ่มลิงก์นี้</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Dialog Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2.5">
          <button
            type="button"
            class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs cursor-pointer transition-all shadow-2xs"
            @click="showItaDialog = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            :disabled="itaLoading"
            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs disabled:opacity-50 cursor-pointer transition-all shadow-xs"
            @click="saveIta"
          >
            <v-icon
              :icon="itaLoading ? 'mdi-loading' : 'mdi-content-save-check-outline'"
              size="15"
              :class="itaLoading ? 'animate-spin' : ''"
            />
            <span>{{ itaLoading ? 'กำลังบันทึก...' : 'บันทึกตัวชี้วัด' }}</span>
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══ DIALOG: จัดการปีประเมิน ITA ═════════════════════════════════════════ -->
    <v-dialog v-model="showManageYearsDialog" max-width="640" persistent>
      <v-card class="!rounded-3xl p-0 bg-white overflow-hidden shadow-2xl border border-slate-100">
        <!-- Dialog Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs">
              <v-icon icon="mdi-calendar-edit-outline" size="20" />
            </div>
            <div>
              <h3 class="text-sm font-bold text-slate-900 leading-tight">จัดการปีการประเมิน ITA</h3>
              <p class="text-[11px] text-slate-500 mt-0.5">เพิ่ม ลบ หรือแก้ไขปีงบประมาณสำหรับการประเมินคุณธรรมและความโปร่งใส</p>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors cursor-pointer"
            @click="showManageYearsDialog = false"
          >
            <v-icon icon="mdi-close" size="18" />
          </button>
        </div>

        <!-- Dialog Body -->
        <div class="px-6 py-5 space-y-5 max-h-[75vh] overflow-y-auto">
          <!-- Form: Add or Edit Year -->
          <div class="p-4 rounded-2xl border transition-all" :class="editingYear ? 'bg-amber-50/50 border-amber-200' : 'bg-slate-50/70 border-slate-200/80'">
            <div class="flex items-center justify-between mb-3">
              <span class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                <v-icon :icon="editingYear ? 'mdi-pencil-outline' : 'mdi-plus-circle-outline'" size="16" :class="editingYear ? 'text-amber-600' : 'text-emerald-600'" />
                <span>{{ editingYear ? `แก้ไขปีประเมิน: พ.ศ. ${editingYear.year}` : 'เพิ่มปีประเมินใหม่' }}</span>
              </span>
              <button
                v-if="editingYear"
                type="button"
                class="text-[11px] font-bold text-slate-500 hover:text-slate-700 underline cursor-pointer"
                @click="cancelEditYear"
              >
                ยกเลิกการแก้ไข
              </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-end">
              <div class="sm:col-span-3">
                <label class="block text-[11px] font-bold text-slate-700 mb-1">
                  ปี พ.ศ. <span class="text-rose-500">*</span>
                </label>
                <input
                  v-model="yearForm.year"
                  type="text"
                  placeholder="เช่น 2570"
                  class="w-full px-3 py-2 text-xs font-bold text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 shadow-2xs"
                />
              </div>

              <div class="sm:col-span-6">
                <label class="block text-[11px] font-bold text-slate-700 mb-1">
                  ชื่อกำกับปี
                </label>
                <input
                  v-model="yearForm.title"
                  type="text"
                  placeholder="เช่น ปีงบประมาณ พ.ศ. 2570"
                  class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 shadow-2xs"
                />
              </div>

              <div class="sm:col-span-3">
                <button
                  type="button"
                  :disabled="yearLoading"
                  class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-white text-xs font-bold disabled:opacity-50 cursor-pointer shadow-xs transition-all"
                  :class="editingYear ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700'"
                  @click="saveYear"
                >
                  <v-icon :icon="yearLoading ? 'mdi-loading' : (editingYear ? 'mdi-check' : 'mdi-plus')" size="15" :class="yearLoading ? 'animate-spin' : ''" />
                  <span>{{ yearLoading ? 'กำลังบันทึก...' : (editingYear ? 'อัปเดต' : 'เพิ่มปี') }}</span>
                </button>
              </div>
            </div>

            <div class="mt-2.5 flex items-center gap-2">
              <label class="inline-flex items-center gap-1.5 text-xs text-slate-700 font-medium cursor-pointer">
                <input
                  v-model="yearForm.is_active"
                  type="checkbox"
                  class="w-3.5 h-3.5 rounded text-emerald-600 focus:ring-emerald-500/20 border-slate-300"
                />
                <span>เปิดให้แสดงผลในหน้าเว็บไซต์สาธารณะ</span>
              </label>
            </div>
          </div>

          <!-- Existing Years List -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-xs font-bold text-slate-800">
                รายการปีประเมินทั้งหมด ({{ itaYears.length }} ปี)
              </h4>
              <span class="text-[11px] text-slate-400">คลิกที่ปุ่มดินสอเพื่อแก้ไข หรือถังขยะเพื่อลบ</span>
            </div>

            <div class="border border-slate-200/90 rounded-2xl overflow-hidden divide-y divide-slate-100 bg-white">
              <div
                v-for="y in itaYears"
                :key="y.id"
                class="flex items-center justify-between gap-3 px-4 py-3 hover:bg-slate-50/60 transition-colors"
                :class="editingYear?.id === y.id ? 'bg-amber-50/30' : ''"
              >
                <div class="flex items-center gap-3">
                  <div class="w-12 h-9 rounded-xl bg-emerald-50 border border-emerald-200/60 text-emerald-800 flex items-center justify-center text-xs font-black shrink-0">
                    {{ y.year }}
                  </div>
                  <div>
                    <div class="text-xs font-bold text-slate-800">{{ y.title || `ปีงบประมาณ พ.ศ. ${y.year}` }}</div>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span
                        class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[10px] font-bold"
                        :class="y.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-slate-100 text-slate-500'"
                      >
                        {{ y.is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                      </span>
                      <span class="text-[11px] text-slate-400">
                        {{ y.items_count || 0 }} ตัวชี้วัด
                      </span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-1 shrink-0">
                  <button
                    type="button"
                    class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                    title="แก้ไขปีประเมิน"
                    @click="openEditYear(y)"
                  >
                    <v-icon icon="mdi-pencil-outline" size="15" />
                  </button>
                  <button
                    type="button"
                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                    title="ลบปีประเมิน"
                    @click="deleteYear(y)"
                  >
                    <v-icon icon="mdi-trash-can-outline" size="15" />
                  </button>
                </div>
              </div>

              <div v-if="!itaYears.length" class="py-8 text-center text-slate-400 text-xs">
                ยังไม่มีข้อมูลปีประเมิน
              </div>
            </div>
          </div>
        </div>

        <!-- Dialog Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
          <button
            type="button"
            class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs cursor-pointer transition-all shadow-xs"
            @click="showManageYearsDialog = false"
          >
            เสร็จสิ้น
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══ DIALOG: ข้อกฎหมาย ═════════════════════════════════════════════════ -->
    <v-dialog v-model="showLawDialog" max-width="540" persistent>
      <v-card class="!rounded-3xl p-0 bg-white overflow-hidden shadow-2xl border border-slate-100">
        <!-- Dialog Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs">
              <v-icon icon="mdi-scale-balance" size="20" />
            </div>
            <div>
              <h3 class="text-sm font-bold text-slate-900 leading-tight">
                {{ editingLaw ? 'แก้ไขข้อกฎหมาย' : 'เพิ่มข้อกฎหมาย' }}
              </h3>
              <p class="text-[11px] text-slate-500 mt-0.5">ระบุชื่อกฎหมาย หมวดหมู่ และเอกสารแนบ</p>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors cursor-pointer"
            @click="showLawDialog = false"
          >
            <v-icon icon="mdi-close" size="18" />
          </button>
        </div>

        <!-- Dialog Body -->
        <div class="px-6 py-5 space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              ชื่อกฎหมาย / ชื่อเรื่อง <span class="text-rose-500">*</span>
            </label>
            <input
              v-model="lawForm.title"
              type="text"
              placeholder="เช่น พระราชบัญญัติมหาวิทยาลัยราชภัฏ พ.ศ. 2547"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs font-semibold"
            />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">
                หมวดหมู่ <span class="text-rose-500">*</span>
              </label>
              <div class="relative">
                <select
                  v-model="lawForm.category"
                  class="w-full px-3.5 py-2.5 pr-8 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 cursor-pointer appearance-none shadow-2xs"
                >
                  <option v-for="c in lawCategories" :key="c.id" :value="c.key">{{ c.name }}</option>
                </select>
                <v-icon icon="mdi-chevron-down" size="15" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
              </div>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">ปี พ.ศ.</label>
              <input
                v-model="lawForm.year"
                type="text"
                placeholder="เช่น 2547"
                class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs font-semibold"
              />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">วันที่มีผลบังคับใช้</label>
              <input
                v-model="lawForm.effective_date"
                type="text"
                placeholder="เช่น 15 มิ.ย. 2547"
                class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">ขนาดไฟล์</label>
              <input
                v-model="lawForm.file_size"
                type="text"
                placeholder="เช่น 1.2 MB (คำนวณอัตโนมัติ)"
                class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs"
              />
            </div>
          </div>

          <!-- File Upload Zone for Regulation -->
          <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
            <div class="flex items-center justify-between">
              <label class="block text-xs font-bold text-slate-700">
                ไฟล์เอกสารข้อกฎหมาย (PDF / Word)
              </label>
              <label
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold cursor-pointer transition-all shadow-2xs"
                :class="uploadLawFileLoading ? 'opacity-60 pointer-events-none' : ''"
              >
                <v-icon
                  :icon="uploadLawFileLoading ? 'mdi-loading' : 'mdi-cloud-upload-outline'"
                  size="15"
                  :class="uploadLawFileLoading ? 'animate-spin' : ''"
                />
                <span>{{ uploadLawFileLoading ? 'กำลังอัปโหลด...' : (lawForm.file_url ? 'เปลี่ยนไฟล์' : 'อัปโหลดไฟล์เอกสาร') }}</span>
                <input
                  type="file"
                  accept=".pdf,.doc,.docx,.xls,.xlsx"
                  class="hidden"
                  @change="handleLawFileUpload"
                />
              </label>
            </div>

            <!-- Current attached file display -->
            <div
              v-if="lawForm.file_url"
              class="flex items-center justify-between gap-2 p-2.5 bg-white border border-emerald-200/80 rounded-xl shadow-2xs"
            >
              <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                  <v-icon icon="mdi-file-pdf-box" size="20" class="text-rose-600" />
                </div>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-slate-800 truncate max-w-xs sm:max-w-sm">
                    {{ lawForm.file_url.split('/').pop() }}
                  </div>
                  <div class="text-[11px] text-slate-400">
                    ขนาด: {{ lawForm.file_size || 'ไม่ระบุ' }}
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-1 shrink-0">
                <a
                  :href="lawForm.file_url"
                  target="_blank"
                  class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                  title="เปิดดูไฟล์"
                >
                  <v-icon icon="mdi-open-in-new" size="16" />
                </a>
                <button
                  type="button"
                  class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                  title="นำไฟล์ออก"
                  @click="lawForm.file_url = ''; lawForm.file_size = ''"
                >
                  <v-icon icon="mdi-close" size="16" />
                </button>
              </div>
            </div>
            <div v-else class="text-xs text-slate-400 italic py-1.5 text-center border border-dashed border-slate-200 rounded-xl">
              ยังไม่มีไฟล์แนบ (คลิกปุ่ม "อัปโหลดไฟล์เอกสาร" ด้านบน หรือระบุ URL ด้านล่าง)
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">หรือระบุ URL ลิงก์ดาวน์โหลดไฟล์</label>
            <input
              v-model="lawForm.file_url"
              type="text"
              placeholder="https://... หรือ /storage/uploads/..."
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-2xs font-mono text-[11px]"
            />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">รายละเอียด / สรุปย่อ</label>
            <textarea
              v-model="lawForm.description"
              rows="2"
              placeholder="กรอกสรุปสาระสำคัญของกฎหมาย..."
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all resize-y shadow-2xs leading-relaxed"
            />
          </div>
        </div>

        <!-- Dialog Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2.5">
          <button
            type="button"
            class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs cursor-pointer transition-all shadow-2xs"
            @click="showLawDialog = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            :disabled="lawLoading"
            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs disabled:opacity-50 cursor-pointer transition-all shadow-xs"
            @click="saveLaw"
          >
            <v-icon
              :icon="lawLoading ? 'mdi-loading' : 'mdi-content-save-check-outline'"
              size="15"
              :class="lawLoading ? 'animate-spin' : ''"
            />
            <span>{{ lawLoading ? 'กำลังบันทึก...' : 'บันทึกข้อกฎหมาย' }}</span>
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══ DIALOG: จัดการหมวดหมู่ข้อกฎหมาย (Manage Law Categories) ════════════ -->
    <v-dialog v-model="showManageLawCategoriesDialog" max-width="640" persistent>
      <v-card class="!rounded-3xl p-0 bg-white overflow-hidden shadow-2xl border border-slate-100">
        <!-- Dialog Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center shrink-0 shadow-2xs">
              <v-icon icon="mdi-folder-cog-outline" size="20" />
            </div>
            <div>
              <h3 class="text-sm font-bold text-slate-900 leading-tight">
                จัดการหมวดหมู่ข้อกฎหมาย
              </h3>
              <p class="text-[11px] text-slate-500 mt-0.5">
                กำหนดชื่อหมวดหมู่ ไอคอน สีสัญลักษณ์ และการจัดกลุ่มข้อกฎหมาย
              </p>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors cursor-pointer"
            @click="showManageLawCategoriesDialog = false"
          >
            <v-icon icon="mdi-close" size="18" />
          </button>
        </div>

        <!-- Dialog Body -->
        <div class="px-6 py-5 space-y-5 max-h-[75vh] overflow-y-auto">
          <!-- Add / Edit Form Card -->
          <div
            class="p-4 rounded-2xl border transition-all"
            :class="editingLawCategory ? 'bg-amber-50/40 border-amber-200/80 ring-2 ring-amber-500/20' : 'bg-slate-50/70 border-slate-200/90'"
          >
            <div class="flex items-center justify-between mb-3">
              <div class="flex items-center gap-2">
                <v-icon
                  :icon="editingLawCategory ? 'mdi-pencil-circle-outline' : 'mdi-plus-circle-outline'"
                  size="18"
                  :class="editingLawCategory ? 'text-amber-600' : 'text-emerald-600'"
                />
                <h4 class="text-xs font-bold text-slate-800">
                  {{ editingLawCategory ? `แก้ไขหมวดหมู่: ${editingLawCategory.name}` : 'เพิ่มหมวดหมู่ข้อกฎหมายใหม่' }}
                </h4>
              </div>
              <button
                v-if="editingLawCategory"
                type="button"
                class="text-[11px] text-slate-400 hover:text-slate-600 font-semibold cursor-pointer underline"
                @click="cancelEditLawCategory"
              >
                ยกเลิกการแก้ไข
              </button>
            </div>

            <div class="space-y-3">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- ชื่อหมวดหมู่เต็ม -->
                <div>
                  <label class="block text-[11px] font-bold text-slate-700 mb-1">
                    ชื่อหมวดหมู่ (ชื่อเต็ม) <span class="text-rose-500">*</span>
                  </label>
                  <input
                    v-model="lawCategoryForm.name"
                    type="text"
                    placeholder="เช่น พระราชบัญญัติ (พ.ร.บ.)"
                    class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-violet-600 transition-all font-semibold"
                  />
                </div>

                <!-- ชื่อย่อ -->
                <div>
                  <label class="block text-[11px] font-bold text-slate-700 mb-1">
                    ชื่อย่อ (แสดงบนป้ายแท็ก)
                  </label>
                  <input
                    v-model="lawCategoryForm.short_name"
                    type="text"
                    placeholder="เช่น พระราชบัญญัติ"
                    class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-violet-600 transition-all"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- รหัสอ้างอิง Key / Slug -->
                <div>
                  <label class="block text-[11px] font-bold text-slate-700 mb-1">
                    รหัสอ้างอิง (Slug / Key ภาษาอังกฤษ)
                  </label>
                  <input
                    v-model="lawCategoryForm.key"
                    type="text"
                    placeholder="เช่น act, regulation, rule"
                    class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-violet-600 font-mono text-[11px] transition-all"
                  />
                </div>

                <!-- ลำดับการแสดงผล -->
                <div>
                  <label class="block text-[11px] font-bold text-slate-700 mb-1">
                    ลำดับการแสดงผล (Sort Order)
                  </label>
                  <input
                    v-model.number="lawCategoryForm.sort_order"
                    type="number"
                    min="1"
                    placeholder="1"
                    class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-violet-600 transition-all"
                  />
                </div>
              </div>

              <!-- คำอธิบายย่อ -->
              <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-1">
                  คำอธิบายสรุปสาระของหมวดหมู่นี้
                </label>
                <input
                  v-model="lawCategoryForm.description"
                  type="text"
                  placeholder="เช่น กฎหมายแม่บท พระราชบัญญัติจัดตั้ง และสภาวิชาชีพครู"
                  class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-violet-600 transition-all"
                />
              </div>

              <!-- เลือกโทนสี (Color Theme Presets) -->
              <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-1.5">
                  โทนสีป้ายหมวดหมู่
                </label>
                <div class="flex flex-wrap gap-2 items-center">
                  <button
                    v-for="p in lawCategoryColorPresets"
                    :key="p.label"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-semibold transition-all border cursor-pointer"
                    :class="lawCategoryForm.color === p.color ? 'ring-2 ring-violet-500/30 border-violet-600 scale-105 shadow-2xs' : 'border-slate-200 hover:border-slate-300'"
                    @click="selectCategoryPreset(p)"
                  >
                    <span class="w-2.5 h-2.5 rounded-full shrink-0" :class="p.dot" />
                    <span>{{ p.label }}</span>
                  </button>
                </div>
              </div>

              <!-- เลือกไอคอน (Icon Presets + Input) -->
              <div>
                <div class="flex items-center justify-between mb-1.5">
                  <label class="block text-[11px] font-bold text-slate-700">
                    ไอคอนประจำหมวดหมู่
                  </label>
                  <span class="text-[11px] text-slate-400 font-mono">
                    icon: {{ lawCategoryForm.icon }}
                  </span>
                </div>
                <div class="flex flex-wrap items-center gap-1.5 mb-2">
                  <button
                    v-for="ic in lawCategoryIconPresets"
                    :key="ic"
                    type="button"
                    class="w-8 h-8 rounded-lg flex items-center justify-center border transition-all cursor-pointer"
                    :class="lawCategoryForm.icon === ic ? 'border-violet-600 bg-violet-50 text-violet-700 ring-2 ring-violet-500/20 shadow-2xs' : 'border-slate-200 bg-white text-slate-500 hover:border-slate-300'"
                    @click="lawCategoryForm.icon = ic"
                    :title="ic"
                  >
                    <v-icon :icon="ic" size="18" />
                  </button>
                </div>
                <input
                  v-model="lawCategoryForm.icon"
                  type="text"
                  placeholder="mdi-file-document-outline"
                  class="w-full px-3 py-1.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-violet-600 font-mono text-[11px]"
                />
              </div>

              <!-- Live Preview of Category Badge -->
              <div class="p-2.5 bg-white border border-slate-200/80 rounded-xl flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                  <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ตัวอย่างการแสดงผล:</span>
                  <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border" :class="lawCategoryForm.badge_class">
                    <v-icon :icon="lawCategoryForm.icon" size="14" />
                    <span>{{ lawCategoryForm.short_name || lawCategoryForm.name || 'ตัวอย่างชื่อหมวด' }}</span>
                  </div>
                </div>

                <label class="inline-flex items-center gap-1.5 text-xs text-slate-700 font-medium cursor-pointer">
                  <input
                    v-model="lawCategoryForm.is_active"
                    type="checkbox"
                    class="w-3.5 h-3.5 rounded text-violet-600 focus:ring-violet-500/20 border-slate-300"
                  />
                  <span>เปิดใช้งาน</span>
                </label>
              </div>

              <!-- Submit button -->
              <div class="flex justify-end gap-2 pt-1">
                <button
                  v-if="editingLawCategory"
                  type="button"
                  class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 cursor-pointer"
                  @click="cancelEditLawCategory"
                >
                  ยกเลิก
                </button>
                <button
                  type="button"
                  :disabled="lawCategoryLoading"
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-white font-bold text-xs cursor-pointer transition-all shadow-xs"
                  :class="editingLawCategory ? 'bg-amber-600 hover:bg-amber-700' : 'bg-violet-600 hover:bg-violet-700'"
                  @click="saveLawCategory"
                >
                  <v-icon :icon="lawCategoryLoading ? 'mdi-loading' : (editingLawCategory ? 'mdi-check' : 'mdi-plus')" size="15" :class="lawCategoryLoading ? 'animate-spin' : ''" />
                  <span>{{ lawCategoryLoading ? 'กำลังบันทึก...' : (editingLawCategory ? 'อัปเดตหมวดหมู่' : 'เพิ่มหมวดหมู่') }}</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Existing Categories List -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-xs font-bold text-slate-800">
                หมวดหมู่ข้อกฎหมายทั้งหมด ({{ lawCategories.length }} หมวดหมู่)
              </h4>
              <span class="text-[11px] text-slate-400">คลิกปุ่มดินสอเพื่อแก้ไข หรือถังขยะเพื่อลบ</span>
            </div>

            <div class="border border-slate-200/90 rounded-2xl overflow-hidden divide-y divide-slate-100 bg-white">
              <div
                v-for="cat in lawCategories"
                :key="cat.id"
                class="flex items-center justify-between gap-3 px-4 py-3 hover:bg-slate-50/60 transition-colors"
                :class="editingLawCategory?.id === cat.id ? 'bg-amber-50/40' : ''"
              >
                <div class="flex items-center gap-3 min-w-0">
                  <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border"
                    :class="cat.color || 'bg-slate-100 text-slate-700'"
                  >
                    <v-icon :icon="cat.icon || 'mdi-file-document-outline'" size="18" />
                  </div>
                  <div class="min-w-0">
                    <div class="flex items-center gap-2">
                      <span class="text-xs font-bold text-slate-800 truncate">{{ cat.name }}</span>
                      <span class="text-[10px] font-mono px-1.5 py-0.2 bg-slate-100 text-slate-500 rounded border border-slate-200">
                        {{ cat.key }}
                      </span>
                    </div>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span
                        class="inline-flex items-center px-2 py-0.2 rounded-full text-[10px] font-bold"
                        :class="cat.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-slate-100 text-slate-500'"
                      >
                        {{ cat.is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                      </span>
                      <span class="text-[11px] text-slate-400">
                        {{ cat.items_count || 0 }} รายการ
                      </span>
                      <span v-if="cat.description" class="text-[11px] text-slate-400 truncate max-w-xs hidden sm:inline">
                        · {{ cat.description }}
                      </span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-1 shrink-0">
                  <button
                    type="button"
                    class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors cursor-pointer"
                    title="แก้ไขหมวดหมู่"
                    @click="openEditLawCategory(cat)"
                  >
                    <v-icon icon="mdi-pencil-outline" size="16" />
                  </button>
                  <button
                    type="button"
                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                    title="ลบหมวดหมู่"
                    @click="deleteLawCategory(cat)"
                  >
                    <v-icon icon="mdi-trash-can-outline" size="16" />
                  </button>
                </div>
              </div>

              <div v-if="!lawCategories.length" class="py-8 text-center text-slate-400 text-xs">
                ยังไม่มีข้อมูลหมวดหมู่
              </div>
            </div>
          </div>
        </div>

        <!-- Dialog Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
          <button
            type="button"
            class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs cursor-pointer transition-all shadow-xs"
            @click="showManageLawCategoriesDialog = false"
          >
            เสร็จสิ้น
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══ DIALOG: เพิ่ม/แก้ไขภาพสไลด์แบนเนอร์ (Carousel Slide Modal) ════════════ -->
    <v-dialog v-model="showCarouselDialog" max-width="600" persistent>
      <v-card class="!rounded-3xl p-0 bg-white overflow-hidden shadow-2xl border border-slate-100">
        <!-- Dialog Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs">
              <v-icon icon="mdi-view-carousel-outline" size="20" />
            </div>
            <div>
              <h3 class="text-sm font-bold text-slate-900 leading-tight">
                {{ editingSlide ? 'แก้ไขภาพสไลด์แบนเนอร์' : 'เพิ่มภาพสไลด์แบนเนอร์' }}
              </h3>
              <p class="text-[11px] text-slate-500 mt-0.5">
                กำหนดภาพแบนเนอร์ สัดส่วน 1920 × 800 px และลิงก์ปลายทาง
              </p>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors cursor-pointer"
            @click="showCarouselDialog = false"
          >
            <v-icon icon="mdi-close" size="18" />
          </button>
        </div>

        <!-- Dialog Body -->
        <div class="px-6 py-5 space-y-4 max-h-[75vh] overflow-y-auto">
          <!-- Image Upload / Preview Zone -->
          <div class="space-y-2">
            <div class="flex items-center justify-between">
              <label class="block text-xs font-bold text-slate-700">
                รูปภาพแบนเนอร์ <span class="text-rose-500">*</span>
              </label>
              <label
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold cursor-pointer transition-all shadow-2xs"
                :class="uploadCarouselLoading ? 'opacity-60 pointer-events-none' : ''"
              >
                <v-icon
                  :icon="uploadCarouselLoading ? 'mdi-loading' : 'mdi-cloud-upload-outline'"
                  size="15"
                  :class="uploadCarouselLoading ? 'animate-spin' : ''"
                />
                <span>{{ uploadCarouselLoading ? 'กำลังอัปโหลด...' : (carouselForm.image_url ? 'เปลี่ยนภาพ' : 'อัปโหลดภาพจากเครื่อง') }}</span>
                <input
                  type="file"
                  accept="image/png,image/jpeg,image/jpg,image/webp"
                  class="hidden"
                  @change="handleCarouselFileUpload"
                />
              </label>
            </div>

            <!-- Banner Aspect Ratio Preview Box -->
            <div
              v-if="carouselForm.image_url"
              class="relative w-full aspect-[24/10] rounded-2xl overflow-hidden bg-slate-900 border border-slate-200 shadow-2xs group"
            >
              <img
                :src="carouselForm.image_url"
                alt="Banner Preview"
                class="w-full h-full object-cover"
                @error="($event.target as HTMLImageElement).src = 'https://placehold.co/1920x800/e2e8f0/64748b?text=Image+Load+Error'"
              />
              <button
                type="button"
                class="absolute top-2.5 right-2.5 w-7 h-7 rounded-lg bg-black/60 hover:bg-rose-600 text-white flex items-center justify-center transition-colors cursor-pointer"
                title="ลบภาพ"
                @click="carouselForm.image_url = ''"
              >
                <v-icon icon="mdi-close" size="16" />
              </button>
            </div>
            <div
              v-else
              class="w-full aspect-[24/10] rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/70 flex flex-col items-center justify-center gap-1.5 text-slate-400 p-4 text-center"
            >
              <v-icon icon="mdi-image-outline" size="36" class="text-slate-300" />
              <p class="text-xs font-bold text-slate-600">ยังไม่ได้เลือกรูปภาพ</p>
              <p class="text-[11px] text-slate-400">
                คลิกปุ่ม "อัปโหลดภาพจากเครื่อง" ด้านบน หรือกรอก URL ภาพด้านล่าง
              </p>
            </div>

            <!-- Helper Box -->
            <div class="p-2.5 bg-emerald-50/60 border border-emerald-200/60 rounded-xl text-[11px] text-emerald-800 flex items-start gap-2">
              <v-icon icon="mdi-lightbulb-on-outline" size="16" class="shrink-0 text-emerald-600 mt-0.5" />
              <span>
                <strong>ขนาดภาพที่แนะนำ:</strong> 1920 × 800 พิกเซล (สัดส่วน 2.4:1) ความกว้างขยายเต็มหน้าจออัตโนมัติ รองรับไฟล์ JPG, PNG และ WebP
              </span>
            </div>
          </div>

          <!-- Direct URL Input -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
              หรือระบุ URL รูปภาพโดยตรง
            </label>
            <input
              v-model="carouselForm.image_url"
              type="text"
              placeholder="https://... หรือ /storage/uploads/..."
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 font-mono text-[11px] transition-all"
            />
          </div>

          <!-- Title -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
              ชื่อภาพสไลด์ / ข้อความบันทึก
            </label>
            <input
              v-model="carouselForm.title"
              type="text"
              placeholder="เช่น ประชาสัมพันธ์รับสมัครนักศึกษาใหม่ 2568"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 font-semibold transition-all"
            />
          </div>

          <!-- Alt text -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
              คำอธิบายภาพ (Alt Text สำหรับ SEO / การเข้าถึง)
            </label>
            <input
              v-model="carouselForm.alt_text"
              type="text"
              placeholder="เช่น ภาพแบนเนอร์ประชาสัมพันธ์โครงการ..."
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 transition-all"
            />
          </div>

          <!-- Link URL & Target -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
              <label class="block text-xs font-bold text-slate-700 mb-1">
                ลิงก์ปลายทาง (เมื่อผู้ใช้คลิกสไลด์)
              </label>
              <input
                v-model="carouselForm.link_url"
                type="text"
                placeholder="เช่น /news/1 หรือ https://..."
                class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 transition-all font-mono text-[11px]"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">
                การเปิดลิงก์
              </label>
              <select
                v-model="carouselForm.target"
                class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 cursor-pointer appearance-none shadow-2xs"
              >
                <option value="_self">เปิดในหน้าเดิม</option>
                <option value="_blank">เปิดหน้าต่างใหม่</option>
              </select>
            </div>
          </div>

          <!-- Sort Order & Active Toggle -->
          <div class="grid grid-cols-2 gap-3 items-center pt-1">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">
                ลำดับการแสดงผล
              </label>
              <input
                v-model.number="carouselForm.sort_order"
                type="number"
                min="1"
                class="w-full px-3.5 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 transition-all font-semibold"
              />
            </div>
            <div class="pt-5">
              <label class="inline-flex items-center gap-2 text-xs text-slate-700 font-bold cursor-pointer">
                <input
                  v-model="carouselForm.is_active"
                  type="checkbox"
                  class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500/20 border-slate-300"
                />
                <span>เปิดให้แสดงผลในหน้าแรก</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Dialog Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-end gap-2.5">
          <button
            type="button"
            class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs cursor-pointer transition-all"
            @click="showCarouselDialog = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            :disabled="carouselLoading"
            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs disabled:opacity-50 cursor-pointer transition-all shadow-xs"
            @click="saveSlide"
          >
            <v-icon
              :icon="carouselLoading ? 'mdi-loading' : 'mdi-content-save-check-outline'"
              size="15"
              :class="carouselLoading ? 'animate-spin' : ''"
            />
            <span>{{ carouselLoading ? 'กำลังบันทึก...' : (editingSlide ? 'บันทึกการแก้ไข' : 'เพิ่มภาพสไลด์') }}</span>
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══ DIALOG: เพิ่ม/แก้ไข ข้อมูลคณะกรรมการ ═════════════════════════════════ -->
    <v-dialog v-model="showCommitteeDialog" max-width="520" persistent>
      <v-card class="!rounded-3xl p-0 bg-white overflow-hidden shadow-2xl border border-slate-100">
        <!-- Dialog Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs">
              <v-icon icon="mdi-account-group-outline" size="20" />
            </div>
            <div>
              <h3 class="text-sm font-bold text-slate-900 leading-tight">
                {{ editingCommittee ? 'แก้ไขข้อมูลกรรมการ' : 'เพิ่มรายชื่อกรรมการ' }}
              </h3>
              <p class="text-[11px] text-slate-500 mt-0.5">คณะกรรมการประจำคณะครุศาสตร์</p>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors cursor-pointer"
            @click="showCommitteeDialog = false"
          >
            <v-icon icon="mdi-close" size="18" />
          </button>
        </div>

        <!-- Dialog Body -->
        <div class="px-6 py-5 space-y-4">
          <!-- ลำดับที่ -->
          <div class="w-1/3">
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              ลำดับที่ (ที่)
            </label>
            <input
              v-model="committeeForm.sort_order"
              type="number"
              min="1"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all font-semibold"
            />
          </div>

          <!-- ชื่อ - นามสกุล -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              ชื่อ - นามสกุล (พร้อมคำนำหน้า/ตำแหน่งทางวิชาการ) <span class="text-rose-500">*</span>
            </label>
            <input
              v-model="committeeForm.name"
              type="text"
              placeholder="เช่น ผู้ช่วยศาสตราจารย์ ดร.ลินดา นาคโปย"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all font-medium"
            />
          </div>

          <!-- ตำแหน่งในคณะกรรมการ -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              ตำแหน่งในคณะกรรมการ <span class="text-rose-500">*</span>
            </label>
            <input
              v-model="committeeForm.position"
              type="text"
              placeholder="เช่น คณบดี, รองคณบดี, ผู้ทรงคุณวุฒิภายนอก"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all font-medium"
            />
            <!-- Quick preset tags -->
            <div class="flex flex-wrap gap-1.5 mt-2">
              <button
                v-for="preset in positionPresets"
                :key="preset"
                type="button"
                class="px-2 py-0.5 rounded-lg text-[10px] font-semibold transition-all cursor-pointer"
                :class="committeeForm.position === preset ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                @click="committeeForm.position = preset"
              >
                + {{ preset }}
              </button>
            </div>
          </div>

          <!-- สวิตช์ เปิดใช้งาน -->
          <div class="pt-2">
            <label class="inline-flex items-center gap-2 text-xs text-slate-700 font-bold cursor-pointer">
              <input
                v-model="committeeForm.is_active"
                type="checkbox"
                class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500/20 border-slate-300"
              />
              <span>เปิดแสดงผลในตารางคณะกรรมการ</span>
            </label>
          </div>
        </div>

        <!-- Dialog Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2.5">
          <button
            type="button"
            class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs cursor-pointer transition-all shadow-2xs"
            @click="showCommitteeDialog = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            :disabled="committeeLoading"
            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs disabled:opacity-50 cursor-pointer transition-all shadow-xs"
            @click="saveCommitteeMember"
          >
            <v-icon
              :icon="committeeLoading ? 'mdi-loading' : 'mdi-content-save-check-outline'"
              size="15"
              :class="committeeLoading ? 'animate-spin' : ''"
            />
            <span>{{ committeeLoading ? 'กำลังบันทึก...' : (editingCommittee ? 'บันทึกการแก้ไข' : 'เพิ่มรายชื่อกรรมการ') }}</span>
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══ DIALOG: ยืนยันการลบข้อมูล (Delete Confirmation Modal) ════════════════ -->
    <v-dialog v-model="showDeleteConfirm" max-width="440" persistent>
      <v-card class="!rounded-3xl p-0 bg-white overflow-hidden shadow-2xl border border-slate-100">
        <div class="p-6 text-center">
          <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 mx-auto flex items-center justify-center ring-8 ring-rose-50/50 mb-4">
            <v-icon icon="mdi-alert-circle-outline" size="30" />
          </div>
          <h3 class="text-base font-black text-slate-900 mb-1.5">
            {{ deleteTarget.title }}
          </h3>
          <p class="text-xs text-slate-500 leading-relaxed max-w-sm mx-auto">
            {{ deleteTarget.description }}
          </p>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-end gap-2.5">
          <button
            type="button"
            class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs cursor-pointer transition-all"
            @click="showDeleteConfirm = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            :disabled="deleteLoading"
            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs disabled:opacity-50 cursor-pointer transition-all shadow-xs"
            @click="handleConfirmDelete"
          >
            <v-icon
              :icon="deleteLoading ? 'mdi-loading' : 'mdi-trash-can-outline'"
              size="15"
              :class="deleteLoading ? 'animate-spin' : ''"
            />
            <span>{{ deleteLoading ? 'กำลังลบ...' : 'ยืนยันการลบ' }}</span>
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- Premium Toast Notification -->
    <AdminToast
      v-model="toast.show"
      :message="toast.message"
      :title="toast.title"
      :type="toast.type"
      :duration="3500"
    />
  </div>
</template>
