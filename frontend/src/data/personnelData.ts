// Import actual cropped portraits matching user screenshot
import ec1 from '@/assets/personnel/early-childhood-1.png'
import ec2 from '@/assets/personnel/early-childhood-2.png'
import ec3 from '@/assets/personnel/early-childhood-3.png'
import ec4 from '@/assets/personnel/early-childhood-4.png'

export interface EducationItem {
  degree: string
  field: string
  institution: string
  year?: string
}

export interface PublicationItem {
  title: string
  source: string
  year: string
  type: 'journal' | 'conference' | 'book' | 'research'
  link?: string
}

export interface CourseItem {
  code: string
  name: string
  level: string
}

export interface ExperienceItem {
  period?: string
  position: string
  organization: string
  description?: string
}

export interface StudyVisitItem {
  year?: string
  topic: string
  organization?: string
  location?: string
}

export interface Person {
  id: string
  name: string
  nameEn?: string
  academicTitle?: string
  roleTitle: string
  avatar: string
  degrees?: string
  departmentId: string
  departmentName: string
  email: string
  phone?: string
  officeRoom?: string
  officeHours?: string
  educationHistory?: EducationItem[]
  expertise?: string[]
  publications?: PublicationItem[]
  courses?: CourseItem[]
  workExperience?: ExperienceItem[]
  studyVisits?: StudyVisitItem[]
  bio?: string
}

export interface Department {
  id: string
  name: string
  degreeTitle?: string
  members: Person[]
}

