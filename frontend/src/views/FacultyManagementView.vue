<script setup lang="ts">
import { ref, onMounted } from 'vue'
import PageHeroBanner from '@/components/PageHeroBanner.vue'
import { api, type ExecutiveCategory } from '@/services/api'

const loading = ref(true)
const categories = ref<ExecutiveCategory[]>([])

// Fallback data in case API is offline
const fallbackCategories: ExecutiveCategory[] = [
  {
    id: 1,
    title: 'คณบดี',
    description: 'ผู้บริหารสูงสุดของคณะครุศาสตร์',
    sort_order: 1,
    is_active: true,
    members: [
      {
        id: 1,
        category_id: 1,
        position: 'คณบดีคณะครุศาสตร์',
        name: 'ผศ.ดร.สมชาย ใจดี',
        avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
        sort_order: 1,
        is_active: true,
      },
    ],
  },
  {
    id: 2,
    title: 'รองคณบดี',
    description: 'รองคณบดีฝ่ายต่างๆ ขับเคลื่อนยุทธศาสตร์คณะ',
    sort_order: 2,
    is_active: true,
    members: [
      {
        id: 2,
        category_id: 2,
        position: 'รองคณบดีฝ่ายวิชาการ',
        name: 'รศ.ดร.วิภาวี สุขสันต์',
        avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
        sort_order: 1,
        is_active: true,
      },
      {
        id: 3,
        category_id: 2,
        position: 'รองคณบดีฝ่ายวิจัยและบริการวิชาการ',
        name: 'ผศ.ดร.ประสิทธิ์ มานะดี',
        avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
        sort_order: 2,
        is_active: true,
      },
      {
        id: 4,
        category_id: 2,
        position: 'รองคณบดีฝ่ายกิจการนักศึกษา',
        name: 'อ.ดร.รัตนา พงษ์ไพร',
        avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
        sort_order: 3,
        is_active: true,
      },
      {
        id: 5,
        category_id: 2,
        position: 'รองคณบดีฝ่ายบริหารและแผน',
        name: 'ผศ.ดร.พิชัย เจริญสุข',
        avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
        sort_order: 4,
        is_active: true,
      },
    ],
  },
  {
    id: 3,
    title: 'ผู้ช่วยคณบดี',
    description: 'ผู้ช่วยคณบดีสนับสนุนการดำเนินงานภารกิจเฉพาะด้าน',
    sort_order: 3,
    is_active: true,
    members: [
      {
        id: 6,
        category_id: 3,
        position: 'ผู้ช่วยคณบดีฝ่ายประกันคุณภาพ',
        name: 'อ.สุภาพร วงษ์สว่าง',
        avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
        sort_order: 1,
        is_active: true,
      },
      {
        id: 7,
        category_id: 3,
        position: 'ผู้ช่วยคณบดีฝ่ายเทคโนโลยีสารสนเทศ',
        name: 'อ.ณัฐพล ทองคำ',
        avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
        sort_order: 2,
        is_active: true,
      },
    ],
  },
  {
    id: 4,
    title: 'หัวหน้าสำนักงานคณบดี',
    description: 'กำกับดูแลงานบริหารทั่วไปและสนับสนุนการศึกษา',
    sort_order: 4,
    is_active: true,
    members: [
      {
        id: 8,
        category_id: 4,
        position: 'หัวหน้าสำนักงานคณบดี',
        name: 'นางสาวมาลี อ่อนละมุน',
        avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
        sort_order: 1,
        is_active: true,
      },
    ],
  },
]

async function loadExecutives() {
  loading.value = true
  try {
    const data = await api.getExecutives({ active_only: true })
    if (data && data.length > 0) {
      categories.value = data
    } else {
      categories.value = fallbackCategories
    }
  } catch (err) {
    console.warn('Failed to load executives, using fallback:', err)
    categories.value = fallbackCategories
  } finally {
    loading.value = false
  }
}

function getCategoryColor(title: string): string {
  if (title.includes('คณบดี') && !title.includes('รอง') && !title.includes('ผู้ช่วย')) {
    return 'bg-emerald-600'
  }
  if (title.includes('รองคณบดี')) {
    return 'bg-emerald-500'
  }
  if (title.includes('ผู้ช่วย')) {
    return 'bg-teal-500'
  }
  return 'bg-slate-400'
}

onMounted(() => {
  loadExecutives()
})
</script>

