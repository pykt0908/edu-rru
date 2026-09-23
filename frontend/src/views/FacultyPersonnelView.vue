<script setup lang="ts">
import { ref, computed } from 'vue'
import PageHeroBanner from '@/components/PageHeroBanner.vue'

// Import actual cropped portraits matching user screenshot
import ec1 from '@/assets/personnel/early-childhood-1.png'
import ec2 from '@/assets/personnel/early-childhood-2.png'
import ec3 from '@/assets/personnel/early-childhood-3.png'
import ec4 from '@/assets/personnel/early-childhood-4.png'

interface Person {
  id: string
  name: string
  roleTitle: string
  avatar: string
  degrees?: string
  email?: string
}

interface Department {
  id: string
  name: string
  degreeTitle?: string
  members: Person[]
}

const selectedDepartment = ref('all')
const searchQuery = ref('')

const departments: Department[] = [
  // 1. สาขาวิชาการศึกษาปฐมวัย (Exact match to reference screenshot)
  {
    id: 'early-childhood',
    name: 'สาขาวิชาการศึกษาปฐมวัย',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    members: [
      {
        id: 'ec-1',
        name: 'ผศ.ธิดารัตน์ อธิปัญจพงษ์',
        roleTitle: 'ประธานสาขาวิชาการศึกษาปฐมวัย',
        avatar: ec1,
        degrees: 'ค.ด. (การศึกษาปฐมวัย), ค.ม. (การศึกษาปฐมวัย), ค.บ. (การศึกษาปฐมวัย)',
        email: 'tidarat.a@rru.ac.th',
      },
      {
        id: 'ec-2',
        name: 'อาจารย์พจนีย์ เชื้อบัณฑิต',
        roleTitle: 'สาขาวิชาการศึกษาปฐมวัย',
        avatar: ec2,
        degrees: 'ค.ม. (การศึกษาปฐมวัย), ค.บ. (การศึกษาปฐมวัย)',
        email: 'potjanee.c@rru.ac.th',
      },
      {
        id: 'ec-3',
        name: 'อาจารย์สุรสา จันทนา',
        roleTitle: 'สาขาวิชาการศึกษาปฐมวัย',
        avatar: ec3,
        degrees: 'กศ.ม. (การศึกษาปฐมวัย), กศ.บ. (การศึกษาปฐมวัย)',
        email: 'surasa.j@rru.ac.th',
      },
      {
        id: 'ec-4',
        name: 'อาจารย์ปรีชา ปั้นเกิด',
        roleTitle: 'สาขาวิชาการศึกษาปฐมวัย',
        avatar: ec4,
        degrees: 'ศษ.ม. (การศึกษาปฐมวัย), ค.บ. (การศึกษาปฐมวัย)',
        email: 'preecha.p@rru.ac.th',
      },
    ],
  },

  // 2. สาขาวิชาวิทยาการข้อมูล
  {
    id: 'datascience',
    name: 'สาขาวิชาวิทยาการข้อมูล',
    degreeTitle: 'วิทยาศาสตรบัณฑิต (วท.บ.)',
    members: [
      {
        id: 'ds-1',
        name: 'ผศ.ดร.สมชาย ใจดี',
        roleTitle: 'ประธานสาขาวิชาวิทยาการข้อมูล',
        avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (วิทยาการคอมพิวเตอร์), วท.ม. (เทคโนโลยีสารสนเทศ)',
        email: 'somchai.jai@rru.ac.th',
      },
      {
        id: 'ds-2',
        name: 'อาจารย์ ดร.พิมพร สุขเกษม',
        roleTitle: 'สาขาวิชาวิทยาการข้อมูล',
        avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
        degrees: 'Ph.D. (Data Science & AI), วท.บ. (วิทยาการคอมพิวเตอร์)',
        email: 'pimporn.suk@rru.ac.th',
      },
      {
        id: 'ds-3',
        name: 'อาจารย์ณัฐพล ทองคำ',
        roleTitle: 'สาขาวิชาวิทยาการข้อมูล',
        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
        degrees: 'วท.ม. (วิทยาการข้อมูล), วศ.บ. (วิศวกรรมคอมพิวเตอร์)',
        email: 'nattapon.t@rru.ac.th',
      },
      {
        id: 'ds-4',
        name: 'ผศ.ดร.อารียา รัตนโชติ',
        roleTitle: 'สาขาวิชาวิทยาการข้อมูล',
        avatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (สถิติประยุกต์), วท.ม. (คณิตศาสตร์ประยุกต์)',
        email: 'areeya.rat@rru.ac.th',
      },
    ],
  },

  // 3. สาขาวิชาการประถมศึกษา
  {
    id: 'elementary',
    name: 'สาขาวิชาการประถมศึกษา',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    members: [
      {
        id: 'el-1',
        name: 'ผศ.ดร.วีระศักดิ์ ศรีวิชัย',
        roleTitle: 'ประธานสาขาวิชาการประถมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ด. (หลักสูตรและการสอน), กศ.ม. (การประถมศึกษา)',
        email: 'weerasak.sri@rru.ac.th',
      },
      {
        id: 'el-2',
        name: 'อาจารย์ ดร.ภัสสร ชัยเจริญ',
        roleTitle: 'สาขาวิชาการประถมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1548142813-c348350df52b?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (การวิจัยและพัฒนาการศึกษา), ค.ม. (การประถมศึกษา)',
        email: 'passorn.c@rru.ac.th',
      },
      {
        id: 'el-3',
        name: 'อาจารย์ปิยนุช วิจิตรศิลป์',
        roleTitle: 'สาขาวิชาการประถมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ม. (การประถมศึกษา), ค.บ. (การประถมศึกษา)',
        email: 'piyanuch.v@rru.ac.th',
      },
      {
        id: 'el-4',
        name: 'ผศ.ชูเกียรติ มั่นคง',
        roleTitle: 'สาขาวิชาการประถมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
        degrees: 'ศษ.ม. (ประถมศึกษา), กศ.บ. (การประถมศึกษา)',
        email: 'chookiat.m@rru.ac.th',
      },
    ],
  },

  // 4. สาขาวิชาภาษาไทย
  {
    id: 'thai',
    name: 'สาขาวิชาภาษาไทย',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    members: [
      {
        id: 'th-1',
        name: 'รศ.ดร.ประภาส บุญส่ง',
        roleTitle: 'ประธานสาขาวิชาภาษาไทย',
        avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
        degrees: 'อ.ด. (ภาษาไทย), อ.ม. (ภาษาไทย), ค.บ. (ภาษาไทย)',
        email: 'praphas.b@rru.ac.th',
      },
      {
        id: 'th-2',
        name: 'ผศ.ดร.อัมพร พงษ์ศิริ',
        roleTitle: 'สาขาวิชาภาษาไทย',
        avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80',
        degrees: 'ศศ.ด. (ภาษาไทย), ศศ.ม. (ภาษาไทยเพื่อการอาชีพ)',
        email: 'amporn.p@rru.ac.th',
      },
      {
        id: 'th-3',
        name: 'อาจารย์รุ่งทิพย์ สถิตย์ธรรม',
        roleTitle: 'สาขาวิชาภาษาไทย',
        avatar: 'https://images.unsplash.com/photo-1573496799652-408c2ac9fe98?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ม. (การสอนภาษาไทย), ค.บ. (ภาษาไทย)',
        email: 'rungthip.s@rru.ac.th',
      },
      {
        id: 'th-4',
        name: 'อาจารย์ปกรณ์เกียรติ สว่างเนตร',
        roleTitle: 'สาขาวิชาภาษาไทย',
        avatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=400&q=80',
        degrees: 'ศศ.ม. (ภาษาไทยประยุกต์), ศศ.บ. (ภาษาและวรรณคดีไทย)',
        email: 'pakornkiat.s@rru.ac.th',
      },
    ],
  },

  // 5. สาขาวิชาภาษาอังกฤษ
  {
    id: 'english',
    name: 'สาขาวิชาภาษาอังกฤษ',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    members: [
      {
        id: 'en-1',
        name: 'ผศ.ดร.จินตนา เจริญผล',
        roleTitle: 'ประธานสาขาวิชาภาษาอังกฤษ',
        avatar: 'https://images.unsplash.com/photo-1580894732444-8ecded7900cd?auto=format&fit=crop&w=400&q=80',
        degrees: 'Ph.D. in ELT, M.A. in Applied Linguistics',
        email: 'jintana.c@rru.ac.th',
      },
      {
        id: 'en-2',
        name: 'อาจารย์ ดร.เอกพล รัตนศิริ',
        roleTitle: 'สาขาวิชาภาษาอังกฤษ',
        avatar: 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=400&q=80',
        degrees: 'Ph.D. in Linguistics, M.A. in English Studies',
        email: 'ekkapol.r@rru.ac.th',
      },
      {
        id: 'en-3',
        name: 'อาจารย์วารุณี สมบูรณ์ทรัพย์',
        roleTitle: 'สาขาวิชาภาษาอังกฤษ',
        avatar: 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?auto=format&fit=crop&w=400&q=80',
        degrees: 'M.Ed. in English Curriculum, B.Ed. in English',
        email: 'warunee.s@rru.ac.th',
      },
      {
        id: 'en-4',
        name: 'อาจารย์นภัสสร ธนกิจ',
        roleTitle: 'สาขาวิชาภาษาอังกฤษ',
        avatar: 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=400&q=80',
        degrees: 'M.A. in Teaching English as an International Language',
        email: 'napassorn.t@rru.ac.th',
      },
    ],
  },

  // 6. สาขาวิชาคณิตศาสตร์
  {
    id: 'mathematics',
    name: 'สาขาวิชาคณิตศาสตร์',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    members: [
      {
        id: 'math-1',
        name: 'ผศ.ดร.พิเชษฐ์ เกียรติสกุล',
        roleTitle: 'ประธานสาขาวิชาคณิตศาสตร์',
        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (คณิตศาสตร์ศึกษา), วท.ม. (คณิตศาสตร์ประยุกต์)',
        email: 'pichet.k@rru.ac.th',
      },
      {
        id: 'math-2',
        name: 'อาจารย์ ดร.สุจิตรา เลิศวิลัย',
        roleTitle: 'สาขาวิชาคณิตศาสตร์',
        avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (คณิตศาสตร์), วท.ม. (สถิติศาสตร์)',
        email: 'sujitra.l@rru.ac.th',
      },
      {
        id: 'math-3',
        name: 'อาจารย์กฤษฎา สว่างภพ',
        roleTitle: 'สาขาวิชาคณิตศาสตร์',
        avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ม. (คณิตศาสตร์ศึกษา), วท.บ. (คณิตศาสตร์)',
        email: 'kritsada.s@rru.ac.th',
      },
      {
        id: 'math-4',
        name: 'อาจารย์ขวัญตา บุญญาธิการ',
        roleTitle: 'สาขาวิชาคณิตศาสตร์',
        avatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
        degrees: 'วท.ม. (คณิตศาสตร์ประยุกต์และวิทยาการคำนวณ)',
        email: 'khwanta.b@rru.ac.th',
      },
    ],
  },

  // 7. สาขาวิชาวิทยาศาสตร์ทั่วไป
  {
    id: 'science',
    name: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    members: [
      {
        id: 'sci-1',
        name: 'ผศ.ดร.ชนะพล เจริญสุข',
        roleTitle: 'ประธานสาขาวิชาวิทยาศาสตร์ทั่วไป',
        avatar: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (วิทยาศาสตร์ศึกษา), วท.ม. (เคมีอินทรีย์)',
        email: 'chanapol.c@rru.ac.th',
      },
      {
        id: 'sci-2',
        name: 'อาจารย์ ดร.รัชดา สุรินทร์',
        roleTitle: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
        avatar: 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=400&q=80',
        degrees: 'Ph.D. in Physics Education, M.Sc. in Applied Physics',
        email: 'ratchada.s@rru.ac.th',
      },
      {
        id: 'sci-3',
        name: 'อาจารย์อภิชาติ ภักดี',
        roleTitle: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
        degrees: 'วท.ม. (ชีววิทยา), ค.บ. (วิทยาศาสตร์ทั่วไป)',
        email: 'apichart.p@rru.ac.th',
      },
      {
        id: 'sci-4',
        name: 'อาจารย์ศุภมาส จิตติพล',
        roleTitle: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
        avatar: 'https://images.unsplash.com/photo-1594744803329-e58b31de8bf5?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ม. (การสอนวิทยาศาสตร์), วท.บ. (วิทยาศาสตร์สิ่งแวดล้อม)',
        email: 'supamas.j@rru.ac.th',
      },
    ],
  },

  // 8. สาขาวิชาสังคมศึกษา
  {
    id: 'social-studies',
    name: 'สาขาวิชาสังคมศึกษา',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    members: [
      {
        id: 'soc-1',
        name: 'ผศ.ดร.มงคล แสนสุภา',
        roleTitle: 'ประธานสาขาวิชาสังคมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (สังคมศึกษา), ศศ.ม. (ประวัติศาสตร์ไทย)',
        email: 'mongkol.s@rru.ac.th',
      },
      {
        id: 'soc-2',
        name: 'อาจารย์ ดร.ทิพวรรณ วงศ์วิจิตร',
        roleTitle: 'สาขาวิชาสังคมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1580894732444-8ecded7900cd?auto=format&fit=crop&w=400&q=80',
        degrees: 'ศศ.ด. (ภูมิศาสตร์), วท.ม. (สารสนเทศภูมิศาสตร์)',
        email: 'thippawan.w@rru.ac.th',
      },
      {
        id: 'soc-3',
        name: 'อาจารย์อนุชา รุ่งโรจน์สกุล',
        roleTitle: 'สาขาวิชาสังคมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=400&q=80',
        degrees: 'ศศ.ม. (เศรษฐศาสตร์การเมือง), ค.บ. (สังคมศึกษา)',
        email: 'anucha.r@rru.ac.th',
      },
      {
        id: 'soc-4',
        name: 'อาจารย์พรพิมล ยิ่งยง',
        roleTitle: 'สาขาวิชาสังคมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1548142813-c348350df52b?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ม. (การสอนสังคมศึกษา), ศศ.บ. (ปรัชญาและศาสนา)',
        email: 'pornpimon.y@rru.ac.th',
      },
    ],
  },

  // 9. สาขาวิชาหลักสูตรและการสอน (ระดับบัณฑิตศึกษา)
  {
    id: 'curriculum-instruction',
    name: 'สาขาวิชาหลักสูตรและการสอน (ป.โท)',
    degreeTitle: 'ครุศาสตรมหาบัณฑิต (ค.ม.)',
    members: [
      {
        id: 'ci-1',
        name: 'รศ.ดร.วิมลพรรณ สุขเกษม',
        roleTitle: 'ประธานสาขาวิชาหลักสูตรและการสอน',
        avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ด. (หลักสูตรและการสอน - จุฬาลงกรณ์ฯ), ค.ม. (หลักสูตรและการสอน)',
        email: 'wimonpan.s@rru.ac.th',
      },
      {
        id: 'ci-2',
        name: 'รศ.ดร.บุญชู ปัญญาเลิศ',
        roleTitle: 'สาขาวิชาหลักสูตรและการสอน',
        avatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=400&q=80',
        degrees: 'Ph.D. in Education (Curriculum & Instruction), กศ.ม. (การวัดผลการศึกษา)',
        email: 'boonchoo.p@rru.ac.th',
      },
      {
        id: 'ci-3',
        name: 'ผศ.ดร.สุภาภรณ์ ชื่นอารมณ์',
        roleTitle: 'สาขาวิชาหลักสูตรและการสอน',
        avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ด. (วิธีวิทยาการวิจัยการศึกษา), ค.ม. (หลักสูตรและการสอน)',
        email: 'supaporn.c@rru.ac.th',
      },
    ],
  },
]

