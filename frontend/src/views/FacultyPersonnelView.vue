<script setup lang="ts">
import { ref, computed } from 'vue'
import PageHeroBanner from '@/components/PageHeroBanner.vue'

interface Instructor {
  id: string
  name: string
  role: 'chair' | 'member'
  roleTitle: string
  degrees: string
  email: string
  avatar: string
  expertise?: string[]
  office?: string
}

interface Department {
  id: string
  name: string
  degreeTitle: string
  icon: string
  chair: Instructor
  instructors: Instructor[]
}

const selectedDepartment = ref('all')
const searchQuery = ref('')

const departments: Department[] = [
  // 1. สาขาวิชาวิทยาการข้อมูล
  {
    id: 'datascience',
    name: 'สาขาวิชาวิทยาการข้อมูล',
    degreeTitle: 'วิทยาศาสตรบัณฑิต (วท.บ.)',
    icon: 'mdi-database-search',
    chair: {
      id: 'ds-1',
      name: 'ผู้ช่วยศาสตราจารย์ ดร. สมชาย ใจดี',
      role: 'chair',
      roleTitle: 'ประธานสาขาวิชา',
      degrees: 'ปร.ด. (วิทยาการคอมพิวเตอร์), วท.ม. (เทคโนโลยีสารสนเทศ), วท.บ. (วิทยาการคอมพิวเตอร์)',
      email: 'somchai.jai@rru.ac.th',
      avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
      expertise: ['Data Science', 'Machine Learning', 'Big Data Analytics'],
      office: 'อาคาร 2 ชั้น 3 ห้อง 2301',
    },
    instructors: [
      {
        id: 'ds-2',
        name: 'อาจารย์ ดร. พิมพร สุขเกษม',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'Ph.D. (Data Science & AI), วท.บ. (วิทยาการคอมพิวเตอร์)',
        email: 'pimporn.suk@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
        expertise: ['Artificial Intelligence', 'Natural Language Processing'],
        office: 'อาคาร 2 ชั้น 3 ห้อง 2302',
      },
      {
        id: 'ds-3',
        name: 'อาจารย์ ณัฐพล ทองคำ',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'วท.ม. (วิทยาการข้อมูล), วศ.บ. (วิศวกรรมคอมพิวเตอร์)',
        email: 'nattapon.t@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
        expertise: ['Data Engineering', 'Cloud Computing', 'Data Warehouse'],
        office: 'อาคาร 2 ชั้น 3 ห้อง 2303',
      },
      {
        id: 'ds-4',
        name: 'ผู้ช่วยศาสตราจารย์ ดร. อารียา รัตนโชติ',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ปร.ด. (สถิติประยุกต์), วท.ม. (คณิตศาสตร์ประยุกต์)',
        email: 'areeya.rat@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
        expertise: ['Statistical Modeling', 'Predictive Analytics'],
        office: 'อาคาร 2 ชั้น 3 ห้อง 2304',
      },
      {
        id: 'ds-5',
        name: 'อาจารย์ ธีรภัทร ชาญวิทย์',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'วท.ม. (ระบบสารสนเทศเพื่อการจัดการ), วท.บ. (สถิติประยุกต์)',
        email: 'theerapat.c@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
        expertise: ['Business Intelligence', 'Data Visualization'],
        office: 'อาคาร 2 ชั้น 3 ห้อง 2305',
      },
    ],
  },

  // 2. สาขาวิชาการศึกษาปฐมวัย
  {
    id: 'early-childhood',
    name: 'สาขาวิชาการศึกษาปฐมวัย',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    icon: 'mdi-baby-face-outline',
    chair: {
      id: 'ec-1',
      name: 'ผู้ช่วยศาสตราจารย์ ดร. กรรณิการ์ ศรีสวัสดิ์',
      role: 'chair',
      roleTitle: 'ประธานสาขาวิชา',
      degrees: 'ค.ด. (การศึกษาปฐมวัย), ค.ม. (การศึกษาปฐมวัย), ค.บ. (การศึกษาปฐมวัย)',
      email: 'kannikar.sri@rru.ac.th',
      avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
      expertise: ['หลักสูตรปฐมวัย', 'การพัฒนาการเด็กปฐมวัย', 'นวัตกรรมการสอนแบบบูรณาการ'],
      office: 'อาคาร 3 ชั้น 2 ห้อง 3201',
    },
    instructors: [
      {
        id: 'ec-2',
        name: 'อาจารย์ ดร. มณฑา สุขสบาย',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ศษ.ด. (หลักสูตรและการสอน), ค.ม. (การศึกษาปฐมวัย)',
        email: 'montha.suk@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=400&q=80',
        expertise: ['จิตวิทยาเด็กปฐมวัย', 'การจัดกิจกรรมเสริมประสบการณ์'],
        office: 'อาคาร 3 ชั้น 2 ห้อง 3202',
      },
      {
        id: 'ec-3',
        name: 'อาจารย์ ศิริลักษณ์ ทองอินทร์',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ค.ม. (การศึกษาปฐมวัย), ค.บ. (การศึกษาปฐมวัย เกียรตินิยม)',
        email: 'sirilak.t@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?auto=format&fit=crop&w=400&q=80',
        expertise: ['สื่อนวัตกรรมสำหรับเด็กปฐมวัย', 'วรรณกรรมสำหรับเด็ก'],
        office: 'อาคาร 3 ชั้น 2 ห้อง 3203',
      },
      {
        id: 'ec-4',
        name: 'ผู้ช่วยศาสตราจารย์ นงนุช วงศ์สว่าง',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'กศ.ม. (การศึกษาปฐมวัย), กศ.บ. (การศึกษาปฐมวัย)',
        email: 'nongnuch.w@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1594744803329-e58b31de8bf5?auto=format&fit=crop&w=400&q=80',
        expertise: ['การประเมินพัฒนาการเด็ก', 'การจัดการศึกษาปฐมวัยตามแนวคิด High Scope'],
        office: 'อาคาร 3 ชั้น 2 ห้อง 3204',
      },
    ],
  },

  // 3. สาขาวิชาการประถมศึกษา
  {
    id: 'elementary',
    name: 'สาขาวิชาการประถมศึกษา',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    icon: 'mdi-human-male-child',
    chair: {
      id: 'el-1',
      name: 'ผู้ช่วยศาสตราจารย์ ดร. วีระศักดิ์ ศรีวิชัย',
      role: 'chair',
      roleTitle: 'ประธานสาขาวิชา',
      degrees: 'ค.ด. (หลักสูตรและการสอน), กศ.ม. (การประถมศึกษา), กศ.บ. (การประถมศึกษา)',
      email: 'weerasak.sri@rru.ac.th',
      avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
      expertise: ['การสอนแบบบูรณาการ', 'การจัดการเรียนรู้ระดับประถมศึกษา', 'การวิจัยในชั้นเรียน'],
      office: 'อาคาร 3 ชั้น 3 ห้อง 3301',
    },
    instructors: [
      {
        id: 'el-2',
        name: 'อาจารย์ ดร. ภัสสร ชัยเจริญ',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ปร.ด. (การวิจัยและพัฒนาการศึกษา), ค.ม. (การประถมศึกษา)',
        email: 'passorn.c@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1548142813-c348350df52b?auto=format&fit=crop&w=400&q=80',
        expertise: ['การวัดและประเมินผลในชั้นเรียน', 'พฤติกรรมการเรียนรู้ของเด็กประถม'],
        office: 'อาคาร 3 ชั้น 3 ห้อง 3302',
      },
      {
        id: 'el-3',
        name: 'อาจารย์ ปิยนุช วิจิตรศิลป์',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ค.ม. (การประถมศึกษา), ค.บ. (การประถมศึกษา)',
        email: 'piyanuch.v@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
        expertise: ['การจัดสภาพแวดล้อมการเรียนรู้', 'นวัตกรรมการสอนภาษาไทยสำหรับประถม'],
        office: 'อาคาร 3 ชั้น 3 ห้อง 3303',
      },
      {
        id: 'el-4',
        name: 'ผู้ช่วยศาสตราจารย์ ชูเกียรติ มั่นคง',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ศษ.ม. (ประถมศึกษา), กศ.บ. (การประถมศึกษา)',
        email: 'chookiat.m@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
        expertise: ['การสอนคณิตศาสตร์และวิทยาศาสตร์ประถม', 'STEM Education'],
        office: 'อาคาร 3 ชั้น 3 ห้อง 3304',
      },
    ],
  },

  // 4. สาขาวิชาภาษาไทย
  {
    id: 'thai',
    name: 'สาขาวิชาภาษาไทย',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    icon: 'mdi-book-open-variant',
    chair: {
      id: 'th-1',
      name: 'รองศาสตราจารย์ ดร. ประภาส บุญส่ง',
      role: 'chair',
      roleTitle: 'ประธานสาขาวิชา',
      degrees: 'อ.ด. (ภาษาไทย), อ.ม. (ภาษาไทย), ค.บ. (ภาษาไทย เกียรตินิยมอันดับหนึ่ง)',
      email: 'praphas.b@rru.ac.th',
      avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
      expertise: ['ภาษาศาสตร์ภาษาไทย', 'วรรณคดีและวรรณกรรมไทย', 'การสอนภาษาไทยเพื่อการสื่อสาร'],
      office: 'อาคาร 1 ชั้น 2 ห้อง 1201',
    },
    instructors: [
      {
        id: 'th-2',
        name: 'ผู้ช่วยศาสตราจารย์ ดร. อัมพร พงษ์ศิริ',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ศศ.ด. (ภาษาไทย), ศศ.ม. (ภาษาไทยเพื่อการอาชีพ)',
        email: 'amporn.p@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80',
        expertise: ['วาทศาสตร์และการพูดในที่สาธารณะ', 'การจัดกิจกรรมการเรียนรู้วรรณคดี'],
        office: 'อาคาร 1 ชั้น 2 ห้อง 1202',
      },
      {
        id: 'th-3',
        name: 'อาจารย์ รุ่งทิพย์ สถิตย์ธรรม',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ค.ม. (การสอนภาษาไทย), ค.บ. (ภาษาไทย)',
        email: 'rungthip.s@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1573496799652-408c2ac9fe98?auto=format&fit=crop&w=400&q=80',
        expertise: ['การพัฒนาทักษะการอ่านและการเขียน', 'นวัตกรรมการสอนภาษาไทยเชิงรุก'],
        office: 'อาคาร 1 ชั้น 2 ห้อง 1203',
      },
      {
        id: 'th-4',
        name: 'อาจารย์ ปกรณ์เกียรติ สว่างเนตร',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ศศ.ม. (ภาษาไทยประยุกต์), ศศ.บ. (ภาษาและวรรณคดีไทย)',
        email: 'pakornkiat.s@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=400&q=80',
        expertise: ['ภาษาไทยในสื่อดิจิทัล', 'การประพันธ์ร้อยกรองไทย'],
        office: 'อาคาร 1 ชั้น 2 ห้อง 1204',
      },
    ],
  },

  // 5. สาขาวิชาภาษาอังกฤษ
  {
    id: 'english',
    name: 'สาขาวิชาภาษาอังกฤษ',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    icon: 'mdi-translate',
    chair: {
      id: 'en-1',
      name: 'ผู้ช่วยศาสตราจารย์ ดร. จินตนา เจริญผล',
      role: 'chair',
      roleTitle: 'ประธานสาขาวิชา',
      degrees: 'Ph.D. in ELT (University of Leeds, UK), M.A. in Applied Linguistics, B.Ed. in English (First Class Hons.)',
      email: 'jintana.c@rru.ac.th',
      avatar: 'https://images.unsplash.com/photo-1580894732444-8ecded7900cd?auto=format&fit=crop&w=400&q=80',
      expertise: ['English Language Teaching (ELT)', 'CEFR Assessment', 'Teacher Professional Development'],
      office: 'อาคาร 1 ชั้น 3 ห้อง 1301',
    },
    instructors: [
      {
        id: 'en-2',
        name: 'อาจารย์ ดร. เอกพล รัตนศิริ',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'Ph.D. in Linguistics, M.A. in English Studies',
        email: 'ekkapol.r@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=400&q=80',
        expertise: ['Phonetics & Phonology', 'Discourse Analysis', 'Second Language Acquisition'],
        office: 'อาคาร 1 ชั้น 3 ห้อง 1302',
      },
      {
        id: 'en-3',
        name: 'อาจารย์ วารุณี สมบูรณ์ทรัพย์',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'M.Ed. in English Curriculum, B.Ed. in English',
        email: 'warunee.s@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?auto=format&fit=crop&w=400&q=80',
        expertise: ['Content and Language Integrated Learning (CLIL)', 'English for Specific Purposes'],
        office: 'อาคาร 1 ชั้น 3 ห้อง 1303',
      },
      {
        id: 'en-4',
        name: 'อาจารย์ นภัสสร ธนกิจ',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'M.A. in Teaching English as an International Language',
        email: 'napassorn.t@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
        expertise: ['Technology-Enhanced Language Learning (TELL)', 'Intercultural Communication'],
        office: 'อาคาร 1 ชั้น 3 ห้อง 1304',
      },
    ],
  },

  // 6. สาขาวิชาคณิตศาสตร์
  {
    id: 'mathematics',
    name: 'สาขาวิชาคณิตศาสตร์',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    icon: 'mdi-calculator-variant-outline',
    chair: {
      id: 'math-1',
      name: 'ผู้ช่วยศาสตราจารย์ ดร. พิเชษฐ์ เกียรติสกุล',
      role: 'chair',
      roleTitle: 'ประธานสาขาวิชา',
      degrees: 'ปร.ด. (คณิตศาสตร์ศึกษา), วท.ม. (คณิตศาสตร์ประยุกต์), ค.บ. (คณิตศาสตร์)',
      email: 'pichet.k@rru.ac.th',
      avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
      expertise: ['การจัดการเรียนรู้คณิตศาสตร์เชิงรุก', 'การคิดเชิงคณิตศาสตร์ขั้นสูง', 'พีชคณิตเชิงเส้น'],
      office: 'อาคาร 2 ชั้น 2 ห้อง 2201',
    },
    instructors: [
      {
        id: 'math-2',
        name: 'อาจารย์ ดร. สุจิตรา เลิศวิลัย',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ปร.ด. (คณิตศาสตร์), วท.ม. (สถิติศาสตร์)',
        email: 'sujitra.l@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
        expertise: ['เรขาคณิตพลวัตด้วย GeoGebra', 'ทฤษฎีจำนวนและการพิสูจน์'],
        office: 'อาคาร 2 ชั้น 2 ห้อง 2202',
      },
      {
        id: 'math-3',
        name: 'อาจารย์ กฤษฎา สว่างภพ',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ค.ม. (คณิตศาสตร์ศึกษา), วท.บ. (คณิตศาสตร์)',
        email: 'kritsada.s@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
        expertise: ['การแก้ปัญหาคณิตศาสตร์แบบเปิด (Open-ended)', 'แคลคูลัสและการประยุกต์'],
        office: 'อาคาร 2 ชั้น 2 ห้อง 2203',
      },
      {
        id: 'math-4',
        name: 'อาจารย์ ขวัญตา บุญญาธิการ',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'วท.ม. (คณิตศาสตร์ประยุกต์และวิทยาการคำนวณ)',
        email: 'khwanta.b@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
        expertise: ['การวิเคราะห์เชิงตัวเลข', 'สะเต็มศึกษาในวิชาคณิตศาสตร์'],
        office: 'อาคาร 2 ชั้น 2 ห้อง 2204',
      },
    ],
  },

  // 7. สาขาวิชาวิทยาศาสตร์ทั่วไป
  {
    id: 'science',
    name: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    icon: 'mdi-flask-outline',
    chair: {
      id: 'sci-1',
      name: 'ผู้ช่วยศาสตราจารย์ ดร. ชนะพล เจริญสุข',
      role: 'chair',
      roleTitle: 'ประธานสาขาวิชา',
      degrees: 'ปร.ด. (วิทยาศาสตร์ศึกษา), วท.ม. (เคมีอินทรีย์), วท.บ. (เคมี)',
      email: 'chanapol.c@rru.ac.th',
      avatar: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
      expertise: ['การจัดการเรียนรู้วิทยาศาสตร์เชิงสืบเสาะ', 'สะเต็มศึกษา (STEM Education)', 'เคมีสิ่งแวดล้อม'],
      office: 'อาคาร 4 ชั้น 2 ห้อง 4201',
    },
    instructors: [
      {
        id: 'sci-2',
        name: 'อาจารย์ ดร. รัชดา สุรินทร์',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'Ph.D. in Physics Education, M.Sc. in Applied Physics',
        email: 'ratchada.s@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=400&q=80',
        expertise: ['การทดลองฟิสิกส์ด้วยเซนเซอร์ดิจิทัล', 'ดาราศาสตร์และการสำรวจอวกาศ'],
        office: 'อาคาร 4 ชั้น 2 ห้อง 4202',
      },
      {
        id: 'sci-3',
        name: 'อาจารย์ อภิชาติ ภักดี',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'วท.ม. (ชีววิทยา), ค.บ. (วิทยาศาสตร์ทั่วไป)',
        email: 'apichart.p@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
        expertise: ['นิเวศวิทยาและความหลากหลายทางชีวภาพ', 'การจัดการเรียนรู้กลางแจ้ง (Field Study)'],
        office: 'อาคาร 4 ชั้น 2 ห้อง 4203',
      },
      {
        id: 'sci-4',
        name: 'อาจารย์ ศุภมาส จิตติพล',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ค.ม. (การสอนวิทยาศาสตร์), วท.บ. (วิทยาศาสตร์สิ่งแวดล้อม)',
        email: 'supamas.j@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1594744803329-e58b31de8bf5?auto=format&fit=crop&w=400&q=80',
        expertise: ['นวัตกรรมและโครงงานวิทยาศาสตร์ในโรงเรียน', 'วิทยาศาสตร์เพื่อความยั่งยืน (SDGs)'],
        office: 'อาคาร 4 ชั้น 2 ห้อง 4204',
      },
    ],
  },

  // 8. สาขาวิชาสังคมศึกษา
  {
    id: 'social-studies',
    name: 'สาขาวิชาสังคมศึกษา',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    icon: 'mdi-earth',
    chair: {
      id: 'soc-1',
      name: 'ผู้ช่วยศาสตราจารย์ ดร. มงคล แสนสุภา',
      role: 'chair',
      roleTitle: 'ประธานสาขาวิชา',
      degrees: 'ปร.ด. (สังคมศึกษา), ศศ.ม. (ประวัติศาสตร์ไทย), ค.บ. (สังคมศึกษา เกียรตินิยม)',
      email: 'mongkol.s@rru.ac.th',
      avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
      expertise: ['ประวัติศาสตร์ท้องถิ่นภาคตะวันออก', 'การศึกษาสังคมศาสตร์เชิงวิพากษ์', 'ความเป็นพลเมืองโลก (GCED)'],
      office: 'อาคาร 1 ชั้น 4 ห้อง 1401',
    },
    instructors: [
      {
        id: 'soc-2',
        name: 'อาจารย์ ดร. ทิพวรรณ วงศ์วิจิตร',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ศศ.ด. (ภูมิศาสตร์), วท.ม. (สารสนเทศภูมิศาสตร์)',
        email: 'thippawan.w@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1580894732444-8ecded7900cd?auto=format&fit=crop&w=400&q=80',
        expertise: ['ระบบสารสนเทศภูมิศาสตร์ (GIS) ในการศึกษา', 'ภูมิศาสตร์สิ่งแวดล้อมและการตั้งถิ่นฐาน'],
        office: 'อาคาร 1 ชั้น 4 ห้อง 1402',
      },
      {
        id: 'soc-3',
        name: 'อาจารย์ อนุชา รุ่งโรจน์สกุล',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ศศ.ม. (เศรษฐศาสตร์การเมือง), ค.บ. (สังคมศึกษา)',
        email: 'anucha.r@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=400&q=80',
        expertise: ['เศรษฐศาสตร์พอเพียงในสถานศึกษา', 'รัฐศาสตร์และกฎหมายในชีวิตประจำวัน'],
        office: 'อาคาร 1 ชั้น 4 ห้อง 1403',
      },
      {
        id: 'soc-4',
        name: 'อาจารย์ พรพิมล ยิ่งยง',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ค.ม. (การสอนสังคมศึกษา), ศศ.บ. (ปรัชญาและศาสนา)',
        email: 'pornpimon.y@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1548142813-c348350df52b?auto=format&fit=crop&w=400&q=80',
        expertise: ['จริยธรรมและศาสนศึกษาเปรียบเทียบ', 'การจัดการเรียนรู้สังคมศึกษาเชิงรุก (Active Learning)'],
        office: 'อาคาร 1 ชั้น 4 ห้อง 1404',
      },
    ],
  },

  // 9. สาขาวิชาหลักสูตรและการสอน (ระดับบัณฑิตศึกษา)
  {
    id: 'curriculum-instruction',
    name: 'สาขาวิชาหลักสูตรและการสอน (ป.โท)',
    degreeTitle: 'ครุศาสตรมหาบัณฑิต (ค.ม.)',
    icon: 'mdi-book-education-outline',
    chair: {
      id: 'ci-1',
      name: 'รองศาสตราจารย์ ดร. วิมลพรรณ สุขเกษม',
      role: 'chair',
      roleTitle: 'ประธานสาขาวิชา',
      degrees: 'ค.ด. (หลักสูตรและการสอน - จุฬาลงกรณ์ฯ), ค.ม. (หลักสูตรและการสอน), ค.บ. (เกียรตินิยม)',
      email: 'wimonpan.s@rru.ac.th',
      avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
      expertise: ['การพัฒนาและประเมินหลักสูตร', 'การออกแบบนวัตกรรมการจัดการเรียนรู้ขั้นสูง', 'การวิจัยเชิงปฏิบัติการในชั้นเรียน'],
      office: 'อาคารบัณฑิตวิทยาลัย ชั้น 3 ห้อง 5301',
    },
    instructors: [
      {
        id: 'ci-2',
        name: 'รองศาสตราจารย์ ดร. บุญชู ปัญญาเลิศ',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'Ph.D. in Education (Curriculum & Instruction), กศ.ม. (การวัดผลการศึกษา)',
        email: 'boonchoo.p@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=400&q=80',
        expertise: ['การวัดและประเมินผลการศึกษาขั้นสูง', 'สถิติและการวิจัยทางการศึกษา'],
        office: 'อาคารบัณฑิตวิทยาลัย ชั้น 3 ห้อง 5302',
      },
      {
        id: 'ci-3',
        name: 'ผู้ช่วยศาสตราจารย์ ดร. สุภาภรณ์ ชื่นอารมณ์',
        role: 'member',
        roleTitle: 'อาจารย์ประจำสาขาวิชา',
        degrees: 'ค.ด. (วิธีวิทยาการวิจัยการศึกษา), ค.ม. (หลักสูตรและการสอน)',
        email: 'supaporn.c@rru.ac.th',
        avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80',
        expertise: ['การวิจัยเชิงผสมผสาน (Mixed Methods)', 'การพัฒนาโมเดลการเรียนรู้'],
        office: 'อาคารบัณฑิตวิทยาลัย ชั้น 3 ห้อง 5303',
      },
    ],
  },
]

