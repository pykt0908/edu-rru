<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { api } from '@/services/api'

interface PersonnelItem {
  id: number
  slug_id: string
  name: string
  name_en?: string
  academic_title?: string
  role_title: string
  avatar?: string
  degrees?: string
  department_id: string
  department_name: string
  email?: string
  phone?: string
  office_room?: string
  office_hours?: string
  sort_order?: number
}

const personnel = ref<PersonnelItem[]>([])
const loading = ref(false)
const search = ref('')
const selectedDept = ref('all')

const departmentOptions = [
  { id: 'all', name: 'ทุกสาขาวิชา / หน่วยงาน' },
  { id: 'early-childhood', name: 'สาขาวิชาการศึกษาปฐมวัย' },
  { id: 'data-science', name: 'สาขาวิชาวิทยาการข้อมูลและการวิเคราะห์สถิติ' },
  { id: 'elementary', name: 'สาขาวิชาการประถมศึกษา' },
  { id: 'thai', name: 'สาขาวิชาภาษาไทย' },
  { id: 'english', name: 'สาขาวิชาภาษาอังกฤษ' },
  { id: 'mathematics', name: 'สาขาวิชาคณิตศาสตร์' },
  { id: 'science', name: 'สาขาวิชาวิทยาศาสตร์ทั่วไป' },
  { id: 'social-studies', name: 'สาขาวิชาสังคมศึกษา' },
  { id: 'curriculum-instruction', name: 'สาขาวิชาหลักสูตรและการสอน (ป.โท)' },
]

// Dialog State
const dialog = ref(false)
const deleteDialog = ref(false)
const isEditing = ref(false)
const saveLoading = ref(false)
const deleteLoading = ref(false)
const currentId = ref<number | null>(null)

// Form fields
const form = ref({
  name: '',
  name_en: '',
  academic_title: '',
  role_title: '',
  department_id: 'early-childhood',
  department_name: 'สาขาวิชาการศึกษาปฐมวัย',
  degrees: '',
  email: '',
  phone: '',
  office_room: '',
  avatar: '',
})

// Notification
const snackbar = ref(false)
const snackbarText = ref('')
const snackbarColor = ref('success')