const isDepartmentDropdownOpen = ref(false)

const currentDepartment = computed(() => {
  if (selectedDepartment.value === 'all') {
    return { id: 'all', name: 'ทุกสาขาวิชา (ทั้งหมด)', count: totalPersonnelCount.value }
  }
  const found = departments.find((d) => d.id === selectedDepartment.value)
  return found
    ? { id: found.id, name: found.name, count: found.members.length }
    : { id: 'all', name: 'ทุกสาขาวิชา (ทั้งหมด)', count: totalPersonnelCount.value }
})

const totalPersonnelCount = computed(() => {
  return departments.reduce((acc, d) => acc + d.members.length, 0)
})

const filteredPersonnelCount = computed(() => {
  return filteredDepartments.value.reduce((acc, d) => acc + d.members.length, 0)
})

const selectDepartment = (id: string) => {
  selectedDepartment.value = id
  isDepartmentDropdownOpen.value = false
}

const clearFilters = () => {
  selectedDepartment.value = 'all'
  searchQuery.value = ''
  isDepartmentDropdownOpen.value = false
}

// Filtered departments based on selection and search
const filteredDepartments = computed(() => {
  let list = departments

  // Department filter
  if (selectedDepartment.value !== 'all') {
    list = list.filter((dept) => dept.id === selectedDepartment.value)
  }

  // Search filter
  const query = searchQuery.value.trim().toLowerCase()
  if (!query) return list

  return list
    .map((dept) => {
      const deptMatches = dept.name.toLowerCase().includes(query)
      const matchedMembers = dept.members.filter(
        (m) =>
          m.name.toLowerCase().includes(query) ||
          m.roleTitle.toLowerCase().includes(query) ||
          (m.degrees && m.degrees.toLowerCase().includes(query))
      )

      if (deptMatches || matchedMembers.length > 0) {
        return {
          ...dept,
          members: deptMatches ? dept.members : matchedMembers,
        }
      }
      return null
    })
    .filter((dept): dept is Department => dept !== null)
})
</script>