// Total counts
const totalDepartments = computed(() => departments.length)
const totalInstructors = computed(() => {
  return departments.reduce((acc, dept) => acc + 1 + dept.instructors.length, 0)
})

// Filtered departments based on selection and search
const filteredDepartments = computed(() => {
  let list = departments

  // Department selection filter
  if (selectedDepartment.value !== 'all') {
    list = list.filter((dept) => dept.id === selectedDepartment.value)
  }

  // Search filter
  const query = searchQuery.value.trim().toLowerCase()
  if (!query) return list

  return list
    .map((dept) => {
      // Check if department name matches
      const deptMatches = dept.name.toLowerCase().includes(query) || dept.degreeTitle.toLowerCase().includes(query)

      // Filter chair
      const chairMatches =
        dept.chair.name.toLowerCase().includes(query) ||
        dept.chair.degrees.toLowerCase().includes(query) ||
        (dept.chair.expertise && dept.chair.expertise.some((e) => e.toLowerCase().includes(query)))

      // Filter instructors
      const matchedInstructors = dept.instructors.filter(
        (inst) =>
          inst.name.toLowerCase().includes(query) ||
          inst.degrees.toLowerCase().includes(query) ||
          (inst.expertise && inst.expertise.some((e) => e.toLowerCase().includes(query)))
      )

      if (deptMatches || chairMatches || matchedInstructors.length > 0) {
        return {
          ...dept,
          // If query matched instructors or chair, keep structure intact
          instructors: deptMatches ? dept.instructors : matchedInstructors,
        }
      }

      return null
    })
    .filter((dept): dept is Department => dept !== null)
})
</script>