<template>
  <div class="min-h-screen bg-white">
    <!-- Hero Banner -->
    <PageHeroBanner
      badge="Faculty Management"
      badge-icon="mdi-account-tie-outline"
      title="คณะผู้บริหาร"
      title-highlight="คณะครุศาสตร์"
      subtitle="ผู้บริหารคณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์ ที่ขับเคลื่อนการพัฒนาการศึกษาคุณภาพ"
    />

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 space-y-14">

      <!-- Loading State -->
      <div v-if="loading" class="space-y-12">
        <div v-for="i in 3" :key="i" class="animate-pulse space-y-4">
          <div class="h-6 bg-slate-200 rounded w-32" />
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            <div v-for="j in 4" :key="j" class="h-64 bg-slate-100 rounded-2xl" />
          </div>
        </div>
      </div>

      <!-- Dynamic Executive Categories -->
      <template v-else>
        <section
          v-for="cat in categories"
          :key="cat.id"
          class="space-y-6"
        >
          <!-- Category Header -->
          <div class="flex items-center gap-3">
            <span class="w-1.5 h-6 rounded-full" :class="getCategoryColor(cat.title)" />
            <h2 class="text-lg font-black text-slate-900 tracking-tight">{{ cat.title }}</h2>
            <span v-if="cat.members && cat.members.length > 1" class="text-xs text-slate-400 font-medium">
              ({{ cat.members.length }} ท่าน)
            </span>
          </div>

          <!-- Single Member Category (Centered Prominent Card) -->
          <div v-if="cat.members && cat.members.length === 1" class="flex justify-center">
            <div
              v-for="m in cat.members"
              :key="m.id"
              class="group w-52 sm:w-60 text-center"
            >
              <div class="relative rounded-3xl overflow-hidden bg-slate-100 shadow-xl shadow-slate-200/80 hover:shadow-2xl hover:shadow-emerald-500/15 transition-all duration-300 hover:-translate-y-1 mb-4 aspect-[4/5] border border-slate-100">
                <img
                  :src="m.avatar || 'https://placehold.co/400x500/f1f5f9/94a3b8?text=EDU+RRU'"
                  :alt="m.name || m.position"
                  class="w-full h-full object-cover object-top"
                  @error="($event.target as HTMLImageElement).src = 'https://placehold.co/400x500/f1f5f9/94a3b8?text=EDU+RRU'"
                />
                <div class="absolute bottom-0 inset-x-0 h-20 bg-gradient-to-t from-slate-900/25 to-transparent pointer-events-none" />
              </div>
              <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug">
                {{ m.name }}
              </h3>
              <p class="text-emerald-700 text-xs sm:text-sm font-semibold mt-0.5">
                {{ m.position }}
              </p>
              <p v-if="m.position_suffix" class="text-slate-400 text-xs mt-0.5">
                {{ m.position_suffix }}
              </p>
              <p v-if="m.email || m.phone" class="text-slate-400 text-[11px] mt-1">
                {{ m.email }} {{ m.phone ? '• ' + m.phone : '' }}
              </p>
            </div>
          </div>

          <!-- Multiple Members Category (Grid Cards) -->
          <div
            v-else-if="cat.members && cat.members.length > 1"
            class="grid grid-cols-2 sm:grid-cols-4 gap-5 sm:gap-6"
          >
            <div
              v-for="m in cat.members"
              :key="m.id"
              class="group text-center"
            >
              <div class="relative rounded-2xl overflow-hidden bg-slate-100 shadow-md shadow-slate-200/70 hover:shadow-xl hover:shadow-emerald-500/15 transition-all duration-300 hover:-translate-y-1 mb-3 aspect-[4/5] border border-slate-100">
                <img
                  :src="m.avatar || 'https://placehold.co/400x500/f1f5f9/94a3b8?text=EDU+RRU'"
                  :alt="m.name || m.position"
                  class="w-full h-full object-cover object-top"
                  @error="($event.target as HTMLImageElement).src = 'https://placehold.co/400x500/f1f5f9/94a3b8?text=EDU+RRU'"
                />
                <div class="absolute bottom-0 inset-x-0 h-12 bg-gradient-to-t from-slate-900/15 to-transparent pointer-events-none" />
              </div>
              <h3 class="text-sm font-bold text-slate-800 leading-snug">
                {{ m.name }}
              </h3>
              <p class="text-emerald-700 font-semibold text-[11px] sm:text-xs mt-0.5 leading-relaxed">
                {{ m.position }}
              </p>
              <p v-if="m.position_suffix" class="text-slate-400 text-[10px] sm:text-[11px]">
                {{ m.position_suffix }}
              </p>
            </div>
          </div>
        </section>
      </template>

    </div>
  </div>
</template>
