<script setup lang="ts">
import PageHeroBanner from '@/components/PageHeroBanner.vue'

interface Person {
  name: string
  position: string
  avatar: string
}

const dean: Person = {
  name: 'ผศ.ดร.สมชาย ใจดี',
  position: 'คณบดีคณะครุศาสตร์',
  avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
}

const viceDeans: Person[] = [
  {
    name: 'รศ.ดร.วิภาวี สุขสันต์',
    position: 'รองคณบดีฝ่ายวิชาการ',
    avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
  },
  {
    name: 'ผศ.ดร.ประสิทธิ์ มานะดี',
    position: 'รองคณบดีฝ่ายวิจัยและบริการวิชาการ',
    avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
  },
  {
    name: 'อ.ดร.รัตนา พงษ์ไพร',
    position: 'รองคณบดีฝ่ายกิจการนักศึกษา',
    avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
  },
  {
    name: 'ผศ.ดร.พิชัย เจริญสุข',
    position: 'รองคณบดีฝ่ายบริหารและแผน',
    avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
  },
]

const assistantDeans: Person[] = [
  {
    name: 'อ.สุภาพร วงษ์สว่าง',
    position: 'ผู้ช่วยคณบดีฝ่ายประกันคุณภาพ',
    avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
  },
  {
    name: 'อ.ณัฐพล ทองคำ',
    position: 'ผู้ช่วยคณบดีฝ่ายเทคโนโลยีสารสนเทศ',
    avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
  },
]

const officeHead: Person = {
  name: 'นางสาวมาลี อ่อนละมุน',
  position: 'หัวหน้าสำนักงานคณบดี',
  avatar: 'https://placehold.co/400x500/e2e8f0/94a3b8?text=รูปภาพ',
}

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

      <!-- คณบดี -->
      <section>
        <div class="flex items-center gap-3 mb-8">
          <span class="w-1 h-6 rounded-full bg-emerald-600" />
          <h2 class="text-lg font-black text-slate-900 tracking-tight">คณบดี</h2>
        </div>

        <div class="flex justify-center">
          <!-- Dean Portrait Card -->
          <div class="group w-52 sm:w-60 text-center">
            <div class="relative rounded-3xl overflow-hidden bg-slate-100 shadow-xl shadow-slate-200/80 hover:shadow-2xl hover:shadow-emerald-500/15 transition-all duration-300 hover:-translate-y-1 mb-4 aspect-[4/5]">
              <img
                :src="dean.avatar"
                :alt="dean.name"
                class="w-full h-full object-cover object-top"
              />
              <!-- Subtle bottom gradient for depth -->
              <div class="absolute bottom-0 inset-x-0 h-20 bg-gradient-to-t from-slate-900/20 to-transparent pointer-events-none" />
            </div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug">{{ dean.name }}</h3>
            <p class="text-slate-500 text-xs sm:text-sm mt-0.5">{{ dean.position }}</p>
          </div>
        </div>
      </section>

      <!-- รองคณบดี -->
      <section>
        <div class="flex items-center gap-3 mb-8">
          <span class="w-1 h-6 rounded-full bg-emerald-500" />
          <h2 class="text-lg font-black text-slate-900 tracking-tight">รองคณบดี</h2>
          <span class="text-xs text-slate-400 font-medium">({{ viceDeans.length }} ท่าน)</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-5 sm:gap-6">
          <div
            v-for="vd in viceDeans"
            :key="vd.name"
            class="group text-center"
          >
            <div class="relative rounded-2xl overflow-hidden bg-slate-100 shadow-md shadow-slate-200/70 hover:shadow-xl hover:shadow-emerald-500/15 transition-all duration-300 hover:-translate-y-1 mb-3 aspect-[4/5]">
              <img :src="vd.avatar" :alt="vd.name" class="w-full h-full object-cover object-top" />
              <div class="absolute bottom-0 inset-x-0 h-12 bg-gradient-to-t from-slate-900/15 to-transparent pointer-events-none" />
            </div>
            <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ vd.name }}</h3>
            <p class="text-slate-500 text-[11px] sm:text-xs mt-0.5 leading-relaxed">{{ vd.position }}</p>
          </div>
        </div>
      </section>

      <!-- ผู้ช่วยคณบดี -->
      <section>
        <div class="flex items-center gap-3 mb-8">
          <span class="w-1 h-6 rounded-full bg-teal-500" />
          <h2 class="text-lg font-black text-slate-900 tracking-tight">ผู้ช่วยคณบดี</h2>
          <span class="text-xs text-slate-400 font-medium">({{ assistantDeans.length }} ท่าน)</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-5 sm:gap-6">
          <div
            v-for="ad in assistantDeans"
            :key="ad.name"
            class="group text-center"
          >
            <div class="relative rounded-2xl overflow-hidden bg-slate-100 shadow-md shadow-slate-200/70 hover:shadow-xl hover:shadow-teal-500/15 transition-all duration-300 hover:-translate-y-1 mb-3 aspect-[4/5]">
              <img :src="ad.avatar" :alt="ad.name" class="w-full h-full object-cover object-top" />
              <div class="absolute bottom-0 inset-x-0 h-12 bg-gradient-to-t from-slate-900/15 to-transparent pointer-events-none" />
            </div>
            <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ ad.name }}</h3>
            <p class="text-slate-500 text-[11px] sm:text-xs mt-0.5 leading-relaxed">{{ ad.position }}</p>
          </div>
        </div>
      </section>

      <!-- หัวหน้าสำนักงาน -->
      <section>
        <div class="flex items-center gap-3 mb-8">
          <span class="w-1 h-6 rounded-full bg-slate-400" />
          <h2 class="text-lg font-black text-slate-900 tracking-tight">หัวหน้าสำนักงาน</h2>
        </div>

        <div class="flex justify-center">
          <div class="group text-center w-40 sm:w-48">
            <div class="relative rounded-2xl overflow-hidden bg-slate-100 shadow-md shadow-slate-200/70 hover:shadow-xl hover:shadow-slate-400/20 transition-all duration-300 hover:-translate-y-1 mb-3 aspect-[4/5]">
              <img :src="officeHead.avatar" :alt="officeHead.name" class="w-full h-full object-cover object-top" />
              <div class="absolute bottom-0 inset-x-0 h-12 bg-gradient-to-t from-slate-900/15 to-transparent pointer-events-none" />
            </div>
            <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ officeHead.name }}</h3>
            <p class="text-slate-500 text-[11px] sm:text-xs mt-0.5 leading-relaxed">{{ officeHead.position }}</p>
          </div>
        </div>
      </section>

    </div>
  </div>
</template>
