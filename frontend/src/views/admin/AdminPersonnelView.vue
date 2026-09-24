<script setup lang="ts">
import { ref, onMounted, computed, nextTick } from 'vue'
import { api, type DepartmentRecord } from '@/services/api'
import AdminToast from '@/components/admin/AdminToast.vue'

interface EducationItem {
  degree: string
  field: string
  institution: string
  year?: string
}

interface PublicationItem {
  title: string
  source: string
  year: string
  type: 'journal' | 'conference' | 'book' | 'research'
  link?: string
}

interface CourseItem {
  code: string
  name: string
  level: string
}

interface ExperienceItem {
  period?: string
  position: string
  organization: string
  description?: string
}

interface StudyVisitItem {
  year?: string
  topic: string
  organization?: string
  location?: string
}

interface PersonnelItem {
  id: number
  slug_id: string
  name: string
  name_en?: string
  academic_title?: string
  role_title: string
  personnel_type?: 'teacher' | 'staff'
  is_head?: boolean
  avatar?: string
  degrees?: string
  department_id: string
  department_name: string
  email?: string
  phone?: string
  office_room?: string
  office_hours?: string
  bio?: string
  education_history?: EducationItem[]
  expertise?: string[]
  publications?: PublicationItem[]
  courses?: CourseItem[]
  work_experience?: ExperienceItem[]
  study_visits?: StudyVisitItem[]
  sort_order?: number
}

// ─── Main Tab Switcher ────────────────────────────────────────────────────────
const activeTab = ref<'personnel' | 'departments'>('personnel')

// ─── Departments State ────────────────────────────────────────────────────────
const departments = ref<DepartmentRecord[]>([])
const deptsLoading = ref(false)
const showDeptDialog = ref(false)
const editingDept = ref<DepartmentRecord | null>(null)
const deptLoading = ref(false)
const deleteDeptDialog = ref(false)
const deleteDeptTarget = ref<DepartmentRecord | null>(null)

const deptForm = ref({
  id: 0,
  name: '',
  slug: '',
  degree_title: '',
  head_personnel_id: null as number | null,
  sort_order: 1,
  is_active: true,
})

// Drag and drop state for departments
const draggingDeptIndex = ref<number | null>(null)
const dragOverDeptIndex = ref<number | null>(null)

// ─── Personnel State ──────────────────────────────────────────────────────────
const personnel = ref<PersonnelItem[]>([])
const loading = ref(false)
const search = ref('')
const selectedDept = ref('all')
const selectedType = ref<'all' | 'teacher' | 'staff'>('all')

// Personnel Dialog & Form State
const dialog = ref(false)
const deleteDialog = ref(false)
const isEditing = ref(false)
const saveLoading = ref(false)
const deleteLoading = ref(false)
const currentId = ref<number | null>(null)
const personnelFormTab = ref<'general' | 'education' | 'works'>('general')
const modalContentRef = ref<HTMLElement | null>(null)

const switchPersonnelTab = (tab: 'general' | 'education' | 'works') => {
  personnelFormTab.value = tab
  nextTick(() => {
    if (modalContentRef.value) {
      modalContentRef.value.scrollTop = 0
    }
  })
}

const form = ref({
  name: '',
  name_en: '',
  academic_title: '',
  role_title: '',
  personnel_type: 'teacher' as 'teacher' | 'staff',
  is_head: false,
  department_id: 'early-childhood',
  department_name: 'สาขาวิชาการศึกษาปฐมวัย',
  degrees: '',
  email: '',
  phone: '',
  office_room: '',
  office_hours: '',
  bio: '',
  avatar: '',
  sort_order: 1,
  education_history: [] as EducationItem[],
  expertise_input: '',
  expertise: [] as string[],
  publications: [] as PublicationItem[],
  courses: [] as CourseItem[],
  work_experience: [] as ExperienceItem[],
  study_visits: [] as StudyVisitItem[],
})

// ─── Toast Feedback ───────────────────────────────────────────────────────────
const toast = ref({
  show: false,
  message: '',
  title: '',
  type: 'success' as 'success' | 'error' | 'warning' | 'info',
})

const showToast = (text: string, color: 'success' | 'error' | 'warning' | 'info' = 'success', title?: string) => {
  toast.value = {
    show: true,
    message: text,
    title: title || (color === 'success' ? 'บันทึกสำเร็จ' : color === 'warning' ? 'แจ้งเตือน' : 'เกิดข้อผิดพลาด'),
    type: color,
  }
}

// ─── Fetch Data ───────────────────────────────────────────────────────────────
const fetchDepartments = async () => {
  deptsLoading.value = true
  try {
    const res = await api.getDepartments({ with_members: true })
    departments.value = res || []
  } catch (err) {
    console.error('Failed to load departments:', err)
  } finally {
    deptsLoading.value = false
  }
}

const fetchPersonnel = async () => {
  loading.value = true
  try {
    const res = await api.getPersonnel({
      department_id: selectedDept.value === 'all' ? undefined : selectedDept.value,
      search: search.value || undefined,
    })
    personnel.value = res.data || []
  } catch (err) {
    console.error(err)
    showToast('ไม่สามารถดึงข้อมูลบุคลากรได้', 'error')
  } finally {
    loading.value = false
  }
}

// Filtered Personnel List
const filteredPersonnel = computed(() => {
  return personnel.value.filter((p) => {
    const pType = p.personnel_type || 'teacher'
    const matchType = selectedType.value === 'all' || pType === selectedType.value
    const matchDept = selectedDept.value === 'all' || p.department_id === selectedDept.value

    const matchSearch =
      !search.value ||
      p.name.toLowerCase().includes(search.value.toLowerCase()) ||
      (p.name_en && p.name_en.toLowerCase().includes(search.value.toLowerCase())) ||
      p.role_title.toLowerCase().includes(search.value.toLowerCase()) ||
      p.department_name.toLowerCase().includes(search.value.toLowerCase()) ||
      (p.academic_title && p.academic_title.toLowerCase().includes(search.value.toLowerCase())) ||
      (p.degrees && p.degrees.toLowerCase().includes(search.value.toLowerCase()))

    return matchType && matchDept && matchSearch
  })
})

// ─── Staff Role Positions ─────────────────────────────────────────────────────
const staffRoleOptions = [
  'หัวหน้าสำนักงานครุศาสตร์',
  'เจ้าหน้าที่บริหารงานทั่วไป',
  'นักวิชาการศึกษา',
  'นักวิชาการโสตทัศนศึกษา',
  'นักวิชาโสตทัศนศึกษา',
]

const setPersonnelType = (type: 'teacher' | 'staff') => {
  form.value.personnel_type = type
  if (type === 'staff') {
    form.value.is_head = false
    form.value.academic_title = ''
    if (!staffRoleOptions.includes(form.value.role_title)) {
      form.value.role_title = staffRoleOptions[0]
    }
    if (form.value.department_id === 'early-childhood') {
      form.value.department_id = 'office'
      form.value.department_name = 'สำนักงานคณบดีคณะครุศาสตร์'
    }
  } else {
    if (staffRoleOptions.includes(form.value.role_title)) {
      form.value.role_title = 'อาจารย์ประจำสาขาวิชา'
    }
    if (form.value.department_id === 'office') {
      const firstDept = departments.value[0]?.slug || 'early-childhood'
      form.value.department_id = firstDept
      form.value.department_name = departments.value[0]?.name || 'สาขาวิชาการศึกษาปฐมวัย'
    }
  }
}

// ─── Vuetify Select Options & Helpers ─────────────────────────────────────────
const deptFilterOptions = computed(() => {
  const officeCount = personnel.value.filter((p) => p.department_id === 'office').length
  return [
    { title: `ทุกสาขาวิชา/หน่วยงาน (ทั้งหมด: ${personnel.value.length} ท่าน)`, value: 'all' },
    ...(officeCount > 0 ? [{ title: `สำนักงานคณบดีคณะครุศาสตร์ (${officeCount})`, value: 'office' }] : []),
    ...departments.value.map((d) => ({
      title: `${d.name} (${d.personnels_count ?? 0})`,
      value: d.slug,
    })),
  ]
})

const deptSelectOptions = computed(() => [
  { title: 'สำนักงานคณบดีคณะครุศาสตร์ (ส่วนกลาง)', value: 'office' },
  ...departments.value.map((d) => ({
    title: d.name,
    value: d.slug,
  })),
])

const getDeptHeadOptions = (dept: DepartmentRecord) => {
  const members = (dept.members || dept.personnels || personnel.value.filter((p) => p.department_id === dept.slug))
    .filter((p) => (p.personnel_type || 'teacher') === 'teacher')
  return [
    { title: '-- ยังไม่กำหนด --', value: null },
    ...members.map((p) => ({
      title: `${p.name} (${p.role_title || 'อาจารย์ประจำสาขา'})`,
      value: p.id,
    })),
  ]
}

