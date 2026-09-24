<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { api, type Category } from '@/services/api'
import AdminToast from '@/components/admin/AdminToast.vue'

// ─── Types ────────────────────────────────────────────────────────────────────
interface PostItem {
  id: number
  title: string
  desc: string
  content?: string[] | string
  category: string
  category_badge_class?: string
  categoryBadgeClass?: string
  date: string
  thumbnail?: string
  views?: string
  featured?: boolean
  key_highlights?: string[]
}

// ─── State ────────────────────────────────────────────────────────────────────
const activeTab = ref<'posts' | 'categories'>('posts')
const router = useRouter()

// Posts
const posts = ref<PostItem[]>([])
const postsLoading = ref(false)
const search = ref('')
const selectedCategory = ref('ทั้งหมด')

// Categories
const categories = ref<Category[]>([])
const categoriesLoading = ref(false)

// ─── Dialog States ─────────────────────────────────────────────────────────
const postDeleteDialog = ref(false)
const deleteLoading = ref(false)
const currentPostId = ref<number | null>(null)

const catDialog = ref(false)
const catDeleteDialog = ref(false)
const catSaveLoading = ref(false)
const catDeleteLoading = ref(false)
const currentCatId = ref<number | null>(null)
const isCatEditing = ref(false)

// ─── Toast Feedback ────────────────────────────────────────────────────────────
const toast = ref({
  show: false,
  message: '',
  title: '',
  type: 'success' as 'success' | 'error' | 'warning' | 'info',
})

const showToast = (text: string, color: 'success' | 'error' | 'info' = 'success', title?: string) => {
  toast.value = {
    show: true,
    message: text,
    title: title || (color === 'success' ? 'บันทึกสำเร็จ' : 'เกิดข้อผิดพลาด'),
    type: color,
  }
}

// ─── Post Form ───────────────────────────────────────────────────────────────
// (Removed — create/edit are now separate pages)

// ─── Category Form ────────────────────────────────────────────────────────────
const catForm = ref({
  name: '',
  badge_class: 'bg-emerald-600 text-white',
  color_hex: '#059669',
  sort_order: 0,
})

// Preset badge color options
const badgePresets = [
  { label: 'เขียว', badge_class: 'bg-emerald-600 text-white', color_hex: '#059669' },
  { label: 'น้ำเงิน', badge_class: 'bg-blue-600 text-white', color_hex: '#2563eb' },
  { label: 'ม่วง', badge_class: 'bg-purple-600 text-white', color_hex: '#9333ea' },
  { label: 'ส้ม', badge_class: 'bg-amber-600 text-white', color_hex: '#d97706' },
  { label: 'แดง', badge_class: 'bg-rose-600 text-white', color_hex: '#e11d48' },
  { label: 'เทา', badge_class: 'bg-slate-600 text-white', color_hex: '#475569' },
  { label: 'ฟ้า', badge_class: 'bg-cyan-600 text-white', color_hex: '#0891b2' },
  { label: 'ชมพู', badge_class: 'bg-pink-600 text-white', color_hex: '#db2777' },
]

// ─── Computed ────────────────────────────────────────────────────────────────
const categoryNames = computed(() => ['ทั้งหมด', ...categories.value.map((c) => c.name)])

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

const getBadgeClass = (catName: string) => {
  const cat = categories.value.find((c) => c.name === catName)
  return cat?.badge_class ?? 'bg-slate-500 text-white'
}

// ─── Fetch ───────────────────────────────────────────────────────────────────
const fetchCategories = async () => {
  categoriesLoading.value = true
  try {
    categories.value = await api.getCategories()
  } catch {
    showToast('โหลดหมวดหมู่ไม่สำเร็จ', 'error')
  } finally {
    categoriesLoading.value = false
  }
}

const fetchPosts = async () => {
  postsLoading.value = true
  try {
    const res = await api.getPosts()
    posts.value = res.data || []
  } catch {
    showToast('โหลดข่าวสารไม่สำเร็จ', 'error')
  } finally {
    postsLoading.value = false
  }
}

