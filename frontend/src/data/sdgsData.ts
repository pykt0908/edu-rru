export interface SdgGoal {
  id: number
  numberStr: string
  titleEn: string
  titleTh: string
  descTh: string
  descEn: string
  color: string
  textColor: string
  svgIcon: string // SVG inner content
}

export interface SdgActivity {
  id: number
  sdgId: number
  titleTh: string
  titleEn: string
  categoryTh: string
  categoryEn: string
  date: string
  image: string
  summaryTh: string
  summaryEn: string
  author: string
}

export const SDG_GOALS: SdgGoal[] = [
  {
    id: 1,
    numberStr: '1',
    titleEn: 'NO POVERTY',
    titleTh: 'ขจัดความยากจน',
    descTh: 'ขจัดความยากจนทุกรูปแบบในทุกพื้นที่ สร้างหลักประกันความมั่นคงทางสังคม',
    descEn: 'End poverty in all its forms everywhere and ensure social protection for all',
    color: '#E5243B',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Family pictogram: Father, mother, children holding hands -->
      <circle cx="9" cy="22" r="3.2" fill="currentColor"/>
      <path d="M5.5 35v-8a4 4 0 0 1 7 0v8h-2.5v-6h-2v6z" fill="currentColor"/>
      <circle cx="19" cy="23.5" r="2.8" fill="currentColor"/>
      <path d="M16 35v-7a3.5 3.5 0 0 1 6 0v7h-2v-5.5h-2V35z" fill="currentColor"/>
      <circle cx="27.5" cy="20" r="3.4" fill="currentColor"/>
      <path d="M23.5 35v-9a4.5 4.5 0 0 1 8 0v9h-2.5v-7h-3v7z" fill="currentColor"/>
      <circle cx="37" cy="24" r="2.6" fill="currentColor"/>
      <path d="M34.5 35v-6a3 3 0 0 1 5 0v6h-1.8v-4.5h-1.4V35z" fill="currentColor"/>
      <!-- Connecting line for unity -->
      <path d="M7 28h30v2H7z" fill="currentColor" opacity="0.6"/>
    `
  },
  {
    id: 2,
    numberStr: '2',
    titleEn: 'ZERO HUNGER',
    titleTh: 'ขจัดความหิวโหย',
    descTh: 'ยุติความหิวโหย บรรลุความมั่นคงทางอาหาร และส่งเสริมการเกษตรกรรมที่ยั่งยืน',
    descEn: 'End hunger, achieve food security and improved nutrition and promote sustainable agriculture',
    color: '#DDA63A',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Steaming soup bowl and wheat/steam -->
      <path d="M12 24c0 7 5 13 12 13s12-6 12-13H12z" fill="currentColor"/>
      <path d="M16 19c-1-2-1-4 1-6s1-4-1-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none"/>
      <path d="M24 19c-1-2-1-4 1-6s1-4-1-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none"/>
      <path d="M32 19c-1-2-1-4 1-6s1-4-1-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none"/>
    `
  },
  {
    id: 3,
    numberStr: '3',
    titleEn: 'GOOD HEALTH AND WELL-BEING',
    titleTh: 'สุขภาพและความเป็นอยู่ที่ดี',
    descTh: 'สร้างหลักประกันการมีสุขภาวะที่ดี และส่งเสริมความเป็นอยู่ที่ดีสำหรับทุกคนในทุกวัย',
    descEn: 'Ensure healthy lives and promote well-being for all at all ages',
    color: '#4C9F38',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Heartbeat pulse ECG line -->
      <path d="M6 25h7l3.5-12 5.5 24 4-15 3 6h9" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      <path d="M37 13a4 4 0 0 1 5 5l-5 5-5-5a4 4 0 0 1 5-5z" fill="currentColor"/>
    `
  },
  {
    id: 4,
    numberStr: '4',
    titleEn: 'QUALITY EDUCATION',
    titleTh: 'การศึกษาที่มีคุณภาพ',
    descTh: 'สร้างหลักประกันการศึกษาที่มีคุณภาพอย่างเท่าเทียมและครอบคลุม ส่งเสริมโอกาสการเรียนรู้ตลอดชีวิต',
    descEn: 'Ensure inclusive and equitable quality education and promote lifelong learning opportunities for all',
    color: '#C5192D',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Open Book with pages and pencil -->
      <path d="M10 20c4-2 9-2 14 0v16c-5-2-10-2-14 0V20z" fill="currentColor"/>
      <path d="M24 20c5-2 10-2 14 0v16c-4-2-9-2-14 0V20z" fill="currentColor" opacity="0.9"/>
      <path d="M23 11l4-4 2 2-4 4-2.5.5z" fill="currentColor"/>
      <path d="M38 15v21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
    `
  },
  {
    id: 5,
    numberStr: '5',
    titleEn: 'GENDER EQUALITY',
    titleTh: 'ความเท่าเทียมทางเพศ',
    descTh: 'บรรลุความเท่าเทียมทางเพศ และเสริมสร้างความเข้มแข็งแก่สตรีและเด็กหญิงทุกคน',
    descEn: 'Achieve gender equality and empower all women and girls',
    color: '#FF3A21',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Gender equality symbol with combined signs and equal bar -->
      <circle cx="21" cy="21" r="7.5" stroke="currentColor" stroke-width="2.8" fill="none"/>
      <!-- Female cross -->
      <path d="M21 28.5v8M17 32.5h8" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/>
      <!-- Male arrow -->
      <path d="M27 15l8-8M29 7h6v6" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
      <!-- Equal bars inside circle -->
      <path d="M17.5 19.5h7M17.5 22.5h7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    `
  },
  {
    id: 6,
    numberStr: '6',
    titleEn: 'CLEAN WATER AND SANITATION',
    titleTh: 'น้ำสะอาดและสุขอนามัย',
    descTh: 'สร้างหลักประกันการเข้าถึงน้ำสะอาดและการจัดการสุขอนามัยที่ยั่งยืนสำหรับทุกคน',
    descEn: 'Ensure availability and sustainable management of water and sanitation for all',
    color: '#26BDE2',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Water droplet in container/glass -->
      <path d="M15 15h18l-3 20H18L15 15z" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linejoin="round"/>
      <path d="M24 18c-3 4-5 7-5 10a5 5 0 0 0 10 0c0-3-2-6-5-10z" fill="currentColor"/>
    `
  },
  {
    id: 7,
    numberStr: '7',
    titleEn: 'AFFORDABLE AND CLEAN ENERGY',
    titleTh: 'พลังงานสะอาดที่เข้าถึงได้',
    descTh: 'สร้างหลักประกันการเข้าถึงพลังงานสมัยใหม่ ยั่งยืน ปลอดภัย และราคาที่ทุกคนเข้าถึงได้',
    descEn: 'Ensure access to affordable, reliable, sustainable and modern energy for all',
    color: '#FCC30B',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Radiant sun burst with electrical plug inside -->
      <circle cx="24" cy="25" r="6" fill="currentColor"/>
      <path d="M24 13v-3M24 40v-3M13 25h-3M40 25h-3M16 17l-2-2M34 33l-2-2M16 33l-2 2M34 17l-2 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
      <path d="M22 23v4M26 23v4" stroke="#FCC30B" stroke-width="1.8" stroke-linecap="round"/>
    `
  },
  {
    id: 8,
    numberStr: '8',
    titleEn: 'DECENT WORK AND ECONOMIC GROWTH',
    titleTh: 'งานที่มีคุณค่าและการเติบโตทางเศรษฐกิจ',
    descTh: 'ส่งเสริมการเติบโตทางเศรษฐกิจที่ต่อเนื่อง ครอบคลุม และยั่งยืน การจ้างงานเต็มที่และมีคุณค่า',
    descEn: 'Promote sustained, inclusive and sustainable economic growth, full and productive employment and decent work for all',
    color: '#A21942',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Upward trending arrow on top of economic bar chart -->
      <path d="M12 34V26h5v8h-5zM20 34V20h5v14h-5zM28 34V16h5v18h-5z" fill="currentColor"/>
      <path d="M10 24l9-8 7 5 10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      <path d="M31 11h5v5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
    `
  },
  {
    id: 9,
    numberStr: '9',
    titleEn: 'INDUSTRY, INNOVATION AND INFRASTRUCTURE',
    titleTh: 'อุตสาหกรรม นวัตกรรม และโครงสร้างพื้นฐาน',
    descTh: 'สร้างโครงสร้างพื้นฐานที่พร้อมรับการเปลี่ยนแปลง ส่งเสริมการพัฒนาอุตสาหกรรมที่ครอบคลุมและยั่งยืน และสนับสนุนนวัตกรรม',
    descEn: 'Build resilient infrastructure, promote inclusive and sustainable industrialization and foster innovation',
    color: '#FD6925',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- 3 Isometric infrastructure blocks stacked in perspective -->
      <path d="M24 10l9 5-9 5-9-5 9-5z" fill="currentColor"/>
      <path d="M15 15v8l9 5v-8l-9-5z" fill="currentColor" opacity="0.85"/>
      <path d="M33 15v8l-9 5v-8l9-5z" fill="currentColor" opacity="0.7"/>
      <path d="M15 26v8l9 5v-8l-9-5z" fill="currentColor" opacity="0.85"/>
      <path d="M33 26v8l-9 5v-8l9-5z" fill="currentColor" opacity="0.7"/>
    `
  },
  {
    id: 10,
    numberStr: '10',
    titleEn: 'REDUCED INEQUALITIES',
    titleTh: 'ลดความเหลื่อมล้ำ',
    descTh: 'ลดความไม่เสมอภาคภายในประเทศและระหว่างประเทศ ส่งเสริมความเท่าเทียมในทุกมิติ',
    descEn: 'Reduce inequality within and among countries',
    color: '#DD1367',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Equal bars with 4 outward expanding arrows -->
      <path d="M18 22h12M18 26h12" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
      <!-- 4 Arrows pointing out -->
      <path d="M24 15l-4 4h8l-4-4zM24 33l-4-4h8l-4 4zM15 24l4-4v8l-4-4zM33 24l-4-4v8l4-4z" fill="currentColor"/>
    `
  },
  {
    id: 11,
    numberStr: '11',
    titleEn: 'SUSTAINABLE CITIES AND COMMUNITIES',
    titleTh: 'เมืองและชุมชนที่ยั่งยืน',
    descTh: 'ทำให้เมืองและการตั้งถิ่นฐานของมนุษย์มีความครอบคลุม ปลอดภัย มีภูมิต้านทาน และยั่งยืน',
    descEn: 'Make cities and human settlements inclusive, safe, resilient and sustainable',
    color: '#FD9D24',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Skyline of modern sustainable buildings with windows -->
      <path d="M12 34V20h8v14h-8zM22 34V14h9v20h-9zM33 34V24h6v10h-6z" fill="currentColor"/>
      <circle cx="16" cy="24" r="1" fill="#FD9D24"/>
      <circle cx="16" cy="28" r="1" fill="#FD9D24"/>
      <circle cx="26" cy="18" r="1" fill="#FD9D24"/>
      <circle cx="26" cy="22" r="1" fill="#FD9D24"/>
      <circle cx="26" cy="26" r="1" fill="#FD9D24"/>
    `
  },
  {
    id: 12,
    numberStr: '12',
    titleEn: 'RESPONSIBLE CONSUMPTION AND PRODUCTION',
    titleTh: 'การบริโภคและการผลิตที่รับผิดชอบ',
    descTh: 'สร้างหลักประกันให้มีรูปแบบการบริโภคและการผลิตที่ยั่งยืน ลดการสูญเสียทรัพยากร',
    descEn: 'Ensure sustainable consumption and production patterns',
    color: '#BF8B2E',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Infinity loop made with directional arrows -->
      <path d="M17 19c-4 0-7 3-7 6s3 6 7 6c5 0 7-6 7-6s2 6 7 6c4 0 7-3 7-6s-3-6-7-6c-5 0-7 6-7 6s-2-6-7-6z" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
      <path d="M12 21l3-2-1 4" fill="currentColor"/>
      <path d="M35 29l-3 2 1-4" fill="currentColor"/>
    `
  },
  {
    id: 13,
    numberStr: '13',
    titleEn: 'CLIMATE ACTION',
    titleTh: 'การรับมือการเปลี่ยนแปลงสภาพภูมิอากาศ',
    descTh: 'ดำเนินมาตรการเร่งด่วนเพื่อรับมือกับการเปลี่ยนแปลงสภาพภูมิอากาศและผลกระทบที่เกิดขึ้น',
    descEn: 'Take urgent action to combat climate change and its impacts',
    color: '#3F7E44',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Eye shape containing globe lines representing climate watching -->
      <path d="M8 25c5-8 11-10 16-10s11 2 16 10c-5 8-11 10-16 10s-11-2-16-10z" stroke="currentColor" stroke-width="2.5" fill="none"/>
      <circle cx="24" cy="25" r="5" fill="currentColor"/>
    `
  },
  {
    id: 14,
    numberStr: '14',
    titleEn: 'LIFE BELOW WATER',
    titleTh: 'ทรัพยากรทางทะเล',
    descTh: 'อนุรักษ์และใช้ประโยชน์จากมหาสมุทร ทะเล และทรัพยากรทางทะเลอย่างยั่งยืนเพื่อการพัฒนา',
    descEn: 'Conserve and sustainably use the oceans, seas and marine resources for sustainable development',
    color: '#0A97D9',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Fish swimming through water waves -->
      <path d="M17 22c5-3 10-2 15 2l3-3v8l-3-3c-5 4-10 5-15 2 2-3 2-3 0-6z" fill="currentColor"/>
      <circle cx="20" cy="22" r="1.2" fill="#0A97D9"/>
      <!-- Waves -->
      <path d="M10 31c3-1 6 1 9 0s6-1 9 0 6 1 9 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
      <path d="M12 35c3-1 6 1 9 0s6-1 9 0 6 1 9 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
    `
  },
  {
    id: 15,
    numberStr: '15',
    titleEn: 'LIFE ON LAND',
    titleTh: 'ระบบนิเวศบนบก',
    descTh: 'ปกป้อง ฟื้นฟู และส่งเสริมการใช้ประโยชน์จากระบบนิเวศบนบกอย่างยั่งยืน จัดการป่าไม้อย่างยั่งยืน',
    descEn: 'Protect, restore and promote sustainable use of terrestrial ecosystems, sustainably manage forests, combat desertification, and halt biodiversity loss',
    color: '#56C02B',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Forest tree standing on solid ground lines -->
      <path d="M24 13l-6 8h4l-5 8h14l-5-8h4l-6-8z" fill="currentColor"/>
      <path d="M22 29v6h4v-6" fill="currentColor"/>
      <path d="M12 36h24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
    `
  },
  {
    id: 16,
    numberStr: '16',
    titleEn: 'PEACE, JUSTICE AND STRONG INSTITUTIONS',
    titleTh: 'สังคมสงบสุข ยุติธรรม และสถาบันเข้มแข็ง',
    descTh: 'ส่งเสริมสังคมที่สงบสุขและครอบคลุมเพื่อการพัฒนาที่ยั่งยืน ให้ทุกคนเข้าถึงความยุติธรรม',
    descEn: 'Promote peaceful and inclusive societies for sustainable development, provide access to justice for all and build effective, accountable and inclusive institutions at all levels',
    color: '#00689D',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Dove of peace with olive branch and judge's gavel -->
      <path d="M14 26c3-5 8-8 13-7l4-4 2 2-3 4c3 3 2 8-2 11l-2-2-4 4-2-2 4-4c-4-1-7-1-8-2z" fill="currentColor"/>
      <!-- Gavel block -->
      <rect x="25" y="32" width="10" height="3" rx="1.5" fill="currentColor"/>
    `
  },
  {
    id: 17,
    numberStr: '17',
    titleEn: 'PARTNERSHIPS FOR THE GOALS',
    titleTh: 'ความร่วมมือเพื่อการพัฒนาที่ยั่งยืน',
    descTh: 'เสริมความเข้มแข็งของกลไกการดำเนินงาน และฟื้นฟูหุ้นส่วนความร่วมมือระดับโลกเพื่อการพัฒนาที่ยั่งยืน',
    descEn: 'Strengthen the means of implementation and revitalize the Global Partnership for Sustainable Development',
    color: '#19486A',
    textColor: '#FFFFFF',
    svgIcon: `
      <!-- Circular interconnected rings/flower of global partnership -->
      <circle cx="24" cy="18" r="4.5" stroke="currentColor" stroke-width="2" fill="none"/>
      <circle cx="29" cy="22" r="4.5" stroke="currentColor" stroke-width="2" fill="none"/>
      <circle cx="27" cy="28" r="4.5" stroke="currentColor" stroke-width="2" fill="none"/>
      <circle cx="21" cy="28" r="4.5" stroke="currentColor" stroke-width="2" fill="none"/>
      <circle cx="19" cy="22" r="4.5" stroke="currentColor" stroke-width="2" fill="none"/>
    `
  }
]

export const SDG_ACTIVITIES: SdgActivity[] = [
  {
    id: 1,
    sdgId: 1,
    titleTh: 'โครงการกองทุนสนับสนุนการศึกษาเพื่อนักศึกษาขาดแคลนทุนทรัพย์ คณะครุศาสตร์',
    titleEn: 'Educational Support Fund for Underprivileged Teacher Students',
    categoryTh: 'บริการวิชาการ & ทุนการศึกษา',
    categoryEn: 'Academic Service & Scholarships',
    date: '12 ก.พ. 2567',
    image: 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์ จัดพิธีมอบทุนการศึกษาแก่นักศึกษาครูที่มีความประพฤติดีแต่ขาดแคลนทุนทรัพย์ เพื่อลดความเหลื่อมล้ำและสร้างโอกาสทางการศึกษาอย่างเท่าเทียม',
    summaryEn: 'Faculty of Education provided financial grants and scholarships to dedicated education students in need to alleviate economic hardship.',
    author: 'ฝ่ายพัฒนานักศึกษา คณะครุศาสตร์'
  },
  {
    id: 2,
    sdgId: 1,
    titleTh: 'กิจกรรมครุอาสาพัฒนาชนบท: เสริมสร้างทักษะอาชีพแก่เยาวชนในชุมชนรอบมหาวิทยาลัย',
    titleEn: 'Volunteer Teaching & Community Vocational Skills Empowerment',
    categoryTh: 'กิจกรรมจิตอาสา',
    categoryEn: 'Volunteer Activity',
    date: '28 ม.ค. 2567',
    image: 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'นักศึกษาและคณาจารย์ร่วมลงพื้นที่จัดกิจกรรมเสริมสร้างทักษะพื้นฐานและแนะแนวการศึกษาต่อแก่นักเรียนในพื้นที่ห่างไกล เพื่อขจัดปัญหาความยากจนในระยะยาว',
    summaryEn: 'Student teachers conducted community tutoring and career development programs for youth in rural Chachoengsao communities.',
    author: 'สโมสรนักศึกษาคณะครุศาสตร์'
  },
  {
    id: 3,
    sdgId: 2,
    titleTh: 'โครงการส่งเสริมเกษตรอินทรีย์เพื่ออาหารกลางวันนักเรียนในโรงเรียน ตชด.',
    titleEn: 'Organic Farming for School Lunch in Border Patrol Police Schools',
    categoryTh: 'บริการวิชาการ',
    categoryEn: 'Academic Service',
    date: '5 มี.ค. 2567',
    image: 'https://images.unsplash.com/photo-1595974482597-4b8da8879bc5?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'บูรณาการองค์ความรู้ด้านการศึกษาและเกษตรกรรมเพื่อความมั่นคงทางอาหาร จัดอบรมคุณครูและนักเรียนในการปลูกผักปลอดสารพิษเพื่อโครงการอาหารกลางวัน',
    summaryEn: 'Promoting school gardens and sustainable food production for school lunches in local rural education centers.',
    author: 'สาขาวิชาวิทยาศาสตร์ทั่วไป'
  },
  {
    id: 4,
    sdgId: 3,
    titleTh: 'การตรวจสุขภาพจิตและส่งเสริมสุขภาวะทางใจของครูประจำการในศตวรรษที่ 21',
    titleEn: 'Mental Health and Well-being Care Program for In-service Teachers',
    categoryTh: 'งานวิจัย & พัฒนาครู',
    categoryEn: 'Research & Teacher Dev',
    date: '18 ก.พ. 2567',
    image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'อบรมเชิงปฏิบัติการการจัดการความเครียดและการสร้างบรรยากาศห้องเรียนแห่งความสุข เพื่อให้ครูและบุคลากรทางการศึกษามีสุขภาวะทางกายและใจที่ดี',
    summaryEn: 'Workshop on teacher stress management, mindfulness, and creating positive mental health classroom environments.',
    author: 'สาขาวิชาจิตวิทยาการศึกษา'
  },
  {
    id: 5,
    sdgId: 4,
    titleTh: 'โครงการพัฒนาสมรรถนะครูสู่การจัดการเรียนรู้เชิงรุก (Active Learning) ในยุคดิจิทัล',
    titleEn: 'Teacher Competency Enhancement in Digital Active Learning Pedagogy',
    categoryTh: 'การศึกษา & วิจัย',
    categoryEn: 'Education & Research',
    date: '10 มี.ค. 2567',
    image: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'คณะครุศาสตร์จัดอบรมเข้มข้นให้แก่ครูในจังหวัดฉะเชิงเทราและภาคตะวันออก เพื่อยกระดับคุณภาพการเรียนการสอน พัฒนาศักยภาพครูยุคใหม่สู่มาตรฐานสากล',
    summaryEn: 'Comprehensive workshops empowering Eastern region teachers with modern active learning strategies and AI educational tools.',
    author: 'ฝ่ายวิชาการและวิจัย คณะครุศาสตร์'
  },
  {
    id: 6,
    sdgId: 4,
    titleTh: 'การผลิตสื่อการสอนและนวัตกรรมการเรียนรู้สำหรับนักเรียนที่มีความต้องการพิเศษ',
    titleEn: 'Inclusive Educational Innovation for Special Needs Learners',
    categoryTh: 'นวัตกรรมทางการศึกษา',
    categoryEn: 'Educational Innovation',
    date: '22 ก.พ. 2567',
    image: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'สร้างสรรค์สื่อการสอนมัลติมีเดียและของเล่นเสริมทักษะสำหรับเด็กที่มีความบกพร่องทางการเรียนรู้ มุ่งสร้างความเท่าเทียมในห้องเรียน',
    summaryEn: 'Developing inclusive pedagogical toolkits ensuring equal access to quality education for learners with special needs.',
    author: 'สาขาวิชาการศึกษาปฐมวัย'
  },
  {
    id: 7,
    sdgId: 5,
    titleTh: 'การส่งเสริมบทบาทผู้นำสตรีในสถานศึกษาและรณรงค์ความเท่าเทียมทางเพศ',
    titleEn: 'Promoting Female Leadership in Education and Gender Parity',
    categoryTh: 'สัมมนาวิชาการ',
    categoryEn: 'Academic Seminar',
    date: '8 มี.ค. 2567',
    image: 'https://images.unsplash.com/photo-1531545514256-b1400bc00f31?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'เสวนาวิชาการเนื่องในวันสตรีสากล ส่งเสริมความเสมอภาคระหว่างเพศในองค์กรทางการศึกษาและหลักสูตรการเรียนรู้ที่ไม่เลือกปฏิบัติ',
    summaryEn: 'Panel discussion highlighting gender equality in administrative education positions and fair curriculum design.',
    author: 'คณะครุศาสตร์ มรภ.ราชนครินทร์'
  },
  {
    id: 8,
    sdgId: 6,
    titleTh: 'โครงการสุขอนามัยในโรงเรียน: การตรวจคุณภาพน้ำดื่มในสถานศึกษาเครือข่าย',
    titleEn: 'Clean Drinking Water Monitoring and Sanitation in Network Schools',
    categoryTh: 'บริการวิชาการ',
    categoryEn: 'Academic Service',
    date: '14 ม.ค. 2567',
    image: 'https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'คณาจารย์และนักศึกษาลงพื้นที่ตรวจวัดคุณภาพน้ำดื่มและปรับปรุงระบบกรองน้ำในโรงเรียนประถมศึกษาชุมชน เพื่อสุขอนามัยที่ดีของเยาวชน',
    summaryEn: 'Field testing drinking water safety and providing filtration maintenance for local primary schools.',
    author: 'สาขาวิชาวิทยาศาสตร์ทั่วไป'
  },
  {
    id: 9,
    sdgId: 7,
    titleTh: 'การนำพลังงานแสงอาทิตย์ (Solar Energy) มาใช้ในอาคารเรียนคณะครุศาสตร์',
    titleEn: 'Solar Rooftop Adoption and Clean Energy Efficiency in Faculty Buildings',
    categoryTh: 'การบริหารจัดการอาคาร',
    categoryEn: 'Facility Management',
    date: '3 ม.ค. 2567',
    image: 'https://images.unsplash.com/photo-1509391365360-2e959784a276?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'ติดตั้งแผงโซลาร์เซลล์บนดาดฟ้าอาคารเรียนคณะครุศาสตร์ ลดการปล่อยคาร์บอนและเป็นแหล่งเรียนรู้ด้านพลังงานหมุนเวียนสำหรับนักศึกษาครู',
    summaryEn: 'Solar PV installation across faculty buildings to foster renewable energy awareness and cut carbon emissions.',
    author: 'งานอาคารสถานที่และสิ่งแวดล้อม'
  },
  {
    id: 10,
    sdgId: 8,
    titleTh: 'โครงการบ่มเพาะทักษะวิชาชีพครูและการเตรียมพร้อมสู่ตลาดแรงงานการศึกษายุคใหม่',
    titleEn: 'Teacher Professional Readiness and Education Career Placement Forum',
    categoryTh: 'พัฒนานักศึกษา',
    categoryEn: 'Student Development',
    date: '19 ม.ค. 2567',
    image: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'เตรียมความพร้อมนักศึกษาครูชั้นปีที่ 4 สู่การสอบบรรจุครูผู้ช่วยและการประกอบวิชาชีพครูอย่างมีศักดิ์ศรีและมั่นคง',
    summaryEn: 'Career counseling, professional certification prep, and pedagogical competencies for graduating teacher students.',
    author: 'ฝ่ายฝึกประสบการณ์วิชาชีพครู'
  },
  {
    id: 11,
    sdgId: 11,
    titleTh: 'มหาวิทยาลัยราชภัฏเพื่อการพัฒนาท้องถิ่นและชุมชนเมืองน่าอยู่อย่างยั่งยืน',
    titleEn: 'Sustainable Urban and Local Community Development Project',
    categoryTh: 'พัฒนาท้องถิ่น',
    categoryEn: 'Local Community Dev',
    date: '25 ก.พ. 2567',
    image: 'https://images.unsplash.com/photo-1477959858617-67f30bc75b82?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'ส่งเสริมการมีส่วนร่วมของชุมชนรอบแม่น้ำบางปะกง ในการอนุรักษ์วัฒนธรรมท้องถิ่นและการสร้างพื้นที่การเรียนรู้สาธารณะ',
    summaryEn: 'Community-led revitalizing of cultural heritage and sustainable public spaces around the Bang Pakong river basin.',
    author: 'ศูนย์บริการวิชาการ คณะครุศาสตร์'
  },
  {
    id: 12,
    sdgId: 12,
    titleTh: 'โครงการ Green Faculty: รณรงค์ลดการใช้กระดาษและพลาสติกแบบใช้ครั้งเดียวทิ้ง',
    titleEn: 'Green Faculty Initiative: Zero Single-use Plastic and Paperless Workflows',
    categoryTh: 'สิ่งแวดล้อมและนโยบาย',
    categoryEn: 'Environment & Policy',
    date: '10 ม.ค. 2567',
    image: 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'ส่งเสริมการทำงานแบบ Paperless และการคัดแยกขยะอย่างถูกต้องเพื่อรีไซเคิล สร้างวัฒนธรรมการบริโภคที่รับผิดชอบในคณะครุศาสตร์',
    summaryEn: 'Transforming administrative workflows into paperless systems while championing comprehensive waste sorting campus-wide.',
    author: 'คณะกรรมการขับเคลื่อน Green Faculty'
  },
  {
    id: 13,
    sdgId: 13,
    titleTh: 'การปลูกป่าชายเลนและฟื้นฟูระบบนิเวศปากแม่น้ำบางปะกงเพื่อลดภาวะโลกร้อน',
    titleEn: 'Mangrove Reforestation and Bang Pakong Coastal Ecological Restoration',
    categoryTh: 'อนุรักษ์สิ่งแวดล้อม',
    categoryEn: 'Environmental Conservation',
    date: '17 ก.พ. 2567',
    image: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'นักศึกษาและอาจารย์คณะครุศาสตร์ร่วมกันปลูกป่าชายเลนกว่า 1,000 ต้น เพื่อเพิ่มพื้นที่ดูดซับคาร์บอนและปกป้องแนวชายฝั่งจากน้ำกัดเซาะ',
    summaryEn: 'Planting over 1,000 mangrove saplings to sequester atmospheric carbon and safeguard vulnerable coastal buffer zones.',
    author: 'สโมสรนักศึกษาคณะครุศาสตร์'
  },
  {
    id: 14,
    sdgId: 17,
    titleTh: 'การลงนามบันทึกความเข้าใจ (MOU) เครือข่ายพัฒนาครูร่วมกับโรงเรียนสังกัด สพฐ.',
    titleEn: 'MOU Signing with Basic Education Commission for Teacher Excellence Network',
    categoryTh: 'ความร่วมมือทางวิชาการ',
    categoryEn: 'Academic Partnership',
    date: '20 ม.ค. 2567',
    image: 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=800&auto=format&fit=crop',
    summaryTh: 'สร้างความร่วมมืออย่างยั่งยืนระหว่างคณะครุศาสตร์และโรงเรียนเครือข่ายฝึกประสบการณ์วิชาชีพครูกว่า 50 แห่งในเขตภาคตะวันออก',
    summaryEn: 'Forging strategic educational alliances with over 50 regional demonstration and partner schools to advance pedagogical training.',
    author: 'คณบดีคณะครุศาสตร์'
  }
]