const deptFormHeadOptions = computed(() => {
  const members = personnel.value
    .filter((item) => (!deptForm.value.slug || item.department_id === deptForm.value.slug))
    .filter((p) => (p.personnel_type || 'teacher') === 'teacher')
  return [
    { title: '-- ยังไม่ระบุประธานสาขา --', value: null },
    ...members.map((p) => ({
      title: `${p.name} (${p.role_title || 'อาจารย์ประจำสาขา'})`,
      value: p.id,
    })),
  ]
})

const pubTypeOptions = [
  { title: 'วารสาร (Journal)', value: 'journal' },
  { title: 'ประชุมวิชาการ (Conference)', value: 'conference' },
  { title: 'ตำรา/หนังสือ (Book)', value: 'book' },
  { title: 'โครงการวิจัย (Research)', value: 'research' },
]

const courseLevelOptions = ['ปริญญาตรี', 'ปริญญาโท', 'ปริญญาเอก']

const academicTitleOptions = computed(() => {
  const defaults = [
    // ตำแหน่งทางวิชาการแบบเต็มทางการ ครบทุกระดับ (ไม่มีตัวย่อซ้ำ)
    'อาจารย์',
    'อาจารย์ ดร.',
    'ผู้ช่วยศาสตราจารย์',
    'ผู้ช่วยศาสตราจารย์ ดร.',
    'รองศาสตราจารย์',
    'รองศาสตราจารย์ ดร.',
    'ศาสตราจารย์',
    'ศาสตราจารย์ ดร.',
    // ตำแหน่งพิเศษ
    'ศาสตราจารย์พิเศษ',
    'ศาสตราจารย์พิเศษ ดร.',
    'ศาสตราจารย์เกียรติคุณ',
    'ศาสตราจารย์เกียรติคุณ ดร.',
    'อาจารย์พิเศษ',
  ]
  const abbreviationSet = new Set(['ผศ.', 'ผศ.ดร.', 'ผศ. ดร.', 'รศ.', 'รศ.ดร.', 'รศ. ดร.', 'ศ.', 'ศ.ดร.', 'ศ. ดร.', 'อาจารย์.ดร.', 'อ.ดร.'])
  const existing = personnel.value
    .map((p) => p.academic_title)
    .filter((t): t is string => !!t && !defaults.includes(t) && !abbreviationSet.has(t))
  return [...new Set([...defaults, ...existing])]
})

// ─── Personnel Actions ────────────────────────────────────────────────────────
const onDepartmentChange = (deptSlug: string) => {
  if (deptSlug === 'office') {
    form.value.department_name = 'สำนักงานคณบดีคณะครุศาสตร์'
    return
  }
  const dept = departments.value.find((d) => d.slug === deptSlug)
  if (dept) {
    form.value.department_name = dept.name
  }
}

const openCreateDialog = () => {
  isEditing.value = false
  currentId.value = null
  personnelFormTab.value = 'general'

  const defaultDept = departments.value[0]?.slug || 'early-childhood'
  const defaultDeptName = departments.value[0]?.name || 'สาขาวิชาการศึกษาปฐมวัย'

  form.value = {
    name: '',
    name_en: '',
    academic_title: '',
    role_title: 'อาจารย์ประจำสาขาวิชา',
    personnel_type: 'teacher',
    is_head: false,
    department_id: defaultDept,
    department_name: defaultDeptName,
    degrees: '',
    email: '',
    phone: '',
    office_room: '',
    office_hours: '',
    bio: '',
    avatar: '',
    sort_order: personnel.value.length + 1,
    education_history: [],
    expertise_input: '',
    expertise: [],
    publications: [],
    courses: [],
    work_experience: [],
    study_visits: [],
  }
  dialog.value = true
  nextTick(() => {
    if (modalContentRef.value) {
      modalContentRef.value.scrollTop = 0
    }
  })
}

const openEditDialog = (item: PersonnelItem) => {
  isEditing.value = true
  currentId.value = item.id
  personnelFormTab.value = 'general'

  form.value = {
    name: item.name,
    name_en: item.name_en || '',
    academic_title: item.academic_title || '',
    role_title: item.role_title,
    personnel_type: item.personnel_type || 'teacher',
    is_head: !!item.is_head,
    department_id: item.department_id,
    department_name: item.department_name,
    degrees: item.degrees || '',
    email: item.email || '',
    phone: item.phone || '',
    office_room: item.office_room || '',
    office_hours: item.office_hours || '',
    bio: item.bio || '',
    avatar: item.avatar || '',
    sort_order: item.sort_order ?? 1,
    education_history: Array.isArray(item.education_history) ? JSON.parse(JSON.stringify(item.education_history)) : [],
    expertise_input: '',
    expertise: Array.isArray(item.expertise) ? [...item.expertise] : [],
    publications: Array.isArray(item.publications) ? JSON.parse(JSON.stringify(item.publications)) : [],
    courses: Array.isArray(item.courses) ? JSON.parse(JSON.stringify(item.courses)) : [],
    work_experience: Array.isArray(item.work_experience) ? JSON.parse(JSON.stringify(item.work_experience)) : [],
    study_visits: Array.isArray(item.study_visits) ? JSON.parse(JSON.stringify(item.study_visits)) : [],
  }
  dialog.value = true
  nextTick(() => {
    if (modalContentRef.value) {
      modalContentRef.value.scrollTop = 0
    }
  })
}

// Array builders for personnel details
const addEducationRow = () => {
  form.value.education_history.push({ degree: '', field: '', institution: '', year: '' })
}
const removeEducationRow = (index: number) => {
  form.value.education_history.splice(index, 1)
}

const addExpertiseTag = () => {
  const val = form.value.expertise_input.trim()
  if (val && !form.value.expertise.includes(val)) {
    form.value.expertise.push(val)
    form.value.expertise_input = ''
  }
}
const removeExpertiseTag = (index: number) => {
  form.value.expertise.splice(index, 1)
}

const addPublicationRow = () => {
  form.value.publications.push({ title: '', source: '', year: '', type: 'journal', link: '' })
}
const removePublicationRow = (index: number) => {
  form.value.publications.splice(index, 1)
}

const addCourseRow = () => {
  form.value.courses.push({ code: '', name: '', level: 'ปริญญาตรี' })
}
const removeCourseRow = (index: number) => {
  form.value.courses.splice(index, 1)
}

const addExperienceRow = () => {
  form.value.work_experience.push({ period: '', position: '', organization: '', description: '' })
}
const removeExperienceRow = (index: number) => {
  form.value.work_experience.splice(index, 1)
}

const addStudyVisitRow = () => {
  form.value.study_visits.push({ year: '', topic: '', organization: '', location: '' })
}
const removeStudyVisitRow = (index: number) => {
  form.value.study_visits.splice(index, 1)
}

// Upload Avatar
const handleFileUpload = async (e: Event) => {
  const target = e.target as HTMLInputElement
  if (!target.files || target.files.length === 0) return

  const file = target.files[0]
  try {
    const res = await api.uploadFile(file)
    form.value.avatar = res.url
    showToast('อัปโหลดรูปภาพสำเร็จ', 'success')
  } catch (err) {
    console.error(err)
    showToast('อัปโหลดรูปภาพล้มเหลว', 'error')
  }
}

const savePersonnel = async () => {
  if (!form.value.name.trim() || !form.value.role_title.trim()) {
    showToast('กรุณากรอกชื่อและตำแหน่งของอาจารย์', 'warning', 'ข้อมูลไม่ครบถ้วน')
    return
  }

  saveLoading.value = true
  try {
    const payload = {
      ...form.value,
      sort_order: Number(form.value.sort_order) || 1,
    }

    if (isEditing.value && currentId.value) {
      await api.updatePersonnel(currentId.value, payload)
      showToast('บันทึกการแก้ไขข้อมูลสำเร็จ')
    } else {
      await api.createPersonnel(payload)
      showToast('เพิ่มข้อมูลบุคลากรสำเร็จ')
    }

    dialog.value = false
    await fetchPersonnel()
    await fetchDepartments()
  } catch (err) {
    console.error(err)
    showToast('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error')
  } finally {
    saveLoading.value = false
  }
}

const openDeleteConfirm = (id: number) => {
  currentId.value = id
  deleteDialog.value = true
}

const confirmDelete = async () => {
  if (!currentId.value) return
  deleteLoading.value = true
  try {
    await api.deletePersonnel(currentId.value)
    showToast('ลบข้อมูลบุคลากรเรียบร้อยแล้ว')
    deleteDialog.value = false
    await fetchPersonnel()
    await fetchDepartments()
  } catch (err) {
    console.error(err)
    showToast('ลบข้อมูลไม่สำเร็จ', 'error')
  } finally {
    deleteLoading.value = false
  }
}

