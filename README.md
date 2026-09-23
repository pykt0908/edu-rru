# คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์ (EDU RRU)
Faculty of Education, Rajabhat Rajanagarindra University

ระบบเว็บไซต์คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์ พัฒนาด้วย Vue 3, Vite, Vuetify 3 และ Tailwind CSS พร้อมมาตรฐาน Responsive Web Design

## โครงสร้างโปรเจกต์ (Project Structure)

```
edu/
├── frontend/          # แอปพลิเคชันเว็บส่วนหน้า (Vue 3 + Vite + Vuetify 3)
│   ├── src/
│   │   ├── assets/    # รูปภาพ โลโก้ ฟอนต์ LINE Seed Sans TH
│   │   ├── components/# คอมโพเนนต์ UI (Navbar, Footer, Hero, BentoGrid ฯลฯ)
│   │   ├── views/     # หน้าเว็บหลักและเมนูย่อย
│   │   ├── router/    # เส้นทางระบบ (Vue Router)
│   │   └── App.vue    # รูทคอมโพเนนต์และแถบนำทาง
│   └── package.json
├── backend/           # เซิร์ฟเวอร์ส่วนหลัง (API)
├── rule.md            # มาตรฐานการออกแบบและข้อกำหนดระบบ (Design Rules)
└── README.md
```

## วิธีการติดตั้งและรันโปรเจกต์ (Getting Started)

### ติดตั้ง Dependencies
```bash
cd frontend
npm install
```

### รันเซิร์ฟเวอร์สำหรับพัฒนา (Development Server)
```bash
npm run dev
```

### บิลด์สำหรับ Production
```bash
npm run build
```
