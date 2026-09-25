<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { api, type ExecutiveCategory, type ExecutiveMember } from '@/services/api'
import AdminToast from '@/components/admin/AdminToast.vue'

// ─── State ───────────────────────────────────────────────────────────────────
const loading = ref(false)
const categories = ref<ExecutiveCategory[]>([])
const personnelList = ref<any[]>([])
const personnelLoading = ref(false)

// Toast notification state
const toast = ref({
  show: false,
  title: '',
  message: '',
  type: 'success' as 'success' | 'error' | 'warning' | 'info',
})

function showToast(message: string, title = 'สำเร็จ', type: 'success' | 'error' | 'warning' | 'info' = 'success') {
  toast.value = { show: true, title, message, type }
}

// ─── Category Dialog State ───────────────────────────────────────────────────
const showCategoryDialog = ref(false)
const categorySaving = ref(false)
const isEditingCategory = ref(false)
const categoryForm = ref({
  id: 0,
  title: '',
  description: '',
  sort_order: 1,
  is_active: true,
})

// Delete Category Dialog
const showDeleteCatDialog = ref(false)
const deleteCatTarget = ref<ExecutiveCategory | null>(null)
const deleteCatLoading = ref(false)

// ─── Member Dialog State ─────────────────────────────────────────────────────
const showMemberDialog = ref(false)
const memberSaving = ref(false)
const isEditingMember = ref(false)
const personnelSearchQuery = ref('')
const uploadLoading = ref(false)

const memberForm = ref({
  id: 0,
  category_id: 0,
  personnel_id: null as number | null,
  position: '',
  position_suffix: '',
  custom_name: '',
  custom_avatar: '',
  custom_email: '',
  custom_phone: '',
  sort_order: 1,
  is_active: true,
})

// Delete Member Dialog
const showDeleteMemberDialog = ref(false)
const deleteMemberTarget = ref<ExecutiveMember | null>(null)
const deleteMemberLoading = ref(false)

// Suggestion positions
const positionSuggestions = [
  'คณบดีคณะครุศาสตร์',
  'รองคณบดีฝ่ายวิชาการและวิจัย',
  'รองคณบดีฝ่ายบริหารและแผนงาน',
  'รองคณบดีฝ่ายกิจการนักศึกษา',
  'รองคณบดีฝ่ายประกันคุณภาพการศึกษา',
  'ผู้ช่วยคณบดีฝ่ายวิชาการ',
  'ผู้ช่วยคณบดีฝ่ายเทคโนโลยีสารสนเทศ',
  'ผู้ช่วยคณบดีฝ่ายประกันคุณภาพ',
  'หัวหน้าสำนักงานคณบดี',
]

// ─── Computed Statistics ─────────────────────────────────────────────────────
const totalCategories = computed(() => categories.value.length)
const totalExecutives = computed(() => {
  return categories.value.reduce((acc, cat) => acc + (cat.members?.length || 0), 0)
})
const activeExecutives = computed(() => {
  return categories.value.reduce((acc, cat) => {
    return acc + (cat.members?.filter((m) => m.is_active)?.length || 0)
  }, 0)
})

// Filtered Personnel for selection modal / combobox
const filteredPersonnel = computed(() => {
  if (!personnelSearchQuery.value.trim()) return personnelList.value
  const q = personnelSearchQuery.value.toLowerCase()
  return personnelList.value.filter((p) => {
    return (
      p.name?.toLowerCase().includes(q) ||
      p.academic_title?.toLowerCase().includes(q) ||
      p.department_name?.toLowerCase().includes(q) ||
      p.role_title?.toLowerCase().includes(q)
    )
  })
})

const selectedPersonnelObj = computed(() => {
  if (!memberForm.value.personnel_id) return null
  return personnelList.value.find((p) => p.id === memberForm.value.personnel_id) || null
})

// ─── Fetch Data ──────────────────────────────────────────────────────────────
async function fetchData() {
  loading.value = true
  try {
    const data = await api.getExecutives()
    categories.value = data
  } catch (err: any) {
    showToast(err.message || 'ไม่สามารถโหลดข้อมูลคณะผู้บริหารได้', 'เกิดข้อผิดพลาด', 'error')
  } finally {
    loading.value = false
  }
}

async function loadPersonnelList() {
  personnelLoading.value = true
  try {
    const res = await api.getPersonnel({ per_page: 500 } as any)
    const raw = res?.data ?? res
    personnelList.value = Array.isArray(raw) ? raw : []
  } catch (err) {
    console.error('Failed to load personnel list:', err)
  } finally {
    personnelLoading.value = false
  }
}

onMounted(() => {
  fetchData()
  loadPersonnelList()
})

// ─── Category CRUD ───────────────────────────────────────────────────────────
function openAddCategory() {
  isEditingCategory.value = false
  categoryForm.value = {
    id: 0,
    title: '',
    description: '',
    sort_order: categories.value.length + 1,
    is_active: true,
  }
  showCategoryDialog.value = true
}

function openEditCategory(cat: ExecutiveCategory) {
  isEditingCategory.value = true
  categoryForm.value = {
    id: cat.id,
    title: cat.title,
    description: cat.description || '',
    sort_order: cat.sort_order,
    is_active: cat.is_active,
  }
  showCategoryDialog.value = true
}

