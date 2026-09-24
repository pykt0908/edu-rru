<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { api } from '@/services/api'

interface PostItem {
  id: number
  title: string
  desc: string
  content?: string[] | string
  category: string
  category_badge_class?: string
  date: string
  thumbnail?: string
  views?: string
  featured?: boolean
  key_highlights?: string[]
}

const posts = ref<PostItem[]>([])
const loading = ref(false)
const search = ref('')
const selectedCategory = ref('ทั้งหมด')

const categories = [
  'ทั้งหมด',
  'ข่าวรับสมัคร',
  'ข่าวประชาสัมพันธ์',
  'วิชาการ & วิจัย',
  'กิจกรรมนิสิต',
  'บริการวิชาการ',
  'ผลงาน & รางวัล',
]

// Dialog State
const dialog = ref(false)
const deleteDialog = ref(false)
const isEditing = ref(false)
const saveLoading = ref(false)
const deleteLoading = ref(false)
const currentPostId = ref<number | null>(null)

// Form fields
const form = ref({
  title: '',
  category: 'ข่าวประชาสัมพันธ์',
  desc: '',
  content: '',
  date: '',
  thumbnail: '',
  featured: false,
})

// Notification Snackbar
const snackbar = ref(false)
const snackbarText = ref('')
const snackbarColor = ref('success')

const showToast = (text: string, color = 'success') => {
  snackbarText.value = text
  snackbarColor.value = color
  snackbar.value = true
}

const fetchPosts = async () => {
  loading.value = true
  try {
    const res = await api.getPosts({
      category: selectedCategory.value === 'ทั้งหมด' ? undefined : selectedCategory.value,
      search: search.value || undefined,
    })
    posts.value = res.data || []
  } catch (err) {
    console.error(err)
    showToast('ไม่สามารถดึงข้อมูลข่าวสารได้', 'error')
  } finally {
    loading.value = false
  }
}

const filteredPosts = computed(() => {
  return posts.value.filter((p) => {
    const matchCat = selectedCategory.value === 'ทั้งหมด' || p.category === selectedCategory.value
    const matchSearch =
      !search.value ||
      p.title.toLowerCase().includes(search.value.toLowerCase()) ||
      (p.desc && p.desc.toLowerCase().includes(search.value.toLowerCase()))
    return matchCat && matchSearch
  })
})

const openCreateDialog = () => {
  isEditing.value = false
  currentPostId.value = null
  form.value = {
    title: '',
    category: 'ข่าวประชาสัมพันธ์',
    desc: '',
    content: '',
    date: new Date().toLocaleDateString('th-TH', { year: 'numeric', month: 'long', day: 'numeric' }),
    thumbnail: '',
    featured: false,
  }
  dialog.value = true
}

const openEditDialog = (post: PostItem) => {
  isEditing.value = true
  currentPostId.value = post.id
  let contentText = ''
  if (Array.isArray(post.content)) {
    contentText = post.content.join('\n\n')
  } else if (typeof post.content === 'string') {
    contentText = post.content
  }

  form.value = {
    title: post.title,
    category: post.category,
    desc: post.desc || '',
    content: contentText,
    date: post.date || '',
    thumbnail: post.thumbnail || '',
    featured: !!post.featured,
  }
  dialog.value = true
}

const openDeleteConfirm = (id: number) => {
  currentPostId.value = id
  deleteDialog.value = true
}

// Upload thumbnail
const handleFileUpload = async (e: Event) => {
  const target = e.target as HTMLInputElement
  if (!target.files || target.files.length === 0) return

  const file = target.files[0]
  try {
    const res = await api.uploadImage(file)
    form.value.thumbnail = res.url
    showToast('อัปโหลดรูปภาพสำเร็จ')
  } catch (err) {
    console.error(err)
    showToast('อัปโหลดรูปภาพล้มเหลว', 'error')
  }
}

const savePost = async () => {
  if (!form.value.title.trim()) {
    showToast('กรุณากรอกหัวข้อข่าว', 'error')
    return
  }

  saveLoading.value = true
  try {
    const contentArray = form.value.content
      ? form.value.content.split('\n').map((s) => s.trim()).filter(Boolean)
      : []

    const payload = {
      title: form.value.title,
      category: form.value.category,
      desc: form.value.desc,
      content: contentArray,
      date: form.value.date,
      thumbnail: form.value.thumbnail,
      featured: form.value.featured,
    }

    if (isEditing.value && currentPostId.value) {
      await api.updatePost(currentPostId.value, payload)
      showToast('บันทึกการแก้ไขข่าวสำเร็จ')
    } else {
      await api.createPost(payload)
      showToast('สร้างข่าวใหม่สำเร็จ')
    }

    dialog.value = false
    await fetchPosts()
  } catch (err) {
    console.error(err)
    showToast('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error')
  } finally {
    saveLoading.value = false
  }
}