const showToast = (text: string, color = 'success') => {
  snackbarText.value = text
  snackbarColor.value = color
  snackbar.value = true
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

const filteredPersonnel = computed(() => {
  return personnel.value.filter((p) => {
    const matchDept = selectedDept.value === 'all' || p.department_id === selectedDept.value
    const matchSearch =
      !search.value ||
      p.name.toLowerCase().includes(search.value.toLowerCase()) ||
      (p.name_en && p.name_en.toLowerCase().includes(search.value.toLowerCase())) ||
      p.role_title.toLowerCase().includes(search.value.toLowerCase()) ||
      p.department_name.toLowerCase().includes(search.value.toLowerCase())
    return matchDept && matchSearch
  })
})

const onDepartmentChange = (deptId: string) => {
  const dept = departmentOptions.find((d) => d.id === deptId)
  if (dept) {
    form.value.department_name = dept.name
  }
}

const openCreateDialog = () => {
  isEditing.value = false
  currentId.value = null
  form.value = {
    name: '',
    name_en: '',
    academic_title: '',
    role_title: '',
    department_id: 'early-childhood',
    department_name: 'สาขาวิชาการศึกษาปฐมวัย',
    degrees: '',
    email: '',
    phone: '',
    office_room: '',
    avatar: '',
  }
  dialog.value = true
}

const openEditDialog = (item: PersonnelItem) => {
  isEditing.value = true
  currentId.value = item.id
  form.value = {
    name: item.name,
    name_en: item.name_en || '',
    academic_title: item.academic_title || '',
    role_title: item.role_title,
    department_id: item.department_id,
    department_name: item.department_name,
    degrees: item.degrees || '',
    email: item.email || '',
    phone: item.phone || '',
    office_room: item.office_room || '',
    avatar: item.avatar || '',
  }
  dialog.value = true
}

const openDeleteConfirm = (id: number) => {
  currentId.value = id
  deleteDialog.value = true
}

// Upload Avatar
const handleFileUpload = async (e: Event) => {
  const target = e.target as HTMLInputElement
  if (!target.files || target.files.length === 0) return

  const file = target.files[0]
  try {
    const res = await api.uploadImage(file)
    form.value.avatar = res.url
    showToast('อัปโหลดรูปภาพสำเร็จ')
  } catch (err) {
    console.error(err)
    showToast('อัปโหลดรูปภาพล้มเหลว', 'error')
  }
}

const savePersonnel = async () => {
  if (!form.value.name.trim() || !form.value.role_title.trim()) {
    showToast('กรุณากรอกชื่อและตำแหน่งของอาจารย์', 'error')
    return
  }

  saveLoading.value = true
  try {
    const payload = { ...form.value }

    if (isEditing.value && currentId.value) {
      await api.updatePersonnel(currentId.value, payload)
      showToast('บันทึกการแก้ไขข้อมูลสำเร็จ')
    } else {
      await api.createPersonnel(payload)
      showToast('เพิ่มข้อมูลบุคลากรสำเร็จ')
    }

    dialog.value = false
    await fetchPersonnel()
  } catch (err) {
    console.error(err)
    showToast('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error')
  } finally {
    saveLoading.value = false
  }
}

const confirmDelete = async () => {
  if (!currentId.value) return
  deleteLoading.value = true
  try {
    await api.deletePersonnel(currentId.value)
    showToast('ลบข้อมูลบุคลากรเรียบร้อยแล้ว')
    deleteDialog.value = false
    await fetchPersonnel()
  } catch (err) {
    console.error(err)
    showToast('ลบข้อมูลไม่สำเร็จ', 'error')
  } finally {
    deleteLoading.value = false
  }
}

onMounted(() => {
  fetchPersonnel()
})
</script>

<template>
  <div class="space-y-6">
    <!-- Action Bar & Filters -->
    <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-4 bg-white">
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <!-- Search Field -->
        <div class="flex-1 max-w-md">
          <v-text-field
            v-model="search"
            density="compact"
            variant="outlined"
            placeholder="ค้นหาชื่ออาจารย์, ตำแหน่ง, สาขาวิชา..."
            prepend-inner-icon="mdi-magnify"
            hide-details
            clearable
            class="text-xs"
          />
        </div>

        <!-- Department Filter & Add Button -->
        <div class="flex items-center gap-2">
          <div class="relative w-64">
            <select
              v-model="selectedDept"
              class="w-full h-10 px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-800 text-xs font-semibold focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 cursor-pointer appearance-none pr-8 shadow-xs"
            >
              <option v-for="dept in departmentOptions" :key="dept.id" :value="dept.id">{{ dept.name }}</option>
            </select>
            <v-icon icon="mdi-chevron-down" size="16" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
          </div>

          <v-btn
            prepend-icon="mdi-plus"
            color="emerald-darken-1"
            rounded="lg"
            variant="flat"
            class="!capitalize font-semibold text-xs shrink-0"
            @click="openCreateDialog"
          >
            เพิ่มอาจารย์ / บุคลากร
          </v-btn>
        </div>
      </div>
    </v-card>

    <!-- Personnel Table -->
    <v-card elevation="0" class="!rounded-xl border border-slate-200/90 overflow-hidden bg-white">
      <div v-if="loading" class="py-16 text-center text-slate-400">
        <v-progress-circular indeterminate color="emerald" size="36" />
        <div class="text-xs mt-3">กำลังโหลดข้อมูลบุคลากรจาก MySQL...</div>
      </div>

      <div v-else-if="filteredPersonnel.length === 0" class="py-16 text-center text-slate-400">
        <v-icon icon="mdi-account-search-outline" size="48" class="text-slate-300" />
        <div class="text-sm font-semibold mt-2 text-slate-600">ไม่พบบุคลากรที่ค้นหา</div>
        <p class="text-xs text-slate-400 mt-1">ลองเปลี่ยนคำค้นหาหรือตัวกรองสาขาวิชา</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
              <th class="py-3 px-4 w-12 text-center">ID</th>
              <th class="py-3 px-4 w-16">รูปภาพ</th>
              <th class="py-3 px-4">ชื่อ - นามสกุล</th>
              <th class="py-3 px-4">ตำแหน่ง / หน้าที่</th>
              <th class="py-3 px-4">สาขาวิชา</th>
              <th class="py-3 px-4">ช่องทางติดต่อ</th>
              <th class="py-3 px-4 w-24 text-center">จัดการ</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-xs">
            <tr
              v-for="person in filteredPersonnel"
              :key="person.id"
              class="hover:bg-slate-50/70 transition-colors"
            >
              <td class="py-3 px-4 font-mono text-slate-400 text-center">{{ person.id }}</td>
              <td class="py-3 px-4">
                <img
                  v-if="person.avatar"
                  :src="person.avatar"
                  alt=""
                  class="w-10 h-10 rounded-full object-cover border border-slate-200"
                />
                <div v-else class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                  <v-icon icon="mdi-account" size="20" />
                </div>
              </td>
              <td class="py-3 px-4">
                <div class="font-bold text-slate-900 leading-tight">
                  {{ person.name }}
                </div>
                <div v-if="person.name_en" class="text-[11px] text-slate-400">
                  {{ person.name_en }}
                </div>
              </td>
              <td class="py-3 px-4">
                <span class="font-medium text-slate-700">{{ person.role_title }}</span>
                <div v-if="person.academic_title" class="text-[10px] text-slate-400">
                  {{ person.academic_title }}
                </div>
              </td>
              <td class="py-3 px-4">
                <v-chip size="x-small" color="blue-grey" variant="tonal" class="font-medium text-[10px]">
                  {{ person.department_name }}
                </v-chip>
              </td>
              <td class="py-3 px-4 text-[11px] text-slate-500">
                <div v-if="person.email" class="flex items-center gap-1">
                  <v-icon icon="mdi-email-outline" size="12" class="text-slate-400" />
                  <span>{{ person.email }}</span>
                </div>
                <div v-if="person.phone" class="flex items-center gap-1 text-slate-400">
                  <v-icon icon="mdi-phone-outline" size="12" />
                  <span>{{ person.phone }}</span>
                </div>
              </td>
              <td class="py-3 px-4 text-center whitespace-nowrap">
                <div class="flex items-center justify-center gap-1">
                  <v-btn
                    icon="mdi-pencil-outline"
                    variant="text"
                    size="x-small"
                    color="emerald-darken-1"
                    title="แก้ไข"
                    @click="openEditDialog(person)"
                  />
                  <v-btn
                    icon="mdi-trash-can-outline"
                    variant="text"
                    size="x-small"
                    color="rose-600"
                    title="ลบ"
                    @click="openDeleteConfirm(person.id)"
                  />
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </v-card>

    <!-- Create / Edit Dialog -->
    <v-dialog v-model="dialog" max-width="700" persistent>
      <v-card class="!rounded-2xl overflow-hidden p-0">
        <div class="bg-gradient-to-r from-emerald-800 to-teal-800 p-5 text-white flex items-center justify-between">
          <div class="flex items-center gap-2">
            <v-icon :icon="isEditing ? 'mdi-account-edit' : 'mdi-account-plus'" size="24" />
            <h3 class="text-base font-bold">
              {{ isEditing ? 'แก้ไขข้อมูลคณาจารย์ / บุคลากร' : 'เพิ่มข้อมูลคณาจารย์ / บุคลากร' }}
            </h3>
          </div>
          <v-btn icon="mdi-close" variant="text" size="small" color="white" @click="dialog = false" />
        </div>

        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
          <!-- Name TH & EN -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">ชื่อ - นามสกุล (ภาษาไทย) *</label>
              <v-text-field
                v-model="form.name"
                density="compact"
                variant="outlined"
                placeholder="เช่น ผศ.ดร.สมชาย ใจดี"
                hide-details
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Name in English</label>
              <v-text-field
                v-model="form.name_en"
                density="compact"
                variant="outlined"
                placeholder="เช่น Asst. Prof. Dr. Somchai Jaidee"
                hide-details
              />
            </div>
          </div>

          <!-- Academic Title & Role Title -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">ตำแหน่งทางวิชาการ</label>
              <v-text-field
                v-model="form.academic_title"
                density="compact"
                variant="outlined"
                placeholder="เช่น ผู้ช่วยศาสตราจารย์, อาจารย์"
                hide-details
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">ตำแหน่งหน้าที่รับผิดชอบ *</label>
              <v-text-field
                v-model="form.role_title"
                density="compact"
                variant="outlined"
                placeholder="เช่น ประธานสาขาวิชา, อาจารย์ประจำสาขา"
                hide-details
              />
            </div>
          </div>

          <!-- Department -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">สาขาวิชา / ฝ่ายงานสังกัด *</label>
            <v-select
              v-model="form.department_id"
              :items="departmentOptions.filter(d => d.id !== 'all')"
              item-title="name"
              item-value="id"
              density="compact"
              variant="outlined"
              hide-details
              @update:model-value="onDepartmentChange"
            />
          </div>

          <!-- Degrees -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">วุฒิการศึกษาย่อ</label>
            <v-text-field
              v-model="form.degrees"
              density="compact"
              variant="outlined"
              placeholder="เช่น ค.ด. (การศึกษาปฐมวัย), ค.ม., ค.บ."
              hide-details
            />
          </div>

          <!-- Contact info -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">อีเมล (Email)</label>
              <v-text-field
                v-model="form.email"
                density="compact"
                variant="outlined"
                placeholder="name@rru.ac.th"
                hide-details
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">เบอร์โทรศัพท์ / ต่อ</label>
              <v-text-field
                v-model="form.phone"
                density="compact"
                variant="outlined"
                placeholder="038-511-233 ต่อ ..."
                hide-details
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">ห้องพักอาจารย์</label>
              <v-text-field
                v-model="form.office_room"
                density="compact"
                variant="outlined"
                placeholder="อาคาร 1 ชั้น 3"
                hide-details
              />
            </div>
          </div>

          <!-- Avatar Image -->
          <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-700">รูปภาพประจำตัว (Avatar)</label>
            <div class="flex items-center gap-3">
              <v-text-field
                v-model="form.avatar"
                density="compact"
                variant="outlined"
                placeholder="URL รูปภาพ หรือกดปุ่มอัปโหลด"
                hide-details
                class="flex-1"
              />
              <label class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold shrink-0 transition-colors">
                <v-icon icon="mdi-cloud-upload-outline" size="16" />
                อัปโหลดรูป
                <input type="file" accept="image/*" class="hidden" @change="handleFileUpload" />
              </label>
            </div>
            <div v-if="form.avatar" class="mt-2">
              <img :src="form.avatar" alt="Preview" class="w-16 h-16 rounded-full object-cover border border-slate-200" />
            </div>
          </div>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200/80 flex items-center justify-end gap-2">
          <v-btn variant="text" size="small" @click="dialog = false">
            ยกเลิก
          </v-btn>
          <v-btn
            color="emerald-darken-1"
            variant="flat"
            size="small"
            rounded="lg"
            :loading="saveLoading"
            class="!capitalize font-semibold"
            @click="savePersonnel"
          >
            {{ isEditing ? 'บันทึกการแก้ไข' : 'บันทึกข้อมูล' }}
          </v-btn>
        </div>
      </v-card>
    </v-dialog>

    <!-- Delete Confirm Dialog -->
    <v-dialog v-model="deleteDialog" max-width="400">
      <v-card class="!rounded-2xl p-5 text-center">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
          <v-icon icon="mdi-alert-outline" size="28" />
        </div>
        <h4 class="text-base font-bold text-slate-900 mb-1">ยืนยันการลบข้อมูลบุคลากร?</h4>
        <p class="text-xs text-slate-500 mb-5">ข้อมูลบุคลากรนี้จะถูกลบออกจากฐานข้อมูล MySQL อย่างถาวร</p>
        <div class="flex items-center justify-center gap-2">
          <v-btn variant="outlined" size="small" class="!capitalize" @click="deleteDialog = false">
            ยกเลิก
          </v-btn>
          <v-btn
            color="rose-600"
            variant="flat"
            size="small"
            class="!capitalize font-semibold text-white"
            :loading="deleteLoading"
            @click="confirmDelete"
          >
            ยืนยันการลบ
          </v-btn>
        </div>
      </v-card>
    </v-dialog>

    <!-- Snackbar Notification -->
    <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="3000" location="top right">
      {{ snackbarText }}
    </v-snackbar>
  </div>
</template>
