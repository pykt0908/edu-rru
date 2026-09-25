<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { api, type CurriculumRecord } from '@/services/api'
import eduLogo from '@/assets/logos/edu-logo-border-white.png'

const route = useRoute()
const activeTab = ref('details')
const loading = ref(true)
const curriculumData = ref<CurriculumRecord | null>(null)
const facultyMembers = ref<any[]>([])

const tabs = [
  { id: 'details', label: 'รายละเอียดหลักสูตร' },
  { id: 'fees', label: 'ค่าธรรมเนียมการศึกษา' },
  { id: 'structure', label: 'โครงสร้างหลักสูตร' },
  { id: 'plan', label: 'แผนการเรียน' },
  { id: 'faculty', label: 'อาจารย์ประจำหลักสูตร' },
]

// Current gallery photo index
const activePhotoIndex = ref(0)
const isFullscreen = ref(false)

const openLightbox = (idx: number) => {
  activePhotoIndex.value = idx
  isFullscreen.value = true
}

// Major Slug from URL Query
const currentMajorSlug = computed(() => {
  return (route.query.major as string) || 'datascience'
})

// Specific major presets
interface MajorPreset {
  nameTh: string
  nameEn: string
  degreeFullTh: string
  degreeFullEn: string
  degreeShortTh: string
  degreeShortEn: string
  credits: string
  format: string
  type: string
  language: string
  location: string
  deptSlug: string
  careers: string[]
  learningOutcomes: { year: string; description: string }[]
  structures: { category: string; credits: string; highlight?: boolean }[]
  photos: { url: string; title: string; badge: string }[]
}