<template>
  <div class="min-h-screen bg-white pb-24 relative">
    <!-- Click-away backdrop for dropdown -->
    <div
      v-if="isDepartmentDropdownOpen"
      class="fixed inset-0 z-20"
      @click="isDepartmentDropdownOpen = false"
    />

    <!-- Hero Banner (Follows rule.md Section 13) -->
    <PageHeroBanner
      badge="คณาจารย์และบุคลากร"
      badge-icon="mdi-account-group"
      title="คณาจารย์ประจำสาขาวิชา"
      title-highlight="คณะครุศาสตร์"
      subtitle="ทำเนียบคณาจารย์ แยกตามสาขาวิชา ประกอบด้วยประธานสาขาวิชาและอาจารย์ประจำสาขาวิชา มหาวิทยาลัยราชภัฏราชนครินทร์"
    />

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-10">
      <!-- Filter & Search Toolbar Card (Dropdown + Search) -->
      <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-xs relative z-30">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4 items-end">
          <!-- 1. Department Dropdown Selector (md:col-span-6 lg:col-span-5) -->
          <div class="relative md:col-span-6 lg:col-span-5">
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
              เลือกสาขาวิชา
            </label>
            <div class="relative">
              <button
                type="button"
                class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl border transition-all duration-150 cursor-pointer text-left min-h-[44px]"
                :class="
                  isDepartmentDropdownOpen
                    ? 'border-emerald-600 ring-2 ring-emerald-500/20 bg-emerald-50/20'
                    : 'border-slate-200 hover:border-slate-300 bg-slate-50/70 hover:bg-slate-50'
                "
                @click="isDepartmentDropdownOpen = !isDepartmentDropdownOpen"
              >
                <div class="flex items-center gap-2.5 min-w-0">
                  <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 bg-emerald-100 text-emerald-800 border border-emerald-200/60">
                    <v-icon icon="mdi-school" size="16" />
                  </div>
                  <div class="min-w-0">
                    <span class="block text-xs sm:text-sm font-bold text-slate-800 truncate">
                      {{ currentDepartment.name }}
                    </span>
                  </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                  <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-200 text-slate-700">
                    {{ currentDepartment.count }} ท่าน
                  </span>
                  <v-icon
                    icon="mdi-chevron-down"
                    size="18"
                    class="text-slate-400 transition-transform duration-200"
                    :class="isDepartmentDropdownOpen ? 'rotate-180 text-emerald-700' : ''"
                  />
                </div>
              </button>

              <!-- Department Dropdown Popover Menu -->
              <transition
                enter-active-class="transition ease-out duration-150"
                enter-from-class="opacity-0 translate-y-1"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-1"
              >
                <div
                  v-if="isDepartmentDropdownOpen"
                  class="absolute top-full left-0 right-0 mt-1.5 z-40 bg-white rounded-2xl shadow-xl shadow-slate-900/12 border border-slate-200/90 py-1.5 overflow-hidden max-h-96 overflow-y-auto"
                >
                  <!-- Option: All Departments -->
                  <button
                    type="button"
                    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 text-left text-xs sm:text-sm transition-colors cursor-pointer border-b border-slate-100"
                    :class="
                      selectedDepartment === 'all'
                        ? 'bg-emerald-50 text-emerald-900 font-bold'
                        : 'text-slate-700 hover:bg-slate-50'
                    "
                    @click="selectDepartment('all')"
                  >
                    <div class="flex items-center gap-2.5 min-w-0">
                      <div
                        class="w-6 h-6 rounded-md flex items-center justify-center shrink-0"
                        :class="selectedDepartment === 'all' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500'"
                      >
                        <v-icon icon="mdi-view-grid" size="14" />
                      </div>
                      <span class="truncate">ทุกสาขาวิชา (ทั้งหมด)</span>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                      <span class="text-[11px] px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                        {{ totalPersonnelCount }}
                      </span>
                      <v-icon
                        v-if="selectedDepartment === 'all'"
                        icon="mdi-check"
                        size="16"
                        class="text-emerald-700"
                      />
                    </div>
                  </button>

                  <!-- List of Departments -->
                  <div class="py-1">
                    <button
                      v-for="dept in departments"
                      :key="dept.id"
                      type="button"
                      class="w-full flex items-center justify-between gap-3 px-3.5 py-2 text-left text-xs sm:text-sm transition-colors cursor-pointer"
                      :class="
                        selectedDepartment === dept.id
                          ? 'bg-emerald-50 text-emerald-900 font-bold'
                          : 'text-slate-700 hover:bg-slate-50'
                      "
                      @click="selectDepartment(dept.id)"
                    >
                      <div class="flex items-center gap-2.5 min-w-0">
                        <div
                          class="w-6 h-6 rounded-md flex items-center justify-center shrink-0"
                          :class="selectedDepartment === dept.id ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500'"
                        >
                          <v-icon icon="mdi-school" size="14" />
                        </div>
                        <span class="truncate">{{ dept.name }}</span>
                      </div>
                      <div class="flex items-center gap-1.5 shrink-0">
                        <span class="text-[11px] px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                          {{ dept.members.length }}
                        </span>
                        <v-icon
                          v-if="selectedDepartment === dept.id"
                          icon="mdi-check"
                          size="16"
                          class="text-emerald-700"
                        />
                      </div>
                    </button>
                  </div>
                </div>
              </transition>
            </div>
          </div>

          <!-- 2. Search Input Box (md:col-span-6 lg:col-span-7) -->
          <div class="md:col-span-6 lg:col-span-7">
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
              ค้นหาคณาจารย์
            </label>
            <div class="relative">
              <v-icon
                icon="mdi-magnify"
                size="18"
                class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"
              />
              <input
                v-model="searchQuery"
                type="text"
                placeholder="ค้นหาชื่อ-นามสกุล, ตำแหน่ง, คุณวุฒิ หรือสาขาวิชา..."
                class="w-full pl-10 pr-9 py-2.5 rounded-xl text-xs sm:text-sm bg-slate-50/70 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 focus:bg-white transition-all min-h-[44px]"
              />
              <button
                v-if="searchQuery"
                type="button"
                aria-label="ล้างคำค้นหา"
                class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 cursor-pointer rounded-full"
                @click="searchQuery = ''"
              >
                <v-icon icon="mdi-close-circle" size="16" />
              </button>
            </div>
          </div>
        </div>

        <!-- Active Filter Summary & Quick Clear -->
        <div
          v-if="selectedDepartment !== 'all' || searchQuery"
          class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs"
        >
          <div class="flex flex-wrap items-center gap-1.5 text-slate-600">
            <span class="text-slate-400 font-medium">ผลการกรอง:</span>
            <!-- Selected Department Chip -->
            <span
              v-if="selectedDepartment !== 'all'"
              class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-medium"
            >
              <v-icon icon="mdi-school" size="12" />
              <span>{{ currentDepartment.name }}</span>
              <button
                type="button"
                class="hover:text-emerald-950 cursor-pointer ml-0.5"
                @click="selectedDepartment = 'all'"
              >
                <v-icon icon="mdi-close" size="12" />
              </button>
            </span>

            <!-- Search Query Chip -->
            <span
              v-if="searchQuery"
              class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800 border border-blue-200 font-medium"
            >
              <v-icon icon="mdi-magnify" size="12" />
              <span>"{{ searchQuery }}"</span>
              <button
                type="button"
                class="hover:text-blue-950 cursor-pointer ml-0.5"
                @click="searchQuery = ''"
              >
                <v-icon icon="mdi-close" size="12" />
              </button>
            </span>

            <span class="text-slate-500 ml-1">
              (พบ {{ filteredPersonnelCount }} ท่าน ใน {{ filteredDepartments.length }} สาขาวิชา)
            </span>
          </div>

          <button
            type="button"
            class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:underline cursor-pointer flex items-center gap-1"
            @click="clearFilters"
          >
            <v-icon icon="mdi-refresh" size="14" />
            <span>ล้างตัวกรองทั้งหมด</span>
          </button>
        </div>
      </div>

      <!-- Department Sections (Matching User's Reference UI) -->
      <div v-if="filteredDepartments.length > 0" class="space-y-16">
        <section
          v-for="dept in filteredDepartments"
          :key="dept.id"
          class="space-y-6"
        >
          <!-- Section Title with Graduation Cap & Green Divider (Exact match to reference) -->
          <div>
            <div class="flex items-center gap-2.5">
              <v-icon icon="mdi-school" size="26" class="text-emerald-700 shrink-0" />
              <h2 class="text-xl sm:text-2xl font-bold text-emerald-800 tracking-tight leading-tight">
                {{ dept.name }}
              </h2>
            </div>
            <!-- Green Divider: Dark thick bar on the left, faint line spanning across -->
            <div class="mt-2.5 flex items-center">
              <div class="h-1 w-28 bg-emerald-700 rounded-full shrink-0" />
              <div class="h-px flex-1 bg-emerald-100/90" />
            </div>
          </div>

          <!-- Cards Grid (Exact match to reference: 4 rounded cards, grey background, text below) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-7 items-start">
            <div
              v-for="person in dept.members"
              :key="person.id"
              class="flex flex-col group cursor-pointer"
            >
              <!-- Photo Container: Rounded Rectangle, Soft Grey Background -->
              <div class="relative w-full aspect-[3/4] rounded-2xl overflow-hidden bg-[#DDE2E6] shadow-xs group-hover:shadow-md transition-all duration-300">
                <img
                  :src="person.avatar"
                  :alt="person.name"
                  class="w-full h-full object-cover object-top group-hover:scale-103 transition-transform duration-300"
                />
              </div>

              <!-- Personnel Information: Centered below the container -->
              <div class="mt-3.5 text-center space-y-1 px-1">
                <h3 class="text-sm sm:text-base font-bold text-slate-800 leading-snug group-hover:text-emerald-800 transition-colors">
                  {{ person.name }}
                </h3>
                <p class="text-xs sm:text-[13px] text-slate-500 font-normal leading-normal">
                  {{ person.roleTitle }}
                </p>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 max-w-sm mx-auto space-y-3">
        <v-icon icon="mdi-account-search-outline" size="52" class="text-slate-300" />
        <h3 class="text-base font-bold text-slate-700">ไม่พบข้อมูลคณาจารย์</h3>
        <p class="text-xs text-slate-400">
          ไม่พบคณาจารย์ที่ตรงกับคำค้นหา "{{ searchQuery }}"
        </p>
        <button
          type="button"
          class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold cursor-pointer shadow-xs"
          @click="selectedDepartment = 'all'; searchQuery = ''"
        >
          <v-icon icon="mdi-refresh" size="14" />
          <span>แสดงคณาจารย์ทั้งหมด</span>
        </button>
      </div>
    </div>
  </div>
</template>