// ─── Post Navigation (replaces dialog open/close) ────────────────────────────
const openCreatePost = () => router.push('/admin/posts/create')
const openEditPost = (post: PostItem) => router.push(`/admin/posts/${post.id}/edit`)
const openDeletePost = (id: number) => {
  currentPostId.value = id
  postDeleteDialog.value = true
}

const confirmDeletePost = async () => {
  if (!currentPostId.value) return
  deleteLoading.value = true
  try {
    await api.deletePost(currentPostId.value)
    showToast('ลบข่าวสารเรียบร้อยแล้ว')
    postDeleteDialog.value = false
    await fetchPosts()
  } catch {
    showToast('ลบข่าวสารไม่สำเร็จ', 'error')
  } finally {
    deleteLoading.value = false
  }
}

// ─── Category CRUD ────────────────────────────────────────────────────────────
const openCreateCat = () => {
  isCatEditing.value = false
  currentCatId.value = null
  catForm.value = { name: '', badge_class: 'bg-emerald-600 text-white', color_hex: '#059669', sort_order: categories.value.length + 1 }
  catDialog.value = true
}

const openEditCat = (cat: Category) => {
  isCatEditing.value = true
  currentCatId.value = cat.id
  catForm.value = { name: cat.name, badge_class: cat.badge_class, color_hex: cat.color_hex ?? '', sort_order: cat.sort_order }
  catDialog.value = true
}

const openDeleteCat = (id: number) => {
  currentCatId.value = id
  catDeleteDialog.value = true
}

const selectPreset = (preset: typeof badgePresets[0]) => {
  catForm.value.badge_class = preset.badge_class
  catForm.value.color_hex = preset.color_hex
}

const saveCat = async () => {
  if (!catForm.value.name.trim()) { showToast('กรุณากรอกชื่อหมวดหมู่', 'error'); return }
  catSaveLoading.value = true
  try {
    if (isCatEditing.value && currentCatId.value) {
      await api.updateCategory(currentCatId.value, catForm.value)
      showToast('แก้ไขหมวดหมู่สำเร็จ')
    } else {
      await api.createCategory(catForm.value)
      showToast('เพิ่มหมวดหมู่สำเร็จ')
    }
    catDialog.value = false
    await fetchCategories()
  } catch {
    showToast('เกิดข้อผิดพลาด', 'error')
  } finally {
    catSaveLoading.value = false
  }
}

const confirmDeleteCat = async () => {
  if (!currentCatId.value) return
  catDeleteLoading.value = true
  try {
    await api.deleteCategory(currentCatId.value)
    showToast('ลบหมวดหมู่สำเร็จ')
    catDeleteDialog.value = false
    await fetchCategories()
  } catch {
    showToast('ลบหมวดหมู่ไม่สำเร็จ', 'error')
  } finally {
    catDeleteLoading.value = false
  }
}

onMounted(async () => {
  await fetchCategories()
  await fetchPosts()
})
</script>