// Reorder personnel within department (เลื่อนลำดับขึ้นลง)
const movePersonnelOrder = async (index: number, direction: 'up' | 'down') => {
  const targetIndex = direction === 'up' ? index - 1 : index + 1
  const list = filteredPersonnel.value
  if (targetIndex < 0 || targetIndex >= list.length) return

  const itemA = list[index]
  const itemB = list[targetIndex]

  const orders = [
    { id: itemA.id, sort_order: targetIndex + 1 },
    { id: itemB.id, sort_order: index + 1 },
  ]

  try {
    await api.reorderPersonnel(orders)
    await fetchPersonnel()
    showToast('ปรับลำดับบุคลากรสำเร็จ', 'success')
  } catch (err) {
    console.error(err)
    showToast('เกิดข้อผิดพลาดในการจัดลำดับบุคลากร', 'error')
  }
}

// Quick toggle Chairperson for personnel
const toggleHeadPersonnel = async (person: PersonnelItem) => {
  try {
    const newIsHead = !person.is_head
    await api.updatePersonnel(person.id, { is_head: newIsHead })
    showToast(
      newIsHead
        ? `แต่งตั้ง ${person.name} เป็นประธานสาขาเรียบร้อย`
        : `ยกเลิกสถานะประธานสาขาของ ${person.name} เรียบร้อย`,
      'success'
    )
    await fetchPersonnel()
    await fetchDepartments()
  } catch (err) {
    console.error(err)
    showToast('ไม่สามารถเปลี่ยนสถานะประธานสาขาได้', 'error')
  }
}

// ─── Department Actions & Drag and Drop ────────────────────────────────────────
const openCreateDept = () => {
  editingDept.value = null
  deptForm.value = {
    id: 0,
    name: '',
    slug: '',
    degree_title: 'ครุศาสตรบัณฑิต (ค.บ.)',
    head_personnel_id: null,
    sort_order: departments.value.length + 1,
    is_active: true,
  }
  showDeptDialog.value = true
}

const openEditDept = (dept: DepartmentRecord) => {
  editingDept.value = dept
  deptForm.value = {
    id: dept.id,
    name: dept.name,
    slug: dept.slug,
    degree_title: dept.degree_title || '',
    head_personnel_id: dept.head_personnel_id || null,
    sort_order: dept.sort_order ?? 1,
    is_active: dept.is_active ?? true,
  }
  showDeptDialog.value = true
}

const saveDepartment = async () => {
  if (!deptForm.value.name.trim()) {
    showToast('กรุณากรอกชื่อสาขาวิชา', 'warning', 'ข้อมูลไม่ครบถ้วน')
    return
  }

  deptLoading.value = true
  try {
    if (editingDept.value) {
      await api.updateDepartment(editingDept.value.id, deptForm.value)
      showToast('อัปเดตข้อมูลสาขาวิชาเรียบร้อยแล้ว', 'success')
    } else {
      await api.createDepartment(deptForm.value)
      showToast('เพิ่มสาขาวิชาใหม่เรียบร้อยแล้ว', 'success')
    }
    showDeptDialog.value = false
    await fetchDepartments()
    await fetchPersonnel()
  } catch (err) {
    console.error(err)
    showToast('เกิดข้อผิดพลาดในการบันทึกสาขาวิชา', 'error')
  } finally {
    deptLoading.value = false
  }
}

const confirmDeleteDept = (dept: DepartmentRecord) => {
  deleteDeptTarget.value = dept
  deleteDeptDialog.value = true
}

const handleDeleteDept = async () => {
  if (!deleteDeptTarget.value) return
  try {
    await api.deleteDepartment(deleteDeptTarget.value.id)
    showToast('ลบสาขาวิชาเรียบร้อยแล้ว', 'success')
    deleteDeptDialog.value = false
    await fetchDepartments()
    await fetchPersonnel()
  } catch (err) {
    console.error(err)
    showToast('ไม่สามารถลบสาขาวิชาได้', 'error')
  }
}

// Change Department Head from dropdown
const onDeptHeadChange = async (dept: DepartmentRecord, newHeadId: number | null) => {
  try {
    await api.setDepartmentHead(dept.id, newHeadId)
    showToast(`อัปเดตประธานสาขา ${dept.name} เรียบร้อยแล้ว`, 'success')
    await fetchDepartments()
    await fetchPersonnel()
  } catch (err) {
    console.error(err)
    showToast('เกิดข้อผิดพลาดในการกำหนดประธานสาขา', 'error')
  }
}

// Move Department order up/down (Alternative to drag)
const moveDeptOrder = async (index: number, direction: 'up' | 'down') => {
  const targetIndex = direction === 'up' ? index - 1 : index + 1
  if (targetIndex < 0 || targetIndex >= departments.value.length) return

  const list = [...departments.value]
  const temp = list[index]
  list[index] = list[targetIndex]
  list[targetIndex] = temp

  const orders = list.map((item, idx) => ({
    id: item.id,
    sort_order: idx + 1,
  }))

  try {
    await api.reorderDepartments(orders)
    departments.value = list
    showToast('ปรับลำดับสาขาวิชาสำเร็จ', 'success')
  } catch (err) {
    console.error(err)
    showToast('เกิดข้อผิดพลาดในการจัดลำดับสาขาวิชา', 'error')
  }
}

// ─── Native HTML5 Drag and Drop for Departments ───────────────────────────────
const onDeptDragStart = (e: DragEvent, index: number) => {
  draggingDeptIndex.value = index
  if (e.dataTransfer) {
    e.dataTransfer.effectAllowed = 'move'
    e.dataTransfer.setData('text/plain', index.toString())
  }
}

const onDeptDragOver = (e: DragEvent, index: number) => {
  e.preventDefault()
  if (e.dataTransfer) {
    e.dataTransfer.dropEffect = 'move'
  }
  dragOverDeptIndex.value = index
}

const onDeptDrop = async (e: DragEvent, dropIndex: number) => {
  e.preventDefault()
  const fromIndex = draggingDeptIndex.value
  if (fromIndex === null || fromIndex === dropIndex) {
    draggingDeptIndex.value = null
    dragOverDeptIndex.value = null
    return
  }

  const list = [...departments.value]
  const [draggedItem] = list.splice(fromIndex, 1)
  list.splice(dropIndex, 0, draggedItem)

  departments.value = list
  draggingDeptIndex.value = null
  dragOverDeptIndex.value = null

  const orders = list.map((item, idx) => ({
    id: item.id,
    sort_order: idx + 1,
  }))

  try {
    await api.reorderDepartments(orders)
    showToast('บันทึกการจัดลำดับสาขาวิชาใหม่สำเร็จ', 'success')
  } catch (err) {
    console.error('Failed to reorder departments:', err)
    showToast('เกิดข้อผิดพลาดในการบันทึกลำดับสาขาวิชา', 'error')
    await fetchDepartments()
  }
}

const onDeptDragEnd = () => {
  draggingDeptIndex.value = null
  dragOverDeptIndex.value = null
}

// Initial Load
onMounted(async () => {
  await fetchDepartments()
  await fetchPersonnel()
})
</script>