const majorPresets: Record<string, MajorPreset> = {
  datascience: {
    nameTh: 'หลักสูตรวิทยาศาสตรบัณฑิต สาขาวิชาวิทยาการข้อมูล',
    nameEn: 'Bachelor of Science Program in Data Science',
    degreeFullTh: 'วิทยาศาสตรบัณฑิต (วิทยาการข้อมูล)',
    degreeFullEn: 'Bachelor of Science (Data Science)',
    degreeShortTh: 'วท.บ. (วิทยาการข้อมูล)',
    degreeShortEn: 'B.Sc. (Data Science)',
    credits: '120 หน่วยกิต',
    format: 'หลักสูตรปริญญาตรี 4 ปี',
    type: 'ปริญญาตรีทางวิชาการ',
    language: 'ภาษาไทย/ภาษาอังกฤษ',
    location: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
    deptSlug: 'data-science',
    careers: [
      'นักวิทยาศาสตร์ข้อมูล (Data Scientist)',
      'นักวิเคราะห์และวางแผนข้อมูล (Data Analyst)',
      'วิศวกรข้อมูล (Data Engineer)',
      'นักวิเคราะห์สถิติ (Statistical Analyst)',
      'นักวิชาการด้านวิทยาการข้อมูล (Academic Staff)',
      'วิศวกรด้านการเรียนรู้ของเครื่อง (Machine Learning Engineer)',
      'ครูและอาจารย์ผู้สอนด้านวิทยาการคำนวณและเทคโนโลยีดิจิทัล',
    ],
    learningOutcomes: [
      { year: 'ชั้นปีที่ 1', description: 'อธิบายหลักการด้านวิทยาการข้อมูล วิธีการในการจัดการข้อมูล หลักการออกแบบวิธีการแก้ปัญหา เขียนโปรแกรมคอมพิวเตอร์เบื้องต้นด้วย Python และ R' },
      { year: 'ชั้นปีที่ 2', description: 'ประยุกต์ใช้องค์ความรู้ด้านวิทยาการข้อมูล วิเคราะห์และบริหารจัดการฐานข้อมูล ออกแบบแบบจำลองทางสถิติ' },
      { year: 'ชั้นปีที่ 3', description: 'พัฒนาโมเดล Machine Learning, วิเคราะห์ Big Data, และสร้าง Data Visualization สื่อสารเชิงธุรกิจ' },
      { year: 'ชั้นปีที่ 4', description: 'บูรณาการโครงงานวิทยาการข้อมูลและปัญญาประดิษฐ์เพื่อแก้ปัญหาจริงในองค์กรและสังคม' },
    ],
    structures: [
      { category: '1. หมวดวิชาศึกษาทั่วไป', credits: '30 หน่วยกิต' },
      { category: '2. หมวดวิชาเฉพาะ (วิชาแกน, วิชาเอกบังคับ, วิชาเอกเลือก)', credits: '84 หน่วยกิต' },
      { category: '3. หมวดวิชาเลือกเสรี', credits: '6 หน่วยกิต' },
      { category: 'รวมหน่วยกิตตลอดหลักสูตร', credits: '120 หน่วยกิต', highlight: true },
    ],
    photos: [
      { url: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1000&q=80', title: 'ห้องปฏิบัติการ Data Science & Big Data Lab', badge: 'LABORATORY' },
      { url: 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1000&q=80', title: 'การเรียนการสอนเชิงปฏิบัติการและการพัฒนานวัตกรรมข้อมูล', badge: 'WORKSHOP & LAB' },
      { url: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1000&q=80', title: 'การจัดนิทรรศการผลงานและการนำเสนอทางวิชาการ', badge: 'EXHIBITION' },
      { url: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1000&q=80', title: 'การแข่งขันทักษะและการรับรางวัลระดับประเทศ', badge: 'HACKATHON' },
    ],
  },
  'early-childhood': {
    nameTh: 'หลักสูตรครุศาสตรบัณฑิต สาขาวิชาการศึกษาปฐมวัย',
    nameEn: 'Bachelor of Education Program in Early Childhood Education',
    degreeFullTh: 'ครุศาสตรบัณฑิต (การศึกษาปฐมวัย)',
    degreeFullEn: 'Bachelor of Education (Early Childhood Education)',
    degreeShortTh: 'ค.บ. (การศึกษาปฐมวัย)',
    degreeShortEn: 'B.Ed. (Early Childhood Education)',
    credits: '132 หน่วยกิต',
    format: 'หลักสูตรปริญญาตรี 4 ปี (วิชาชีพครู)',
    type: 'ปริญญาตรีวิชาชีพครู',
    language: 'ภาษาไทย',
    location: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
    deptSlug: 'early-childhood',
    careers: [
      'ครูผู้สอนระดับปฐมวัยในสถานศึกษาของรัฐบาลและเอกชน',
      'นักวิชาการและผู้เชี่ยวชาญด้านพัฒนาการเด็กปฐมวัย',
      'ผู้บริหารสถานศึกษาปฐมวัย / ศูนย์พัฒนาเด็กเล็ก',
      'นักออกแบบสื่อและนวัตกรรมการเรียนรู้สำหรับเด็กปฐมวัย',
      'ผู้ประกอบการสถานรับเลี้ยงเด็กและสถานพัฒนาเด็กปฐมวัย',
    ],
    learningOutcomes: [
      { year: 'ชั้นปีที่ 1', description: 'เข้าใจจิตวิทยาพัฒนาการเด็กปฐมวัย พัฒนาทักษะสมอง EF (Executive Functions) และปรัชญาการศึกษาปฐมวัย' },
      { year: 'ชั้นปีที่ 2', description: 'ออกแบบกิจกรรมการเรียนรู้ผ่านการเล่น (Play-based Learning) และผลิตสื่อการสอนปฐมวัยที่สร้างสรรค์' },
      { year: 'ชั้นปีที่ 3', description: 'ประเมินพัฒนาการเด็กแบบองค์รวม และฝึกปฏิบัติการสอนในสถานศึกษาจำลอง' },
      { year: 'ชั้นปีที่ 4', description: 'ปฏิบัติการสอนในสถานศึกษาเต็มเวลา 1 ปีการศึกษาตามมาตรฐานวิชาชีพคุรุสภา' },
    ],
    structures: [
      { category: '1. หมวดวิชาศึกษาทั่วไป', credits: '30 หน่วยกิต' },
      { category: '2. หมวดวิชาเฉพาะ (วิชาชีพครู และวิชาเอกปฐมวัย)', credits: '96 หน่วยกิต' },
      { category: '3. หมวดวิชาเลือกเสรี', credits: '6 หน่วยกิต' },
      { category: 'รวมหน่วยกิตตลอดหลักสูตร', credits: '132 หน่วยกิต', highlight: true },
    ],
    photos: [
      { url: 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=1000&q=80', title: 'การจัดประสบการณ์การเรียนรู้ระดับปฐมวัย', badge: 'CLASSROOM' },
      { url: 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?auto=format&fit=crop&w=1000&q=80', title: 'นวัตกรรมสื่อและของเล่นส่งเสริมพัฒนาการเด็ก', badge: 'INNOVATION' },
      { url: 'https://images.unsplash.com/photo-1576495199011-eb94736d05d6?auto=format&fit=crop&w=1000&q=80', title: 'กิจกรรมเสริมสร้างทักษะสมอง EF ในเด็กปฐมวัย', badge: 'ACTIVITIES' },
      { url: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1000&q=80', title: 'การฝึกประสบการณ์วิชาชีพครูในโรงเรียนสาธิต', badge: 'TEACHING' },
    ],
  },
  elementary: {
    nameTh: 'หลักสูตรครุศาสตรบัณฑิต สาขาวิชาการประถมศึกษา',
    nameEn: 'Bachelor of Education Program in Elementary Education',
    degreeFullTh: 'ครุศาสตรบัณฑิต (การประถมศึกษา)',
    degreeFullEn: 'Bachelor of Education (Elementary Education)',
    degreeShortTh: 'ค.บ. (การประถมศึกษา)',
    degreeShortEn: 'B.Ed. (Elementary Education)',
    credits: '132 หน่วยกิต',
    format: 'หลักสูตรปริญญาตรี 4 ปี (วิชาชีพครู)',
    type: 'ปริญญาตรีวิชาชีพครู',
    language: 'ภาษาไทย',
    location: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
    deptSlug: 'elementary',
    careers: [
      'ครูผู้สอนระดับประถมศึกษาประจำชั้นและประจำกลุ่มสาระ',
      'นักวิชาการศึกษา และนักออกแบบหลักสูตรระดับประถมศึกษา',
      'ครูผู้เชี่ยวชาญการจัดการเรียนรู้บูรณาการและสะเต็มศึกษา',
    ],
    learningOutcomes: [
      { year: 'ชั้นปีที่ 1', description: 'รอบรู้เนื้อหาพื้นฐาน 8 กลุ่มสาระการเรียนรู้ และจิตวิทยาพัฒนาการเด็กวัยประถม' },
      { year: 'ชั้นปีที่ 2', description: 'ออกแบบการจัดกิจกรรมบูรณาการ การสอนแบบ Active Learning และการวัดประเมินผล' },
      { year: 'ชั้นปีที่ 3', description: 'วิจัยในชั้นเรียนเพื่อแก้ปัญหาการเรียนรู้ของผู้เรียนระดับประถม' },
      { year: 'ชั้นปีที่ 4', description: 'ปฏิบัติการสอนในสถานศึกษาตามมาตรฐานคุรุสภา 1 ปีการศึกษา' },
    ],
    structures: [
      { category: '1. หมวดวิชาศึกษาทั่วไป', credits: '30 หน่วยกิต' },
      { category: '2. หมวดวิชาเฉพาะ (วิชาชีพครู และวิชาเอกประถมศึกษา)', credits: '96 หน่วยกิต' },
      { category: '3. หมวดวิชาเลือกเสรี', credits: '6 หน่วยกิต' },
      { category: 'รวมหน่วยกิตตลอดหลักสูตร', credits: '132 หน่วยกิต', highlight: true },
    ],
    photos: [
      { url: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=1000&q=80', title: 'การจัดการเรียนรู้เชิงรุกระดับประถมศึกษา', badge: 'ACTIVE LEARNING' },
      { url: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1000&q=80', title: 'ห้องเรียนแห่งศตวรรษที่ 21 คณะครุศาสตร์', badge: '21ST CENTURY' },
      { url: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1000&q=80', title: 'การฝึกประสบการณ์ในโรงเรียนเครือข่าย', badge: 'INTERNSHIP' },
      { url: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1000&q=80', title: 'การประกวดนวัตกรรมการสอนระดับประถมศึกษา', badge: 'AWARDS' },
    ],
  },
}

// Resolved active program data
const activePreset = computed(() => {
  const slug = currentMajorSlug.value
  if (majorPresets[slug]) return majorPresets[slug]

  // Default fallback for other majors
  const title = curriculumData.value?.title || 'สาขาวิชา'
  const titleEn = curriculumData.value?.title_en || 'Major Program'
  const degree = curriculumData.value?.degree_title || 'ครุศาสตรบัณฑิต (ค.บ.)'
  const credits = curriculumData.value?.credits || '132 หน่วยกิต'
  const duration = curriculumData.value?.duration || '4 ปี'
  const coverImg = curriculumData.value?.image || 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1000&q=80'

  return {
    nameTh: `หลักสูตร${degree.includes('วท.บ.') ? 'วิทยาศาสตรบัณฑิต' : degree.includes('ค.ม.') ? 'ครุศาสตรมหาบัณฑิต' : degree.includes('ป.บัณฑิต') ? 'ประกาศนียบัตรบัณฑิต' : 'ครุศาสตรบัณฑิต'} ${title}`,
    nameEn: `Program in ${titleEn}`,
    degreeFullTh: `${degree} (${title.replace('สาขาวิชา', '')})`,
    degreeFullEn: `Bachelor of Education (${titleEn})`,
    degreeShortTh: degree,
    degreeShortEn: degree.includes('วท.บ.') ? 'B.Sc.' : degree.includes('ค.ม.') ? 'M.Ed.' : 'B.Ed.',
    credits: credits,
    format: `หลักสูตร${duration}`,
    type: 'ปริญญาตรีทางวิชาชีพ',
    language: 'ภาษาไทย',
    location: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
    deptSlug: slug,
    careers: [
      `ครูผู้สอน${title.replace('สาขาวิชา', '')} ในสถานศึกษาของรัฐและเอกชน`,
      `นักวิชาการศึกษา และนักวิจัยด้าน${title.replace('สาขาวิชา', '')}`,
      'ผู้ประกอบการและนักพัฒนานวัตกรรมการจัดการเรียนรู้',
      'บุคลากรทางการศึกษาในหน่วยงานภาครัฐและเอกชน',
    ],
    learningOutcomes: [
      { year: 'ชั้นปีที่ 1', description: `สร้างความรู้พื้นฐานและเจตคติที่ดีต่อวิชาชีพและศาสตร์ด้าน${title.replace('สาขาวิชา', '')}` },
      { year: 'ชั้นปีที่ 2', description: 'พัฒนาทักษะการออกแบบหลักสูตรและวิธีสอนสมัยใหม่' },
      { year: 'ชั้นปีที่ 3', description: 'วิจัยและพัฒนานวัตกรรมการจัดการเรียนรู้ในชั้นเรียน' },
      { year: 'ชั้นปีที่ 4', description: 'ฝึกปฏิบัติการสอนในสถานศึกษาเต็มเวลาตามมาตรฐานวิชาชีพ' },
    ],
    structures: [
      { category: '1. หมวดวิชาศึกษาทั่วไป', credits: '30 หน่วยกิต' },
      { category: '2. หมวดวิชาเฉพาะ', credits: '96 หน่วยกิต' },
      { category: '3. หมวดวิชาเลือกเสรี', credits: '6 หน่วยกิต' },
      { category: 'รวมหน่วยกิตตลอดหลักสูตร', credits: credits, highlight: true },
    ],
    photos: [
      { url: coverImg, title: `ภาพกิจกรรมและการเรียนการสอน ${title}`, badge: 'FEATURED' },
      { url: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1000&q=80', title: 'การเรียนรู้เชิงปฏิบัติการ คณะครุศาสตร์', badge: 'WORKSHOP' },
      { url: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1000&q=80', title: 'การฝึกประสบการณ์ในสถานศึกษา', badge: 'PRACTICE' },
      { url: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1000&q=80', title: 'นิทรรศการผลงานและการนำเสนอทางวิชาการ', badge: 'EXHIBITION' },
    ],
  }
})

// Current photos array
const photos = computed<{ url: string; title: string; badge: string }[]>(() => {
  if (curriculumData.value?.detail_content?.gallery && Array.isArray(curriculumData.value.detail_content.gallery) && curriculumData.value.detail_content.gallery.length > 0) {
    return curriculumData.value.detail_content.gallery
  }
  const presetPhotos = [...activePreset.value.photos]
  if (curriculumData.value?.image) {
    presetPhotos[0] = {
      url: curriculumData.value.image,
      title: `ภาพหน้าปกหลักสูตร ${curriculumData.value.title}`,
      badge: 'COVER IMAGE',
    }
  }
  return presetPhotos
})

// Resolved dynamic detail content from database with fallback to presets
const resolvedCareers = computed(() => {
  if (curriculumData.value?.detail_content?.careers && Array.isArray(curriculumData.value.detail_content.careers) && curriculumData.value.detail_content.careers.length > 0) {
    return curriculumData.value.detail_content.careers
  }
  return activePreset.value.careers || []
})

const resolvedLearningOutcomes = computed(() => {
  if (curriculumData.value?.detail_content?.learningOutcomes && Array.isArray(curriculumData.value.detail_content.learningOutcomes) && curriculumData.value.detail_content.learningOutcomes.length > 0) {
    return curriculumData.value.detail_content.learningOutcomes
  }
  return activePreset.value.learningOutcomes || []
})

const resolvedTuitionFees = computed(() => {
  if (curriculumData.value?.detail_content?.tuitionFees && Array.isArray(curriculumData.value.detail_content.tuitionFees) && curriculumData.value.detail_content.tuitionFees.length > 0) {
    return curriculumData.value.detail_content.tuitionFees
  }
  return tuitionFees
})

const resolvedStructures = computed(() => {
  if (curriculumData.value?.detail_content?.structures && Array.isArray(curriculumData.value.detail_content.structures) && curriculumData.value.detail_content.structures.length > 0) {
    return curriculumData.value.detail_content.structures
  }
  return activePreset.value.structures || []
})

const nextPhoto = () => {
  activePhotoIndex.value = (activePhotoIndex.value + 1) % photos.value.length
}

const prevPhoto = () => {
  activePhotoIndex.value = (activePhotoIndex.value - 1 + photos.value.length) % photos.value.length
}

const handleKeyDown = (e: KeyboardEvent) => {
  if (!isFullscreen.value) return
  if (e.key === 'ArrowRight') {
    nextPhoto()
  } else if (e.key === 'ArrowLeft') {
    prevPhoto()
  } else if (e.key === 'Escape') {
    isFullscreen.value = false
  }
}

// Breadcrumb Link & Label
const degreeBreadcrumb = computed(() => {
  const level = curriculumData.value?.degree_level || 'bachelor'
  if (level === 'grad-diploma') {
    return { to: '/curriculum/grad-diploma', label: 'หลักสูตรประกาศนียบัตรบัณฑิต' }
  }
  if (level === 'master') {
    return { to: '/curriculum/master', label: 'หลักสูตรปริญญาโท' }
  }
  return { to: '/curriculum/bachelor', label: 'หลักสูตรปริญญาตรี' }
})

// Load Curriculum & Faculty Data
async function loadDetail() {
  loading.value = true
  try {
    const slug = currentMajorSlug.value
    const data = await api.getCurriculum(slug)
    if (data) {
      curriculumData.value = data
    }
  } catch (err) {
    console.warn(`Could not load curriculum data for slug ${currentMajorSlug.value}:`, err)
  }

  // Load faculty members for this department
  try {
    const deptSlug = activePreset.value.deptSlug
    const personnelRes = await api.getPersonnel({ department_id: deptSlug })
    const list = personnelRes?.data ?? personnelRes
    if (Array.isArray(list) && list.length > 0) {
      facultyMembers.value = list
    } else {
      facultyMembers.value = []
    }
  } catch (err) {
    console.warn('Could not load faculty members:', err)
  } finally {
    loading.value = false
  }
}

watch(
  () => route.query.major,
  () => {
    loadDetail()
  }
)

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown)
  loadDetail()
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown)
})

// Tuition Fees Data
const tuitionFees = [
  { term: 'ภาคการศึกษาที่ 1', amount: '13,500 บาท', note: 'รวมค่าธรรมเนียมแรกเข้าและอุปกรณ์' },
  { term: 'ภาคการศึกษาที่ 2', amount: '11,500 บาท', note: 'ค่าลงทะเบียนปกติ' },
  { term: 'รวมตลอดหลักสูตร (8 ภาคการศึกษา)', amount: '94,000 บาท', note: 'เฉลี่ยเทอมละประมาณ 11,750 บาท' },
]

// Study Plan Sample
const studyPlan = [
  {
    year: 'ชั้นปีที่ 1',
    semesters: [
      {
        name: 'ภาคเรียนที่ 1',
        courses: [
          'ความเป็นครูและจิตวิญญาณความเป็นครู (3 นก.)',
          'ภาษาอังกฤษเพื่อการสื่อสารทางวิชาการ (3 นก.)',
          'การคิดเชิงคำนวณและการแก้ปัญหา (3 นก.)',
          'การรู้ดิจิทัลและการรู้สารสนเทศ (3 นก.)',
          'วิชาศึกษาทั่วไปเลือก (3 นก.)',
        ],
      },
      {
        name: 'ภาคเรียนที่ 2',
        courses: [
          'จิตวิทยาสำหรับครูและการแนะแนว (3 นก.)',
          'ปรัชญาและหลักสูตรการศึกษา (3 นก.)',
          'ทักษะชีวิตและความเป็นพลเมือง (3 นก.)',
          'วิชาพื้นฐานเฉพาะสาขาวิชา (3 นก.)',
          'วิชาศึกษาทั่วไปเลือก (3 นก.)',
        ],
      },
    ],
  },
  {
    year: 'ชั้นปีที่ 2',
    semesters: [
      {
        name: 'ภาคเรียนที่ 1',
        courses: [
          'การจัดการเรียนรู้และการจัดการชั้นเรียน (3 นก.)',
          'นวัตกรรมและเทคโนโลยีดิจิทัลเพื่อการศึกษา (3 นก.)',
          'วิชาเอกบังคับ 1 (3 นก.)',
          'วิชาเอกบังคับ 2 (3 นก.)',
        ],
      },
      {
        name: 'ภาคเรียนที่ 2',
        courses: [
          'การวัดและประเมินผลการเรียนรู้ (3 นก.)',
          'การวิจัยเพื่อพัฒนาการเรียนรู้ (3 นก.)',
          'วิชาเอกบังคับ 3 (3 นก.)',
          'วิชาเอกเลือก 1 (3 นก.)',
        ],
      },
    ],
  },
]
</script>

<template>
  <div class="min-h-screen bg-slate-50/50 pb-20">
    <!-- Top Breadcrumb & Program Title -->
    <div class="bg-white border-b border-slate-200/80">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-2">
              <RouterLink to="/" class="hover:text-emerald-700 no-underline text-slate-500">หน้าแรก</RouterLink>
              <span>/</span>
              <RouterLink :to="degreeBreadcrumb.to" class="hover:text-emerald-700 no-underline text-slate-500">
                {{ degreeBreadcrumb.label }}
              </RouterLink>
              <span>/</span>
              <span class="text-slate-800 font-semibold">
                {{ curriculumData?.title || activePreset.nameTh }}
              </span>
            </div>

            <!-- Page Title -->
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">
              {{ curriculumData?.title || activePreset.nameTh }}
            </h1>
            <p class="text-sm sm:text-base text-slate-500 font-medium tracking-wide mt-1">
              {{ curriculumData?.title_en || activePreset.nameEn }}
            </p>
          </div>

          <!-- Back to Programs Link -->
          <div>
            <RouterLink
              :to="degreeBreadcrumb.to"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold transition-colors no-underline cursor-pointer"
            >
              <v-icon icon="mdi-arrow-left" size="16" />
              <span>ดูหลักสูตรทั้งหมด</span>
            </RouterLink>
          </div>
        </div>
      </div>
    </div>

    <!-- Gallery / Photo Strip (Enhanced with Curriculum Cover Image) -->
    <div class="relative bg-slate-950 overflow-hidden select-none">
      <div class="max-w-6xl mx-auto relative group">
        <!-- 4-Image Flex Strip on Desktop / Single Slider on Mobile -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-1.5 sm:gap-2 p-1.5 sm:p-2">
          <div
            v-for="(photo, idx) in photos"
            :key="idx"
            class="relative aspect-[4/3] overflow-hidden bg-slate-900 rounded-xl group/item cursor-pointer"
            :class="idx === activePhotoIndex ? 'ring-2 ring-emerald-500' : 'opacity-90 hover:opacity-100'"
            @click="openLightbox(Number(idx))"
          >
            <img
              :src="photo.url"
              :alt="photo.title"
              class="w-full h-full object-cover transition-transform duration-500 group-hover/item:scale-105"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-60 group-hover/item:opacity-80 transition-opacity" />
            <div class="absolute bottom-2 left-2 right-2 flex items-center justify-between">
              <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold bg-black/60 text-white tracking-wider backdrop-blur-xs">
                {{ photo.badge }}
              </span>
              <span class="text-[10px] text-white/75 opacity-0 group-hover/item:opacity-100 transition-opacity flex items-center gap-0.5">
                <v-icon icon="mdi-magnify-plus" size="12" />
                <span>ขยาย</span>
              </span>
            </div>
          </div>
        </div>

        <!-- Fullscreen Button -->
        <button
          type="button"
          aria-label="ดูภาพขนาดเต็ม"
          class="absolute right-14 top-3 w-8 h-8 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center backdrop-blur-xs transition-all shadow-md cursor-pointer"
          @click="openLightbox(activePhotoIndex)"
        >
          <v-icon icon="mdi-fullscreen" size="18" />
        </button>

        <!-- Arrow Controls -->
        <button
          type="button"
          aria-label="รูปก่อนหน้า"
          class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center backdrop-blur-xs transition-all shadow-lg cursor-pointer"
          @click="prevPhoto"
        >
          <v-icon icon="mdi-chevron-left" size="22" />
        </button>
        <button
          type="button"
          aria-label="รูปถัดไป"
          class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center backdrop-blur-xs transition-all shadow-lg cursor-pointer"
          @click="nextPhoto"
        >
          <v-icon icon="mdi-chevron-right" size="22" />
        </button>
      </div>

      <!-- Fullscreen Lightbox Modal -->
      <transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="isFullscreen"
          class="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-xs flex flex-col justify-between p-3 sm:p-6"
          @click.self="isFullscreen = false"
        >
          <div class="w-full max-w-5xl mx-auto flex items-center justify-between py-2 text-white z-20">
            <div class="flex items-center gap-2.5">
              <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur-md border border-white/30 tracking-wider">
                {{ activePhotoIndex + 1 }} / {{ photos.length }}
              </span>
              <span class="text-xs sm:text-sm font-medium text-white/90 truncate max-w-xs sm:max-w-md hidden sm:inline-block">
                {{ photos[activePhotoIndex]?.title }}
              </span>
            </div>

            <button
              type="button"
              class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/35 text-white flex items-center justify-center backdrop-blur-md transition-all cursor-pointer border border-white/30 shadow-lg"
              @click="isFullscreen = false"
            >
              <v-icon icon="mdi-close" size="22" />
            </button>
          </div>

          <div
            class="relative w-full max-w-5xl mx-auto flex-1 flex items-center justify-center px-2 sm:px-14 my-2 select-none"
            @click.self="isFullscreen = false"
          >
            <button
              type="button"
              class="absolute left-1 sm:left-3 z-30 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center backdrop-blur-md transition-all shadow-xl hover:scale-105 cursor-pointer border border-white/20"
              @click.stop="prevPhoto"
            >
              <v-icon icon="mdi-chevron-left" size="28" />
            </button>

            <div class="relative max-h-[72vh] flex flex-col items-center justify-center">
              <img
                :key="activePhotoIndex"
                :src="photos[activePhotoIndex]?.url"
                :alt="photos[activePhotoIndex]?.title"
                class="max-w-full max-h-[72vh] object-contain rounded-2xl shadow-2xl ring-1 ring-white/20"
              />
              <p class="text-white text-xs sm:text-sm mt-3 font-medium text-center bg-black/60 px-3 py-1 rounded-full backdrop-blur-xs sm:hidden">
                {{ photos[activePhotoIndex]?.title }}
              </p>
            </div>

            <button
              type="button"
              class="absolute right-1 sm:right-3 z-30 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center backdrop-blur-md transition-all shadow-xl hover:scale-105 cursor-pointer border border-white/20"
              @click.stop="nextPhoto"
            >
              <v-icon icon="mdi-chevron-right" size="28" />
            </button>
          </div>

          <div class="w-full max-w-xl mx-auto flex flex-col items-center gap-2 py-2 z-20">
            <div class="flex items-center gap-2">
              <button
                v-for="(photo, idx) in photos"
                :key="idx"
                type="button"
                class="w-12 h-9 sm:w-16 sm:h-11 rounded-lg overflow-hidden border-2 transition-all duration-200 cursor-pointer"
                :class="idx === activePhotoIndex ? 'border-emerald-400 scale-105 shadow-lg ring-2 ring-emerald-400/40' : 'border-white/30 opacity-60 hover:opacity-100'"
                @click="activePhotoIndex = Number(idx)"
              >
                <img :src="photo.url" :alt="photo.title" class="w-full h-full object-cover" />
              </button>
            </div>
          </div>
        </div>
      </transition>
    </div>

    <!-- Main Container -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
      <!-- Navigation Tabs Container -->
      <div class="bg-emerald-50/70 p-1.5 rounded-2xl flex items-center gap-1 overflow-x-auto scrollbar-none border border-emerald-100/80">
        <button
          v-for="t in tabs"
          :key="t.id"
          type="button"
          class="flex-1 py-2.5 px-3 sm:px-4 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200 cursor-pointer text-center"
          :class="
            activeTab === t.id
              ? 'bg-emerald-700 text-white shadow-md shadow-emerald-700/20'
              : 'text-emerald-900/80 hover:bg-emerald-100/70 hover:text-emerald-950'
          "
          @click="activeTab = t.id"
        >
          {{ t.label }}
        </button>
      </div>

      <!-- Main Two-Column Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- LEFT COLUMN: Main Details -->
        <div class="lg:col-span-8 space-y-8">
          <!-- TAB 1: รายละเอียดหลักสูตร -->
          <div v-if="activeTab === 'details'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-8">
            <!-- 1. ชื่อหลักสูตร -->
            <section class="space-y-4">
              <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                ชื่อหลักสูตร
              </h2>
              <div class="border-t border-slate-100 divide-y divide-slate-100 text-xs sm:text-sm">
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ชื่อภาษาไทย</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">
                    {{ activePreset.nameTh }}
                  </span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ชื่อภาษาอังกฤษ</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">
                    {{ activePreset.nameEn }}
                  </span>
                </div>
              </div>
            </section>

            <!-- 2. ชื่อปริญญาและสาขาวิชา -->
            <section class="space-y-4">
              <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                ชื่อปริญญาและสาขาวิชา
              </h2>
              <div class="border-t border-slate-100 divide-y divide-slate-100 text-xs sm:text-sm">
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ชื่อเต็ม (ภาษาไทย)</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">
                    {{ curriculumData?.degree_title || activePreset.degreeFullTh }}
                  </span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ชื่อเต็ม (ภาษาอังกฤษ)</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">
                    {{ curriculumData?.degree_title_en || activePreset.degreeFullEn }}
                  </span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 py-3 gap-1 sm:gap-4">
                  <span class="text-slate-500 font-medium">ชื่อย่อ (ภาษาไทย)</span>
                  <span class="sm:col-span-2 font-medium text-slate-900">
                    {{ activePreset.degreeShortTh }}
                  </span>
                </div>
              </div>
            </section>

            <!-- 3. จุดเด่นและวัตถุประสงค์ -->
            <section class="space-y-3">
              <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                จุดเด่นของหลักสูตร
              </h2>
              <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                {{ curriculumData?.desc || 'หลักสูตรมุ่งเน้นการผลิตบัณฑิตที่มีความเป็นเลิศทั้งด้านวิชาการ การปฏิบัติการจริง และคุณธรรมจริยธรรม ตอบสนองต่อการพัฒนาสังคมและการศึกษาแห่งอนาคต' }}
              </p>
            </section>

            <!-- 4. ผลการเรียนรู้ที่คาดหวัง (ELO) -->
            <section class="space-y-4">
              <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                ผลการเรียนรู้ที่คาดหวังของหลักสูตร (ELO)
              </h2>
              <div class="space-y-3">
                <div
                  v-for="elo in resolvedLearningOutcomes"
                  :key="elo.year"
                  class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1"
                >
                  <div class="text-xs font-bold text-emerald-800">
                    {{ elo.year }}
                  </div>
                  <p class="text-xs text-slate-600 leading-relaxed">
                    {{ elo.description }}
                  </p>
                </div>
              </div>
            </section>

            <!-- 5. อาชีพที่สามารถประกอบได้ -->
            <section class="space-y-4">
              <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                อาชีพที่สามารถประกอบได้หลังสำเร็จการศึกษา
              </h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div
                  v-for="(job, idx) in resolvedCareers"
                  :key="idx"
                  class="flex items-center gap-2.5 p-3 rounded-xl bg-emerald-50/50 border border-emerald-100 text-xs font-medium text-slate-800"
                >
                  <v-icon icon="mdi-check-circle" size="16" class="text-emerald-600 shrink-0" />
                  <span>{{ job }}</span>
                </div>
              </div>
            </section>
          </div>

          <!-- TAB 2: ค่าธรรมเนียมการศึกษา -->
          <div v-else-if="activeTab === 'fees'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-6">
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
              ค่าธรรมเนียมการศึกษา
            </h2>
            <div class="border border-slate-200 rounded-xl overflow-hidden">
              <table class="w-full text-xs sm:text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold">
                  <tr>
                    <th class="p-3.5">ภาคการศึกษา</th>
                    <th class="p-3.5">อัตราค่าธรรมเนียม</th>
                    <th class="p-3.5 hidden sm:table-cell">หมายเหตุ</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="(fee, idx) in resolvedTuitionFees" :key="idx" :class="idx === resolvedTuitionFees.length - 1 ? 'bg-emerald-50/50 font-bold text-emerald-900' : ''">
                    <td class="p-3.5">{{ fee.term }}</td>
                    <td class="p-3.5 font-semibold">{{ fee.amount }}</td>
                    <td class="p-3.5 text-slate-500 hidden sm:table-cell text-xs">{{ fee.note }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p class="text-[11px] text-slate-400">
              * อัตราค่าธรรมเนียมอาจมีการเปลี่ยนแปลงตามประกาศของมหาวิทยาลัยราชภัฏราชนครินทร์
            </p>
          </div>

          <!-- TAB 3: โครงสร้างหลักสูตร -->
          <div v-else-if="activeTab === 'structure'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-6">
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
              โครงสร้างหลักสูตร
            </h2>
            <div class="space-y-3">
              <div
                v-for="st in resolvedStructures"
                :key="st.category"
                class="flex items-center justify-between p-4 rounded-xl border text-xs sm:text-sm"
                :class="st.highlight ? 'bg-emerald-700 text-white font-bold border-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-800 font-medium'"
              >
                <span>{{ st.category }}</span>
                <span class="font-bold">{{ st.credits }}</span>
              </div>
            </div>
          </div>

          <!-- TAB 4: แผนการเรียน -->
          <div v-else-if="activeTab === 'plan'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-6">
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
              แผนการศึกษาตลอดหลักสูตร
            </h2>
            <div class="space-y-6">
              <div
                v-for="yr in studyPlan"
                :key="yr.year"
                class="border border-slate-200 rounded-xl p-5 space-y-4"
              >
                <h3 class="text-sm font-bold text-emerald-800 flex items-center gap-2">
                  <v-icon icon="mdi-calendar-check" size="18" />
                  <span>{{ yr.year }}</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div
                    v-for="sem in yr.semesters"
                    :key="sem.name"
                    class="p-4 rounded-lg bg-slate-50 border border-slate-200/70 space-y-2"
                  >
                    <div class="text-xs font-bold text-slate-800">{{ sem.name }}</div>
                    <ul class="text-xs text-slate-600 space-y-1 list-disc list-inside">
                      <li v-for="(c, cIdx) in sem.courses" :key="cIdx">{{ c }}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 5: อาจารย์ประจำหลักสูตร (Dynamic from Database!) -->
          <div v-else-if="activeTab === 'faculty'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-6">
            <div class="flex items-center justify-between">
              <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                คณาจารย์ประจำหลักสูตร
              </h2>
              <span class="text-xs text-slate-400">
                {{ facultyMembers.length }} ท่าน
              </span>
            </div>

            <!-- Empty Faculty -->
            <div v-if="facultyMembers.length === 0" class="py-8 text-center text-slate-400">
              <v-icon icon="mdi-account-group-outline" size="32" class="mb-2" />
              <p class="text-xs">กำลังปรับปรุงรายชื่อคณาจารย์ประจำหลักสูตร</p>
            </div>

            <!-- Faculty Cards Grid -->
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div
                v-for="person in facultyMembers"
                :key="person.id"
                class="p-4 rounded-xl border border-slate-200/80 hover:border-emerald-200 bg-white flex items-center gap-3.5 transition-all shadow-2xs hover:shadow-sm"
              >
                <div class="w-14 h-16 rounded-xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200">
                  <img
                    v-if="person.avatar"
                    :src="person.avatar"
                    :alt="person.name"
                    class="w-full h-full object-cover object-top"
                    @error="($event.target as HTMLImageElement).src = 'https://placehold.co/100x120/f1f5f9/94a3b8?text=EDU'"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center text-slate-300">
                    <v-icon icon="mdi-account" size="24" />
                  </div>
                </div>
                <div class="min-w-0 flex-1">
                  <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                    {{ person.academic_title ? person.academic_title + ' ' : '' }}{{ person.name }}
                  </h4>
                  <p class="text-[11px] text-emerald-700 font-semibold mt-0.5 truncate">
                    {{ person.role_title }}
                  </p>
                  <p v-if="person.degrees" class="text-[10px] text-slate-400 truncate mt-0.5">
                    {{ person.degrees }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN: Sidebar Widgets -->
        <div class="lg:col-span-4 space-y-6">
          <!-- Widget 1: เอกสารหลักสูตร (Curriculum Booklet & Actions) -->
          <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs space-y-5">
            <h3 class="text-base font-bold text-slate-900">
              เอกสารหลักสูตร
            </h3>

            <!-- Booklet Cover Preview (Dynamic Title Matching Major) -->
            <div class="relative mx-auto w-48 sm:w-52 aspect-[3/4] bg-white border border-slate-200 shadow-md rounded-lg p-5 flex flex-col items-center justify-between text-center select-none">
              <!-- Top Emblem -->
              <div class="w-12 h-12 rounded-full border border-slate-300 p-1 flex items-center justify-center bg-slate-50">
                <img :src="eduLogo" alt="ตราสัญลักษณ์" class="w-8 h-8 object-contain" />
              </div>

              <!-- Title on cover -->
              <div class="space-y-1.5 my-auto">
                <span class="text-[10px] text-slate-500 uppercase tracking-widest block font-bold">มคอ. 2</span>
                <p class="text-xs font-bold text-slate-800 leading-snug">
                  {{ curriculumData?.degree_title || activePreset.degreeFullTh }}
                </p>
                <p class="text-[11px] text-slate-600">
                  {{ curriculumData?.title || activePreset.nameTh }}
                </p>
                <p class="text-[9px] text-slate-400">หลักสูตรปรับปรุง พ.ศ. 2568</p>
              </div>

              <!-- Bottom affiliation -->
              <div class="text-[8px] text-slate-500 leading-tight border-t border-slate-100 pt-2 w-full">
                <p class="font-medium">คณะครุศาสตร์</p>
                <p>มหาวิทยาลัยราชภัฏราชนครินทร์</p>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2.5 pt-2">
              <a
                v-if="curriculumData?.document_url"
                :href="curriculumData.document_url"
                target="_blank"
                class="w-full py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm shadow-sm transition-colors flex items-center justify-center gap-2 no-underline cursor-pointer"
              >
                <v-icon icon="mdi-download" size="18" />
                <span>ดาวน์โหลดเล่มหลักสูตร (PDF)</span>
              </a>
              <button
                v-else
                type="button"
                class="w-full py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm shadow-sm transition-colors flex items-center justify-center gap-2 cursor-pointer"
              >
                <v-icon icon="mdi-download" size="18" />
                <span>ดาวน์โหลดโบรชัวร์</span>
              </button>

              <a
                href="https://admission.rru.ac.th"
                target="_blank"
                class="w-full py-2.5 px-4 rounded-xl bg-white hover:bg-emerald-50 text-emerald-700 font-bold text-sm border-2 border-emerald-700 transition-colors flex items-center justify-center gap-2 no-underline cursor-pointer"
              >
                <v-icon icon="mdi-account-plus-outline" size="18" />
                <span>สมัครเรียนออนไลน์</span>
              </a>
            </div>
          </div>

          <!-- Widget 2: Social / Contact Box -->
          <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900">
              ติดต่อสอบถาม
            </h3>

            <!-- Social Card -->
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70 space-y-3">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                  <v-icon icon="mdi-school" size="20" />
                </div>
                <div class="min-w-0">
                  <h4 class="text-xs font-bold text-slate-900 truncate">
                    {{ curriculumData?.title || activePreset.nameTh }}
                  </h4>
                  <p class="text-[11px] text-slate-500">คณะครุศาสตร์ มรภ.ราชนครินทร์</p>
                </div>
              </div>
            </div>

            <!-- Contact Information List -->
            <div class="space-y-2 text-xs text-slate-700 pt-1">
              <a
                href="https://edu.rru.ac.th"
                target="_blank"
                class="flex items-center gap-2 text-slate-700 hover:text-emerald-700 transition-colors no-underline"
              >
                <v-icon icon="mdi-link-variant" size="15" class="text-slate-400 shrink-0" />
                <span class="truncate">edu.rru.ac.th</span>
              </a>

              <a
                href="tel:038511170"
                class="flex items-center gap-2 text-slate-700 hover:text-emerald-700 transition-colors no-underline"
              >
                <v-icon icon="mdi-phone" size="15" class="text-slate-400 shrink-0" />
                <span>038-511170, 09-2265-8433</span>
              </a>

              <a
                href="mailto:educ@rru.ac.th"
                class="flex items-center gap-2 text-slate-700 hover:text-emerald-700 transition-colors no-underline"
              >
                <v-icon icon="mdi-email-outline" size="15" class="text-slate-400 shrink-0" />
                <span class="truncate">educ@rru.ac.th</span>
              </a>
            </div>

            <!-- Facebook Page Direct Button -->
            <a
              href="https://facebook.com"
              target="_blank"
              rel="noopener noreferrer"
              class="w-full py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs text-center transition-colors flex items-center justify-center gap-2 no-underline shadow-xs"
            >
              <v-icon icon="mdi-facebook" size="16" />
              <span>Facebook คณะครุศาสตร์</span>
            </a>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