<template>
  <div class="space-y-5">
    <!-- ── Tab Switcher ────────────────────────────────────────────────── -->
    <div class="flex items-center gap-2">
      <button
        v-for="tab in [{ key: 'posts', icon: 'mdi-newspaper-variant-outline', label: 'จัดการข่าวสาร' }, { key: 'categories', icon: 'mdi-tag-multiple-outline', label: 'จัดการหมวดหมู่' }]"
        :key="tab.key"
        type="button"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 cursor-pointer border"
        :class="activeTab === tab.key
          ? 'bg-emerald-700 text-white border-emerald-700 shadow-md shadow-emerald-900/20'
          : 'bg-white text-slate-600 border-slate-200 hover:border-emerald-400 hover:text-emerald-700'"
        @click="activeTab = tab.key as 'posts' | 'categories'"
      >
        <v-icon :icon="tab.icon" size="18" />
        {{ tab.label }}
      </button>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         TAB: POSTS
    ══════════════════════════════════════════════════════════════════════ -->
    <template v-if="activeTab === 'posts'">
      <!-- Action Bar -->
      <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-4 bg-white">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
          <!-- Search -->
          <div class="flex-1 max-w-md">
            <v-text-field
              v-model="search"
              density="compact"
              variant="outlined"
              placeholder="ค้นหาชื่อข่าวสาร, เนื้อหา..."
              prepend-inner-icon="mdi-magnify"
              hide-details
              clearable
            />
          </div>

          <div class="flex items-center gap-2">
            <!-- Category filter -->
            <div class="relative w-48">
              <select
                v-model="selectedCategory"
                class="w-full h-10 px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-800 text-xs font-semibold focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 cursor-pointer appearance-none pr-8 shadow-xs"
              >
                <option v-for="cat in categoryNames" :key="cat" :value="cat">{{ cat }}</option>
              </select>
              <v-icon icon="mdi-chevron-down" size="16" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
            </div>

            <v-btn
              prepend-icon="mdi-plus"
              color="emerald-darken-1"
              rounded="lg"
              variant="flat"
              class="!capitalize font-semibold text-xs shrink-0"
              @click="openCreatePost"
            >
              สร้างข่าวสาร
            </v-btn>
          </div>
        </div>
      </v-card>

      <!-- Posts Table -->
      <v-card elevation="0" class="!rounded-xl border border-slate-200/90 overflow-hidden bg-white">
        <div v-if="postsLoading" class="py-16 text-center text-slate-400">
          <v-progress-circular indeterminate color="emerald" size="36" />
          <div class="text-xs mt-3">กำลังโหลดข้อมูลข่าว...</div>
        </div>

        <div v-else-if="filteredPosts.length === 0" class="py-16 text-center text-slate-400">
          <v-icon icon="mdi-newspaper-remove-outline" size="48" class="text-slate-300" />
          <div class="text-sm font-semibold mt-2 text-slate-600">ไม่พบข่าวสาร</div>
          <p class="text-xs text-slate-400 mt-1">ลองเปลี่ยนคำค้นหาหรือตัวกรองหมวดหมู่</p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                <th class="py-3 px-4 w-12 text-center">ID</th>
                <th class="py-3 px-4 w-20">ภาพ</th>
                <th class="py-3 px-4">หัวข้อข่าวสาร</th>
                <th class="py-3 px-4 w-36">หมวดหมู่</th>
                <th class="py-3 px-4 w-24 text-center">ยอดชม</th>
                <th class="py-3 px-4 w-32">วันที่</th>
                <th class="py-3 px-4 w-28 text-center">จัดการ</th>
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
                  <div class="font-bold text-slate-900 line-clamp-1 mb-0.5">{{ post.title }}</div>
                  <div class="text-[11px] text-slate-500 line-clamp-1">{{ post.desc }}</div>
                  <v-chip v-if="post.featured" size="x-small" color="amber-darken-2" variant="flat" class="font-semibold text-[10px] mt-0.5">
                    <v-icon icon="mdi-star" start size="10" /> ข่าวเด่น
                  </v-chip>
                </td>
                <td class="py-3 px-4">
                  <span :class="['px-2 py-0.5 rounded-full text-[11px] font-bold', getBadgeClass(post.category)]">
                    {{ post.category }}
                  </span>
                </td>
                <td class="py-3 px-4 text-center font-mono text-slate-600">{{ post.views || '0' }}</td>
                <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ post.date }}</td>
                <td class="py-3 px-4 text-center whitespace-nowrap">
                  <div class="flex items-center justify-center gap-0.5">
                    <v-btn :to="`/posts/${post.id}`" target="_blank" icon="mdi-eye-outline" variant="text" size="x-small" color="slate-500" title="ดูหน้าเว็บ" />
                    <v-btn icon="mdi-pencil-outline" variant="text" size="x-small" color="emerald-darken-1" title="แก้ไข" @click="openEditPost(post)" />
                    <v-btn icon="mdi-trash-can-outline" variant="text" size="x-small" color="rose-600" title="ลบ" @click="openDeletePost(post.id)" />
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </v-card>
    </template>

    <!-- ══════════════════════════════════════════════════════════════════
         TAB: CATEGORIES
    ══════════════════════════════════════════════════════════════════════ -->
    <template v-else>
      <!-- Action Bar -->
      <v-card elevation="0" class="!rounded-xl border border-slate-200/90 p-4 bg-white">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-sm font-bold text-slate-800">หมวดหมู่ข่าวสาร</h3>
            <p class="text-xs text-slate-500 mt-0.5">จัดการชื่อและสีของหมวดหมู่ข่าว</p>
          </div>
          <v-btn
            prepend-icon="mdi-plus"
            color="emerald-darken-1"
            rounded="lg"
            variant="flat"
            class="!capitalize font-semibold text-xs"
            @click="openCreateCat"
          >
            เพิ่มหมวดหมู่
          </v-btn>
        </div>
      </v-card>

      <!-- Categories Table -->
      <v-card elevation="0" class="!rounded-xl border border-slate-200/90 overflow-hidden bg-white">
        <div v-if="categoriesLoading" class="py-12 text-center text-slate-400">
          <v-progress-circular indeterminate color="emerald" size="32" />
        </div>

        <div v-else-if="categories.length === 0" class="py-12 text-center text-slate-400">
          <v-icon icon="mdi-tag-off-outline" size="40" class="text-slate-300" />
          <div class="text-sm font-semibold mt-2">ยังไม่มีหมวดหมู่</div>
          <p class="text-xs mt-1">คลิก "เพิ่มหมวดหมู่" เพื่อเริ่มต้น</p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                <th class="py-3 px-4 w-12 text-center">ID</th>
                <th class="py-3 px-4">ชื่อหมวดหมู่</th>
                <th class="py-3 px-4 w-48">Badge สี</th>
                <th class="py-3 px-4 w-24 text-center">ลำดับ</th>
                <th class="py-3 px-4 w-24 text-center">จัดการ</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
              <tr
                v-for="cat in categories"
                :key="cat.id"
                class="hover:bg-slate-50/70 transition-colors"
              >
                <td class="py-3 px-4 font-mono text-slate-400 text-center">{{ cat.id }}</td>
                <td class="py-3 px-4 font-bold text-slate-800">{{ cat.name }}</td>
                <td class="py-3 px-4">
                  <span :class="['px-2.5 py-1 rounded-full text-[11px] font-bold', cat.badge_class]">
                    {{ cat.name }}
                  </span>
                </td>
                <td class="py-3 px-4 text-center text-slate-500">{{ cat.sort_order }}</td>
                <td class="py-3 px-4 text-center">
                  <div class="flex items-center justify-center gap-0.5">
                    <v-btn icon="mdi-pencil-outline" variant="text" size="x-small" color="emerald-darken-1" title="แก้ไข" @click="openEditCat(cat)" />
                    <v-btn icon="mdi-trash-can-outline" variant="text" size="x-small" color="rose-600" title="ลบ" @click="openDeleteCat(cat.id)" />
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </v-card>
    </template>



    <!-- ══════════════════════════════════════════════════════════════════
         DIALOG: DELETE POST
    ══════════════════════════════════════════════════════════════════════ -->
    <v-dialog v-model="postDeleteDialog" max-width="400">
      <v-card class="!rounded-2xl p-5 text-center">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
          <v-icon icon="mdi-trash-can-outline" size="26" />
        </div>
        <h4 class="text-base font-bold text-slate-900 mb-1">ยืนยันการลบข่าวสาร?</h4>
        <p class="text-xs text-slate-500 mb-5">ข้อมูลข่าวสารนี้จะถูกลบออกจากฐานข้อมูลอย่างถาวร</p>
        <div class="flex items-center justify-center gap-2">
          <v-btn variant="outlined" size="small" @click="postDeleteDialog = false">ยกเลิก</v-btn>
          <v-btn color="rose-600" variant="flat" size="small" class="!capitalize font-semibold text-white" :loading="deleteLoading" @click="confirmDeletePost">ยืนยันการลบ</v-btn>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══════════════════════════════════════════════════════════════════
         DIALOG: CREATE / EDIT CATEGORY
    ══════════════════════════════════════════════════════════════════════ -->
    <v-dialog v-model="catDialog" max-width="500" persistent>
      <v-card class="!rounded-2xl overflow-hidden p-0">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-800 to-slate-700 p-5 text-white flex items-center justify-between">
          <div class="flex items-center gap-2">
            <v-icon :icon="isCatEditing ? 'mdi-tag-edit-outline' : 'mdi-tag-plus-outline'" size="22" />
            <h3 class="text-base font-bold">{{ isCatEditing ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่ใหม่' }}</h3>
          </div>
          <v-btn icon="mdi-close" variant="text" size="small" color="white" @click="catDialog = false" />
        </div>

        <!-- Body -->
        <div class="p-6 space-y-5">
          <!-- Name -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">ชื่อหมวดหมู่ *</label>
            <v-text-field v-model="catForm.name" density="compact" variant="outlined" placeholder="เช่น ข่าวประชาสัมพันธ์" hide-details />
          </div>

          <!-- Badge Preview -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-2">สี Badge</label>
            <!-- Presets -->
            <div class="flex flex-wrap gap-2 mb-3">
              <button
                v-for="preset in badgePresets"
                :key="preset.badge_class"
                type="button"
                :class="['px-3 py-1 rounded-full text-[11px] font-bold border-2 transition-all cursor-pointer', preset.badge_class, catForm.badge_class === preset.badge_class ? 'ring-2 ring-offset-1 ring-slate-700 scale-105' : 'border-transparent']"
                @click="selectPreset(preset)"
              >
                {{ preset.label }}
              </button>
            </div>
            <!-- Preview -->
            <div class="flex items-center gap-3 bg-slate-50 rounded-xl p-3 border border-slate-200">
              <span class="text-xs text-slate-500">ตัวอย่าง:</span>
              <span :class="['px-3 py-1 rounded-full text-xs font-bold', catForm.badge_class]">
                {{ catForm.name || 'ชื่อหมวดหมู่' }}
              </span>
            </div>
          </div>

          <!-- Sort Order -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">ลำดับการแสดง</label>
            <v-text-field v-model.number="catForm.sort_order" density="compact" variant="outlined" type="number" min="0" hide-details />
          </div>
        </div>

        <!-- Footer -->
        <div class="p-4 bg-slate-50 border-t border-slate-200/80 flex items-center justify-end gap-2">
          <v-btn variant="text" size="small" @click="catDialog = false">ยกเลิก</v-btn>
          <v-btn color="emerald-darken-1" variant="flat" size="small" rounded="lg" :loading="catSaveLoading" class="!capitalize font-semibold" @click="saveCat">
            {{ isCatEditing ? 'บันทึกการแก้ไข' : 'เพิ่มหมวดหมู่' }}
          </v-btn>
        </div>
      </v-card>
    </v-dialog>

    <!-- ══════════════════════════════════════════════════════════════════
         DIALOG: DELETE CATEGORY
    ══════════════════════════════════════════════════════════════════════ -->
    <v-dialog v-model="catDeleteDialog" max-width="400">
      <v-card class="!rounded-2xl p-5 text-center">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
          <v-icon icon="mdi-tag-off-outline" size="26" />
        </div>
        <h4 class="text-base font-bold text-slate-900 mb-1">ยืนยันการลบหมวดหมู่?</h4>
        <p class="text-xs text-slate-500 mb-5">หมวดหมู่นี้จะถูกลบออกจากระบบ ข่าวสารที่อยู่ในหมวดหมู่นี้จะยังคงอยู่</p>
        <div class="flex items-center justify-center gap-2">
          <v-btn variant="outlined" size="small" @click="catDeleteDialog = false">ยกเลิก</v-btn>
          <v-btn color="rose-600" variant="flat" size="small" class="!capitalize font-semibold text-white" :loading="catDeleteLoading" @click="confirmDeleteCat">ยืนยันการลบ</v-btn>
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