<template>
  <div class="space-y-6 pb-16">
    <!-- Page Header & Mode Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-1.5 text-xs text-slate-400 mb-1">
          <v-icon icon="mdi-home-outline" size="13" />
          <span>/</span><span>Admin</span><span>/</span>
          <span class="text-slate-700 font-semibold">บุคลากรและสาขาวิชา</span>
        </div>
        <h1 class="text-xl font-black text-slate-900">จัดการบุคลากรและสาขาวิชา</h1>
        <p class="text-xs text-slate-500 mt-0.5">
          จัดการรายชื่ออาจารย์ ตำแหน่งหน้าที่ และสาขาวิชาในคณะครุศาสตร์
        </p>
      </div>

      <!-- Pill Mode Tabs -->
      <div class="flex items-center gap-1 bg-slate-200/80 p-1 rounded-2xl border border-slate-200 shadow-2xs self-start sm:self-auto">
        <button
          type="button"
          class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer"
          :class="
            activeTab === 'personnel'
              ? 'bg-white text-emerald-700 shadow-xs ring-1 ring-slate-900/5'
              : 'text-slate-600 hover:text-slate-900'
          "
          @click="activeTab = 'personnel'"
        >
          <v-icon icon="mdi-account-group-outline" size="16" />
          <span>คณาจารย์และบุคลากร</span>
          <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
            {{ personnel.length }}
          </span>
        </button>

        <button
          type="button"
          class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer"
          :class="
            activeTab === 'departments'
              ? 'bg-white text-emerald-700 shadow-xs ring-1 ring-slate-900/5'
              : 'text-slate-600 hover:text-slate-900'
          "
          @click="activeTab = 'departments'"
        >
          <v-icon icon="mdi-view-headline" size="16" />
          <span>สาขาวิชา & ลำดับการแสดงผล</span>
          <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
            {{ departments.length }}
          </span>
        </button>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
         TAB 1: จัดการบุคลากร & อาจารย์ (Personnel Tab)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div v-if="activeTab === 'personnel'" class="space-y-4">
      <!-- Action Bar & Filters -->
      <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-3">
        <div class="flex flex-col xl:flex-row items-stretch xl:items-center justify-between gap-3">
          <!-- Search Field -->
          <div class="flex-1 max-w-md relative">
            <v-icon icon="mdi-magnify" size="16" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
            <input
              v-model="search"
              type="text"
              placeholder="ค้นหาชื่อ, ตำแหน่ง, สังกัด, วุฒิการศึกษา..."
              class="w-full pl-9 pr-3.5 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 transition-all shadow-2xs"
            />
          </div>

          <!-- Type Filter Pills -->
          <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200 self-start sm:self-auto shrink-0">
            <button
              type="button"
              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer"
              :class="selectedType === 'all' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
              @click="selectedType = 'all'"
            >
              ทั้งหมด ({{ personnel.length }})
            </button>
            <button
              type="button"
              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5"
              :class="selectedType === 'teacher' ? 'bg-white text-emerald-800 shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
              @click="selectedType = 'teacher'"
            >
              <v-icon icon="mdi-school-outline" size="14" class="text-emerald-600" />
              <span>อาจารย์</span>
              <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-emerald-100 text-emerald-800">
                {{ personnel.filter(p => (p.personnel_type || 'teacher') === 'teacher').length }}
              </span>
            </button>
            <button
              type="button"
              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5"
              :class="selectedType === 'staff' ? 'bg-white text-sky-800 shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
              @click="selectedType = 'staff'"
            >
              <v-icon icon="mdi-briefcase-account-outline" size="14" class="text-sky-600" />
              <span>บุคลากร</span>
              <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-sky-100 text-sky-800">
                {{ personnel.filter(p => p.personnel_type === 'staff').length }}
              </span>
            </button>
          </div>

          <!-- Department Filter & Add Button -->
          <div class="flex flex-wrap items-center gap-3">
            <div class="w-full sm:w-72">
              <v-select
                v-model="selectedDept"
                :items="deptFilterOptions"
                item-title="title"
                item-value="value"
                density="compact"
                variant="outlined"
                hide-details
                rounded="xl"
                color="primary"
                bg-color="white"
                class="custom-v-select text-xs font-semibold"
              >
                <template #prepend-inner>
                  <v-icon icon="mdi-filter-variant" size="16" class="text-slate-400 mr-1" />
                </template>
              </v-select>
            </div>

            <button
              type="button"
              class="inline-flex items-center gap-1.5 px-4 h-10 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer shrink-0"
              @click="openCreateDialog"
            >
              <v-icon icon="mdi-plus" size="16" />
              <span>เพิ่มอาจารย์ / บุคลากร</span>
            </button>
          </div>
        </div>
      </div>
      <!-- Personnel Table -->
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div v-if="loading" class="py-16 text-center text-slate-400">
          <v-icon icon="mdi-loading" size="36" class="animate-spin text-emerald-600 mb-2" />
          <div class="text-xs">กำลังโหลดข้อมูลบุคลากร...</div>
        </div>

        <div v-else-if="filteredPersonnel.length === 0" class="py-16 text-center text-slate-400">
          <v-icon icon="mdi-account-search-outline" size="48" class="text-slate-300 mb-2" />
          <div class="text-sm font-bold text-slate-700">ไม่พบบุคลากรที่ค้นหา</div>
          <p class="text-xs text-slate-400 mt-1">ลองเปลี่ยนคำค้นหาหรือเลือกดูสาขาวิชาอื่น</p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/70 border-b border-slate-200 text-xs font-bold text-slate-700">
                <th class="py-3.5 px-3 w-20 text-center">ลำดับ</th>
                <th class="py-3.5 px-3 w-14 text-center">รูป</th>
                <th class="py-3.5 px-4">ชื่อ - นามสกุล</th>
                <th class="py-3.5 px-4">ตำแหน่ง / หน้าที่</th>
                <th class="py-3.5 px-4">สาขาวิชา</th>
                <th class="py-3.5 px-4">ช่องทางติดต่อ</th>
                <th class="py-3.5 px-4 w-28 text-center">จัดการ</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
              <tr
                v-for="(person, idx) in filteredPersonnel"
                :key="person.id"
                class="hover:bg-slate-50/70 transition-colors group"
                :class="person.is_head ? 'bg-amber-50/20' : ''"
              >
                <!-- ลำดับ + เลื่อนขึ้นลง -->
                <td class="py-3 px-3 text-center">
                  <div class="flex items-center justify-center gap-1">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 text-slate-600 font-bold text-[11px]">
                      {{ idx + 1 }}
                    </span>
                    <div class="inline-flex flex-col gap-0.5 opacity-60 group-hover:opacity-100 transition-opacity">
                      <button
                        type="button"
                        :disabled="idx === 0"
                        class="p-0.5 rounded text-slate-400 hover:text-slate-800 disabled:opacity-20 cursor-pointer"
                        title="เลื่อนขึ้น"
                        @click="movePersonnelOrder(idx, 'up')"
                      >
                        <v-icon icon="mdi-chevron-up" size="12" />
                      </button>
                      <button
                        type="button"
                        :disabled="idx === filteredPersonnel.length - 1"
                        class="p-0.5 rounded text-slate-400 hover:text-slate-800 disabled:opacity-20 cursor-pointer"
                        title="เลื่อนลง"
                        @click="movePersonnelOrder(idx, 'down')"
                      >
                        <v-icon icon="mdi-chevron-down" size="12" />
                      </button>
                    </div>
                  </div>
                </td>

                <!-- รูปประจำตัว -->
                <td class="py-3 px-3 text-center">
                  <img
                    v-if="person.avatar"
                    :src="person.avatar"
                    alt=""
                    class="w-10 h-10 rounded-full object-cover border border-slate-200 mx-auto shadow-2xs"
                  />
                  <div v-else class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold mx-auto border border-emerald-100">
                    <v-icon icon="mdi-account" size="20" />
                  </div>
                </td>

                <!-- ชื่อ นามสกุล -->
                <td class="py-3 px-4">
                  <div class="font-bold text-slate-900 text-[13px] flex items-center gap-1.5">
                    <span>{{ person.name }}</span>
                    <!-- Crown Badge for Chairperson -->
                    <span
                      v-if="person.is_head"
                      class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-black border border-amber-300"
                      title="ประธานสาขาวิชา"
                    >
                      <v-icon icon="mdi-crown" size="11" class="text-amber-600" />
                      ประธานสาขา
                    </span>
                  </div>
                  <div v-if="person.name_en" class="text-[11px] text-slate-400">
                    {{ person.name_en }}
                  </div>
                </td>

                <!-- ตำแหน่ง -->
                <td class="py-3 px-4">
                  <div class="flex items-center gap-1.5 flex-wrap">
                    <span
                      class="px-1.5 py-0.5 rounded text-[10px] font-bold"
                      :class="person.personnel_type === 'staff' ? 'bg-sky-100 text-sky-800' : 'bg-emerald-100 text-emerald-800'"
                    >
                      {{ person.personnel_type === 'staff' ? 'บุคลากร' : 'อาจารย์' }}
                    </span>
                    <span class="font-medium text-slate-700">{{ person.role_title }}</span>
                  </div>
                  <div v-if="person.academic_title" class="mt-1 flex items-center gap-1">
                    <span
                      class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold"
                      :class="
                        person.academic_title.includes('ศ.') || person.academic_title.includes('ศาสตราจารย์')
                          ? 'bg-rose-50 text-rose-700 border border-rose-200'
                          : person.academic_title.includes('รศ') || person.academic_title.includes('รองศาสตราจารย์')
                          ? 'bg-purple-50 text-purple-700 border border-purple-200'
                          : person.academic_title.includes('ผศ') || person.academic_title.includes('ผู้ช่วยศาสตราจารย์')
                          ? 'bg-amber-50 text-amber-700 border border-amber-200'
                          : 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                      "
                    >
                      <v-icon icon="mdi-certificate-outline" size="12" />
                      <span>{{ person.academic_title }}</span>
                    </span>
                  </div>
                </td>

                <!-- สาขาวิชา -->
                <td class="py-3 px-4">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                    {{ person.department_name }}
                  </span>
                </td>

                <!-- ช่องทางติดต่อ -->
                <td class="py-3 px-4 text-[11px] text-slate-500">
                  <div v-if="person.email" class="flex items-center gap-1 truncate max-w-[180px]">
                    <v-icon icon="mdi-email-outline" size="13" class="text-slate-400" />
                    <span>{{ person.email }}</span>
                  </div>
                  <div v-if="person.phone" class="flex items-center gap-1 text-slate-400">
                    <v-icon icon="mdi-phone-outline" size="13" />
                    <span>{{ person.phone }}</span>
                  </div>
                  <div v-if="person.office_room" class="flex items-center gap-1 text-slate-400">
                    <v-icon icon="mdi-map-marker-outline" size="13" />
                    <span class="truncate max-w-[180px]">{{ person.office_room }}</span>
                  </div>
                </td>

                <!-- จัดการ -->
                <td class="py-3 px-4 text-center whitespace-nowrap">
                  <div class="flex items-center justify-center gap-1">
                    <!-- Toggle Chairperson button (only for teachers) -->
                    <button
                      v-if="(person.personnel_type || 'teacher') === 'teacher'"
                      type="button"
                      class="p-1.5 rounded-xl border border-slate-200 hover:border-amber-300 transition-colors cursor-pointer"
                      :class="person.is_head ? 'bg-amber-50 text-amber-600' : 'text-slate-400 hover:text-amber-600 hover:bg-amber-50'"
                      :title="person.is_head ? 'ยกเลิกสถานะประธานสาขา' : 'ตั้งเป็นประธานสาขาวิชา'"
                      @click="toggleHeadPersonnel(person)"
                    >
                      <v-icon :icon="person.is_head ? 'mdi-crown' : 'mdi-crown-outline'" size="16" />
                    </button>

                    <button
                      type="button"
                      class="p-1.5 rounded-xl border border-slate-200 hover:border-emerald-300 text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 transition-colors cursor-pointer"
                      title="แก้ไขข้อมูล"
                      @click="openEditDialog(person)"
                    >
                      <v-icon icon="mdi-pencil-outline" size="15" />
                    </button>

                    <button
                      type="button"
                      class="p-1.5 rounded-xl border border-slate-200 hover:border-rose-300 text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                      title="ลบ"
                      @click="openDeleteConfirm(person.id)"
                    >
                      <v-icon icon="mdi-trash-can-outline" size="15" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
         TAB 2: จัดการสาขาวิชา & จัดลำดับการแสดงผล แบบลากวาง (Drag & Drop)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div v-else class="space-y-4">
      <!-- Toolbar Header -->
      <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs flex flex-wrap items-center justify-between gap-3">
        <div>
          <div class="flex items-center gap-2">
            <h2 class="text-sm font-bold text-slate-800">สาขาวิชาทั้งหมดในคณะครุศาสตร์</h2>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
              {{ departments.length }} สาขาวิชา
            </span>
          </div>
          <p class="text-[11px] text-slate-400 mt-0.5">
            💡 <strong>ลากวาง (Drag & Drop)</strong> แถบสาขาวิชาเพื่อจัดลำดับว่าสาขาวิชาไหนจะแสดงผลก่อน-หลังในหน้าเว็บไซต์
          </p>
        </div>

        <button
          type="button"
          class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer shrink-0"
          @click="openCreateDept"
        >
          <v-icon icon="mdi-plus" size="16" />
          <span>เพิ่มสาขาวิชาใหม่</span>
        </button>
      </div>

      <!-- Drag and Drop Department Cards -->
      <div v-if="deptsLoading" class="py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
        <v-icon icon="mdi-loading" size="36" class="animate-spin text-emerald-600 mb-2" />
        <p class="text-xs">กำลังโหลดข้อมูลสาขาวิชา...</p>
      </div>

      <div v-else class="space-y-2.5">
        <div
          v-for="(dept, idx) in departments"
          :key="dept.id"
          draggable="true"
          class="bg-white rounded-2xl border transition-all duration-150 p-4 select-none"
          :class="[
            draggingDeptIndex === idx ? 'opacity-40 scale-[0.98] border-dashed border-emerald-400' : 'border-slate-200/90 hover:border-slate-300 shadow-xs',
            dragOverDeptIndex === idx ? 'border-2 border-emerald-500 bg-emerald-50/40 ring-4 ring-emerald-500/10' : '',
          ]"
          @dragstart="onDeptDragStart($event, idx)"
          @dragover="onDeptDragOver($event, idx)"
          @drop="onDeptDrop($event, idx)"
          @dragend="onDeptDragEnd"
        >
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Left: Drag Handle, Order, Name, Degree -->
            <div class="flex items-center gap-3.5 flex-1 min-w-0">
              <!-- Drag Handle -->
              <div
                class="cursor-grab active:cursor-grabbing p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors shrink-0"
                title="คลิกลากเพื่อจัดลำดับ"
              >
                <v-icon icon="mdi-drag-vertical" size="22" />
              </div>

              <!-- Order Badge -->
              <div class="w-7 h-7 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200">
                {{ idx + 1 }}
              </div>

              <!-- Info -->
              <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                  <h3 class="text-sm font-bold text-slate-900 leading-tight">
                    {{ dept.name }}
                  </h3>
                  <span class="text-[10px] font-mono px-2 py-0.5 rounded-md bg-slate-100 text-slate-500">
                    {{ dept.slug }}
                  </span>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-400 mt-1">
                  <span v-if="dept.degree_title" class="font-medium text-slate-600">
                    {{ dept.degree_title }}
                  </span>
                  <span>·</span>
                  <span class="text-emerald-700 font-semibold">
                    {{ dept.personnels_count ?? 0 }} อาจารย์
                  </span>
                </div>
              </div>
            </div>

            <!-- Center: Chairperson (การเลือกประธานสาขาวิชา) -->
            <div class="flex items-center gap-2 sm:w-72 w-full">
              <div class="text-[11px] font-bold text-slate-500 whitespace-nowrap flex items-center gap-1 shrink-0">
                <v-icon icon="mdi-crown" size="15" class="text-amber-500" />
                <span>ประธานสาขา:</span>
              </div>
              <div class="flex-1 min-w-0">
                <v-select
                  :model-value="dept.head_personnel_id ?? null"
                  :items="getDeptHeadOptions(dept)"
                  item-title="title"
                  item-value="value"
                  density="compact"
                  variant="outlined"
                  hide-details
                  rounded="xl"
                  color="primary"
                  bg-color="white"
                  class="custom-v-select text-xs font-medium"
                  placeholder="เลือกประธานสาขา"
                  @update:model-value="(val) => onDeptHeadChange(dept, val !== null ? Number(val) : null)"
                />
              </div>
            </div>

            <!-- Right: Order buttons and Actions -->
            <div class="flex items-center justify-end gap-1.5 shrink-0">
              <!-- Up / Down buttons -->
              <button
                type="button"
                :disabled="idx === 0"
                class="p-1.5 rounded-lg border border-slate-200 text-slate-400 hover:text-slate-800 hover:bg-slate-100 disabled:opacity-20 cursor-pointer"
                title="เลื่อนขึ้น"
                @click="moveDeptOrder(idx, 'up')"
              >
                <v-icon icon="mdi-arrow-up" size="14" />
              </button>
              <button
                type="button"
                :disabled="idx === departments.length - 1"
                class="p-1.5 rounded-lg border border-slate-200 text-slate-400 hover:text-slate-800 hover:bg-slate-100 disabled:opacity-20 cursor-pointer"
                title="เลื่อนลง"
                @click="moveDeptOrder(idx, 'down')"
              >
                <v-icon icon="mdi-arrow-down" size="14" />
              </button>

              <div class="h-4 w-px bg-slate-200 mx-1" />

              <button
                type="button"
                class="p-1.5 rounded-xl border border-slate-200 hover:border-emerald-300 text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 transition-colors cursor-pointer"
                title="แก้ไขข้อมูลสาขาวิชา"
                @click="openEditDept(dept)"
              >
                <v-icon icon="mdi-pencil-outline" size="15" />
              </button>
              <button
                type="button"
                class="p-1.5 rounded-xl border border-slate-200 hover:border-rose-300 text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                title="ลบสาขาวิชา"
                @click="confirmDeleteDept(dept)"
              >
                <v-icon icon="mdi-trash-can-outline" size="15" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
         DIALOG 1: เพิ่ม / แก้ไข บุคลากร (Full Comprehensive Modal with Tabs)
    ══════════════════════════════════════════════════════════════════════════ -->
    <v-dialog v-model="dialog" max-width="840" width="840" persistent>
      <v-card
        height="720"
        class="!rounded-3xl !overflow-hidden !p-0 bg-white border border-slate-100 shadow-2xl !flex !flex-col personnel-modal-card"
        style="height: min(720px, 88vh) !important; min-height: min(720px, 88vh) !important; max-height: 88vh !important; display: flex !important; flex-direction: column !important;"
      >
        <!-- Dialog Header -->
        <div class="bg-slate-900 p-5 text-white flex items-center justify-between shrink-0">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
              <v-icon :icon="isEditing ? 'mdi-account-edit' : 'mdi-account-plus'" size="22" />
            </div>
            <div>
              <h3 class="text-sm font-bold text-white">
                {{ isEditing ? 'แก้ไขข้อมูลคณาจารย์ / บุคลากร' : 'เพิ่มข้อมูลคณาจารย์ / บุคลากร' }}
              </h3>
              <p class="text-[11px] text-slate-400">กรอกข้อมูลประวัติ ผลงาน และช่องทางติดต่อตามมาตรฐาน</p>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-xl text-slate-400 hover:text-white hover:bg-white/10 flex items-center justify-center cursor-pointer transition-colors"
            @click="dialog = false"
          >
            <v-icon icon="mdi-close" size="18" />
          </button>
        </div>

        <!-- Form Navigation Subtabs -->
        <div class="flex border-b border-slate-200 bg-slate-50/80 px-6 pt-2 overflow-x-auto gap-2 shrink-0">
          <button
            type="button"
            class="px-4 py-2.5 text-xs font-bold border-b-2 transition-all cursor-pointer whitespace-nowrap"
            :class="personnelFormTab === 'general' ? 'border-emerald-600 text-emerald-800 bg-white rounded-t-xl shadow-2xs' : 'border-transparent text-slate-500 hover:text-slate-800'"
            @click="switchPersonnelTab('general')"
          >
            1. ข้อมูลพื้นฐาน & สังกัด
          </button>
          <button
            type="button"
            class="px-4 py-2.5 text-xs font-bold border-b-2 transition-all cursor-pointer whitespace-nowrap"
            :class="personnelFormTab === 'education' ? 'border-emerald-600 text-emerald-800 bg-white rounded-t-xl shadow-2xs' : 'border-transparent text-slate-500 hover:text-slate-800'"
            @click="switchPersonnelTab('education')"
          >
            2. ประวัติการศึกษา ({{ form.education_history.length }})
          </button>
          <button
            type="button"
            class="px-4 py-2.5 text-xs font-bold border-b-2 transition-all cursor-pointer whitespace-nowrap"
            :class="personnelFormTab === 'works' ? 'border-emerald-600 text-emerald-800 bg-white rounded-t-xl shadow-2xs' : 'border-transparent text-slate-500 hover:text-slate-800'"
            @click="switchPersonnelTab('works')"
          >
            3. ผลงาน & การสอน ({{ form.publications.length + form.courses.length }})
          </button>
        </div>

        <!-- Dialog Scrollable Content -->
        <div
          ref="modalContentRef"
          class="p-6 space-y-5 flex-1 min-h-0 overflow-y-auto personnel-modal-body"
          style="flex: 1 1 0% !important; min-height: 0 !important; overflow-y: auto !important;"
        >
          <!-- TAB A: ข้อมูลพื้นฐาน -->
          <div v-show="personnelFormTab === 'general'" class="space-y-4">
            <!-- 0. เลือกประเภท: อาจารย์ vs บุคลากร -->
            <div class="p-3 bg-slate-50/80 rounded-2xl border border-slate-200">
              <label class="block text-xs font-bold text-slate-700 mb-2">
                ประเภทบุคลากร <span class="text-rose-500">*</span>
              </label>
              <div class="grid grid-cols-2 gap-3">
                <button
                  type="button"
                  class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border text-xs font-bold transition-all cursor-pointer"
                  :class="form.personnel_type === 'teacher'
                    ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs'
                    : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'"
                  @click="setPersonnelType('teacher')"
                >
                  <v-icon icon="mdi-school-outline" size="18" />
                  <span>อาจารย์ (Faculty / Teacher)</span>
                </button>
                <button
                  type="button"
                  class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border text-xs font-bold transition-all cursor-pointer"
                  :class="form.personnel_type === 'staff'
                    ? 'bg-sky-600 text-white border-sky-600 shadow-xs'
                    : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'"
                  @click="setPersonnelType('staff')"
                >
                  <v-icon icon="mdi-briefcase-account-outline" size="18" />
                  <span>บุคลากร / เจ้าหน้าที่ (Staff)</span>
                </button>
              </div>
            </div>

            <!-- 1. ชื่อ - นามสกุล -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                  ชื่อ - นามสกุล (ภาษาไทย) <span class="text-rose-500">*</span>
                </label>
                <input
                  v-model="form.name"
                  type="text"
                  :placeholder="form.personnel_type === 'teacher' ? 'เช่น ผศ.ดร.สมชาย ใจดี' : 'เช่น นายสมชาย ใจดี'"
                  class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 font-medium"
                />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Name in English</label>
                <input
                  v-model="form.name_en"
                  type="text"
                  :placeholder="form.personnel_type === 'teacher' ? 'เช่น Asst. Prof. Dr. Somchai Jaidee' : 'เช่น Mr. Somchai Jaidee'"
                  class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600"
                />
              </div>
            </div>

            <!-- 2. ตำแหน่งทางวิชาการ (สำหรับอาจารย์) & ตำแหน่งหน้าที่รับผิดชอบ -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- ถ้าเป็นอาจารย์: แสดงตำแหน่งทางวิชาการ -->
              <div v-if="form.personnel_type === 'teacher'">
                <label class="block text-xs font-bold text-slate-700 mb-1">
                  ตำแหน่งทางวิชาการ
                  <span class="text-[11px] font-normal text-slate-400 ml-1">
                    (เช่น อาจารย์, อาจารย์ ดร., ผศ., ผศ.ดร., รศ., ศ.)
                  </span>
                </label>
                <v-select
                  v-model="form.academic_title"
                  :items="academicTitleOptions"
                  density="compact"
                  variant="outlined"
                  hide-details
                  rounded="xl"
                  color="primary"
                  bg-color="white"
                  class="custom-v-select text-xs font-semibold"
                  placeholder="เลือกตำแหน่งทางวิชาการ เช่น อาจารย์, ผศ.ดร."
                  clearable
                />
              </div>

              <!-- ตำแหน่งหน้าที่รับผิดชอบ: ถ้าเป็นบุคลากรให้เป็น Dropdown 4 ตำแหน่ง, ถ้าเป็นอาจารย์ให้พิมพ์หรือระบุ -->
              <div :class="form.personnel_type === 'staff' ? 'sm:col-span-2' : ''">
                <label class="block text-xs font-bold text-slate-700 mb-1">
                  ตำแหน่งหน้าที่รับผิดชอบ <span class="text-rose-500">*</span>
                  <span v-if="form.personnel_type === 'staff'" class="text-[11px] font-normal text-slate-400 ml-1">
                    (เลือกตำแหน่งบุคลากร)
                  </span>
                </label>
                <v-select
                  v-if="form.personnel_type === 'staff'"
                  v-model="form.role_title"
                  :items="staffRoleOptions"
                  density="compact"
                  variant="outlined"
                  hide-details
                  rounded="xl"
                  color="primary"
                  bg-color="white"
                  class="custom-v-select text-xs font-semibold"
                  placeholder="เลือกตำแหน่งบุคลากร"
                />
                <input
                  v-else
                  v-model="form.role_title"
                  type="text"
                  placeholder="เช่น อาจารย์ประจำสาขาวิชา, ประธานสาขาวิชา..."
                  class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 font-medium"
                />
              </div>
            </div>

            <!-- Department & Is Head Toggle -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                  {{ form.personnel_type === 'teacher' ? 'สาขาวิชาที่สังกัด' : 'สาขาวิชา / หน่วยงานที่สังกัด' }} <span class="text-rose-500">*</span>
                </label>
                <v-select
                  v-model="form.department_id"
                  :items="deptSelectOptions"
                  item-title="title"
                  item-value="value"
                  density="compact"
                  variant="outlined"
                  hide-details
                  rounded="xl"
                  color="primary"
                  bg-color="white"
                  class="custom-v-select text-xs font-medium"
                  placeholder="เลือกสาขาวิชา / หน่วยงาน"
                  @update:model-value="onDepartmentChange"
                />
              </div>

              <!-- แต่งตั้งเป็นประธานสาขา: แสดงเฉพาะอาจารย์ -->
              <div v-if="form.personnel_type === 'teacher'" class="pt-4">
                <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors w-full">
                  <input
                    v-model="form.is_head"
                    type="checkbox"
                    class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500/20 border-slate-300 cursor-pointer"
                  />
                  <div class="flex items-center gap-1.5">
                    <v-icon icon="mdi-crown" size="16" class="text-amber-500" />
                    <span>ประธานสาขาวิชา</span>
                  </div>
                </label>
              </div>
            </div>

            <!-- Degrees Summary -->
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">วุฒิการศึกษาย่อ (Degree Summary)</label>
              <input
                v-model="form.degrees"
                type="text"
                placeholder="เช่น ค.ด. (การศึกษาปฐมวัย), ค.ม. (การศึกษาปฐมวัย), ค.บ. (การศึกษาปฐมวัย)"
                class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600"
              />
            </div>

            <!-- Avatar Upload & URL -->
            <div class="space-y-2 pt-1">
              <label class="block text-xs font-bold text-slate-700">รูปภาพประจำตัว (Avatar Portrait)</label>
              <div class="flex items-center gap-3">
                <input
                  v-model="form.avatar"
                  type="text"
                  placeholder="URL รูปภาพ หรือกดปุ่มอัปโหลดรูปภาพ"
                  class="flex-1 px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600"
                />
                <label class="cursor-pointer inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold shrink-0 transition-colors border border-slate-200">
                  <v-icon icon="mdi-cloud-upload-outline" size="16" />
                  <span>อัปโหลดรูป</span>
                  <input type="file" accept="image/*" class="hidden" @change="handleFileUpload" />
                </label>
              </div>
              <div v-if="form.avatar" class="flex items-center gap-3 mt-2 p-2 bg-slate-50 rounded-xl border border-slate-200">
                <img :src="form.avatar" alt="Preview" class="w-14 h-14 rounded-full object-cover border border-slate-300" />
                <span class="text-xs text-slate-500 truncate max-w-sm">{{ form.avatar }}</span>
              </div>
            </div>
          </div>

          <!-- TAB B: ประวัติการศึกษา -->
          <div v-show="personnelFormTab === 'education'" class="space-y-4">
            <div class="flex items-center justify-between">
              <label class="text-xs font-bold text-slate-700">ประวัติการศึกษา (Education Background)</label>
              <button
                type="button"
                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-bold transition-colors cursor-pointer border border-emerald-200"
                @click="addEducationRow"
              >
                <v-icon icon="mdi-plus" size="14" />
                <span>เพิ่มประวัติการศึกษา</span>
              </button>
            </div>

            <div v-if="!form.education_history.length" class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-slate-400 text-xs">
              ยังไม่มีข้อมูลประวัติการศึกษา คลิกปุ่มด้านบนเพื่อเพิ่ม
            </div>

            <div v-else class="space-y-3">
              <div
                v-for="(edu, i) in form.education_history"
                :key="i"
                class="p-3 bg-slate-50 rounded-xl border border-slate-200 grid grid-cols-1 sm:grid-cols-4 gap-2.5 items-center"
              >
                <input
                  v-model="edu.degree"
                  type="text"
                  placeholder="วุฒิการศึกษา เช่น ค.ด."
                  class="px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-600"
                />
                <input
                  v-model="edu.field"
                  type="text"
                  placeholder="สาขาวิชา เช่น การศึกษาปฐมวัย"
                  class="px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-600"
                />
                <input
                  v-model="edu.institution"
                  type="text"
                  placeholder="สถาบัน เช่น จุฬาลงกรณ์มหาวิทยาลัย"
                  class="px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-600"
                />
                <div class="flex items-center gap-2">
                  <input
                    v-model="edu.year"
                    type="text"
                    placeholder="ปี พ.ศ."
                    class="w-20 px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-600"
                  />
                  <button
                    type="button"
                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                    title="ลบแถว"
                    @click="removeEducationRow(i)"
                  >
                    <v-icon icon="mdi-trash-can-outline" size="16" />
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB C: ผลงาน ความเชี่ยวชาญ และการสอน -->
          <div v-show="personnelFormTab === 'works'" class="space-y-6">
            <!-- 1. ความเชี่ยวชาญ -->
            <div class="space-y-2">
              <label class="block text-xs font-bold text-slate-700">ความเชี่ยวชาญทางวิชาการ (Expertise)</label>
              <div class="flex gap-2">
                <input
                  v-model="form.expertise_input"
                  type="text"
                  placeholder="พิมพ์ความเชี่ยวชาญแล้วกด Enter หรือปุ่มเพิ่ม..."
                  class="flex-1 px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600"
                  @keydown.enter.prevent="addExpertiseTag"
                />
                <button
                  type="button"
                  class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold cursor-pointer"
                  @click="addExpertiseTag"
                >
                  + เพิ่ม
                </button>
              </div>
              <div class="flex flex-wrap gap-1.5 pt-1">
                <span
                  v-for="(tag, idx) in form.expertise"
                  :key="idx"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs bg-emerald-50 text-emerald-800 border border-emerald-200"
                >
                  <span>{{ tag }}</span>
                  <button type="button" class="hover:text-rose-600 cursor-pointer" @click="removeExpertiseTag(idx)">
                    <v-icon icon="mdi-close" size="12" />
                  </button>
                </span>
              </div>
            </div>

            <!-- 2. ผลงานทางวิชาการ & วิจัย -->
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-slate-700">ผลงานทางวิชาการ / วิจัย / ตำรา</label>
                <button
                  type="button"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 cursor-pointer"
                  @click="addPublicationRow"
                >
                  <v-icon icon="mdi-plus" size="14" />
                  <span>เพิ่มผลงาน</span>
                </button>
              </div>
              <div v-if="!form.publications.length" class="p-4 text-center bg-slate-50 rounded-xl text-slate-400 text-xs border border-dashed">
                ยังไม่มีรายการผลงาน
              </div>
              <div v-else class="space-y-2">
                <div
                  v-for="(pub, i) in form.publications"
                  :key="i"
                  class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2"
                >
                  <div class="flex items-center gap-2">
                    <input
                      v-model="pub.title"
                      type="text"
                      placeholder="ชื่อผลงานวิจัย / บทความ / หนังสือ *"
                      class="flex-1 px-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-600 font-semibold"
                    />
                    <div class="w-48 shrink-0">
                      <v-select
                        v-model="pub.type"
                        :items="pubTypeOptions"
                        item-title="title"
                        item-value="value"
                        density="compact"
                        variant="outlined"
                        hide-details
                        rounded="lg"
                        color="primary"
                        bg-color="white"
                        class="custom-v-select text-xs"
                      />
                    </div>
                    <button
                      type="button"
                      class="p-1 text-slate-400 hover:text-rose-600 cursor-pointer"
                      @click="removePublicationRow(i)"
                    >
                      <v-icon icon="mdi-trash-can-outline" size="16" />
                    </button>
                  </div>
                  <div class="flex gap-2">
                    <input
                      v-model="pub.source"
                      type="text"
                      placeholder="แหล่งเผยแพร่ / วารสารปีที่..."
                      class="flex-1 px-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg"
                    />
                    <input
                      v-model="pub.year"
                      type="text"
                      placeholder="ปี พ.ศ."
                      class="w-24 px-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- 3. รายวิชาที่สอน -->
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-slate-700">รายวิชาที่รับผิดชอบการสอน</label>
                <button
                  type="button"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 cursor-pointer"
                  @click="addCourseRow"
                >
                  <v-icon icon="mdi-plus" size="14" />
                  <span>เพิ่มวิชาที่สอน</span>
                </button>
              </div>
              <div v-if="!form.courses.length" class="p-4 text-center bg-slate-50 rounded-xl text-slate-400 text-xs border border-dashed">
                ยังไม่มีรายวิชาที่สอน
              </div>
              <div v-else class="space-y-2">
                <div
                  v-for="(course, i) in form.courses"
                  :key="i"
                  class="p-2.5 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-2"
                >
                  <input
                    v-model="course.code"
                    type="text"
                    placeholder="รหัสวิชา เช่น ECE101"
                    class="w-28 px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg font-mono font-semibold"
                  />
                  <input
                    v-model="course.name"
                    type="text"
                    placeholder="ชื่อรายวิชา"
                    class="flex-1 px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg"
                  />
                  <div class="w-36 shrink-0">
                    <v-select
                      v-model="course.level"
                      :items="courseLevelOptions"
                      density="compact"
                      variant="outlined"
                      hide-details
                      rounded="lg"
                      color="primary"
                      bg-color="white"
                      class="custom-v-select text-xs"
                    />
                  </div>
                  <button
                    type="button"
                    class="p-1 text-slate-400 hover:text-rose-600 cursor-pointer"
                    @click="removeCourseRow(i)"
                  >
                    <v-icon icon="mdi-trash-can-outline" size="16" />
                  </button>
                </div>
              </div>
            </div>

            <!-- 4. ประสบการณ์การทำงาน -->
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-slate-700">ประสบการณ์การทำงาน (Work Experience)</label>
                <button
                  type="button"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 cursor-pointer"
                  @click="addExperienceRow"
                >
                  <v-icon icon="mdi-plus" size="14" />
                  <span>เพิ่มประสบการณ์</span>
                </button>
              </div>
              <div v-if="!form.work_experience.length" class="p-3 text-center bg-slate-50 rounded-xl text-slate-400 text-xs border border-dashed">
                ยังไม่มีรายการประสบการณ์การทำงาน
              </div>
              <div v-else class="space-y-2">
                <div
                  v-for="(exp, i) in form.work_experience"
                  :key="i"
                  class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2"
                >
                  <div class="flex items-center gap-2">
                    <input
                      v-model="exp.period"
                      type="text"
                      placeholder="ช่วงเวลา เช่น 2564 - ปัจจุบัน"
                      class="w-36 px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg"
                    />
                    <input
                      v-model="exp.position"
                      type="text"
                      placeholder="ตำแหน่ง เช่น อาจารย์ประจำสาขา"
                      class="flex-1 px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg font-semibold"
                    />
                    <button
                      type="button"
                      class="p-1 text-slate-400 hover:text-rose-600 cursor-pointer"
                      @click="removeExperienceRow(i)"
                    >
                      <v-icon icon="mdi-trash-can-outline" size="16" />
                    </button>
                  </div>
                  <input
                    v-model="exp.organization"
                    type="text"
                    placeholder="หน่วยงาน / องค์กร"
                    class="w-full px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg"
                  />
                  <input
                    v-model="exp.description"
                    type="text"
                    placeholder="รายละเอียดภาระงาน (ถ้ามี)"
                    class="w-full px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg"
                  />
                </div>
              </div>
            </div>

            <!-- 5. การศึกษาดูงาน -->
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-slate-700">การศึกษาดูงานและอบรม (Study Visits & Training)</label>
                <button
                  type="button"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 cursor-pointer"
                  @click="addStudyVisitRow"
                >
                  <v-icon icon="mdi-plus" size="14" />
                  <span>เพิ่มการศึกษาดูงาน</span>
                </button>
              </div>
              <div v-if="!form.study_visits.length" class="p-3 text-center bg-slate-50 rounded-xl text-slate-400 text-xs border border-dashed">
                ยังไม่มีรายการศึกษาดูงาน
              </div>
              <div v-else class="space-y-2">
                <div
                  v-for="(visit, i) in form.study_visits"
                  :key="i"
                  class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-2"
                >
                  <input
                    v-model="visit.year"
                    type="text"
                    placeholder="ปี พ.ศ."
                    class="w-20 px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg"
                  />
                  <input
                    v-model="visit.topic"
                    type="text"
                    placeholder="หัวข้อการศึกษาดูงาน / อบรม"
                    class="flex-1 px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg"
                  />
                  <input
                    v-model="visit.organization"
                    type="text"
                    placeholder="หน่วยงาน / สถานที่"
                    class="flex-1 px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg"
                  />
                  <button
                    type="button"
                    class="p-1 text-slate-400 hover:text-rose-600 cursor-pointer"
                    @click="removeStudyVisitRow(i)"
                  >
                    <v-icon icon="mdi-trash-can-outline" size="16" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Dialog Footer -->
        <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between shrink-0">
          <div class="text-[11px] text-slate-400">
            * ฟิลด์ที่จำเป็นต้องกรอก
          </div>
          <div class="flex items-center gap-2">
            <button
              type="button"
              class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs cursor-pointer transition-all"
              @click="dialog = false"
            >
              ยกเลิก
            </button>
            <button
              type="button"
              :disabled="saveLoading"
              class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs disabled:opacity-50 cursor-pointer transition-all shadow-xs"
              @click="savePersonnel"
            >
              <v-icon :icon="saveLoading ? 'mdi-loading' : 'mdi-content-save-check-outline'" size="15" :class="saveLoading ? 'animate-spin' : ''" />
              <span>{{ saveLoading ? 'กำลังบันทึก...' : (isEditing ? 'บันทึกการแก้ไข' : 'บันทึกข้อมูลอาจารย์') }}</span>
            </button>
          </div>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══════════════════════════════════════════════════════════════════════════
         DIALOG 2: เพิ่ม / แก้ไข สาขาวิชา (Department Modal)
    ══════════════════════════════════════════════════════════════════════════ -->
    <v-dialog v-model="showDeptDialog" max-width="500" persistent>
      <v-card class="!rounded-3xl overflow-hidden p-0 bg-white border border-slate-100 shadow-2xl">
        <div class="bg-slate-900 p-5 text-white flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
              <v-icon icon="mdi-domain" size="20" />
            </div>
            <div>
              <h3 class="text-sm font-bold text-white">
                {{ editingDept ? 'แก้ไขข้อมูลสาขาวิชา' : 'เพิ่มสาขาวิชาใหม่' }}
              </h3>
              <p class="text-[11px] text-slate-400">กำหนดชื่อสาขาวิชา วุฒิปริญญา และประธานสาขา</p>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-xl text-slate-400 hover:text-white hover:bg-white/10 flex items-center justify-center cursor-pointer"
            @click="showDeptDialog = false"
          >
            <v-icon icon="mdi-close" size="18" />
          </button>
        </div>

        <div class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
              ชื่อสาขาวิชา <span class="text-rose-500">*</span>
            </label>
            <input
              v-model="deptForm.name"
              type="text"
              placeholder="เช่น สาขาวิชาการศึกษาปฐมวัย"
              class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 font-semibold"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
              รหัสย่อ (Slug ภาษาอังกฤษ)
            </label>
            <input
              v-model="deptForm.slug"
              type="text"
              placeholder="เช่น early-childhood (หากเว้นไว้ ระบบจะสร้างให้อัตโนมัติ)"
              class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 font-mono"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
              ชื่อย่อปริญญาที่ได้รับ
            </label>
            <input
              v-model="deptForm.degree_title"
              type="text"
              placeholder="เช่น ครุศาสตรบัณฑิต (ค.บ.), วิทยาศาสตรบัณฑิต (วท.บ.)"
              class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
              เลือกประธานสาขาวิชา (Chairperson)
            </label>
            <v-select
              v-model="deptForm.head_personnel_id"
              :items="deptFormHeadOptions"
              item-title="title"
              item-value="value"
              density="compact"
              variant="outlined"
              hide-details
              rounded="xl"
              color="primary"
              bg-color="white"
              class="custom-v-select text-xs font-medium"
              placeholder="-- ยังไม่ระบุประธานสาขา --"
            />
          </div>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2">
          <button
            type="button"
            class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs cursor-pointer"
            @click="showDeptDialog = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            :disabled="deptLoading"
            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs disabled:opacity-50 cursor-pointer shadow-xs"
            @click="saveDepartment"
          >
            <v-icon :icon="deptLoading ? 'mdi-loading' : 'mdi-content-save-check-outline'" size="15" :class="deptLoading ? 'animate-spin' : ''" />
            <span>{{ deptLoading ? 'กำลังบันทึก...' : 'บันทึกสาขาวิชา' }}</span>
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══════════════════════════════════════════════════════════════════════════
         DIALOG 3: ยืนยันการลบ บุคลากร
    ══════════════════════════════════════════════════════════════════════════ -->
    <v-dialog v-model="deleteDialog" max-width="400">
      <v-card class="!rounded-3xl p-6 text-center bg-white shadow-2xl border border-slate-100">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-3">
          <v-icon icon="mdi-alert-circle-outline" size="28" />
        </div>
        <h4 class="text-base font-bold text-slate-900 mb-1">ยืนยันการลบข้อมูลบุคลากร?</h4>
        <p class="text-xs text-slate-500 mb-5">ข้อมูลบุคลากรนี้จะถูกลบออกจากฐานข้อมูลอย่างถาวร</p>
        <div class="flex items-center justify-center gap-2">
          <button
            type="button"
            class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs cursor-pointer hover:bg-slate-100"
            @click="deleteDialog = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            :disabled="deleteLoading"
            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs cursor-pointer shadow-xs disabled:opacity-50"
            @click="confirmDelete"
          >
            <v-icon :icon="deleteLoading ? 'mdi-loading' : 'mdi-trash-can-outline'" size="15" :class="deleteLoading ? 'animate-spin' : ''" />
            <span>ยืนยันการลบ</span>
          </button>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══════════════════════════════════════════════════════════════════════════
         DIALOG 4: ยืนยันการลบ สาขาวิชา
    ══════════════════════════════════════════════════════════════════════════ -->
    <v-dialog v-model="deleteDeptDialog" max-width="420">
      <v-card class="!rounded-3xl p-6 text-center bg-white shadow-2xl border border-slate-100">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-3">
          <v-icon icon="mdi-alert-circle-outline" size="28" />
        </div>
        <h4 class="text-base font-bold text-slate-900 mb-1">
          ลบสาขาวิชา "{{ deleteDeptTarget?.name }}"?
        </h4>
        <p class="text-xs text-slate-500 mb-5 leading-relaxed">
          หากลบสาขาวิชานี้ สาขาวิชาจะถูกลบออกจากรายการแสดงผลบนหน้าเว็บไซต์
        </p>
        <div class="flex items-center justify-center gap-2">
          <button
            type="button"
            class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs cursor-pointer hover:bg-slate-100"
            @click="deleteDeptDialog = false"
          >
            ยกเลิก
          </button>
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs cursor-pointer shadow-xs"
            @click="handleDeleteDept"
          >
            <v-icon icon="mdi-trash-can-outline" size="15" />
            <span>ยืนยันการลบสาขาวิชา</span>
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

<style scoped>
:deep(.custom-v-select .v-field) {
  font-size: 0.8125rem !important; /* 13px */
  border-radius: 0.75rem !important;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.04);
}

:deep(.custom-v-select .v-field__input) {
  min-height: 40px !important;
  padding-top: 6px !important;
  padding-bottom: 6px !important;
  padding-left: 14px !important;
  padding-right: 10px !important;
  font-size: 0.8125rem !important;
}

:deep(.custom-v-select.v-field--prepended .v-field__input) {
  padding-left: 6px !important;
}

:deep(.custom-v-select .v-select__selection-text) {
  font-size: 0.8125rem !important;
  color: #1e293b;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>