<template>
  <div class="min-h-screen bg-slate-50/60 pb-20">
    <!-- Hero Banner (Follows rule.md Section 13) -->
    <PageHeroBanner
      badge="คณาจารย์และบุคลากร"
      badge-icon="mdi-account-group"
      title="คณาจารย์ประจำสาขาวิชา"
      title-highlight="คณะครุศาสตร์"
      subtitle="ทำเนียบคณาจารย์ แยกตามสาขาวิชา ประกอบด้วยประธานสาขาวิชาและอาจารย์ประจำสาขาวิชา มหาวิทยาลัยราชภัฏราชนครินทร์"
    />

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-8">
      <!-- Search & Department Filter Card -->
      <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 shadow-xs space-y-5">
        <!-- Top Row: Department Filter Buttons & Count Summary -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
          <div>
            <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">
              เลือกดูตามสาขาวิชา
            </h2>
            <p class="text-xs text-slate-400">
              คณะครุศาสตร์เปิดสอน {{ totalDepartments }} สาขาวิชา รวมคณาจารย์ {{ totalInstructors }} ท่าน
            </p>
          </div>

          <!-- Search Input -->
          <div class="relative w-full lg:w-80">
            <v-icon
              icon="mdi-magnify"
              size="18"
              class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"
            />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="ค้นหาชื่ออาจารย์ หรือสาขาวิชา..."
              class="w-full pl-10 pr-9 py-2 rounded-xl text-xs sm:text-sm bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600 transition-all min-h-[42px]"
            />
            <button
              v-if="searchQuery"
              type="button"
              aria-label="ล้างคำค้นหา"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer"
              @click="searchQuery = ''"
            >
              <v-icon icon="mdi-close-circle" size="16" />
            </button>
          </div>
        </div>

        <!-- Filter Pills Scroll / Wrap -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
          <button
            type="button"
            class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-150 cursor-pointer whitespace-nowrap min-h-[38px] border"
            :class="
              selectedDepartment === 'all'
                ? 'bg-emerald-700 text-white border-emerald-700 shadow-sm shadow-emerald-700/20'
                : 'bg-slate-50 text-slate-600 border-slate-200/80 hover:bg-slate-100 hover:text-slate-900'
            "
            @click="selectedDepartment = 'all'"
          >
            <span>ทุกสาขาวิชา ({{ totalDepartments }})</span>
          </button>

          <button
            v-for="dept in departments"
            :key="dept.id"
            type="button"
            class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-150 cursor-pointer whitespace-nowrap min-h-[38px] border flex items-center gap-1.5"
            :class="
              selectedDepartment === dept.id
                ? 'bg-emerald-700 text-white border-emerald-700 shadow-sm shadow-emerald-700/20'
                : 'bg-slate-50 text-slate-600 border-slate-200/80 hover:bg-slate-100 hover:text-slate-900'
            "
            @click="selectedDepartment = dept.id"
          >
            <v-icon :icon="dept.icon" size="15" />
            <span>{{ dept.name.replace('สาขาวิชา', '') }}</span>
          </button>
        </div>
      </div>

      <!-- Department Sections -->
      <div v-if="filteredDepartments.length > 0" class="space-y-12">
        <section
          v-for="dept in filteredDepartments"
          :key="dept.id"
          class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-8"
        >
          <!-- Department Header -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-3">
            <div class="flex items-start sm:items-center gap-3.5">
              <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100/90 text-emerald-700 flex items-center justify-center shrink-0 shadow-xs">
                <v-icon :icon="dept.icon" size="24" />
              </div>
              <div>
                <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight leading-tight">
                  {{ dept.name }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                  {{ dept.degreeTitle }}
                </p>
              </div>
            </div>

            <!-- Total count badge in department -->
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                <v-icon icon="mdi-account-group-outline" size="14" class="text-emerald-700" />
                <span>คณาจารย์ {{ 1 + dept.instructors.length }} ท่าน</span>
              </span>
            </div>
          </div>

          <!-- Section 1: ประธานสาขาวิชา (Featured Lead Card) -->
          <div class="space-y-3">
            <div class="flex items-center gap-2">
              <span class="w-1.5 h-4 rounded-full bg-emerald-600" />
              <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                ประธานสาขาวิชา
              </h3>
            </div>

            <div class="bg-gradient-to-br from-emerald-50/50 via-white to-slate-50/30 rounded-2xl p-5 sm:p-6 border border-emerald-200/70 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col md:flex-row items-center md:items-start gap-6">
              <!-- Avatar -->
              <div class="relative shrink-0">
                <div class="w-28 h-36 sm:w-32 sm:h-40 rounded-2xl overflow-hidden shadow-md ring-2 ring-emerald-600/30 bg-slate-100">
                  <img
                    :src="dept.chair.avatar"
                    :alt="dept.chair.name"
                    class="w-full h-full object-cover object-top hover:scale-105 transition-transform duration-300"
                  />
                </div>
                <div class="absolute -bottom-2 -right-2 bg-emerald-700 text-white rounded-full p-1.5 shadow-md">
                  <v-icon icon="mdi-star" size="14" />
                </div>
              </div>

              <!-- Chair Information -->
              <div class="flex-1 text-center md:text-left space-y-2.5">
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                  <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-700 text-white shadow-xs">
                    <v-icon icon="mdi-account-tie" size="13" />
                    <span>ประธานสาขาวิชา</span>
                  </span>
                  <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                    อาจารย์ประจำหลักสูตร
                  </span>
                </div>

                <div>
                  <h4 class="text-base sm:text-lg font-black text-slate-900 tracking-tight leading-snug">
                    {{ dept.chair.name }}
                  </h4>
                  <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed">
                    <span class="font-medium text-slate-700">คุณวุฒิ:</span> {{ dept.chair.degrees }}
                  </p>
                </div>

                <!-- Expertise Tags -->
                <div v-if="dept.chair.expertise" class="flex flex-wrap items-center justify-center md:justify-start gap-1.5 pt-1">
                  <span class="text-[11px] text-slate-400 font-medium">ความเชี่ยวชาญ:</span>
                  <span
                    v-for="(exp, eIdx) in dept.chair.expertise"
                    :key="eIdx"
                    class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-white text-slate-700 border border-slate-200/80 shadow-2xs"
                  >
                    {{ exp }}
                  </span>
                </div>

                <!-- Contact & Office -->
                <div class="pt-2 flex flex-wrap items-center justify-center md:justify-start gap-4 text-xs text-slate-500 border-t border-emerald-100/70">
                  <a
                    :href="'mailto:' + dept.chair.email"
                    class="inline-flex items-center gap-1.5 text-emerald-700 hover:text-emerald-900 font-medium no-underline hover:underline"
                  >
                    <v-icon icon="mdi-email-outline" size="15" />
                    <span>{{ dept.chair.email }}</span>
                  </a>
                  <span v-if="dept.chair.office" class="inline-flex items-center gap-1.5">
                    <v-icon icon="mdi-map-marker-outline" size="15" class="text-slate-400" />
                    <span>{{ dept.chair.office }}</span>
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Section 2: อาจารย์ประจำสาขาวิชา (Instructors Grid) -->
          <div class="space-y-4">
            <div class="flex items-center gap-2">
              <span class="w-1.5 h-4 rounded-full bg-slate-300" />
              <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                อาจารย์ประจำสาขาวิชา ({{ dept.instructors.length }} ท่าน)
              </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
              <div
                v-for="inst in dept.instructors"
                :key="inst.id"
                class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs hover:shadow-md hover:border-emerald-600/40 transition-all duration-200 flex flex-col justify-between group"
              >
                <div class="space-y-3.5">
                  <!-- Photo with subtle zoom -->
                  <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-slate-100 shadow-2xs">
                    <img
                      :src="inst.avatar"
                      :alt="inst.name"
                      class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300"
                    />
                    <div class="absolute bottom-2 left-2 right-2">
                      <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-black/60 text-white backdrop-blur-xs">
                        {{ inst.roleTitle }}
                      </span>
                    </div>
                  </div>

                  <!-- Details -->
                  <div class="space-y-1.5">
                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug">
                      {{ inst.name }}
                    </h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed line-clamp-2">
                      {{ inst.degrees }}
                    </p>
                  </div>

                  <!-- Expertise tags -->
                  <div v-if="inst.expertise" class="flex flex-wrap gap-1">
                    <span
                      v-for="(exp, eIdx) in inst.expertise.slice(0, 2)"
                      :key="eIdx"
                      class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-50 text-slate-600 border border-slate-200/60"
                    >
                      {{ exp }}
                    </span>
                  </div>
                </div>

                <!-- Footer Contact -->
                <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                  <a
                    :href="'mailto:' + inst.email"
                    class="inline-flex items-center gap-1 text-slate-600 hover:text-emerald-700 font-medium no-underline hover:underline truncate max-w-[170px]"
                    :title="inst.email"
                  >
                    <v-icon icon="mdi-email-outline" size="14" class="shrink-0" />
                    <span class="truncate">{{ inst.email }}</span>
                  </a>
                  <span v-if="inst.office" class="text-[10px] text-slate-400 shrink-0" :title="inst.office">
                    <v-icon icon="mdi-door" size="13" />
                  </span>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!-- Empty State -->
      <div v-else class="bg-white rounded-3xl p-12 text-center border border-slate-200/90 max-w-md mx-auto space-y-3">
        <v-icon icon="mdi-account-search-outline" size="56" class="text-slate-300" />
        <h3 class="text-base font-bold text-slate-800">ไม่พบคณาจารย์ที่ตรงกับคำค้นหา</h3>
        <p class="text-xs text-slate-500">
          ไม่พบข้อมูลสำหรับ "{{ searchQuery }}" กรุณาลองเปลี่ยนคำค้นหาหรือเลือกดูทุกสาขาวิชา
        </p>
        <button
          type="button"
          class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold cursor-pointer shadow-xs"
          @click="selectedDepartment = 'all'; searchQuery = ''"
        >
          <v-icon icon="mdi-refresh" size="15" />
          <span>แสดงคณาจารย์ทั้งหมด</span>
        </button>
      </div>
    </div>
  </div>
</template>
