<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { api, type Category } from '@/services/api'
import ThaiDatePicker from '@/components/admin/ThaiDatePicker.vue'
import RichTextEditor from '@/components/admin/RichTextEditor.vue'
import AdminToast from '@/components/admin/AdminToast.vue'

const router = useRouter()

// ─── State ────────────────────────────────────────────────────────────────────
const categories = ref<Category[]>([])
const saveLoading = ref(false)
const uploadCoverLoading = ref(false)
const uploadGalleryLoading = ref(false)
const uploadAttachLoading = ref(false)
const uploadAvatarLoading = ref(false)

const form = ref({
  title: '',
  category: '',
  desc: '',
  content: '',
  date: new Date().toLocaleDateString('th-TH', { year: 'numeric', month: 'long', day: 'numeric' }),
  readTime: '3 นาที',
  thumbnail: '',
  featured: false,
  tags: '',
  // Author
  author: {
    name: 'ฝ่ายสื่อสารองค์กร คณะครุศาสตร์',
    role: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
    avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=120&auto=format&fit=crop&q=80',
  },
  // Key Highlights
  keyHighlights: [
    'หลักสูตรได้รับการรับรองมาตรฐานวิชาชีพครูจากคุรุสภา',
    'พร้อมทุนการศึกษาเต็มจำนวนและโครงการสนับสนุนนิสิต',
  ] as string[],
  // Quote
  quote: {
    text: '',
    by: '',
  },
  // Gallery
  gallery: [] as string[],
  // Attachments
  attachments: [] as { name: string; size: string; type: string; url: string }[],
})

// Input helpers
const newHighlight = ref('')
const newGalleryUrl = ref('')
const newAttach = ref({ name: '', size: '1.5 MB', type: 'PDF', url: '' })

// Toast Feedback
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

// ─── Key Highlights Management ─────────────────────────────────────────────────
const addHighlight = () => {
  const val = newHighlight.value.trim()
  if (!val) return
  form.value.keyHighlights.push(val)
  newHighlight.value = ''
}

const removeHighlight = (index: number) => {
  form.value.keyHighlights.splice(index, 1)
}

// ─── Gallery Management ───────────────────────────────────────────────────────
const addGalleryUrl = () => {
  const val = newGalleryUrl.value.trim()
  if (!val) return
  form.value.gallery.push(val)
  newGalleryUrl.value = ''
}

const removeGallery = (index: number) => {
  form.value.gallery.splice(index, 1)
}

const handleGalleryUpload = async (e: Event) => {
  const target = e.target as HTMLInputElement
  if (!target.files?.length) return
  uploadGalleryLoading.value = true
  try {
    for (let i = 0; i < target.files.length; i++) {
      const res = await api.uploadImage(target.files[i])
      form.value.gallery.push(res.url)
    }
    showToast('อัปโหลดรูปภาพลงอัลบั้มสำเร็จ')
  } catch {
    showToast('อัปโหลดรูปภาพอัลบั้มล้มเหลว', 'error')
  } finally {
    uploadGalleryLoading.value = false
    target.value = ''
  }
}

// ─── Attachments Management ───────────────────────────────────────────────────
const addAttachment = () => {
  if (!newAttach.value.name.trim() || !newAttach.value.url.trim()) {
    showToast('กรุณากรอกชื่อเอกสารและ URL', 'error')
    return
  }
  form.value.attachments.push({
    name: newAttach.value.name.trim(),
    size: newAttach.value.size.trim() || '1.0 MB',
    type: newAttach.value.type.trim() || 'PDF',
    url: newAttach.value.url.trim(),
  })
  newAttach.value = { name: '', size: '1.5 MB', type: 'PDF', url: '' }
}

const removeAttachment = (index: number) => {
  form.value.attachments.splice(index, 1)
}

