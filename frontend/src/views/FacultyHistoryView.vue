<script setup lang="ts">
import PageHeroBanner from '@/components/PageHeroBanner.vue'

const branches = [
  'การศึกษาปฐมวัย',
  'จิตวิทยาและการแนะแนว',
  'เทคโนโลยีการศึกษา',
  'ภาษาอังกฤษ',
  'ภาษาไทย',
  'สังคมศึกษา',
  'คณิตศาสตร์',
  'วิทยาศาสตร์ทั่วไป',
  'คอมพิวเตอร์ศึกษา',
  'ภาษาจีน',
]

const groups = [
  'พื้นฐานการศึกษาและบริหารการศึกษา',
  'ทดสอบและวัดผลการศึกษา',
  'หลักสูตรและการสอน',
  'พลศึกษาและนันทนาการ',
]
</script>

<template>
  <div class="min-h-screen bg-white">
    <!-- Hero Banner -->
    <PageHeroBanner
      badge="Faculty History"
      badge-icon="mdi-book-open-page-variant-outline"
      title="ประวัติ"
      title-highlight="คณะครุศาสตร์"
      subtitle="กว่า 80 ปีแห่งการสร้างครูดีมีคุณภาพสู่สังคมไทย ตั้งแต่ พ.ศ. 2483 จนถึงปัจจุบัน"
    />

    <!-- Timeline Content -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
      <!-- Timeline wrapper -->
      <div class="relative">
        <!-- Vertical line -->
        <div class="hidden sm:block absolute left-[calc(theme(spacing.24)+theme(spacing.4))] top-0 bottom-0 w-px bg-gradient-to-b from-emerald-400/80 via-emerald-500/40 to-transparent" />

        <div class="space-y-10 sm:space-y-12">

          <!-- 2483 -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2483</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white ring-offset-0 shadow" />
              <div class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md">
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  ก่อตั้ง "โรงเรียนฝึกหัดครูประกาศนียบัตรจังหวัด"
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                  เริ่มก่อตั้งครั้งแรกเมื่อปี พ.ศ. 2483 ชื่อว่า <strong class="text-slate-800">โรงเรียนฝึกหัดครูประกาศนียบัตรจังหวัด</strong> เปิดสอนหลักสูตรประกาศนียบัตรจังหวัด (ครู ว.)
                </p>
              </div>
            </div>
          </div>

          <!-- 2485 -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2485</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white shadow" />
              <div class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md">
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  เปลี่ยนชื่อเป็น "โรงเรียนสตรีฝึกหัดครูฉะเชิงเทรา"
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                  เปลี่ยนชื่อเป็น<strong class="text-slate-800">โรงเรียนสตรีฝึกหัดครูฉะเชิงเทรา</strong> เปิดสอนหลักสูตรครูประชาบาล (ป.ป.)
                </p>
              </div>
            </div>
          </div>

          <!-- 2493 -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2493</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white shadow" />
              <div class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md">
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  เปิดสอนหลักสูตรครูมูล
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                  เปิดสอนหลักสูตร<strong class="text-slate-800">ครูมูล</strong>
                </p>
              </div>
            </div>
          </div>

          <!-- 2494 -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2494</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white shadow" />
              <div class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md">
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  เปิดสอนหลักสูตรฝึกหัดครูประถม (ครั้งแรกในส่วนภูมิภาค)
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                  เปิดสอนหลักสูตรฝึกหัดครูประถม ซึ่งเป็นการเปิดสอนนักเรียนฝึกหัดครู ป.ป. หญิงเป็นครั้งแรกในส่วนภูมิภาค
                  โดยความร่วมมือขององค์การระหว่างประเทศ <strong class="text-slate-800">(FAO, WHO, USOM)</strong>
                </p>
              </div>
            </div>
          </div>

          <!-- 2498 -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2498</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white shadow" />
              <div class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md">
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  เปลี่ยนชื่อเป็น "โรงเรียนฝึกหัดครูฉะเชิงเทรา"
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                  สังกัดกรมการฝึกหัดครู เปลี่ยนหลักสูตรเป็น <strong class="text-slate-800">ป.กศ.</strong> และรับนักศึกษาชายเข้าเรียนด้วย
                </p>
              </div>
            </div>
          </div>

          <!-- 2513 -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2513</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white shadow" />
              <div class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md">
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  ได้รับการสถาปนาเป็น "วิทยาลัยครูฉะเชิงเทรา"
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                  วันที่ 1 ตุลาคม ได้รับการสถาปนาเป็น<strong class="text-slate-800">วิทยาลัยครูฉะเชิงเทรา</strong>
                  เปิดสอนหลักสูตรประกาศนียบัตรการศึกษาชั้นต้น และประกาศนียบัตรการศึกษาชั้นสูง
                </p>
              </div>
            </div>
          </div>

          <!-- 2518 -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2518</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white shadow" />
              <div class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md">
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  พ.ร.บ. วิทยาลัยครู — เปิดสอนระดับปริญญาตรี
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                  ตามพระราชบัญญัติวิทยาลัยครู พ.ศ. 2518 เปิดสอนระดับปริญญาตรี และแบ่งส่วนราชการเป็นคณะวิชา
                  โดย <strong class="text-slate-800">"คณะวิชาครุศาสตร์"</strong> ได้พัฒนามาจากหมวดวิชาการศึกษาเดิม ประกอบด้วยภาควิชาหลักสูตรและการสอน,
                  พื้นฐานการศึกษา, จิตวิทยาและการแนะแนว, การอนุบาลศึกษา, ทดสอบและวัดผลการศึกษา,
                  เทคโนโลยีและนวัตกรรมการศึกษา และต่อมาได้รวมภาควิชาพลศึกษาและนันทนาการเข้ามาด้วย
                </p>
              </div>
            </div>
          </div>

          <!-- 2527 -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2527</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white shadow" />
              <div class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md">
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  พ.ร.บ. วิทยาลัยครู (ฉบับ 2) — ขยายสาขาวิชา
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                  เปิดสอนสาขาวิชาต่าง ๆ ระดับปริญญาตรี ได้แก่ สาขาวิชาวิทยาศาสตร์ <strong class="text-slate-800">(วท.บ.)</strong>
                  สาขาวิชาศิลปศาสตร์ <strong class="text-slate-800">(ศศ.บ.)</strong> และสาขาวิชาการศึกษา <strong class="text-slate-800">(ค.บ.)</strong>
                </p>
              </div>
            </div>
          </div>

          <!-- 2535 — milestone -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2535</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-emerald-100 shadow-lg" />
              <div class="bg-emerald-900 border border-emerald-700/60 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-lg group-hover:shadow-emerald-900/30">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider mb-2">
                  <v-icon icon="mdi-crown-outline" size="12" />
                  จุดสำคัญ
                </div>
                <h3 class="font-bold text-white text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0" />
                  ได้รับพระราชทานนาม "สถาบันราชภัฏ" — เปลี่ยนเป็น "คณะครุศาสตร์"
                </h3>
                <p class="text-emerald-200/80 text-sm leading-relaxed">
                  วันที่ 14 กุมภาพันธ์ ได้รับพระราชทานนาม <strong class="text-white">"สถาบันราชภัฏ"</strong>
                  และคณะวิชาครุศาสตร์ ได้เปลี่ยนชื่อเป็น <strong class="text-white">"คณะครุศาสตร์"</strong>
                </p>
              </div>
            </div>
          </div>

          <!-- 2542 -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2542</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white shadow" />
              <div class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md">
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  ปรับเปลี่ยนการบริหารเป็นแบบโปรแกรมวิชา
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                  ปรับเปลี่ยนการบริหารภายในเป็นแบบโปรแกรมวิชา ในระยะแรกมี <strong class="text-slate-800">7 โปรแกรมวิชา</strong> และ 1 สาขาวิชา
                </p>
              </div>
            </div>
          </div>

          <!-- 2545 -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2545</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white shadow" />
              <div class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md">
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  รวมสาขาวิชาการศึกษาทั้งหมด
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                  สถาบันมีนโยบายให้สาขาวิชาการศึกษาทั้งหมดมาสังกัดคณะครุศาสตร์ รวมเป็น <strong class="text-slate-800">13 โปรแกรมวิชา</strong> 1 สาขาวิชา
                </p>
              </div>
            </div>
          </div>

          <!-- 2547 — milestone -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2547</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-emerald-100 shadow-lg" />
              <div class="bg-emerald-900 border border-emerald-700/60 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-lg group-hover:shadow-emerald-900/30">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider mb-2">
                  <v-icon icon="mdi-crown-outline" size="12" />
                  จุดสำคัญ
                </div>
                <h3 class="font-bold text-white text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0" />
                  ยกฐานะเป็น "มหาวิทยาลัยราชภัฏราชนครินทร์"
                </h3>
                <p class="text-emerald-200/80 text-sm leading-relaxed">
                  ปรับหลักสูตรการผลิตครูเป็น<strong class="text-white">หลักสูตร 5 ปี</strong> และในวันที่ 15 มิถุนายน
                  ได้ยกฐานะเป็น <strong class="text-white">"มหาวิทยาลัยราชภัฏราชนครินทร์"</strong>
                </p>
              </div>
            </div>
          </div>

          <!-- 2550 -->
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 group">
            <div class="sm:w-24 shrink-0 flex sm:flex-col sm:items-end">
              <span class="text-sm font-black text-emerald-600 tracking-wider">พ.ศ. 2550</span>
            </div>
            <div class="relative sm:pl-8">
              <div class="hidden sm:flex absolute -left-[5px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white shadow" />
              <div class="bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-200 rounded-2xl p-5 sm:p-6 transition-all duration-200 group-hover:shadow-md">
                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" />
                  เปลี่ยนจากโปรแกรมมาเป็น "สาขาวิชา"
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">
                  แบ่งเป็น <strong class="text-slate-800">10 สาขาวิชา</strong> กับ <strong class="text-slate-800">4 กลุ่มวิชา</strong> ดังนี้
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <p class="text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">10 สาขาวิชา</p>
                    <ul class="space-y-1.5">
                      <li v-for="s in branches" :key="s" class="flex items-start gap-1.5 text-xs text-slate-600">
                        <v-icon icon="mdi-circle-small" size="16" class="text-emerald-500 shrink-0 mt-0.5" />
                        {{ s }}
                      </li>
                    </ul>
                  </div>
                  <div>
                    <p class="text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">4 กลุ่มวิชา</p>
                    <ul class="space-y-1.5">
                      <li v-for="g in groups" :key="g" class="flex items-start gap-1.5 text-xs text-slate-600">
                        <v-icon icon="mdi-circle-small" size="16" class="text-emerald-500 shrink-0 mt-0.5" />
                        {{ g }}
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>