async function saveCategory() {
  if (!categoryForm.value.title.trim()) {
    showToast('กรุณาระบุชื่อหมวดหมู่ผู้บริหาร', 'ข้อมูลไม่ครบ', 'warning')
    return
  }

  categorySaving.value = true
  try {
    if (isEditingCategory.value) {
      await api.updateExecutiveCategory(categoryForm.value.id, {
        title: categoryForm.value.title.trim(),
        description: categoryForm.value.description.trim() || undefined,
        sort_order: categoryForm.value.sort_order,
        is_active: categoryForm.value.is_active,
      })
      showToast('บันทึกการแก้ไขหมวดหมู่เรียบร้อยแล้ว')
    } else {
      await api.createExecutiveCategory({
        title: categoryForm.value.title.trim(),
        description: categoryForm.value.description.trim() || undefined,
        sort_order: categoryForm.value.sort_order,
        is_active: categoryForm.value.is_active,
      })
      showToast('สร้างหมวดหมู่ใหม่เรียบร้อยแล้ว')
    }
    showCategoryDialog.value = false
    await fetchData()
  } catch (err: any) {
    showToast(err.response?.data?.message || err.message || 'บันทึกหมวดหมู่ไม่สำเร็จ', 'ข้อผิดพลาด', 'error')
  } finally {
    categorySaving.value = false
  }
}

function confirmDeleteCat(cat: ExecutiveCategory) {
  deleteCatTarget.value = cat
  showDeleteCatDialog.value = true
}

async function doDeleteCategory() {
  if (!deleteCatTarget.value) return
  deleteCatLoading.value = true
  try {
    await api.deleteExecutiveCategory(deleteCatTarget.value.id)
    showToast(`ลบหมวดหมู่ "${deleteCatTarget.value.title}" เรียบร้อยแล้ว`)
    showDeleteCatDialog.value = false
    deleteCatTarget.value = null
    await fetchData()
  } catch (err: any) {
    showToast(err.response?.data?.message || err.message || 'ลบหมวดหมู่ไม่สำเร็จ', 'ข้อผิดพลาด', 'error')
  } finally {
    deleteCatLoading.value = false
  }
}

// Move Category Up / Down
async function moveCategory(index: number, direction: 'up' | 'down') {
  const targetIndex = direction === 'up' ? index - 1 : index + 1
  if (targetIndex < 0 || targetIndex >= categories.value.length) return

  const itemA = categories.value[index]
  const itemB = categories.value[targetIndex]
  const tempOrder = itemA.sort_order
  itemA.sort_order = itemB.sort_order
  itemB.sort_order = tempOrder

  categories.value.splice(index, 1)
  categories.value.splice(targetIndex, 0, itemA)

  try {
    const orders = categories.value.map((c, i) => ({ id: c.id, sort_order: i + 1 }))
    await api.reorderExecutiveCategories(orders)
    showToast('ปรับลำดับหมวดหมู่เรียบร้อยแล้ว')
  } catch (err) {
    showToast('ไม่สามารถบันทึกลำดับได้', 'เกิดข้อผิดพลาด', 'error')
    await fetchData()
  }
}

// ─── Member CRUD ─────────────────────────────────────────────────────────────
function openAddMember(defaultCategoryId?: number) {
  isEditingMember.value = false
  personnelSearchQuery.value = ''
  const catId = defaultCategoryId || (categories.value[0]?.id ?? 0)
  const targetCat = categories.value.find((c) => c.id === catId)
  const nextOrder = (targetCat?.members?.length || 0) + 1

  memberForm.value = {
    id: 0,
    category_id: catId,
    personnel_id: null,
    position: '',
    position_suffix: '',
    custom_name: '',
    custom_avatar: '',
    custom_email: '',
    custom_phone: '',
    sort_order: nextOrder,
    is_active: true,
  }
  showMemberDialog.value = true
}

function openEditMember(member: ExecutiveMember) {
  isEditingMember.value = true
  personnelSearchQuery.value = ''
  memberForm.value = {
    id: member.id,
    category_id: member.category_id,
    personnel_id: member.personnel_id || null,
    position: member.position,
    position_suffix: member.position_suffix || '',
    custom_name: member.custom_name || '',
    custom_avatar: member.custom_avatar || '',
    custom_email: member.custom_email || '',
    custom_phone: member.custom_phone || '',
    sort_order: member.sort_order,
    is_active: member.is_active,
  }
  showMemberDialog.value = true
}

function selectPersonnel(p: any) {
  memberForm.value.personnel_id = p.id
  // Suggest position if empty
  if (!memberForm.value.position) {
    const currentCat = categories.value.find((c) => c.id === memberForm.value.category_id)
    if (currentCat) {
      if (currentCat.title.includes('คณบดี') && !currentCat.title.includes('รอง') && !currentCat.title.includes('ผู้ช่วย')) {
        memberForm.value.position = 'คณบดีคณะครุศาสตร์'
      } else if (currentCat.title.includes('รองคณบดี')) {
        memberForm.value.position = 'รองคณบดี'
      } else if (currentCat.title.includes('ผู้ช่วย')) {
        memberForm.value.position = 'ผู้ช่วยคณบดี'
      } else if (currentCat.title.includes('หัวหน้า')) {
        memberForm.value.position = 'หัวหน้าสำนักงานคณบดี'
      }
    }
  }
}

function clearSelectedPersonnel() {
  memberForm.value.personnel_id = null
}