export const departments: Department[] = [
  // 1. สาขาวิชาการศึกษาปฐมวัย (Exact match to reference screenshot)
  {
    id: 'early-childhood',
    name: 'สาขาวิชาการศึกษาปฐมวัย',
    degreeTitle: 'ครุศาสตรบัณฑิต (ค.บ.)',
    members: [
      {
        id: 'ec-1',
        name: 'ผศ.ธิดารัตน์ อธิปัญจพงษ์',
        nameEn: 'Asst. Prof. Thidarat Athipanjaphong',
        academicTitle: 'ผู้ช่วยศาสตราจารย์',
        roleTitle: 'ประธานสาขาวิชาการศึกษาปฐมวัย',
        avatar: ec1,
        degrees: 'ค.ด. (การศึกษาปฐมวัย), ค.ม. (การศึกษาปฐมวัย), ค.บ. (การศึกษาปฐมวัย)',
        departmentId: 'early-childhood',
        departmentName: 'สาขาวิชาการศึกษาปฐมวัย',
        email: 'tidarat.a@rru.ac.th',
        phone: '038-511-233 ต่อ 2101',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาการศึกษาปฐมวัย อาคาร 1 ชั้น 3 (ห้อง 1302)',
        officeHours: 'จันทร์ - ศุกร์ 09:00 - 16:30 น.',
        bio: 'ผู้เชี่ยวชาญด้านการพัฒนาหลักสูตรและการจัดประสบการณ์การเรียนรู้สำหรับเด็กปฐมวัย มีประสบการณ์การสอนและการวิจัยทางด้านการพัฒนาสมองและทักษะสมอง EF (Executive Functions) ในเด็กปฐมวัยมากกว่า 15 ปี',
        educationHistory: [
          {
            degree: 'ครุศาสตรดุษฎีบัณฑิต (ค.ด.)',
            field: 'การศึกษาปฐมวัย',
            institution: 'จุฬาลงกรณ์มหาวิทยาลัย',
            year: '2558',
          },
          {
            degree: 'ครุศาสตรมหาบัณฑิต (ค.ม.)',
            field: 'การศึกษาปฐมวัย',
            institution: 'จุฬาลงกรณ์มหาวิทยาลัย',
            year: '2550',
          },
          {
            degree: 'ครุศาสตรบัณฑิต (ค.บ.) เกียรตินิยม',
            field: 'การศึกษาปฐมวัย',
            institution: 'มหาวิทยาลัยราชภัฏราชนครินทร์',
            year: '2545',
          },
        ],
        expertise: [
          'การจัดประสบการณ์การเรียนรู้สำหรับเด็กปฐมวัย (Early Childhood Learning Experience)',
          'การพัฒนาทักษะทางสมองเพื่อความสำเร็จ (Executive Functions : EF)',
          'นวัตกรรมการประเมินพัฒนาการเด็กปฐมวัยตามสภาพจริง (Authentic Assessment)',
          'การสร้างความร่วมมือระหว่างโรงเรียน ครอบครัว และชุมชน (School-Family-Community Partnerships)',
          'การผลิตสื่อและของเล่นส่งเสริมพัฒนาการปฐมวัย',
        ],
        publications: [
          {
            title: 'การพัฒนารูปแบบการจัดกิจกรรมการเรียนรู้แบบบูรณาการเพื่อเสริมสร้างทักษะสมอง EF ในเด็กปฐมวัย',
            source: 'วารสารครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์ ปีที่ 18 ฉบับที่ 2',
            year: '2566',
            type: 'journal',
          },
          {
            title: 'ผลของการจัดประสบการณ์การเรียนรู้ผ่านการเล่นต่อความฉลาดทางอารมณ์และสังคมของเด็กปฐมวัย',
            source: 'การประชุมวิชาการระดับชาติด้านการศึกษาและการพัฒนาเด็กปฐมวัย ครั้งที่ 9',
            year: '2565',
            type: 'conference',
          },
          {
            title: 'ตำรา: การจัดประสบการณ์และการประเมินพัฒนาการสำหรับเด็กปฐมวัยยุคดิจิทัล',
            source: 'สำนักพิมพ์มหาวิทยาลัยราชภัฏราชนครินทร์',
            year: '2564',
            type: 'book',
          },
          {
            title: 'โครงการวิจัย: การเสริมพลังครูปฐมวัยในเขตพื้นที่ภาคตะวันออกด้วยแนวคิดไฮสโคป (HighScope)',
            source: 'ทุนอุดหนุนการวิจัยจากกองทุนส่งเสริมวิทยาศาสตร์ วิจัยและนวัตกรรม (ววน.)',
            year: '2563',
            type: 'research',
          },
        ],
        courses: [
          { code: 'ECE101', name: 'ความรู้เบื้องต้นเกี่ยวกับการศึกษาปฐมวัย', level: 'ปริญญาตรี' },
          { code: 'ECE203', name: 'การจัดประสบการณ์การเรียนรู้สำหรับเด็กปฐมวัย', level: 'ปริญญาตรี' },
          { code: 'ECE305', name: 'การประเมินพัฒนาการและการเรียนรู้ของเด็กปฐมวัย', level: 'ปริญญาตรี' },
          { code: 'ECE402', name: 'การวิจัยเพื่อพัฒนาการเรียนรู้ปฐมวัย', level: 'ปริญญาตรี' },
        ],
        workExperience: [
          {
            period: '2564 - ปัจจุบัน',
            position: 'ประธานสาขาวิชาการศึกษาปฐมวัย',
            organization: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
            description: 'บริหารจัดการและพัฒนาหลักสูตรครุศาสตรบัณฑิต สาขาวิชาการศึกษาปฐมวัย',
          },
          {
            period: '2561 - 2564',
            position: 'กรรมการฝ่ายประกันคุณภาพการศึกษา',
            organization: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
            description: 'ขับเคลื่อนมาตรฐานคุณภาพการจัดการศึกษาตามเกณฑ์ AUN-QA',
          },
          {
            period: '2555 - ปัจจุบัน',
            position: 'อาจารย์ประจำกลุ่มวิชาชีพครูและปฐมวัย',
            organization: 'มหาวิทยาลัยราชภัฏราชนครินทร์',
            description: 'จัดการเรียนการสอนและพัฒนางานวิจัยด้านการศึกษาปฐมวัย',
          },
        ],
        studyVisits: [
          {
            year: '2566',
            topic: 'ศึกษาดูงานการจัดการศึกษาและการดูแลเด็กปฐมวัยตามแนวทางเรจจิโอ เอมิเลีย (Reggio Emilia Approach)',
            organization: 'ศูนย์พัฒนาเด็กปฐมวัยและโรงเรียนต้นแบบการศึกษาปฐมวัย',
            location: 'กรุงเทพมหานคร',
          },
          {
            year: '2565',
            topic: 'การศึกษาดูงานด้านนวัตกรรมการเรียนรู้และทักษะสมอง EF ในเด็กปฐมวัย',
            organization: 'สถาบันวิจัยการเรียนรู้และโรงเรียนสาธิตจุฬาลงกรณ์มหาวิทยาลัย',
            location: 'กรุงเทพมหานคร',
          },
          {
            year: '2562',
            topic: 'โครงการศึกษาดูงานการจัดการเรียนรู้ปฐมวัยในศตวรรษที่ 21 ณ ประเทศสิงคโปร์',
            organization: 'National Institute of Early Childhood Development (NIEC)',
            location: 'ประเทศสิงคโปร์',
          },
        ],
      },
      {
        id: 'ec-2',
        name: 'อาจารย์พจนีย์ เชื้อบัณฑิต',
        nameEn: 'Lect. Potjanee Chueabandit',
        academicTitle: 'อาจารย์',
        roleTitle: 'อาจารย์ประจำสาขาวิชาการศึกษาปฐมวัย',
        avatar: ec2,
        degrees: 'ค.ม. (การศึกษาปฐมวัย), ค.บ. (การศึกษาปฐมวัย)',
        departmentId: 'early-childhood',
        departmentName: 'สาขาวิชาการศึกษาปฐมวัย',
        email: 'potjanee.c@rru.ac.th',
        phone: '038-511-233 ต่อ 2102',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาการศึกษาปฐมวัย อาคาร 1 ชั้น 3 (ห้อง 1302)',
        officeHours: 'อังคาร - พฤหัสบดี 10:00 - 16:00 น.',
        bio: 'ผู้เชี่ยวชาญด้านการจัดสภาพแวดล้อมเพื่อการเรียนรู้และวรรณกรรมสำหรับเด็กปฐมวัย การส่งเสริมพัฒนาการด้านภาษาและการรู้หนังสือเริ่มต้น (Emergent Literacy)',
        educationHistory: [
          {
            degree: 'ครุศาสตรมหาบัณฑิต (ค.ม.)',
            field: 'การศึกษาปฐมวัย',
            institution: 'จุฬาลงกรณ์มหาวิทยาลัย',
            year: '2554',
          },
          {
            degree: 'ครุศาสตรบัณฑิต (ค.บ.) เกียรตินิยม',
            field: 'การศึกษาปฐมวัย',
            institution: 'มหาวิทยาลัยศรีนครินทรวิโรฒ',
            year: '2548',
          },
        ],
        expertise: [
          'วรรณกรรมและนิทานสำหรับเด็กปฐมวัย (Children’s Literature & Storytelling)',
          'การจัดสภาพแวดล้อมเพื่อการเรียนรู้ในระดับปฐมวัย (Learning Environments)',
          'การส่งเสริมพัฒนาการทางภาษาและการรู้หนังสือเริ่มต้น (Emergent Literacy)',
        ],
        publications: [
          {
            title: 'การใช้นิทานภาพประกอบกิจกรรมเพื่อส่งเสริมทักษะทางภาษาของเด็กปฐมวัย',
            source: 'วารสารวิชาการศึกษาศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
            year: '2565',
            type: 'journal',
          },
        ],
        courses: [
          { code: 'ECE102', name: 'จิตวิทยาและพฤติกรรมเด็กปฐมวัย', level: 'ปริญญาตรี' },
          { code: 'ECE204', name: 'นิทานและวรรณกรรมสำหรับเด็กปฐมวัย', level: 'ปริญญาตรี' },
          { code: 'ECE308', name: 'การจัดสภาพแวดล้อมการเรียนรู้ในระดับปฐมวัย', level: 'ปริญญาตรี' },
        ],
        workExperience: [
          {
            period: '2558 - ปัจจุบัน',
            position: 'อาจารย์ประจำสาขาวิชาการศึกษาปฐมวัย',
            organization: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
            description: 'อาจารย์ผู้รับผิดชอบหลักสูตรและวิทยากรด้านวรรณกรรมเด็ก',
          },
          {
            period: '2552 - 2557',
            position: 'ครูปฐมวัยชำนาญการ',
            organization: 'โรงเรียนสาธิตมหาวิทยาลัยราชภัฏราชนครินทร์',
            description: 'จัดการเรียนรู้และพัฒนาสื่อนวัตกรรมส่งเสริมพัฒนาการทางภาษา',
          },
        ],
        studyVisits: [
          {
            year: '2565',
            topic: 'ศึกษาดูงานการจัดพื้นที่เรียนรู้และห้องสมุดมีชีวิตสำหรับเด็กปฐมวัย',
            organization: 'TK Park และพิพิธภัณฑ์เด็กกรุงเทพมหานคร',
            location: 'กรุงเทพมหานคร',
          },
          {
            year: '2563',
            topic: 'การศึกษาดูงานการจัดสภาพแวดล้อมเพื่อการเรียนรู้ตามแนวคิดวอลดอร์ฟ (Waldorf)',
            organization: 'โรงเรียนปัญโญทัย',
            location: 'กรุงเทพมหานคร',
          },
        ],
      },
      {
        id: 'ec-3',
        name: 'อาจารย์สุรสา จันทนา',
        nameEn: 'Lect. Surasa Chantana',
        academicTitle: 'อาจารย์',
        roleTitle: 'อาจารย์ประจำสาขาวิชาการศึกษาปฐมวัย',
        avatar: ec3,
        degrees: 'กศ.ม. (การศึกษาปฐมวัย), กศ.บ. (การศึกษาปฐมวัย)',
        departmentId: 'early-childhood',
        departmentName: 'สาขาวิชาการศึกษาปฐมวัย',
        email: 'surasa.j@rru.ac.th',
        phone: '038-511-233 ต่อ 2103',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาการศึกษาปฐมวัย อาคาร 1 ชั้น 3 (ห้อง 1303)',
        officeHours: 'จันทร์ - พุธ 09:30 - 15:30 น.',
        bio: 'ผู้เชี่ยวชาญด้านศิลปะ ดนตรี และการเคลื่อนไหวสร้างสรรค์สำหรับเด็กปฐมวัย การนำแนวคิดการเรียนรู้ผ่านการลงมือปฏิบัติ (Hands-on Activity) มาประยุกต์ในการเรียนการสอน',
        educationHistory: [
          {
            degree: 'การศึกษามหาบัณฑิต (กศ.ม.)',
            field: 'การศึกษาปฐมวัย',
            institution: 'มหาวิทยาลัยศรีนครินทรวิโรฒ',
            year: '2556',
          },
          {
            degree: 'การศึกษาบัณฑิต (กศ.บ.)',
            field: 'การศึกษาปฐมวัย',
            institution: 'มหาวิทยาลัยศรีนครินทรวิโรฒ',
            year: '2551',
          },
        ],
        expertise: [
          'ศิลปะสร้างสรรค์สำหรับเด็กปฐมวัย (Creative Arts for Young Children)',
          'ดนตรีและการเคลื่อนไหวเพื่อส่งเสริมพัฒนาการเด็ก (Music & Movement)',
          'การออกแบบสื่อการเรียนรู้ปฐมวัยจากวัสดุท้องถิ่น',
        ],
        publications: [
          {
            title: 'การจัดกิจกรรมศิลปะสร้างสรรค์เพื่อส่งเสริมความคิดสร้างสรรค์ของเด็กปฐมวัย',
            source: 'วารสารพัฒนาการเด็กและการศึกษาปฐมวัย',
            year: '2564',
            type: 'journal',
          },
        ],
        courses: [
          { code: 'ECE205', name: 'ศิลปะสร้างสรรค์สำหรับเด็กปฐมวัย', level: 'ปริญญาตรี' },
          { code: 'ECE206', name: 'ดนตรีและการเคลื่อนไหวสำหรับเด็กปฐมวัย', level: 'ปริญญาตรี' },
          { code: 'ECE310', name: 'การผลิตสื่อการเรียนรู้ปฐมวัย', level: 'ปริญญาตรี' },
        ],
        workExperience: [
          {
            period: '2559 - ปัจจุบัน',
            position: 'อาจารย์ประจำสาขาวิชาการศึกษาปฐมวัย',
            organization: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
            description: 'ผู้สอนและวิทยากรด้านดนตรี การเคลื่อนไหว และศิลปะสร้างสรรค์สำหรับเด็ก',
          },
          {
            period: '2553 - 2558',
            position: 'นักวิชาการศึกษาด้านสื่อการเรียนรู้',
            organization: 'สำนักการศึกษา กรุงเทพมหานคร',
            description: 'พัฒนาสื่อการเรียนรู้และคู่มือครูปฐมวัย',
          },
        ],
        studyVisits: [
          {
            year: '2566',
            topic: 'ศึกษาดูงานการบูรณาการดนตรีและการเคลื่อนไหวตามแนวทาง Orff Schulwerk',
            organization: 'วิทยาลัยดุริยางคศิลป์ มหาวิทยาลัยมหิดล',
            location: 'จ.นครปฐม',
          },
        ],
      },
      {
        id: 'ec-4',
        name: 'อาจารย์อัญชลี รัตนประเสริฐ',
        nameEn: 'Lect. Anchalee Rattanaprasert',
        academicTitle: 'อาจารย์',
        roleTitle: 'อาจารย์ประจำสาขาวิชาการศึกษาปฐมวัย',
        avatar: ec4,
        degrees: 'ศษ.ม. (การศึกษาปฐมวัย), ค.บ. (การศึกษาปฐมวัย)',
        departmentId: 'early-childhood',
        departmentName: 'สาขาวิชาการศึกษาปฐมวัย',
        email: 'anchalee.r@rru.ac.th',
        phone: '038-511-233 ต่อ 2104',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาการศึกษาปฐมวัย อาคาร 1 ชั้น 3 (ห้อง 1303)',
        officeHours: 'จันทร์ - ศุกร์ 09:00 - 16:00 น.',
        bio: 'ผู้เชี่ยวชาญด้านวิทยาศาสตร์และคณิตศาสตร์ระดับปฐมวัย การจัดการเรียนรู้แบบสะเต็มศึกษา (Early Childhood STEM/STEAM) และการประเมินพัฒนาการเด็ก',
        educationHistory: [
          {
            degree: 'ศึกษาศาสตรมหาบัณฑิต (ศษ.ม.)',
            field: 'การศึกษาปฐมวัย',
            institution: 'มหาวิทยาลัยเกษตรศาสตร์',
            year: '2559',
          },
          {
            degree: 'ครุศาสตรบัณฑิต (ค.บ.) เกียรตินิยม',
            field: 'การศึกษาปฐมวัย',
            institution: 'มหาวิทยาลัยราชภัฏราชนครินทร์',
            year: '2553',
          },
        ],
        expertise: [
          'การส่งเสริมทักษะวิทยาศาสตร์และคณิตศาสตร์ปฐมวัย (Early STEM / STEAM Education)',
          'การจัดประสบการณ์ตามแนวทางมอนเตสซอรี่ (Montessori Method)',
          'การบูรณาการเทคโนโลยีดิจิทัลในการส่งเสริมการเรียนรู้เด็กปฐมวัย',
        ],
        publications: [
          {
            title: 'การพัฒนากิจกรรมการเรียนรู้สะเต็มศึกษาเพื่อส่งเสริมทักษะการสืบเสาะของเด็กปฐมวัย',
            source: 'วารสารการศึกษาและการวิจัย มหาวิทยาลัยราชภัฏราชนครินทร์',
            year: '2566',
            type: 'journal',
          },
        ],
        courses: [
          { code: 'ECE207', name: 'คณิตศาสตร์และวิทยาศาสตร์สำหรับเด็กปฐมวัย', level: 'ปริญญาตรี' },
          { code: 'ECE312', name: 'การจัดประสบการณ์สะเต็มศึกษาในระดับปฐมวัย', level: 'ปริญญาตรี' },
          { code: 'ECE405', name: 'การสัมมนาการศึกษาปฐมวัย', level: 'ปริญญาตรี' },
        ],
        workExperience: [
          {
            period: '2562 - ปัจจุบัน',
            position: 'อาจารย์ประจำสาขาวิชาการศึกษาปฐมวัย',
            organization: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
            description: 'ผู้รับผิดชอบงานด้านสะเต็มศึกษาปฐมวัยและการจัดการเรียนการสอนแบบมอนเตสซอรี่',
          },
          {
            period: '2556 - 2561',
            position: 'ครูปฐมวัยประจำโรงเรียนเอกชนต้นแบบ',
            organization: 'โรงเรียนอนุบาลมอนเตสซอรี่',
            description: 'จัดการเรียนรู้ตามแนวคิดมอนเตสซอรี่ในห้องเรียนปฐมวัย',
          },
        ],
        studyVisits: [
          {
            year: '2566',
            topic: 'ศึกษาดูงานการจัดการเรียนรู้สะเต็มศึกษาปฐมวัย (Early STEM) ในระดับนานาชาติ',
            organization: 'ศูนย์สะเต็มศึกษาแห่งชาติ และโรงเรียนนานาชาติ',
            location: 'กรุงเทพมหานคร',
          },
        ],
      },
    ],
  },

  // 2. สาขาวิชาวิทยาการข้อมูลและการวิเคราะห์สถิติ
  {
    id: 'data-science',
    name: 'สาขาวิชาวิทยาการข้อมูลและการวิเคราะห์สถิติ',
    degreeTitle: 'วิทยาศาสตรบัณฑิต (วท.บ.)',
    members: [
      {
        id: 'ds-1',
        name: 'ผศ.ดร.สุรเชษฐ์ สถิตพัฒนากุล',
        nameEn: 'Asst. Prof. Dr. Surachet Sathitpattanakul',
        academicTitle: 'ผู้ช่วยศาสตราจารย์',
        roleTitle: 'ประธานสาขาวิชาวิทยาการข้อมูล',
        avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (วิทยาการคอมพิวเตอร์), วท.ม. (เทคโนโลยีสารสนเทศ)',
        departmentId: 'data-science',
        departmentName: 'สาขาวิชาวิทยาการข้อมูลและการวิเคราะห์สถิติ',
        email: 'surachet.s@rru.ac.th',
        phone: '038-511-233 ต่อ 2201',
        officeRoom: 'ห้องพักคณาจารย์วิทยาการข้อมูล อาคาร 4 ชั้น 2 (ห้อง 4201)',
        officeHours: 'จันทร์, พุธ, ศุกร์ 09:00 - 15:00 น.',
        bio: 'ผู้เชี่ยวชาญด้านการวิเคราะห์ข้อมูลขนาดใหญ่ (Big Data Analytics), การเรียนรู้ของเครื่อง (Machine Learning) และการประยุกต์ปัญญาประดิษฐ์เพื่อการศึกษา',
        educationHistory: [
          { degree: 'ปรัชญาดุษฎีบัณฑิต (ปร.ด.)', field: 'วิทยาการคอมพิวเตอร์', institution: 'สถาบันเทคโนโลยีพระจอมเกล้าเจ้าคุณทหารลาดกระบัง' },
          { degree: 'วิทยาศาสตรมหาบัณฑิต (วท.ม.)', field: 'เทคโนโลยีสารสนเทศ', institution: 'มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี' },
        ],
        expertise: ['Machine Learning & Deep Learning', 'Educational Data Mining', 'Big Data Engineering'],
        publications: [
          { title: 'Machine Learning Models for Student Dropout Prediction in Higher Education', source: 'IEEE Access, Vol. 11', year: '2023', type: 'journal' },
        ],
        courses: [
          { code: 'DS101', name: 'การเขียนโปรแกรมสำหรับวิทยาการข้อมูล (Python)', level: 'ปริญญาตรี' },
          { code: 'DS301', name: 'การเรียนรู้ของเครื่อง (Machine Learning)', level: 'ปริญญาตรี' },
        ],
      },
      {
        id: 'ds-2',
        name: 'อาจารย์ ดร.เกศรา วิเชียรศรี',
        nameEn: 'Dr. Ketsara Wichiansri',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาวิทยาการข้อมูล',
        avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
        degrees: 'วท.ด. (สถิติประยุกต์), วท.บ. (สถิติศาสตร์)',
        departmentId: 'data-science',
        departmentName: 'สาขาวิชาวิทยาการข้อมูลและการวิเคราะห์สถิติ',
        email: 'ketsara.w@rru.ac.th',
        phone: '038-511-233 ต่อ 2202',
        officeRoom: 'ห้องพักคณาจารย์วิทยาการข้อมูล อาคาร 4 ชั้น 2',
        officeHours: 'อังคาร, พฤหัสบดี 09:30 - 16:00 น.',
        bio: 'ผู้เชี่ยวชาญด้านสถิติประยุกต์ การสร้างแบบจำลองสถิติเชิงพยากรณ์ และการแสดงผลข้อมูลด้วยภาพ (Data Visualization)',
        educationHistory: [
          { degree: 'วิทยาศาสตรดุษฎีบัณฑิต (วท.ด.)', field: 'สถิติประยุกต์', institution: 'สถาบันบัณฑิตพัฒนบริหารศาสตร์ (NIDA)' },
          { degree: 'วิทยาศาสตรบัณฑิต (วท.บ.)', field: 'สถิติศาสตร์', institution: 'จุฬาลงกรณ์มหาวิทยาลัย' },
        ],
        expertise: ['Applied Statistics & Modeling', 'Data Visualization (Tableau, PowerBI)', 'Time Series Analysis'],
        courses: [
          { code: 'DS202', name: 'การวิเคราะห์เชิงสถิติขั้นสูง', level: 'ปริญญาตรี' },
          { code: 'DS205', name: 'การแสดงภาพข้อมูล (Data Visualization)', level: 'ปริญญาตรี' },
        ],
      },
      {
        id: 'ds-3',
        name: 'อาจารย์ณัฐพล ทองธรรมชาติ',
        nameEn: 'Lect. Nattapon Thongthammachart',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาวิทยาการข้อมูล',
        avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
        degrees: 'วศ.ม. (วิศวกรรมคอมพิวเตอร์), วศ.บ. (วิศวกรรมคอมพิวเตอร์)',
        departmentId: 'data-science',
        departmentName: 'สาขาวิชาวิทยาการข้อมูลและการวิเคราะห์สถิติ',
        email: 'nattapon.t@rru.ac.th',
        phone: '038-511-233 ต่อ 2203',
        officeRoom: 'ห้องพักคณาจารย์วิทยาการข้อมูล อาคาร 4 ชั้น 2',
        courses: [
          { code: 'DS204', name: 'ระบบการจัดการฐานข้อมูลและการประมวลผลบนคลาวด์', level: 'ปริญญาตรี' },
        ],
      },
      {
        id: 'ds-4',
        name: 'ผศ.ดร.อารียา รัตนโชติ',
        nameEn: 'Asst. Prof. Dr. Areeya Rattanachote',
        academicTitle: 'ผู้ช่วยศาสตราจารย์',
        roleTitle: 'สาขาวิชาวิทยาการข้อมูล',
        avatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (สถิติประยุกต์), วท.ม. (คณิตศาสตร์ประยุกต์)',
        departmentId: 'data-science',
        departmentName: 'สาขาวิชาวิทยาการข้อมูลและการวิเคราะห์สถิติ',
        email: 'areeya.rat@rru.ac.th',
        phone: '038-511-233 ต่อ 2204',
        officeRoom: 'ห้องพักคณาจารย์วิทยาการข้อมูล อาคาร 4 ชั้น 2',
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
        nameEn: 'Asst. Prof. Dr. Weerasak Sriwichai',
        academicTitle: 'ผู้ช่วยศาสตราจารย์',
        roleTitle: 'ประธานสาขาวิชาการประถมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ด. (หลักสูตรและการสอน), กศ.ม. (การประถมศึกษา)',
        departmentId: 'elementary',
        departmentName: 'สาขาวิชาการประถมศึกษา',
        email: 'weerasak.sri@rru.ac.th',
        phone: '038-511-233 ต่อ 2301',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาการประถมศึกษา อาคาร 1 ชั้น 2',
      },
      {
        id: 'el-2',
        name: 'อาจารย์ ดร.ภัสสร ชัยเจริญ',
        nameEn: 'Dr. Passorn Chaicharoen',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาการประถมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1548142813-c348350df52b?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (การวิจัยและพัฒนาการศึกษา), ค.ม. (การประถมศึกษา)',
        departmentId: 'elementary',
        departmentName: 'สาขาวิชาการประถมศึกษา',
        email: 'passorn.c@rru.ac.th',
        phone: '038-511-233 ต่อ 2302',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาการประถมศึกษา อาคาร 1 ชั้น 2',
      },
      {
        id: 'el-3',
        name: 'อาจารย์ปิยนุช วิจิตรศิลป์',
        nameEn: 'Lect. Piyanuch Wijitsin',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาการประถมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ม. (การประถมศึกษา), ค.บ. (การประถมศึกษา)',
        departmentId: 'elementary',
        departmentName: 'สาขาวิชาการประถมศึกษา',
        email: 'piyanuch.v@rru.ac.th',
        phone: '038-511-233 ต่อ 2303',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาการประถมศึกษา อาคาร 1 ชั้น 2',
      },
      {
        id: 'el-4',
        name: 'ผศ.ชูเกียรติ มั่นคง',
        nameEn: 'Asst. Prof. Chookiat Mankhong',
        academicTitle: 'ผู้ช่วยศาสตราจารย์',
        roleTitle: 'สาขาวิชาการประถมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
        degrees: 'ศษ.ม. (ประถมศึกษา), กศ.บ. (การประถมศึกษา)',
        departmentId: 'elementary',
        departmentName: 'สาขาวิชาการประถมศึกษา',
        email: 'chookiat.m@rru.ac.th',
        phone: '038-511-233 ต่อ 2304',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาการประถมศึกษา อาคาร 1 ชั้น 2',
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
        nameEn: 'Assoc. Prof. Dr. Praphas Boonsong',
        academicTitle: 'รองศาสตราจารย์',
        roleTitle: 'ประธานสาขาวิชาภาษาไทย',
        avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
        degrees: 'อ.ด. (ภาษาไทย), อ.ม. (ภาษาไทย), ค.บ. (ภาษาไทย)',
        departmentId: 'thai',
        departmentName: 'สาขาวิชาภาษาไทย',
        email: 'praphas.b@rru.ac.th',
        phone: '038-511-233 ต่อ 2401',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาภาษาไทย อาคาร 2 ชั้น 3',
      },
      {
        id: 'th-2',
        name: 'ผศ.ดร.อัมพร พงษ์ศิริ',
        nameEn: 'Asst. Prof. Dr. Amporn Pongsiri',
        academicTitle: 'ผู้ช่วยศาสตราจารย์',
        roleTitle: 'สาขาวิชาภาษาไทย',
        avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80',
        degrees: 'ศศ.ด. (ภาษาไทย), ศศ.ม. (ภาษาไทยเพื่อการอาชีพ)',
        departmentId: 'thai',
        departmentName: 'สาขาวิชาภาษาไทย',
        email: 'amporn.p@rru.ac.th',
        phone: '038-511-233 ต่อ 2402',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาภาษาไทย อาคาร 2 ชั้น 3',
      },
      {
        id: 'th-3',
        name: 'อาจารย์รุ่งทิพย์ สถิตย์ธรรม',
        nameEn: 'Lect. Rungthip Sathittham',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาภาษาไทย',
        avatar: 'https://images.unsplash.com/photo-1573496799652-408c2ac9fe98?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ม. (การสอนภาษาไทย), ค.บ. (ภาษาไทย)',
        departmentId: 'thai',
        departmentName: 'สาขาวิชาภาษาไทย',
        email: 'rungthip.s@rru.ac.th',
        phone: '038-511-233 ต่อ 2403',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาภาษาไทย อาคาร 2 ชั้น 3',
      },
      {
        id: 'th-4',
        name: 'อาจารย์ธวัชชัย รื่นเริง',
        nameEn: 'Lect. Thawatchai Ruenroeng',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาภาษาไทย',
        avatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=400&q=80',
        degrees: 'ศศ.ม. (ภาษาไทย), กศ.บ. (ภาษาไทย)',
        departmentId: 'thai',
        departmentName: 'สาขาวิชาภาษาไทย',
        email: 'thawatchai.r@rru.ac.th',
        phone: '038-511-233 ต่อ 2404',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาภาษาไทย อาคาร 2 ชั้น 3',
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
        name: 'ผศ.ดร.เจริญชัย นพคุณ',
        nameEn: 'Asst. Prof. Dr. Charoenchai Noppakun',
        academicTitle: 'ผู้ช่วยศาสตราจารย์',
        roleTitle: 'ประธานสาขาวิชาภาษาอังกฤษ',
        avatar: 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=400&q=80',
        degrees: 'Ph.D. in English Linguistics, M.A. in Applied Linguistics',
        departmentId: 'english',
        departmentName: 'สาขาวิชาภาษาอังกฤษ',
        email: 'charoenchai.n@rru.ac.th',
        phone: '038-511-233 ต่อ 2501',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาภาษาอังกฤษ อาคาร 2 ชั้น 4',
      },
      {
        id: 'en-2',
        name: 'อาจารย์ ดร.สิรินภา เกียรติบูรณ์',
        nameEn: 'Dr. Sirinapa Kiatboon',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาภาษาอังกฤษ',
        avatar: 'https://images.unsplash.com/photo-1594744803329-e58b31de8bf5?auto=format&fit=crop&w=400&q=80',
        degrees: 'Ph.D. in TESOL, M.Ed. in Curriculum and Instruction',
        departmentId: 'english',
        departmentName: 'สาขาวิชาภาษาอังกฤษ',
        email: 'sirinapa.k@rru.ac.th',
        phone: '038-511-233 ต่อ 2502',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาภาษาอังกฤษ อาคาร 2 ชั้น 4',
      },
      {
        id: 'en-3',
        name: 'อาจารย์วารุณี แสนสุข',
        nameEn: 'Lect. Warunee Saensuk',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาภาษาอังกฤษ',
        avatar: 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?auto=format&fit=crop&w=400&q=80',
        degrees: 'M.Ed. in English Curriculum, B.Ed. in English',
        departmentId: 'english',
        departmentName: 'สาขาวิชาภาษาอังกฤษ',
        email: 'warunee.s@rru.ac.th',
        phone: '038-511-233 ต่อ 2503',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาภาษาอังกฤษ อาคาร 2 ชั้น 4',
      },
      {
        id: 'en-4',
        name: 'อาจารย์นภัสสร ธนกิจ',
        nameEn: 'Lect. Napassorn Thanakit',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาภาษาอังกฤษ',
        avatar: 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=400&q=80',
        degrees: 'M.A. in Teaching English as an International Language',
        departmentId: 'english',
        departmentName: 'สาขาวิชาภาษาอังกฤษ',
        email: 'napassorn.t@rru.ac.th',
        phone: '038-511-233 ต่อ 2504',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาภาษาอังกฤษ อาคาร 2 ชั้น 4',
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
        nameEn: 'Asst. Prof. Dr. Pichet Kiatsakul',
        academicTitle: 'ผู้ช่วยศาสตราจารย์',
        roleTitle: 'ประธานสาขาวิชาคณิตศาสตร์',
        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (คณิตศาสตร์ศึกษา), วท.ม. (คณิตศาสตร์ประยุกต์)',
        departmentId: 'mathematics',
        departmentName: 'สาขาวิชาคณิตศาสตร์',
        email: 'pichet.k@rru.ac.th',
        phone: '038-511-233 ต่อ 2601',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาคณิตศาสตร์ อาคาร 3 ชั้น 2',
      },
      {
        id: 'math-2',
        name: 'อาจารย์ ดร.สุจิตรา เลิศวิลัย',
        nameEn: 'Dr. Sujitra Lertwilai',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาคณิตศาสตร์',
        avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (คณิตศาสตร์), วท.ม. (สถิติศาสตร์)',
        departmentId: 'mathematics',
        departmentName: 'สาขาวิชาคณิตศาสตร์',
        email: 'sujitra.l@rru.ac.th',
        phone: '038-511-233 ต่อ 2602',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาคณิตศาสตร์ อาคาร 3 ชั้น 2',
      },
      {
        id: 'math-3',
        name: 'อาจารย์กฤษฎา สว่างภพ',
        nameEn: 'Lect. Kritsada Sawangphop',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาคณิตศาสตร์',
        avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ม. (คณิตศาสตร์ศึกษา), วท.บ. (คณิตศาสตร์)',
        departmentId: 'mathematics',
        departmentName: 'สาขาวิชาคณิตศาสตร์',
        email: 'kritsada.s@rru.ac.th',
        phone: '038-511-233 ต่อ 2603',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาคณิตศาสตร์ อาคาร 3 ชั้น 2',
      },
      {
        id: 'math-4',
        name: 'อาจารย์ขวัญตา บุญญาธิการ',
        nameEn: 'Lect. Khwanta Boonyathikarn',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาคณิตศาสตร์',
        avatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
        degrees: 'วท.ม. (คณิตศาสตร์ประยุกต์และวิทยาการคำนวณ)',
        departmentId: 'mathematics',
        departmentName: 'สาขาวิชาคณิตศาสตร์',
        email: 'khwanta.b@rru.ac.th',
        phone: '038-511-233 ต่อ 2604',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาคณิตศาสตร์ อาคาร 3 ชั้น 2',
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
        nameEn: 'Asst. Prof. Dr. Chanapol Charoensuk',
        academicTitle: 'ผู้ช่วยศาสตราจารย์',
        roleTitle: 'ประธานสาขาวิชาวิทยาศาสตร์ทั่วไป',
        avatar: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (วิทยาศาสตร์ศึกษา), วท.ม. (เคมีอินทรีย์)',
        departmentId: 'science',
        departmentName: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
        email: 'chanapol.c@rru.ac.th',
        phone: '038-511-233 ต่อ 2701',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาวิทยาศาสตร์ทั่วไป อาคาร 3 ชั้น 3',
      },
      {
        id: 'sci-2',
        name: 'อาจารย์ ดร.รัชดา สุรินทร์',
        nameEn: 'Dr. Ratchada Surin',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
        avatar: 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=400&q=80',
        degrees: 'Ph.D. in Physics Education, M.Sc. in Applied Physics',
        departmentId: 'science',
        departmentName: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
        email: 'ratchada.s@rru.ac.th',
        phone: '038-511-233 ต่อ 2702',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาวิทยาศาสตร์ทั่วไป อาคาร 3 ชั้น 3',
      },
      {
        id: 'sci-3',
        name: 'อาจารย์อภิชาติ ภักดี',
        nameEn: 'Lect. Apichart Phakdee',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
        degrees: 'วท.ม. (ชีววิทยา), ค.บ. (วิทยาศาสตร์ทั่วไป)',
        departmentId: 'science',
        departmentName: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
        email: 'apichart.p@rru.ac.th',
        phone: '038-511-233 ต่อ 2703',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาวิทยาศาสตร์ทั่วไป อาคาร 3 ชั้น 3',
      },
      {
        id: 'sci-4',
        name: 'อาจารย์ศุภมาส จิตติพล',
        nameEn: 'Lect. Supamas Jittiphon',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
        avatar: 'https://images.unsplash.com/photo-1594744803329-e58b31de8bf5?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ม. (การสอนวิทยาศาสตร์), วท.บ. (วิทยาศาสตร์สิ่งแวดล้อม)',
        departmentId: 'science',
        departmentName: 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
        email: 'supamas.j@rru.ac.th',
        phone: '038-511-233 ต่อ 2704',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาวิทยาศาสตร์ทั่วไป อาคาร 3 ชั้น 3',
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
        nameEn: 'Asst. Prof. Dr. Mongkol Saensupha',
        academicTitle: 'ผู้ช่วยศาสตราจารย์',
        roleTitle: 'ประธานสาขาวิชาสังคมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (สังคมศึกษา), ศศ.ม. (ประวัติศาสตร์ไทย)',
        departmentId: 'social-studies',
        departmentName: 'สาขาวิชาสังคมศึกษา',
        email: 'mongkol.s@rru.ac.th',
        phone: '038-511-233 ต่อ 2801',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาสังคมศึกษา อาคาร 2 ชั้น 2',
      },
      {
        id: 'soc-2',
        name: 'อาจารย์ ดร.อรอนงค์ เจริญผล',
        nameEn: 'Dr. Onanong Charoenphon',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาสังคมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
        degrees: 'ปร.ด. (การสอนสังคมศึกษา), ค.บ. (สังคมศึกษา)',
        departmentId: 'social-studies',
        departmentName: 'สาขาวิชาสังคมศึกษา',
        email: 'onanong.c@rru.ac.th',
        phone: '038-511-233 ต่อ 2802',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาสังคมศึกษา อาคาร 2 ชั้น 2',
      },
      {
        id: 'soc-3',
        name: 'อาจารย์ธีรภัทร บุญประเสริฐ',
        nameEn: 'Lect. Theeraphat Boonprasert',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาสังคมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
        degrees: 'ศศ.ม. (ภูมิศาสตร์), วท.บ. (ภูมิศาสตร์กายภาพ)',
        departmentId: 'social-studies',
        departmentName: 'สาขาวิชาสังคมศึกษา',
        email: 'theeraphat.b@rru.ac.th',
        phone: '038-511-233 ต่อ 2803',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาสังคมศึกษา อาคาร 2 ชั้น 2',
      },
      {
        id: 'soc-4',
        name: 'อาจารย์พรพิมล ยิ่งยง',
        nameEn: 'Lect. Pornpimon Yingyong',
        academicTitle: 'อาจารย์',
        roleTitle: 'สาขาวิชาสังคมศึกษา',
        avatar: 'https://images.unsplash.com/photo-1548142813-c348350df52b?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ม. (การสอนสังคมศึกษา), ศศ.บ. (ปรัชญาและศาสนา)',
        departmentId: 'social-studies',
        departmentName: 'สาขาวิชาสังคมศึกษา',
        email: 'pornpimon.y@rru.ac.th',
        phone: '038-511-233 ต่อ 2804',
        officeRoom: 'ห้องพักอาจารย์สาขาวิชาสังคมศึกษา อาคาร 2 ชั้น 2',
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
        nameEn: 'Assoc. Prof. Dr. Wimonpan Sukkasem',
        academicTitle: 'รองศาสตราจารย์',
        roleTitle: 'ประธานสาขาวิชาหลักสูตรและการสอน',
        avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ด. (หลักสูตรและการสอน - จุฬาลงกรณ์ฯ), ค.ม. (หลักสูตรและการสอน)',
        departmentId: 'curriculum-instruction',
        departmentName: 'สาขาวิชาหลักสูตรและการสอน (ป.โท)',
        email: 'wimonpan.s@rru.ac.th',
        phone: '038-511-233 ต่อ 2901',
        officeRoom: 'ห้องพักอาจารย์หลักสูตรและการสอน อาคารบัณฑิตวิทยาลัย ชั้น 3',
      },
      {
        id: 'ci-2',
        name: 'รศ.ดร.บุญชู ปัญญาเลิศ',
        nameEn: 'Assoc. Prof. Dr. Boonchoo Panyalert',
        academicTitle: 'รองศาสตราจารย์',
        roleTitle: 'สาขาวิชาหลักสูตรและการสอน',
        avatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=400&q=80',
        degrees: 'Ph.D. in Education (Curriculum & Instruction), กศ.ม. (การวัดผลการศึกษา)',
        departmentId: 'curriculum-instruction',
        departmentName: 'สาขาวิชาหลักสูตรและการสอน (ป.โท)',
        email: 'boonchoo.p@rru.ac.th',
        phone: '038-511-233 ต่อ 2902',
        officeRoom: 'ห้องพักอาจารย์หลักสูตรและการสอน อาคารบัณฑิตวิทยาลัย ชั้น 3',
      },
      {
        id: 'ci-3',
        name: 'ผศ.ดร.สุภาภรณ์ ชื่นอารมณ์',
        nameEn: 'Asst. Prof. Dr. Supaporn Chuen-arom',
        academicTitle: 'ผู้ช่วยศาสตราจารย์',
        roleTitle: 'สาขาวิชาหลักสูตรและการสอน',
        avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80',
        degrees: 'ค.ด. (วิธีวิทยาการวิจัยการศึกษา), ค.ม. (หลักสูตรและการสอน)',
        departmentId: 'curriculum-instruction',
        departmentName: 'สาขาวิชาหลักสูตรและการสอน (ป.โท)',
        email: 'supaporn.c@rru.ac.th',
        phone: '038-511-233 ต่อ 2903',
        officeRoom: 'ห้องพักอาจารย์หลักสูตรและการสอน อาคารบัณฑิตวิทยาลัย ชั้น 3',
      },
    ],
  },
]

// Helper functions
export const allMembers = departments.flatMap((dept) => dept.members)

export const getPersonById = (id: string): Person | undefined => {
  return allMembers.find((p) => p.id === id)
}

export const getDepartmentByPersonId = (id: string): Department | undefined => {
  return departments.find((dept) => dept.members.some((m) => m.id === id))
}

export const getRelatedFaculty = (deptId: string, currentPersonId: string): Person[] => {
  const dept = departments.find((d) => d.id === deptId)
  if (!dept) return []
  return dept.members.filter((m) => m.id !== currentPersonId)
}