const confirmDelete = async () => {
  if (!currentPostId.value) return
  deleteLoading.value = true
  try {
    await api.deletePost(currentPostId.value)
    showToast('ลบข่าวสารเรียบร้อยแล้ว')
    deleteDialog.value = false
    await fetchPosts()
  } catch (err) {
    console.error(err)
    showToast('ลบข่าวสารไม่สำเร็จ', 'error')
  } finally {
    deleteLoading.value = false
  }
}

onMounted(() => {
  fetchPosts()
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
            placeholder="ค้นหาชื่อข่าวสาร, เนื้อหา..."
            prepend-inner-icon="mdi-magnify"
            hide-details
            clearable
            class="text-xs"
          />
        </div>

        <!-- Category Selector & Add Button -->
        <div class="flex items-center gap-2">
          <div class="relative w-44">
            <select
              v-model="selectedCategory"
              class="w-full h-10 px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-800 text-xs font-semibold focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 cursor-pointer appearance-none pr-8 shadow-xs"
            >
              <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
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
            สร้างข่าวสาร
          </v-btn>
        </div>
      </div>
    </v-card>

    <!-- Posts Table / List Card -->
    <v-card elevation="0" class="!rounded-xl border border-slate-200/90 overflow-hidden bg-white">
      <div v-if="loading" class="py-16 text-center text-slate-400">
        <v-progress-circular indeterminate color="emerald" size="36" />
        <div class="text-xs mt-3">กำลังโหลดข้อมูลข่าวจาก MySQL...</div>
      </div>

      <div v-else-if="filteredPosts.length === 0" class="py-16 text-center text-slate-400">
        <v-icon icon="mdi-newspaper-remove-outline" size="48" class="text-slate-300" />
        <div class="text-sm font-semibold mt-2 text-slate-600">ไม่พบข่าวสารที่ค้นหา</div>
        <p class="text-xs text-slate-400 mt-1">ลองเปลี่ยนคำค้นหาหรือตัวกรองหมวดหมู่</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
              <th class="py-3 px-4 w-12 text-center">ID</th>
              <th class="py-3 px-4 w-20">รูปภาพ</th>
              <th class="py-3 px-4">หัวข้อข่าวสาร</th>
              <th class="py-3 px-4 w-36">หมวดหมู่</th>
              <th class="py-3 px-4 w-28 text-center">ยอดชม</th>
              <th class="py-3 px-4 w-32">วันที่</th>
              <th class="py-3 px-4 w-24 text-center">จัดการ</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-xs">
            <tr
              v-for="post in filteredPosts"
              :key="post.id"
              class="hover:bg-slate-50/70 transition-colors"
            >
              <td class="py-3 px-4 font-mono text-slate-400 text-center">{{ post.id }}</td>
              <td class="py-3 px-4">
                <img
                  v-if="post.thumbnail"
                  :src="post.thumbnail"
                  alt=""
                  class="w-14 h-10 object-cover rounded-md border border-slate-200"
                />
                <div v-else class="w-14 h-10 rounded-md bg-slate-100 flex items-center justify-center text-slate-300">
                  <v-icon icon="mdi-image-off-outline" size="18" />
                </div>
              </td>
              <td class="py-3 px-4">
                <div class="font-bold text-slate-900 line-clamp-1 mb-0.5">
                  {{ post.title }}
                </div>
                <div class="text-[11px] text-slate-500 line-clamp-1">
                  {{ post.desc }}
                </div>
                <div v-if="post.featured" class="mt-1">
                  <v-chip size="x-small" color="amber-darken-2" variant="flat" class="font-semibold text-[10px]">
                    <v-icon icon="mdi-star" start size="10" />
                    ปักหมุดข่าวเด่น
                  </v-chip>
                </div>
              </td>
              <td class="py-3 px-4">
                <v-chip size="small" color="emerald" variant="tonal" class="font-semibold text-[11px]">
                  {{ post.category }}
                </v-chip>
              </td>
              <td class="py-3 px-4 text-center font-mono text-slate-600">
                {{ post.views || '0' }}
              </td>
              <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                {{ post.date }}
              </td>
              <td class="py-3 px-4 text-center whitespace-nowrap">
                <div class="flex items-center justify-center gap-1">
                  <v-btn
                    :to="`/posts/${post.id}`"
                    target="_blank"
                    icon="mdi-eye-outline"
                    variant="text"
                    size="x-small"
                    color="slate-500"
                    title="ดูหน้าเว็บ"
                  />
                  <v-btn
                    icon="mdi-pencil-outline"
                    variant="text"
                    size="x-small"
                    color="emerald-darken-1"
                    title="แก้ไข"
                    @click="openEditDialog(post)"
                  />
                  <v-btn
                    icon="mdi-trash-can-outline"
                    variant="text"
                    size="x-small"
                    color="rose-600"
                    title="ลบ"
                    @click="openDeleteConfirm(post.id)"
                  />
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </v-card>

    <!-- Create / Edit Modal Dialog -->
    <v-dialog v-model="dialog" max-width="720" persistent>
      <v-card class="!rounded-2xl overflow-hidden p-0">
        <div class="bg-gradient-to-r from-emerald-800 to-teal-800 p-5 text-white flex items-center justify-between">
          <div class="flex items-center gap-2">
            <v-icon :icon="isEditing ? 'mdi-pencil-box' : 'mdi-plus-box'" size="24" />
            <h3 class="text-base font-bold">
              {{ isEditing ? 'แก้ไขข่าวสาร' : 'สร้างข่าวสาร / ประชาสัมพันธ์ใหม่' }}
            </h3>
          </div>
          <v-btn icon="mdi-close" variant="text" size="small" color="white" @click="dialog = false" />
        </div>

        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
          <!-- Title -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">หัวข้อข่าวสาร *</label>
            <v-text-field
              v-model="form.title"
              density="compact"
              variant="outlined"
              placeholder="ระบุหัวข้อข่าวสาร หรือประชาสัมพันธ์"
              hide-details
            />
          </div>

          <!-- Category & Date -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">หมวดหมู่ข่าวสาร *</label>
              <v-select
                v-model="form.category"
                :items="categories.filter(c => c !== 'ทั้งหมด')"
                density="compact"
                variant="outlined"
                hide-details
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">วันที่ประกาศ</label>
              <v-text-field
                v-model="form.date"
                density="compact"
                variant="outlined"
                placeholder="เช่น 24 กันยายน 2569"
                hide-details
              />
            </div>
          </div>

          <!-- Desc / Excerpt -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">บทคัดย่อ / สรุปความสั้นๆ</label>
            <v-textarea
              v-model="form.desc"
              rows="2"
              density="compact"
              variant="outlined"
              placeholder="คำบรรยายสั้นๆ ที่จะแสดงบนการ์ดข่าวหน้าแรก"
              hide-details
            />
          </div>

          <!-- Content -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">เนื้อหาข่าวฉบับเต็ม (แยกย่อหน้าด้วยการขึ้นบรรทัดใหม่)</label>
            <v-textarea
              v-model="form.content"
              rows="5"
              density="compact"
              variant="outlined"
              placeholder="กรอกเนื้อหารายละเอียดข่าว แต่ละย่อหน้าเว้นบรรทัด"
              hide-details
            />
          </div>

          <!-- Thumbnail Image -->
          <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-700">รูปภาพหน้าปก (Thumbnail)</label>
            <div class="flex items-center gap-3">
              <v-text-field
                v-model="form.thumbnail"
                density="compact"
                variant="outlined"
                placeholder="https://... หรืออัปโหลดไฟล์รูปภาพ"
                hide-details
                class="flex-1"
              />
              <label class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold shrink-0 transition-colors">
                <v-icon icon="mdi-cloud-upload-outline" size="16" />
                อัปโหลดรูป
                <input type="file" accept="image/*" class="hidden" @change="handleFileUpload" />
              </label>
            </div>
            <div v-if="form.thumbnail" class="mt-2">
              <img :src="form.thumbnail" alt="Preview" class="h-28 rounded-lg object-cover border border-slate-200" />
            </div>
          </div>

          <!-- Featured Switch -->
          <div class="pt-2">
            <v-switch
              v-model="form.featured"
              color="emerald"
              label="ปักหมุดเป็นข่าวเด่นหน้าแรก (Featured Post)"
              hide-details
              density="compact"
            />
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
            @click="savePost"
          >
            {{ isEditing ? 'บันทึกการแก้ไข' : 'บันทึกข่าวสาร' }}
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
        <h4 class="text-base font-bold text-slate-900 mb-1">ยืนยันการลบข่าวสาร?</h4>
        <p class="text-xs text-slate-500 mb-5">ข้อมูลข่าวสารนี้จะถูกลบออกจากฐานข้อมูล MySQL อย่างถาวร</p>
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