async function handleAvatarUpload(event: Event) {
  const target = event.target as HTMLInputElement
  if (!target.files || !target.files[0]) return
  const file = target.files[0]

  uploadLoading.value = true
  try {
    const res = await api.uploadFile(file)
    memberForm.value.custom_avatar = res.url
    showToast('อัปโหลดรูปภาพสำเร็จ')
  } catch (err: any) {
    showToast(err.response?.data?.message || 'อัปโหลดรูปภาพไม่สำเร็จ', 'ข้อผิดพลาด', 'error')
  } finally {
    uploadLoading.value = false
    target.value = ''
  }
}

async function saveMember() {
  if (!memberForm.value.category_id) {
    showToast('กรุณาเลือกหมวดหมู่ผู้บริหาร', 'ข้อมูลไม่ครบ', 'warning')
    return
  }
  if (!memberForm.value.position.trim()) {
    showToast('กรุณาระบุตำแหน่งผู้บริหาร (เช่น คณบดี, รองคณบดีฝ่ายวิชาการ)', 'ข้อมูลไม่ครบ', 'warning')
    return
  }
  if (!memberForm.value.personnel_id && !memberForm.value.custom_name.trim()) {
    showToast('กรุณาเลือกอาจารย์/บุคลากร หรือระบุชื่อผู้บริหาร', 'ข้อมูลไม่ครบ', 'warning')
    return
  }

  memberSaving.value = true
  try {
    const payload = {
      category_id: memberForm.value.category_id,
      personnel_id: memberForm.value.personnel_id,
      position: memberForm.value.position.trim(),
      position_suffix: memberForm.value.position_suffix.trim() || undefined,
      custom_name: memberForm.value.custom_name.trim() || undefined,
      custom_avatar: memberForm.value.custom_avatar.trim() || undefined,
      custom_email: memberForm.value.custom_email.trim() || undefined,
      custom_phone: memberForm.value.custom_phone.trim() || undefined,
      sort_order: memberForm.value.sort_order,
      is_active: memberForm.value.is_active,
    }

    if (isEditingMember.value) {
      await api.updateExecutiveMember(memberForm.value.id, payload)
      showToast('บันทึกข้อมูลผู้บริหารเรียบร้อยแล้ว')
    } else {
      await api.createExecutiveMember(payload)
      showToast('เพิ่มผู้บริหารใหม่เรียบร้อยแล้ว')
    }

    showMemberDialog.value = false
    await fetchData()
  } catch (err: any) {
    showToast(err.response?.data?.message || err.message || 'บันทึกข้อมูลไม่สำเร็จ', 'ข้อผิดพลาด', 'error')
  } finally {
    memberSaving.value = false
  }
}

function confirmDeleteMember(member: ExecutiveMember) {
  deleteMemberTarget.value = member
  showDeleteMemberDialog.value = true
}

async function doDeleteMember() {
  if (!deleteMemberTarget.value) return
  deleteMemberLoading.value = true
  try {
    await api.deleteExecutiveMember(deleteMemberTarget.value.id)
    showToast(`ลบข้อมูล "${deleteMemberTarget.value.name || deleteMemberTarget.value.position}" เรียบร้อยแล้ว`)
    showDeleteMemberDialog.value = false
    deleteMemberTarget.value = null
    await fetchData()
  } catch (err: any) {
    showToast(err.response?.data?.message || err.message || 'ลบข้อมูลไม่สำเร็จ', 'ข้อผิดพลาด', 'error')
  } finally {
    deleteMemberLoading.value = false
  }
}

// Move Member Up / Down within Category
async function moveMember(cat: ExecutiveCategory, memberIndex: number, direction: 'up' | 'down') {
  if (!cat.members) return
  const targetIndex = direction === 'up' ? memberIndex - 1 : memberIndex + 1
  if (targetIndex < 0 || targetIndex >= cat.members.length) return

  const itemA = cat.members[memberIndex]
  const itemB = cat.members[targetIndex]
  const tempOrder = itemA.sort_order
  itemA.sort_order = itemB.sort_order
  itemB.sort_order = tempOrder

  cat.members.splice(memberIndex, 1)
  cat.members.splice(targetIndex, 0, itemA)

  try {
    const orders = cat.members.map((m, i) => ({ id: m.id, sort_order: i + 1, category_id: cat.id }))
    await api.reorderExecutiveMembers(orders)
    showToast('ปรับลำดับผู้บริหารเรียบร้อยแล้ว')
  } catch (err) {
    showToast('ไม่สามารถบันทึกลำดับได้', 'เกิดข้อผิดพลาด', 'error')
    await fetchData()
  }
}