const handleAttachmentUpload = async (e: Event) => {
  const target = e.target as HTMLInputElement
  if (!target.files?.length) return
  uploadAttachLoading.value = true
  const total = target.files.length
  try {
    for (let i = 0; i < total; i++) {
      const file = target.files[i]
      const res = await api.uploadFile(file)
      const sizeStr =
        res.file_size ||
        (file.size > 1024 * 1024
          ? (file.size / (1024 * 1024)).toFixed(1) + ' MB'
          : (file.size / 1024).toFixed(0) + ' KB')
      const ext = (res.extension || file.name.split('.').pop() || 'PDF').toUpperCase()
      form.value.attachments.push({
        name: res.name || file.name,
        size: sizeStr,
        type: ext,
        url: res.url,
      })
    }
    showToast(`อัปโหลดเอกสารแนบ ${total} รายการสำเร็จ`)
  } catch {
    showToast('อัปโหลดเอกสารแนบล้มเหลว', 'error')
  } finally {
    uploadAttachLoading.value = false
    target.value = ''
  }
}

// ─── File Uploads ──────────────────────────────────────────────────────────────
const handleCoverUpload = async (e: Event) => {
  const target = e.target as HTMLInputElement
  if (!target.files?.length) return
  uploadCoverLoading.value = true
  try {
    const res = await api.uploadImage(target.files[0])
    form.value.thumbnail = res.url
    showToast('อัปโหลดรูปภาพหน้าปกสำเร็จ')
  } catch {
    showToast('อัปโหลดรูปภาพล้มเหลว', 'error')
  } finally {
    uploadCoverLoading.value = false
  }
}

const handleAvatarUpload = async (e: Event) => {
  const target = e.target as HTMLInputElement
  if (!target.files?.length) return
  uploadAvatarLoading.value = true
  try {
    const res = await api.uploadImage(target.files[0])
    form.value.author.avatar = res.url
    showToast('อัปโหลดรูปโปรไฟล์ผู้เขียนสำเร็จ')
  } catch {
    showToast('อัปโหลดรูปโปรไฟล์ล้มเหลว', 'error')
  } finally {
    uploadAvatarLoading.value = false
  }
}

// ─── Save ─────────────────────────────────────────────────────────────────────
const save = async () => {
  if (!form.value.title.trim()) { showToast('กรุณากรอกหัวข้อข่าว', 'error'); return }
  if (!form.value.category) { showToast('กรุณาเลือกหมวดหมู่', 'error'); return }

  saveLoading.value = true
  try {
    const selectedCat = categories.value.find((c) => c.name === form.value.category)
    
    const payload = {
      title: form.value.title.trim(),
      category: form.value.category,
      category_badge_class: selectedCat?.badge_class ?? 'bg-slate-600 text-white',
      desc: form.value.desc.trim(),
      content: form.value.content || '',
      date: form.value.date,
      read_time: form.value.readTime || '3 นาที',
      thumbnail: form.value.thumbnail,
      featured: form.value.featured,
      tags: form.value.tags
        ? form.value.tags.split(',').map((t) => t.trim()).filter(Boolean)
        : [],
      author: form.value.author,
      quote: form.value.quote.text.trim()
        ? { text: form.value.quote.text.trim(), by: form.value.quote.by.trim() }
        : null,
      gallery: form.value.gallery.filter(Boolean),
      attachments: form.value.attachments,
    }

    await api.createPost(payload)
    showToast('สร้างข่าวสารใหม่สำเร็จ')
    setTimeout(() => router.push('/admin/posts'), 1000)
  } catch (err) {
    console.error('Save post failed:', err)
    showToast('เกิดข้อผิดพลาดในการบันทึกข่าวสาร', 'error')
  } finally {
    saveLoading.value = false
  }
}

// ─── Init ─────────────────────────────────────────────────────────────────────
onMounted(async () => {
  try {
    categories.value = await api.getCategories()
    if (categories.value.length > 0) {
      form.value.category = categories.value[0].name
    }
  } catch {
    showToast('โหลดหมวดหมู่ไม่สำเร็จ', 'error')
  }
})
</script>

<template>
  <div class="max-w-6xl mx-auto pb-16">
    <!-- ── Sticky Top Bar ───────────────────────────────────────────────── -->
    <div class="sticky top-0 z-20 bg-white/95 backdrop-blur-sm border-b border-slate-200 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-3.5 mb-8 flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-3">
        <button
          type="button"
          class="inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-800 text-sm font-semibold transition-colors cursor-pointer"
          @click="router.push('/admin/posts')"
        >
          <v-icon icon="mdi-arrow-left" size="18" />
          <span>กลับ</span>
        </button>
        <div class="w-px h-5 bg-slate-200" />
        <h1 class="text-sm font-bold text-slate-800">สร้างข่าวสารและกิจกรรมใหม่</h1>
      </div>
      <div class="flex items-center gap-2.5">
        <button
          type="button"
          class="px-4 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 font-bold text-xs shadow-2xs transition-all cursor-pointer"
          @click="router.push('/admin/posts')"
        >
          ยกเลิก
        </button>
        <button
          type="button"
          :disabled="saveLoading"
          class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-xs shadow-xs hover:shadow transition-all disabled:opacity-50 cursor-pointer"
          @click="save"
        >
          <v-icon :icon="saveLoading ? 'mdi-loading' : 'mdi-content-save-outline'" size="16" :class="saveLoading ? 'animate-spin' : ''" />
          <span>{{ saveLoading ? 'กำลังบันทึก...' : 'บันทึกข่าวสาร' }}</span>
        </button>
      </div>
    </div>

    <!-- ── Two-Column Layout ────────────────────────────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- ── LEFT: Main Article Details (2 cols) ─────────────────────── -->
      <div class="lg:col-span-2 space-y-6">

        <!-- 1. Title & Excerpt -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 space-y-4 shadow-xs">
          <div class="flex items-center gap-2 text-slate-800 border-b border-slate-100 pb-3">
            <v-icon icon="mdi-format-title" size="20" class="text-emerald-700" />
            <h2 class="text-sm font-bold">1. ข้อมูลพื้นฐานของข่าวสาร</h2>
          </div>

          <!-- Title -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">หัวข้อข่าวสาร *</label>
            <textarea
              v-model="form.title"
              rows="2"
              placeholder="พิมพ์หัวข้อข่าวสารที่นี่..."
              class="w-full resize-none text-base sm:text-lg font-bold text-slate-900 placeholder:text-slate-300 p-3.5 bg-slate-50/60 rounded-xl border border-slate-300 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 outline-none leading-snug transition-all"
            />
          </div>

          <!-- Excerpt / Desc -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">บทคัดย่อ / สรุปสั้นๆ (Desc)</label>
            <textarea
              v-model="form.desc"
              rows="3"
              placeholder="คำบรรยายสั้นๆ ที่จะแสดงบนการ์ดข่าวหน้าแรกและส่วนหัว (แนะนำ 1-3 ประโยค)"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-400 leading-relaxed resize-y shadow-2xs"
            />
          </div>
        </div>

        <!-- 2. Cover Image (Thumbnail) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 space-y-3 shadow-xs">
          <div class="flex items-center gap-2 text-slate-800 border-b border-slate-100 pb-3">
            <v-icon icon="mdi-image-outline" size="20" class="text-emerald-700" />
            <h2 class="text-sm font-bold">2. รูปภาพหน้าปกหลัก (Cover Thumbnail)</h2>
          </div>

          <!-- Preview -->
          <div
            v-if="form.thumbnail"
            class="relative w-full aspect-video rounded-xl overflow-hidden bg-slate-100 border border-slate-200 group"
          >
            <img :src="form.thumbnail" alt="Preview" class="w-full h-full object-cover" />
            <button
              type="button"
              class="absolute top-2 right-2 w-8 h-8 rounded-full bg-black/60 hover:bg-rose-600 text-white flex items-center justify-center transition-colors cursor-pointer shadow-md"
              title="ลบรูปภาพ"
              @click="form.thumbnail = ''"
            >
              <v-icon icon="mdi-close" size="16" />
            </button>
          </div>

          <!-- Input row -->
          <div class="flex items-center gap-3">
            <input
              v-model="form.thumbnail"
              type="text"
              placeholder="URL รูปภาพหน้าปก หรือกดอัปโหลด..."
              class="flex-1 px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-400 shadow-2xs"
            />
            <label
              class="cursor-pointer inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold shrink-0 transition-all shadow-xs hover:shadow"
              :class="uploadCoverLoading ? 'opacity-60 pointer-events-none' : ''"
            >
              <v-icon :icon="uploadCoverLoading ? 'mdi-loading' : 'mdi-cloud-upload-outline'" size="16" :class="uploadCoverLoading ? 'animate-spin' : ''" />
              <span>{{ uploadCoverLoading ? 'กำลังอัปโหลด...' : 'อัปโหลดภาพ' }}</span>
              <input type="file" accept="image/*" class="hidden" @change="handleCoverUpload" />
            </label>
          </div>
        </div>

        <!-- 3. Key Highlights (ประเด็นสำคัญของข่าวนี้) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 space-y-4 shadow-xs">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2 text-slate-800">
              <v-icon icon="mdi-star-four-points" size="20" class="text-emerald-700" />
              <div>
                <h2 class="text-sm font-bold">3. ประเด็นสำคัญของข่าว (Key Highlights)</h2>
                <p class="text-[11px] text-slate-400">แสดงในกล่องสีเขียวเด่นด้านบนบทความ</p>
              </div>
            </div>
            <span class="text-xs text-slate-500 font-medium">{{ form.keyHighlights.length }} ข้อ</span>
          </div>

          <!-- List of current highlights -->
          <div v-if="form.keyHighlights.length > 0" class="space-y-2">
            <div
              v-for="(_, idx) in form.keyHighlights"
              :key="idx"
              class="flex items-center gap-2 p-2.5 bg-emerald-50/60 border border-emerald-200/80 rounded-xl"
            >
              <v-icon icon="mdi-check-circle" size="16" class="text-emerald-600 shrink-0" />
              <input
                v-model="form.keyHighlights[idx]"
                class="flex-1 text-xs sm:text-sm text-emerald-950 bg-transparent outline-none font-medium"
              />
              <button
                type="button"
                class="p-1 text-slate-400 hover:text-rose-600 rounded-md transition-colors cursor-pointer"
                title="ลบข้อนี้"
                @click="removeHighlight(idx)"
              >
                <v-icon icon="mdi-trash-can-outline" size="16" />
              </button>
            </div>
          </div>

          <!-- Add new highlight -->
          <div class="flex items-center gap-2">
            <input
              v-model="newHighlight"
              type="text"
              placeholder="พิมพ์ประเด็นสำคัญแล้วกดเพิ่ม หรือ Enter..."
              class="flex-1 px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-400 shadow-2xs"
              @keydown.enter.prevent="addHighlight"
            />
            <button
              type="button"
              class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold shrink-0 transition-all shadow-xs hover:shadow cursor-pointer"
              @click="addHighlight"
            >
              <v-icon icon="mdi-plus" size="16" />
              <span>เพิ่มประเด็น</span>
            </button>
          </div>
        </div>

        <!-- 4. Content Paragraphs (เนื้อหาข่าวฉบับเต็ม - Rich Text) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 space-y-3 shadow-xs">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2 text-slate-800">
              <v-icon icon="mdi-text-box-outline" size="20" class="text-emerald-700" />
              <h2 class="text-sm font-bold">4. เนื้อหาข่าวฉบับเต็ม (Article Body - Rich Text)</h2>
            </div>
            <span class="text-[11px] text-slate-400">รองรับตัวหนา, ตัวเอียง, ขีดเส้นใต้, หัวข้อ H2/H3, รายการ และลิงก์</span>
          </div>

          <RichTextEditor
            v-model="form.content"
            placeholder="พิมพ์เนื้อหาข่าว หรือคัดลอกจาก Word/Docs มาวางได้โดยตรง..."
          />
        </div>

        <!-- 5. Featured Quote (ข้อความอ้างอิง / คำคมเด่น) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 space-y-4 shadow-xs">
          <div class="flex items-center gap-2 text-slate-800 border-b border-slate-100 pb-3">
            <v-icon icon="mdi-format-quote-open" size="20" class="text-emerald-700" />
            <div>
              <h2 class="text-sm font-bold">5. กล่องข้อความอ้างอิงเด่น (Featured Quote)</h2>
              <p class="text-[11px] text-slate-400">เว้นว่างไว้หากไม่มีข้อความอ้างอิง</p>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">ข้อความอ้างอิง / คำกล่าว</label>
            <textarea
              v-model="form.quote.text"
              rows="2"
              placeholder="เช่น “เรามุ่งมั่นสร้างครูที่มีหัวใจแห่งความเป็นครู เพียบพร้อมด้วยปัญญา...”"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-400 resize-y shadow-2xs"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">ผู้กล่าว / แหล่งที่มา</label>
            <input
              v-model="form.quote.by"
              type="text"
              placeholder="เช่น คณบดีคณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-400 shadow-2xs"
            />
          </div>
        </div>

        <!-- 6. Photo Gallery (ภาพบรรยากาศและกิจกรรม) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 space-y-4 shadow-xs">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2 text-slate-800">
              <v-icon icon="mdi-image-multiple-outline" size="20" class="text-emerald-700" />
              <div>
                <h2 class="text-sm font-bold">6. ภาพบรรยากาศและกิจกรรม (Photo Gallery)</h2>
                <p class="text-[11px] text-slate-400">รูปภาพเพิ่มเติมที่จะแสดงแบบตาราง Gallery ท้ายข่าว</p>
              </div>
            </div>
            <span class="text-xs text-slate-500 font-medium">{{ form.gallery.length }} รูป</span>
          </div>

          <!-- Gallery Grid Preview -->
          <div v-if="form.gallery.length > 0" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <div
              v-for="(photo, pIdx) in form.gallery"
              :key="pIdx"
              class="relative aspect-4/3 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 group shadow-2xs"
            >
              <img :src="photo" alt="Gallery item" class="w-full h-full object-cover" />
              <button
                type="button"
                class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-black/60 hover:bg-rose-600 text-white flex items-center justify-center transition-colors cursor-pointer"
                title="ลบรูปนี้"
                @click="removeGallery(pIdx)"
              >
                <v-icon icon="mdi-close" size="12" />
              </button>
            </div>
          </div>

          <!-- Add Photo Row -->
          <div class="flex items-center gap-2">
            <input
              v-model="newGalleryUrl"
              type="text"
              placeholder="วาง URL รูปภาพแล้วกดเพิ่ม..."
              class="flex-1 px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-400 shadow-2xs"
              @keydown.enter.prevent="addGalleryUrl"
            />
            <button
              type="button"
              class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 text-xs font-bold shrink-0 transition-all shadow-2xs cursor-pointer"
              @click="addGalleryUrl"
            >
              <v-icon icon="mdi-link-plus" size="16" class="text-slate-500" />
              <span>เพิ่ม URL</span>
            </button>
            <label
              class="cursor-pointer inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold shrink-0 transition-all shadow-xs hover:shadow"
              :class="uploadGalleryLoading ? 'opacity-60 pointer-events-none' : ''"
            >
              <v-icon :icon="uploadGalleryLoading ? 'mdi-loading' : 'mdi-cloud-upload-outline'" size="16" :class="uploadGalleryLoading ? 'animate-spin' : ''" />
              <span>{{ uploadGalleryLoading ? 'กำลังอัปโหลด...' : 'อัปโหลดภาพ' }}</span>
              <input type="file" accept="image/*" multiple class="hidden" @change="handleGalleryUpload" />
            </label>
          </div>
        </div>

        <!-- 7. Attachments (เอกสารแนบที่เกี่ยวข้อง) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 space-y-4 shadow-xs">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2 text-slate-800">
              <v-icon icon="mdi-file-document-outline" size="20" class="text-emerald-700" />
              <div>
                <h2 class="text-sm font-bold">7. เอกสารแนบที่เกี่ยวข้อง (Downloadable Attachments)</h2>
                <p class="text-[11px] text-slate-400">ไฟล์ประกาศ แฟ้มข้อมูล PDF ที่ให้ดาวน์โหลด</p>
              </div>
            </div>
            <span class="text-xs text-slate-500 font-medium">{{ form.attachments.length }} ไฟล์</span>
          </div>

          <!-- Existing attachments list -->
          <div v-if="form.attachments.length > 0" class="space-y-2">
            <div
              v-for="(att, aIdx) in form.attachments"
              :key="aIdx"
              class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-xl"
            >
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center text-[10px] font-bold shrink-0">
                  {{ att.type || 'PDF' }}
                </div>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-slate-800 truncate">{{ att.name }}</div>
                  <div class="text-[10px] text-slate-400">{{ att.size }} • <a :href="att.url" target="_blank" class="text-emerald-600 hover:underline">ดูไฟล์</a></div>
                </div>
              </div>
              <button
                type="button"
                class="p-1 text-slate-400 hover:text-rose-600 rounded-md transition-colors cursor-pointer"
                title="ลบไฟล์แนบ"
                @click="removeAttachment(aIdx)"
              >
                <v-icon icon="mdi-trash-can-outline" size="16" />
              </button>
            </div>
          </div>

          <!-- Add attachment form -->
          <div class="p-4 bg-slate-50/70 border border-slate-200 rounded-xl space-y-3">
            <div class="text-xs font-bold text-slate-700">เพิ่มเอกสารแนบ</div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
              <div class="sm:col-span-2">
                <input
                  v-model="newAttach.name"
                  type="text"
                  placeholder="ชื่อเอกสาร เช่น ประกาศรับสมัคร..."
                  class="w-full px-3.5 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500"
                />
              </div>
              <div>
                <input
                  v-model="newAttach.size"
                  type="text"
                  placeholder="ขนาด เช่น 2.4 MB"
                  class="w-full px-3.5 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500"
                />
              </div>
            </div>
            <div class="flex items-center gap-2">
              <input
                v-model="newAttach.url"
                type="text"
                placeholder="URL ลิงก์ดาวน์โหลด..."
                class="flex-1 px-3.5 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500"
              />
              <button
                type="button"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 text-xs font-bold shrink-0 transition-all shadow-2xs cursor-pointer"
                @click="addAttachment"
              >
                <v-icon icon="mdi-plus" size="16" class="text-slate-500" />
                <span>เพิ่มเอกสาร</span>
              </button>
              <label
                class="cursor-pointer inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold shrink-0 transition-all shadow-xs hover:shadow"
                :class="uploadAttachLoading ? 'opacity-60 pointer-events-none' : ''"
              >
                <v-icon :icon="uploadAttachLoading ? 'mdi-loading' : 'mdi-cloud-upload-outline'" size="16" :class="uploadAttachLoading ? 'animate-spin' : ''" />
                <span>{{ uploadAttachLoading ? 'กำลังอัปโหลด...' : 'อัปโหลดไฟล์' }}</span>
                <input
                  type="file"
                  multiple
                  accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,image/*"
                  class="hidden"
                  @change="handleAttachmentUpload"
                />
              </label>
            </div>
          </div>
        </div>

      </div>

      <!-- ── RIGHT: Sidebar Metadata (1 col) ────────────────────────── -->
      <div class="space-y-6">

        <!-- 1. Publish Settings -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 space-y-4 shadow-xs">
          <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-2">
            การตั้งค่าการเผยแพร่
          </h3>

          <!-- Category -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">หมวดหมู่ *</label>
            <div class="relative">
              <select
                v-model="form.category"
                class="w-full px-3.5 py-2.5 pr-9 text-xs font-semibold text-slate-800 bg-white border border-slate-300 rounded-xl appearance-none focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer shadow-2xs"
              >
                <option v-for="cat in categories" :key="cat.id" :value="cat.name">
                  {{ cat.name }}
                </option>
              </select>
              <v-icon
                icon="mdi-chevron-down"
                size="18"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"
              />
            </div>
          </div>

          <!-- Category badge preview -->
          <div v-if="form.category" class="pt-1">
            <span
              :class="['px-3 py-1 rounded-full text-xs font-bold shadow-xs inline-block', categories.find(c => c.name === form.category)?.badge_class ?? 'bg-slate-600 text-white']"
            >
              {{ form.category }}
            </span>
          </div>

          <!-- Date -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">วันที่ประกาศ</label>
            <ThaiDatePicker v-model="form.date" />
          </div>

          <!-- Read Time -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">เวลาที่ใช้ในการอ่าน</label>
            <input
              v-model="form.readTime"
              type="text"
              placeholder="เช่น 3 นาที"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-400 shadow-2xs"
            />
          </div>

          <!-- Featured Switch -->
          <div class="pt-3 border-t border-slate-100">
            <label class="relative flex items-center gap-3 cursor-pointer select-none">
              <input type="checkbox" v-model="form.featured" class="sr-only peer" />
              <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:bg-emerald-600 transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all after:shadow-sm peer-checked:after:translate-x-5"></div>
              <div>
                <div class="text-xs font-bold text-slate-800">ข่าวเด่น (Featured)</div>
                <div class="text-[11px] text-slate-400">แสดงเป็นการ์ดเด่นขนาดใหญ่หน้าแรก</div>
              </div>
            </label>
          </div>
        </div>

        <!-- 2. Author Info -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 space-y-3.5 shadow-xs">
          <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-2">
            ข้อมูลผู้เขียน / ผู้เผยแพร่
          </h3>

          <!-- Author Avatar Preview -->
          <div class="flex items-center gap-3">
            <img
              :src="form.author.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80'"
              alt="Author Avatar"
              class="w-12 h-12 rounded-full object-cover border border-slate-200 shadow-xs"
            />
            <div class="flex-1 min-w-0">
              <label
                class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 text-xs font-bold transition-all shadow-2xs"
                :class="uploadAvatarLoading ? 'opacity-60 pointer-events-none' : ''"
              >
                <v-icon :icon="uploadAvatarLoading ? 'mdi-loading' : 'mdi-camera'" size="14" :class="uploadAvatarLoading ? 'animate-spin' : ''" />
                <span>{{ uploadAvatarLoading ? 'กำลังอัปโหลด...' : 'เปลี่ยนรูป' }}</span>
                <input type="file" accept="image/*" class="hidden" @change="handleAvatarUpload" />
              </label>
            </div>
          </div>

          <!-- Author Name -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">ชื่อผู้เขียน / ฝ่ายงาน</label>
            <input
              v-model="form.author.name"
              type="text"
              placeholder="เช่น ฝ่ายสื่อสารองค์กร คณะครุศาสตร์"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-400 shadow-2xs"
            />
          </div>

          <!-- Author Role -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">สังกัด / บทบาท</label>
            <input
              v-model="form.author.role"
              type="text"
              placeholder="เช่น คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์"
              class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-400 shadow-2xs"
            />
          </div>
        </div>

        <!-- 3. Tags -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 space-y-3 shadow-xs">
          <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-2">
            แท็ก (Tags)
          </h3>
          <input
            v-model="form.tags"
            type="text"
            placeholder="รับสมัคร, ทุนการศึกษา, TCAS69"
            class="w-full px-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-400 shadow-2xs"
          />
          <p class="text-[11px] text-slate-400">คั่นแต่ละแท็กด้วยเครื่องหมายจุลภาค (,)</p>
        </div>

      </div>
    </div>

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