// Toggle Member is_active directly
async function toggleMemberActive(member: ExecutiveMember) {
  try {
    const newStatus = !member.is_active
    await api.updateExecutiveMember(member.id, { is_active: newStatus })
    member.is_active = newStatus
    showToast(newStatus ? 'เปิดแสดงผลผู้บริหารท่านนี้แล้ว' : 'ปิดแสดงผลผู้บริหารท่านนี้แล้ว', 'อัปเดตสถานะ')
  } catch (err) {
    showToast('ไม่สามารถเปลี่ยนสถานะได้', 'ข้อผิดพลาด', 'error')
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
            <v-icon icon="mdi-account-tie-outline" size="24" />
          </div>
          <div>
            <h2 class="text-lg sm:text-xl font-black text-slate-900 leading-tight">
              จัดการคณะผู้บริหาร
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
              จัดกลุ่มตำแหน่งและรายชื่อคณะผู้บริหารคณะครุศาสตร์ โดยดึงข้อมูลจากอาจารย์และบุคลากร
            </p>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-wrap items-center gap-2.5">
        <a
          href="/about/management"
          target="_blank"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors no-underline cursor-pointer"
        >
          <v-icon icon="mdi-open-in-new" size="16" />
          <span>ดูหน้าเว็บจริง</span>
        </a>

        <button
          type="button"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/70 transition-colors cursor-pointer"
          @click="openAddCategory"
        >
          <v-icon icon="mdi-folder-plus-outline" size="16" />
          <span>เพิ่มหมวดหมู่ใหม่</span>
        </button>

        <button
          type="button"
          class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-700/20 transition-all cursor-pointer"
          @click="openAddMember()"
        >
          <v-icon icon="mdi-account-plus-outline" size="16" />
          <span>เพิ่มผู้บริหาร</span>
        </button>
      </div>
    </div>

    <!-- Quick Overview Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
          <v-icon icon="mdi-view-headline" size="22" />
        </div>
        <div>
          <div class="text-xs font-medium text-slate-500">หมวดหมู่ทั้งหมด</div>
          <div class="text-xl font-black text-slate-900">{{ totalCategories }} หมวดหมู่</div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
          <v-icon icon="mdi-account-group" size="22" />
        </div>
        <div>
          <div class="text-xs font-medium text-slate-500">ผู้บริหารทั้งหมด</div>
          <div class="text-xl font-black text-slate-900">{{ totalExecutives }} ท่าน</div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
          <v-icon icon="mdi-eye-check-outline" size="22" />
        </div>
        <div>
          <div class="text-xs font-medium text-slate-500">เปิดแสดงผลหน้าเว็บ</div>
          <div class="text-xl font-black text-slate-900">{{ activeExecutives }} ท่าน</div>
        </div>
      </div>
    </div>

    <!-- Loading Skeleton -->
    <div v-if="loading" class="space-y-4">
      <div v-for="i in 3" :key="i" class="bg-white rounded-2xl border border-slate-200/80 p-6 animate-pulse space-y-4">
        <div class="h-6 bg-slate-200 rounded w-1/4" />
        <div class="h-20 bg-slate-100 rounded-xl" />
      </div>
    </div>

    <!-- Empty State -->
    <div
      v-else-if="categories.length === 0"
      class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center"
    >
      <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-4">
        <v-icon icon="mdi-account-tie-outline" size="32" />
      </div>
      <h3 class="text-base font-bold text-slate-800">ยังไม่มีข้อมูลคณะผู้บริหาร</h3>
      <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
        คุณสามารถเริ่มสร้างหมวดหมู่ผู้บริหารแรกได้ เช่น "คณบดี", "รองคณบดี", "ผู้ช่วยคณบดี" เพื่อจัดโครงสร้าง
      </p>
      <div class="mt-4 flex justify-center gap-3">
        <button
          type="button"
          class="px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 cursor-pointer"
          @click="openAddCategory"
        >
          + เพิ่มหมวดหมู่แรก
        </button>
      </div>
    </div>

    <!-- Categories & Executive Members List -->
    <div v-else class="space-y-6">
      <div
        v-for="(cat, catIndex) in categories"
        :key="cat.id"
        class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden transition-all duration-200 hover:border-slate-300"
      >
        <!-- Category Card Header -->
        <div class="bg-slate-50/80 border-b border-slate-200/80 px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div class="flex items-center gap-3">
            <!-- Category Order Pill -->
            <span class="w-7 h-7 rounded-lg bg-emerald-100/80 text-emerald-800 text-xs font-bold flex items-center justify-center shrink-0">
              {{ catIndex + 1 }}
            </span>
            <div>
              <div class="flex items-center gap-2 flex-wrap">
                <h3 class="text-base font-bold text-slate-900">{{ cat.title }}</h3>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-200/70 text-slate-700">
                  {{ cat.members?.length || 0 }} ท่าน
                </span>
                <span
                  class="px-2 py-0.5 rounded-full text-[11px] font-semibold"
                  :class="cat.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600'"
                >
                  {{ cat.is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                </span>
              </div>
              <p v-if="cat.description" class="text-xs text-slate-500 mt-0.5">
                {{ cat.description }}
              </p>
            </div>
          </div>

          <!-- Category Action Controls -->
          <div class="flex items-center gap-1.5 self-end sm:self-auto">
            <!-- Move Up -->
            <button
              type="button"
              class="w-8 h-8 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer"
              title="เลื่อนหมวดหมู่ขึ้น"
              :disabled="catIndex === 0"
              @click="moveCategory(catIndex, 'up')"
            >
              <v-icon icon="mdi-arrow-up" size="16" />
            </button>

            <!-- Move Down -->
            <button
              type="button"
              class="w-8 h-8 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer"
              title="เลื่อนหมวดหมู่ลง"
              :disabled="catIndex === categories.length - 1"
              @click="moveCategory(catIndex, 'down')"
            >
              <v-icon icon="mdi-arrow-down" size="16" />
            </button>

            <!-- Add Member to Category -->
            <button
              type="button"
              class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200/60 text-xs font-semibold flex items-center gap-1 transition-colors cursor-pointer"
              title="เพิ่มผู้บริหารในหมวดหมู่นี้"
              @click="openAddMember(cat.id)"
            >
              <v-icon icon="mdi-plus" size="15" />
              <span>เพิ่มคน</span>
            </button>

            <!-- Edit Category -->
            <button
              type="button"
              class="w-8 h-8 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
              title="แก้ไขชื่อหมวดหมู่"
              @click="openEditCategory(cat)"
            >
              <v-icon icon="mdi-pencil-outline" size="16" />
            </button>

            <!-- Delete Category -->
            <button
              type="button"
              class="w-8 h-8 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer"
              title="ลบหมวดหมู่"
              @click="confirmDeleteCat(cat)"
            >
              <v-icon icon="mdi-delete-outline" size="16" />
            </button>
          </div>
        </div>

        <!-- Category Members Table / Cards -->
        <div class="p-4 sm:p-5">
          <div v-if="!cat.members || cat.members.length === 0" class="py-8 text-center text-slate-400">
            <v-icon icon="mdi-account-off-outline" size="28" class="mb-1" />
            <p class="text-xs">ยังไม่มีรายชื่อผู้บริหารในหมวดหมู่นี้</p>
            <button
              type="button"
              class="mt-2 text-xs font-semibold text-emerald-600 hover:text-emerald-700 cursor-pointer"
              @click="openAddMember(cat.id)"
            >
              + เพิ่มผู้บริหารท่านแรกในหมวดนี้
            </button>
          </div>

          <div v-else class="space-y-3">
            <div
              v-for="(member, memIndex) in cat.members"
              :key="member.id"
              class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-3.5 rounded-xl border border-slate-200/70 hover:border-emerald-200 hover:bg-slate-50/50 transition-all duration-200 bg-white"
            >
              <!-- Member Info -->
              <div class="flex items-center gap-3.5 min-w-0">
                <!-- Member Order Pill -->
                <span class="w-6 h-6 rounded-md bg-slate-100 text-slate-500 text-[11px] font-bold flex items-center justify-center shrink-0">
                  {{ memIndex + 1 }}
                </span>

                <!-- Avatar -->
                <div class="relative w-12 h-14 rounded-xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200">
                  <img
                    v-if="member.avatar"
                    :src="member.avatar"
                    :alt="member.name || member.position"
                    class="w-full h-full object-cover object-top"
                    @error="($event.target as HTMLImageElement).src = 'https://placehold.co/100x120/f1f5f9/94a3b8?text=EDU'"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                    <v-icon icon="mdi-account" size="22" />
                  </div>
                </div>

                <!-- Text Details -->
                <div class="min-w-0 flex-1">
                  <div class="flex items-center gap-2 flex-wrap">
                    <h4 class="text-sm font-bold text-slate-900 leading-snug">
                      {{ member.name || member.custom_name || '(ยังไม่ระบุชื่อ)' }}
                    </h4>

                    <!-- Linked Personnel Badge -->
                    <span
                      v-if="member.personnel_id && member.personnel"
                      class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/60 inline-flex items-center gap-1"
                      title="เชื่อมโยงจากฐานข้อมูลอาจารย์ & บุคลากร"
                    >
                      <v-icon icon="mdi-link-variant" size="12" />
                      <span>{{ member.personnel.department_name || 'อาจารย์/บุคลากร' }}</span>
                    </span>
                    <span
                      v-else-if="member.custom_name"
                      class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/60 inline-flex items-center gap-1"
                    >
                      <v-icon icon="mdi-account-edit-outline" size="12" />
                      <span>กรอกชื่อเอง</span>
                    </span>
                  </div>

                  <div class="text-xs font-semibold text-emerald-700 mt-0.5">
                    {{ member.position }}
                    <span v-if="member.position_suffix" class="text-slate-500 font-normal">
                      ({{ member.position_suffix }})
                    </span>
                  </div>

                  <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-1 flex-wrap">
                    <span v-if="member.email" class="inline-flex items-center gap-1">
                      <v-icon icon="mdi-email-outline" size="13" />
                      <span>{{ member.email }}</span>
                    </span>
                    <span v-if="member.phone" class="inline-flex items-center gap-1">
                      <v-icon icon="mdi-phone-outline" size="13" />
                      <span>{{ member.phone }}</span>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Member Actions -->
              <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                <!-- Active Toggle -->
                <button
                  type="button"
                  class="px-2 py-1 rounded-lg text-[11px] font-semibold border transition-colors flex items-center gap-1 cursor-pointer"
                  :class="member.is_active
                    ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100'
                    : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200'"
                  @click="toggleMemberActive(member)"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="member.is_active ? 'bg-emerald-500' : 'bg-slate-400'" />
                  <span>{{ member.is_active ? 'แสดงผล' : 'ซ่อน' }}</span>
                </button>

                <!-- Reorder buttons -->
                <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                  <button
                    type="button"
                    class="p-1 text-slate-500 hover:text-slate-800 hover:bg-slate-200 disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer transition-colors"
                    title="เลื่อนขึ้น"
                    :disabled="memIndex === 0"
                    @click="moveMember(cat, memIndex, 'up')"
                  >
                    <v-icon icon="mdi-chevron-up" size="18" />
                  </button>
                  <button
                    type="button"
                    class="p-1 text-slate-500 hover:text-slate-800 hover:bg-slate-200 disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer transition-colors border-l border-slate-200"
                    title="เลื่อนลง"
                    :disabled="memIndex === (cat.members?.length || 0) - 1"
                    @click="moveMember(cat, memIndex, 'down')"
                  >
                    <v-icon icon="mdi-chevron-down" size="18" />
                  </button>
                </div>

                <!-- Edit Member -->
                <button
                  type="button"
                  class="w-8 h-8 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                  title="แก้ไขข้อมูลผู้บริหาร"
                  @click="openEditMember(member)"
                >
                  <v-icon icon="mdi-pencil-outline" size="16" />
                </button>

                <!-- Delete Member -->
                <button
                  type="button"
                  class="w-8 h-8 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer"
                  title="ลบผู้บริหาร"
                  @click="confirmDeleteMember(member)"
                >
                  <v-icon icon="mdi-delete-outline" size="16" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ─── Category Dialog (Add / Edit) ──────────────────────────────────────── -->
    <v-dialog v-model="showCategoryDialog" max-width="500px">
      <v-card class="rounded-2xl p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <v-icon :icon="isEditingCategory ? 'mdi-pencil-outline' : 'mdi-folder-plus-outline'" size="18" />
            </div>
            <h3 class="text-base font-bold text-slate-900">
              {{ isEditingCategory ? 'แก้ไขหมวดหมู่ผู้บริหาร' : 'เพิ่มหมวดหมู่ผู้บริหารใหม่' }}
            </h3>
          </div>
          <button
            type="button"
            class="text-slate-400 hover:text-slate-600 cursor-pointer"
            @click="showCategoryDialog = false"
          >
            <v-icon icon="mdi-close" size="20" />
          </button>
        </div>

        <form class="space-y-4 pt-4" @submit.prevent="saveCategory">
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">
              ชื่อหมวดหมู่ผู้บริหาร <span class="text-rose-500">*</span>
            </label>
            <input
              v-model="categoryForm.title"
              type="text"
              required
              placeholder="เช่น คณบดี, รองคณบดี, ผู้ช่วยคณบดี, คณะกรรมการบริหาร"
              class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">
              คำอธิบายหมวดหมู่ (ถ้ามี)
            </label>
            <textarea
              v-model="categoryForm.description"
              rows="2"
              placeholder="คำอธิบายสรุปบทบาทของกลุ่มผู้บริหารนี้"
              class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none resize-none"
            />
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">ลำดับการแสดงผล</label>
              <input
                v-model.number="categoryForm.sort_order"
                type="number"
                min="1"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">สถานะ</label>
              <div class="flex items-center h-[38px]">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                  <input
                    v-model="categoryForm.is_active"
                    type="checkbox"
                    class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300"
                  />
                  <span class="text-xs font-medium text-slate-700">เปิดใช้งานแสดงผล</span>
                </label>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
            <button
              type="button"
              class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 cursor-pointer"
              @click="showCategoryDialog = false"
            >
              ยกเลิก
            </button>
            <button
              type="submit"
              class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 cursor-pointer"
              :disabled="categorySaving"
            >
              {{ categorySaving ? 'กำลังบันทึก...' : 'บันทึกหมวดหมู่' }}
            </button>
          </div>
        </form>
      </v-card>
    </v-dialog>

    <!-- ─── Member Dialog (Add / Edit) ────────────────────────────────────────── -->
    <v-dialog v-model="showMemberDialog" max-width="680px">
      <v-card class="rounded-2xl p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
              <v-icon :icon="isEditingMember ? 'mdi-account-edit-outline' : 'mdi-account-plus-outline'" size="18" />
            </div>
            <div>
              <h3 class="text-base font-bold text-slate-900 leading-tight">
                {{ isEditingMember ? 'แก้ไขข้อมูลผู้บริหาร' : 'เพิ่มผู้บริหารใหม่' }}
              </h3>
              <p class="text-[11px] text-slate-500">เลือกอาจารย์/บุคลากรเพื่อดึงข้อมูลอัตโนมัติ หรือกรอกข้อมูลเอง</p>
            </div>
          </div>
          <button
            type="button"
            class="text-slate-400 hover:text-slate-600 cursor-pointer"
            @click="showMemberDialog = false"
          >
            <v-icon icon="mdi-close" size="20" />
          </button>
        </div>

        <form class="space-y-4 pt-4" @submit.prevent="saveMember">
          <!-- 1. Category Selection -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">
              หมวดหมู่ผู้บริหาร <span class="text-rose-500">*</span>
            </label>
            <select
              v-model.number="memberForm.category_id"
              required
              class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none bg-white"
            >
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.title }}
              </option>
            </select>
          </div>

          <!-- 2. Select Personnel from DB -->
          <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
            <div class="flex items-center justify-between">
              <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                <v-icon icon="mdi-database-search-outline" size="16" class="text-emerald-600" />
                <span>ดึงข้อมูลจากอาจารย์ & บุคลากร (แนะนำ)</span>
              </label>
              <button
                v-if="memberForm.personnel_id"
                type="button"
                class="text-[11px] font-semibold text-rose-600 hover:text-rose-700 cursor-pointer"
                @click="clearSelectedPersonnel"
              >
                ยกเลิกการเชื่อมโยง
              </button>
            </div>

            <!-- If already selected -->
            <div
              v-if="selectedPersonnelObj"
              class="flex items-center gap-3 p-3 rounded-xl bg-white border border-emerald-300 shadow-xs"
            >
              <div class="w-12 h-14 rounded-lg overflow-hidden bg-slate-100 shrink-0 border border-slate-200">
                <img
                  v-if="selectedPersonnelObj.avatar"
                  :src="selectedPersonnelObj.avatar"
                  :alt="selectedPersonnelObj.name"
                  class="w-full h-full object-cover object-top"
                />
                <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                  <v-icon icon="mdi-account" size="20" />
                </div>
              </div>
              <div class="min-w-0 flex-1">
                <div class="text-xs font-bold text-slate-900 truncate">
                  {{ selectedPersonnelObj.academic_title ? selectedPersonnelObj.academic_title + ' ' : '' }}{{ selectedPersonnelObj.name }}
                </div>
                <div class="text-[11px] text-slate-500 truncate">
                  {{ selectedPersonnelObj.department_name }} • {{ selectedPersonnelObj.role_title }}
                </div>
                <div class="text-[10px] text-emerald-600 font-medium truncate mt-0.5">
                  เชื่อมโยงแล้ว (รูปถ่ายและข้อมูลติดต่อจะอัปเดตตามอาจารย์ท่านนี้)
                </div>
              </div>
              <button
                type="button"
                class="px-2.5 py-1 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 border border-slate-200 cursor-pointer"
                @click="memberForm.personnel_id = null"
              >
                เปลี่ยนคน
              </button>
            </div>

            <!-- Personnel Search & Picker -->
            <div v-else class="space-y-2">
              <div class="relative">
                <v-icon icon="mdi-magnify" size="18" class="absolute left-3 top-2.5 text-slate-400" />
                <input
                  v-model="personnelSearchQuery"
                  type="text"
                  placeholder="พิมพ์ค้นหาชื่ออาจารย์, วุฒิ หรือสาขาวิชา..."
                  class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none bg-white"
                />
              </div>

              <!-- List of available personnel to pick -->
              <div class="max-h-48 overflow-y-auto space-y-1.5 border border-slate-200 rounded-xl p-2 bg-white">
                <div
                  v-for="p in filteredPersonnel.slice(0, 30)"
                  :key="p.id"
                  class="flex items-center justify-between p-2 rounded-lg hover:bg-emerald-50/60 cursor-pointer transition-colors"
                  @click="selectPersonnel(p)"
                >
                  <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-9 rounded-md overflow-hidden bg-slate-100 shrink-0 border border-slate-200">
                      <img
                        v-if="p.avatar"
                        :src="p.avatar"
                        :alt="p.name"
                        class="w-full h-full object-cover object-top"
                      />
                      <div v-else class="w-full h-full flex items-center justify-center text-slate-300">
                        <v-icon icon="mdi-account" size="14" />
                      </div>
                    </div>
                    <div class="min-w-0 text-left">
                      <div class="text-xs font-semibold text-slate-800 truncate">
                        {{ p.name }}
                      </div>
                      <div class="text-[10px] text-slate-400 truncate">
                        {{ p.department_name }}
                      </div>
                    </div>
                  </div>
                  <span class="text-[11px] font-semibold text-emerald-600 hover:underline shrink-0">
                    เลือกท่านนี้
                  </span>
                </div>

                <div v-if="filteredPersonnel.length === 0" class="py-4 text-center text-xs text-slate-400">
                  ไม่พบบุคลากรที่ตรงกับคำค้นหา
                </div>
              </div>
            </div>
          </div>

          <!-- 3. Position Details -->
          <div class="space-y-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">
                ตำแหน่งผู้บริหาร <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="memberForm.position"
                type="text"
                required
                placeholder="เช่น คณบดีคณะครุศาสตร์, รองคณบดีฝ่ายวิชาการและวิจัย"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
              />

              <!-- Suggestion Pills -->
              <div class="flex items-center gap-1.5 flex-wrap mt-2">
                <span class="text-[10px] text-slate-400">คำแนะนำด่วน:</span>
                <button
                  v-for="sug in positionSuggestions"
                  :key="sug"
                  type="button"
                  class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 border border-slate-200 hover:border-emerald-200 transition-colors cursor-pointer"
                  @click="memberForm.position = sug"
                >
                  {{ sug }}
                </button>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">
                ส่วนขยายตำแหน่ง / ฝ่ายงาน (ถ้ามี)
              </label>
              <input
                v-model="memberForm.position_suffix"
                type="text"
                placeholder="เช่น ฝ่ายวิชาการและวิจัย, ประกันคุณภาพ"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
              />
            </div>
          </div>

          <!-- 4. Optional Custom / Override Fields (Collapsible / Advanced) -->
          <div class="border border-slate-200 rounded-xl p-3.5 bg-slate-50/50 space-y-3">
            <div class="text-xs font-bold text-slate-700 flex items-center justify-between">
              <span>ข้อมูลเพิ่มเติม / กำหนดข้อมูลเฉพาะ (Custom Override)</span>
              <span class="text-[10px] text-slate-400 font-normal">กรณีไม่ได้เลือกบุคลากร หรือต้องการแก้ไขพิเศษ</span>
            </div>

            <div>
              <label class="block text-xs font-medium text-slate-600 mb-1">
                ชื่อที่ต้องการแสดงผลเฉพาะ (ไม่ดึงจากบุคลากร)
              </label>
              <input
                v-model="memberForm.custom_name"
                type="text"
                placeholder="เช่น ผศ.ดร.สมชาย ใจดี"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none bg-white"
              />
            </div>

            <!-- Custom Avatar Upload / URL -->
            <div>
              <label class="block text-xs font-medium text-slate-600 mb-1">
                รูปถ่ายเฉพาะผู้บริหาร
              </label>
              <div class="flex items-center gap-3">
                <div class="w-12 h-14 rounded-lg overflow-hidden bg-slate-100 shrink-0 border border-slate-200">
                  <img
                    v-if="memberForm.custom_avatar"
                    :src="memberForm.custom_avatar"
                    alt="Preview"
                    class="w-full h-full object-cover object-top"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center text-slate-300">
                    <v-icon icon="mdi-camera" size="18" />
                  </div>
                </div>

                <div class="flex-1 space-y-1.5">
                  <input
                    v-model="memberForm.custom_avatar"
                    type="text"
                    placeholder="URL รูปภาพ เช่น https://... หรืออัปโหลดไฟล์ด้านล่าง"
                    class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none bg-white"
                  />
                  <label class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 cursor-pointer">
                    <v-icon :icon="uploadLoading ? 'mdi-loading' : 'mdi-upload'" :class="{ 'animate-spin': uploadLoading }" size="14" />
                    <span>{{ uploadLoading ? 'กำลังอัปโหลด...' : 'อัปโหลดรูปภาพใหม่' }}</span>
                    <input
                      type="file"
                      accept="image/*"
                      class="hidden"
                      :disabled="uploadLoading"
                      @change="handleAvatarUpload"
                    />
                  </label>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">อีเมลติดต่อเฉพาะ</label>
                <input
                  v-model="memberForm.custom_email"
                  type="email"
                  placeholder="executive@rru.ac.th"
                  class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none bg-white"
                />
              </div>
              <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">เบอร์โทรศัพท์เฉพาะ</label>
                <input
                  v-model="memberForm.custom_phone"
                  type="text"
                  placeholder="038-511-233 ต่อ 2200"
                  class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none bg-white"
                />
              </div>
            </div>
          </div>

          <!-- 5. Sort Order & Active Status -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">ลำดับในหมวดหมู่</label>
              <input
                v-model.number="memberForm.sort_order"
                type="number"
                min="1"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">สถานะการแสดงผล</label>
              <div class="flex items-center h-[38px]">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                  <input
                    v-model="memberForm.is_active"
                    type="checkbox"
                    class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300"
                  />
                  <span class="text-xs font-medium text-slate-700">เปิดแสดงผลหน้าเว็บไซต์</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
            <button
              type="button"
              class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 cursor-pointer"
              @click="showMemberDialog = false"
            >
              ยกเลิก
            </button>
            <button
              type="submit"
              class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 cursor-pointer"
              :disabled="memberSaving"
            >
              {{ memberSaving ? 'กำลังบันทึก...' : 'บันทึกข้อมูลผู้บริหาร' }}
            </button>
          </div>
        </form>
      </v-card>
    </v-dialog>

    <!-- ─── Delete Category Confirmation Dialog ──────────────────────────────── -->
    <v-dialog v-model="showDeleteCatDialog" max-width="420px">
      <v-card class="rounded-2xl p-6 text-center">
        <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 mx-auto flex items-center justify-center mb-3">
          <v-icon icon="mdi-alert-outline" size="26" />
        </div>
        <h3 class="text-base font-bold text-slate-900">ยืนยันการลบหมวดหมู่</h3>
        <p class="text-xs text-slate-500 mt-1">
          คุณต้องการลบหมวดหมู่ <strong class="text-slate-800">"{{ deleteCatTarget?.title }}"</strong> ใช่หรือไม่?
          <br /><span class="text-rose-600 text-[11px]">รายชื่อผู้บริหารในหมวดหมู่นี้ทั้งหมดจะถูกลบไปด้วย</span>
        </p>
        <div class="flex items-center justify-center gap-2 mt-5">
          <button
            type="button"
            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 cursor-pointer"
            @click="showDeleteCatDialog = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 disabled:opacity-50 cursor-pointer"
            :disabled="deleteCatLoading"
            @click="doDeleteCategory"
          >
            {{ deleteCatLoading ? 'กำลังลบ...' : 'ยืนยันการลบ' }}
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- ─── Delete Member Confirmation Dialog ────────────────────────────────── -->
    <v-dialog v-model="showDeleteMemberDialog" max-width="420px">
      <v-card class="rounded-2xl p-6 text-center">
        <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 mx-auto flex items-center justify-center mb-3">
          <v-icon icon="mdi-account-remove-outline" size="26" />
        </div>
        <h3 class="text-base font-bold text-slate-900">ยืนยันการลบผู้บริหาร</h3>
        <p class="text-xs text-slate-500 mt-1">
          คุณต้องการลบ <strong class="text-slate-800">"{{ deleteMemberTarget?.name || deleteMemberTarget?.position }}"</strong> ออกจากคณะผู้บริหารใช่หรือไม่?
        </p>
        <div class="flex items-center justify-center gap-2 mt-5">
          <button
            type="button"
            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 cursor-pointer"
            @click="showDeleteMemberDialog = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 disabled:opacity-50 cursor-pointer"
            :disabled="deleteMemberLoading"
            @click="doDeleteMember"
          >
            {{ deleteMemberLoading ? 'กำลังลบ...' : 'ยืนยันการลบ' }}
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
