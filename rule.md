# Responsive Mobile Design Guidelines & System Rules
คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์ (EDU RRU)

เอกสารระเบียบและมาตรฐานการออกแบบ (Design & Coding Rules) เพื่อรองรับการแสดงผลแบบ Responsive บนอุปกรณ์พกพา (Mobile), แท็บเล็ต (Tablet) และเดสก์ท็อป (Desktop) สำหรับระบบเว็บแอปพลิเคชัน

---

## 1. หลักการออกแบบพื้นฐาน (Core Principles)

- **Mobile-First Approach**: ออกแบบและทดสอบจากหน้าจอขนาดเล็กก่อนเสมอ (ตั้งแต่ขนาด 360px ขึ้นไป) แล้วค่อยขยายสู่จอขนาดใหญ่
- **Touch-Friendly Interface**: องค์ประกอบที่ผู้ใช้สามารถแตะหรือกดได้ (Buttons, Links, Inputs) ต้องมีขนาดพื้นที่แตะ (Touch Target) ขั้นต่ำไม่น้อยกว่า **44px × 44px** ตามมาตรฐาน WCAG 2.1
- **Fluid & Scalable Layout**: ห้ามใช้ความกว้างแบบฟิกซ์พิกเซล (Fixed width) ที่อาจทำให้เกิดการเลื่อนแนวนอน (Horizontal Overflow) บนหน้าจอมือถือ
- **Fast & Lightweight**: จัดการทรัพยากร รูปภาพ ฟอนต์ และ CSS ให้อัปโหลดเร็ว ใช้งานไอคอนระบบจาก SVG/MDI และฟอนต์มาตรฐานโลคอล (LINE Seed Sans TH)

---

## 2. Breakpoints & Viewport Grid

ระบบอ้างอิงตาม Tailwind CSS Breakpoints มาตรฐาน:

| Breakpoint | ขนาดความกว้าง (Width) | กลุ่มอุปกรณ์เป้าหมาย |
| :--- | :--- | :--- |
| **Default (Mobile)** | `< 640px` | สมาร์ทโฟนทั่วไป (iPhone, Android) |
| **`sm`** | `≥ 640px` | สมาร์ทโฟนขนาดใหญ่ / หน้าจอแนวนอน |
| **`md`** | `≥ 768px` | แท็บเล็ตแนวตั้ง (iPad, Android Tablet) |
| **`lg`** | `≥ 1024px` | แล็ปท็อป และแท็บเล็ตแนวนอน |
| **`xl`** | `≥ 1280px` | เดสก์ท็อปจอมาตรฐาน |
| **`2xl`** | `≥ 1536px` | จอคอมพิวเตอร์ขนาดใหญ่ |

### กฎการจัดการความกว้างและ Padding:
- Container หลักต้องใช้ `max-w-6xl` หรือ `max-w-7xl` พร้อมกำหนด `mx-auto`
- Padding ขอบจอ:
  - Mobile: `px-4` (16px) หรือ `px-3` (12px บนจอเล็กพิเศษ)
  - Tablet: `sm:px-6` (24px)
  - Desktop: `lg:px-8` (32px)
- ควบคุม `overflow-x: hidden` ที่ระดับ `html` และ `body` เพื่อป้องกันหน้าเว็บเลื่อนหลุดขอบข้าง

### 2.1 กฎความสูง Carousel สำหรับแท็บเล็ตและหน้าจอแนวตั้ง (iPad Pro 13"):
- **ห้ามใช้ `height: calc(100vh - 5rem)` กับ Breakpoint `lg: (1024px)` เด็ดขาด** เนื่องจากหน้าจอ iPad Pro 13" ในแนวตั้ง (Portrait) มีความสูงถึง 1366px ซึ่งจะทำให้ Carousel สูงถึง 1286px เกิดช่องว่างสีขาวขนาดใหญ่ (Blank gap) ก่อนถึง Hero Section
- กำหนดความสูง Carousel ให้สัมพันธ์กับสัดส่วนภาพ Banner (1920x800):
  - Mobile (`< 640px`): `240px`
  - Tablet (`640px - 1023px`): `360px`
  - iPad Pro 13" Portrait (`1024px - 1279px`): `460px`
  - iPad Pro 13" Landscape / Laptops (`1280px - 1535px`): `540px`
  - Desktops (`≥ 1536px`): `620px`

---

## 3. Typography & ฟอนต์ตัวอักษร

- ฟอนต์หลักของระบบคือ **LINE Seed Sans TH** เท่านั้น
- ลำดับการกำหนดฟอนต์ใน CSS: `'LINE Seed Sans TH', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif`
- **ลำดับขนาดตัวอักษร (Responsive Font Scaling)**:
  - หัวเรื่องหลัก (H1 / Hero Title): `text-2xl sm:text-4xl md:text-5xl`
  - หัวเรื่องหน้ารอง (Page Heading): `text-2xl sm:text-3xl`
  - หัวข้อย่อยในการ์ด (Card Heading): `text-base sm:text-lg font-semibold`
  - เนื้อหาทั่วไป (Body Text): `text-sm sm:text-base text-slate-600`
  - คำบรรยายประกอบ (Caption / Subtext): `text-xs sm:text-sm text-slate-500`
  - แท็กไลน์และข้อความขนาดเล็ก: `text-[10px] sm:text-xs`
- ตัวหนังสือภาษาไทยต้องกำหนด `leading-normal` หรือ `leading-relaxed` เสมอ เพื่อให้อ่านง่าย สระบน-ล่างไม่ชนกัน

---

## 4. แถบนำทางและเมนู (Navbar & Navigation Rules)

### 4.1 แถบส่วนหัว (Header)
- กำหนดความสูงแบบยืดหยุ่น: `h-18 sm:h-20` เพื่อรองรับขนาดโลโก้ที่ชัดเจน
- ความกว้าง Container:
  - **ก่อนเลื่อนจอ (ก่อนย่อเป็น Navpill)**: ตัวกล่อง Navbar ด้านนอกขยายเต็มหน้าจอ แต่ **Container ด้านในต้องกำหนดความกว้างให้ตรงกับ Container เนื้อหาของระบบเสมอ (`max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`)** เพื่อให้โลโก้ทางซ้ายและเมนูทางขวาเรียงตรงเป็นระเบียบแนวเดียวกับเนื้อหาหลัก
- ใช้ `sticky top-0 z-40` พร้อมพื้นหลัง `bg-white/95 backdrop-blur-md`
- โลโก้แบรนด์:
  - Mobile: `w-11 h-11` (44px)
  - Tablet: `sm:w-14 sm:h-14` (56px)
  - Desktop: `lg:w-16 lg:h-16` (64px)
- **การป้องกันชื่อเว็บตกบรรทัด (Desktop No-Wrap Rule)**:
  - ชื่อคณะและ Tagline บนเดสก์ท็อปต้องกำหนด `whitespace-nowrap` และหุ้มด้วย `shrink-0` เสมอ เพื่อห้ามไม่ให้ชื่อมหาวิทยาลัยหรือคำว่า "นครินทร์" ตกบรรทัดเด็ดขาด
  - เมนูเดสก์ท็อปต้องใช้ `whitespace-nowrap` ร่วมกับ `px-2 xl:px-3` และ `gap-0.5 xl:gap-1.5` เพื่อให้จัดวางได้พอดีและสวยงามบนทุกขนาดหน้าจอคอมพิวเตอร์


### 4.2 การแสดงผลเมนู (Desktop vs Mobile)
- **Desktop (`≥ 1024px`)**:
  - แสดงผลเมนูแนวนอน (`hidden lg:flex`)
  - **ห้ามมีกล่องสีพื้นหลัง (Background) ทั้งในสถานะ Hover และ Active**
  - Hover: เปลี่ยนสีข้อความและไอคอนเป็นสีเขียว (`text-emerald-700`)
  - Active: ตัวหนังสือสีเขียวเข้มและหนา (`text-emerald-700 font-semibold`) โดยไม่ต้องมีเส้นขีดล่าง (No Underline) เพื่อความเรียบหรูและสะอาดตา
- **Mobile (`< 1024px`)**:
  - ซ่อนเมนูแนวนอน และแสดงปุ่มเมนู Hamburger (`mdi-menu`) ทางขวามือ
  - เมื่อกดจะเปิด Navigation Drawer ขนาดกว้าง `300px` จากฝั่งขวา
  - รายการเมนูใน Drawer ต้องมีความสูงอย่างน้อย `min-h-[44px]`
  - ปิด Background Overlay ของ Vuetify บนรายการเมนู เพื่อให้สะอาดตาและสอดคล้องกับเวอร์ชัน Desktop

### 4.3 แถบนำทางลอยตัวเมื่อเลื่อนลง (Sticky Floating Pill Navbar on Scroll)
- **การตรึงตำแหน่ง (Sticky/Fixed Positioning)**:
  - ใช้ `fixed top-0 inset-x-0 z-50` พร้อมตัวเว้นวรรคส่วนหัว (Header Spacer) ขนาด `h-18 sm:h-20` เพื่อการันตีว่า Navbar จะติดอยู่ด้านบนจอเสมอ (Always Sticky) ตลอดการเลื่อนหน้าจอ และไม่เกิด Layout Shift หรือถูกซ่อนด้วยบั๊ก `overflow` ของ Parent
  - ใช้ `overflow-x: clip` ในระดับ `html, body` แทน `overflow-x: hidden` เพื่อไม่ให้รบกวน Scroll Context ของเบราว์เซอร์
- **พฤติกรรมการเปลี่ยนรูปทรง (Pill Transformation)**:
  - เมื่อเลื่อนหน้าจอลง (`window.scrollY > 40px`):
    - แถบ Navbar จะเปลี่ยนสถานะเป็นทรงกระบอกแคปซูลลอยตัว (**Pill Shape**) ติดอยู่บนสุดของจอ
    - ลอยตรงกลางด้านบน (`max-w-5xl xl:max-w-6xl mx-auto rounded-full`)
    - ใช้พื้นหลังโปร่งแสงและเงาลึก (`bg-white/92 backdrop-blur-xl border border-slate-200/90 shadow-xl shadow-slate-900/10`)
    - ความสูงกระชับลงเหลือ `h-14 sm:h-16` และโลโก้ปรับลงเป็น `w-9 sm:w-11`
  - **การจัดการเส้นขอบป้องกันเส้นดำกระพริบ (No-Border Transition Rule)**:
    - บนแท็ก `<header>` ด้านนอกสุด ต้องกำหนด **`border-0` เสมอ และห้ามใส่ `border-b`** เพื่อป้องกันไม่ให้เบราว์เซอร์ทำการ Interpolate สีขอบผ่านค่า `currentColor` (สีดำ) ขณะที่กำลังย่อ/ขยายตัวเข้าสู่รูปทรง Pill
    - ตัวเส้นขอบให้ย้ายไปกำหนดที่กล่อง Container ด้านใน โดยใช้ `border` ร่วมกับ `border-transparent` ในสถานะปกติ และเปลี่ยนเป็น `border-slate-200/90` ในสถานะลอยตัว ทำให้เส้นขอบค่อยๆ เฟดเนียนตา 100% ปราศจากเส้นสีดำกระพริบ
  - เมื่อเลื่อนกลับขึ้นด้านบนสุด (`window.scrollY <= 40px`):
    - ขยายกลับเป็นแถบเต็มจอปกติอย่างราบรื่น (Smooth Transition 300ms) โดย Container ด้านในคงรูปตาม `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8` ไร้เส้นขอบสีดำ

### 4.4 เมนูแบบ Megamenu ("เกี่ยวกับคณะ")
- **Layout & Structure**:
  - เมนู "เกี่ยวกับคณะ" ใช้รูปแบบ **2-Column Megamenu** (`w-[720px] max-w-[calc(100vw-2rem)] rounded-2xl shadow-2xl`)
  - **คอลัมน์ 1 (ข้อมูลและโครงสร้างคณะ)**: ประวัติคณะ, ปรัชญาและวิสัยทัศน์, คณะผู้บริหาร, คณะกรรมการคณะครุศาสตร์, การประเมิน ITA, ข้อกฎหมายสำหรับบุคลากร พร้อมไอคอนและคำอธิบายย่อ
  - **คอลัมน์ 2 (หลักสูตรที่เปิดสอน)**: ปริญญาตรี, ประกาศนียบัตรบัณฑิตวิชาชีพครู, ปริญญาโท (ไม่มีจำนวนปีต่อท้าย) พร้อมไอคอนและคำอธิบายย่อ
  - **Hover Bridge**: มีพื้นที่รองรับการลากเมาส์ข้ามจากปุ่มเมนูไปยังตัวการ์ดโดยไม่กระตุกหรือหลุดออกจากสถานะ Hover
  - **แถบส่วนท้าย (Footer bar)**: แสดงตราคณะและปุ่มทางลัด "ติดต่อสอบถาม"

---

## 5. แบนเนอร์สไลด์เต็มหน้าจอ (Fullscreen Hero Carousel)

- หน้าแรกของระบบมีแบนเนอร์ภาพสไลด์แบบเต็มหน้าจอ (Fullscreen Hero Banner Carousel):
  - **ความสูงแบบ Responsive (Responsive Banner Height)**:
    - **Mobile (`< 640px`)**: กำหนดความสูงกระชับพอดีที่ **`240px`** (เพื่อไม่ให้ภาพแนวนอน 1920x800 ถูกบีบหรือเหลือพื้นที่ว่างสีขาวขนาดใหญ่บนหน้าจอมือถือ)
    - **Tablet (`640px - 1024px`)**: กำหนดความสูง **`380px`**
    - **Desktop (`≥ 1024px`)**: กำหนดความสูงเต็มจอ **`calc(100vh - 5rem)`**
  - มีระบบสลับภาพอัตโนมัติ (`cycle`, `interval="5000"`)
  - **ซ่อนปุ่มนำทาง (Hide Delimiters)**: กำหนด `hide-delimiters` เพื่อไม่ให้มีจุดกลมบอกตำแหน่งบดบังภาพแบนเนอร์
  - **ปุ่มลูกศรแบบธรรมชาติ (Natural Glassmorphic Arrows)**:
    - ปุ่มวงกลมโปร่งแสงแบบมินิมอล (`bg-black/30 hover:bg-black/55 text-white backdrop-blur-md rounded-full`)
    - ไอคอน Chevron ขนาดพอดี (`mdi-chevron-left`, `mdi-chevron-right`) พร้อมแอนิเมชันขยับเบาๆ เมื่อ Hover (`translate-x`)
  - **รูปแบบเนื้อหาในสไลด์ (Pure Banner / Image Placeholders)**:
    - ไม่ใส่ UI หรือโค้ด HTML ข้อความ/ปุ่ม ทับซ้อนในสไลด์ (No UI overlays) ให้เป็นรูปภาพแบนเนอร์เต็มจอล้วนๆ
    - ใช้รูปภาพ Placeholder ตรงๆ ผ่าน URL (เช่น `https://placehold.co/1920x800/...`) พร้อมคุณสมบัติ `cover` เพื่อให้สลับเป็นไฟล์ภาพจริงได้ทันทีโดยการเปลี่ยน URL ภาพใน array `slides`
    - กำหนด `m-0 p-0` และ `leading-none` อย่างเคร่งครัดเพื่อไม่ให้เกิดช่องว่างขาวด้านล่าง Carousel
    - **การปิดการทำงานของ `.v-responsive__sizer`**: Vuetify จะแอบคำนวณ Aspect Ratio ของภาพ Placeholder (เช่น 1920x800 = `padding-bottom: 41.6667%`) และสร้างกล่อง `.v-responsive__sizer` ดันความสูงไว้ ทำให้เกิดพื้นที่ว่างสีขาวขนาดใหญ่ใต้ภาพจนถึงขอบล่างของ Carousel ดังนั้น **ต้องกำหนด `display: none !important; padding-bottom: 0 !important;` ให้กับ `.v-responsive__sizer` และบังคับ `.v-carousel-item, .v-responsive, .v-img` ให้เป็น `height: 100% !important;` เสมอ** เพื่อให้รูปขยายเต็มพื้นที่ `calc(100vh - 5rem)` อย่างแท้จริง 100%



---

## 6. ส่วนแนะนำหลักแบบไทโปรกราฟิกขนาดใหญ่ (Big Typography Faculty Hero Section)

- สไตล์ Hero Section ถัดจากแบนเนอร์ Carousel:
  - **การจัดวางชิด Carousel (Flush Spacing)**: ด้านบนของ Hero Section จัดวางชิดติดกับ Carousel ด้านบน (`pt-0`, `items-start`, `justify-start`) เพื่อความต่อเนื่องทางสายตา ไม่มีช่องว่างขาวคั่น
  - **ข้อความนำ (Eyebrow)**: ใช้คำว่า `สร้างครูดี มีความรู้ สู่สังคม` (สีเขียวของคณะ `text-emerald-700 font-bold`)
  - **ตัวอักษรขนาดใหญ่พิเศษ (Massive Typography)**:
    - ตัวพิมพ์ใหญ่หนาพิเศษ สีดำ/เข้ม (`FACULTY`, `OF EDUCATION`) ขนาดสูงสุด `7.5rem` (`leading-[0.9]`) แบบเพียวๆ ไม่มีข้อความภาษาไทยทับซ้อน
  - **ลายน้ำพื้นหลังวิ่งแบบ Marquee (Infinite Running Watermark)**:
    - ใช้ข้อความชื่อเต็ม: `FACULTY OF EDUCATION RAJABHATRAJANAGARINDA •`
    - กำหนดแอนิเมชันวิ่งวนแนวนอนแบบไร้รอยต่อ (Seamless Infinite Marquee) ตลอดเวลาอย่างนุ่มนวล
  - **ตราสัญลักษณ์ฝั่งขวา (Right Column Logo)**:
    - แสดงเฉพาะตัวรูปภาพโลโก้คณะพร้อมเงา (`drop-shadow-xl`) **โดยไม่ต้องมีพื้นหลังวงกลมสีขาว** เพื่อความโปร่งโล่งและกลมกลืนกับพื้นผิว
    - คำโปรยวิสัยทัศน์/พันธกิจแบบกระชับ
    - ปุ่มวงกลมสีดำพร้อมลูกศรชี้ลง (`mdi-arrow-down`) สำหรับกด Scroll ไปยังส่วนเนื้อหาถัดไป (`#explore-content`) อย่างนุ่มนวล


---

## 7. ส่วนแสดงผลโพสต์ข่าวสารแบบเบนโตะกริด (Bento Grid Posts Section)

- ตำแหน่งการจัดวาง: วางต่อจาก `FacultyHeroSection` โดยตรงที่จุดเชื่อมต่อ `#explore-content`
- **โครงสร้างข้อมูลโพสต์มาตรฐาน (Standard Post Model)**:
  - แต่ละการ์ดโพสต์ประกอบด้วยองค์ประกอบมาตรฐานครบถ้วน:
    1. **Thumbnail**: ภาพหน้าปกข่าวสาร/กิจกรรมสัดส่วนชัดเจน พร้อมเอฟเฟกต์ซูมเนียนตาเมื่อนำเมาส์ไปชี้ (`group-hover:scale-105 transition-transform`)
    2. **Title**: หัวข้อข่าวสารตัวหนา อ่านง่าย (`line-clamp-2` พร้อมไฮไลต์สีเขียวเมื่อ Hover)
    3. **Desc / Excerpt**: เนื้อหาย่อสรุปของโพสต์ (`line-clamp-2` หรือ `line-clamp-3`)
    4. **Date & Metadata**: วันที่เผยแพร่ พร้อมไอคอนปฏิทิน ยอดเข้าชม (`views`) และเวลาที่ใช้ในการอ่าน (`readTime`)
    5. **Category Badge**: ป้ายหมวดหมู่ชัดเจน (เช่น ข่าวประชาสัมพันธ์, วิชาการ & วิจัย, กิจกรรมนิสิต, บริการวิชาการ)
    6. **Author**: รูปและชื่อฝ่าย/ผู้เผยแพร่โพสต์
- **การจัดวางแบบเบนโตะกริด (Asymmetrical Bento Layout)**:
  - **การ์ดข่าวเด่นหลัก (Featured Hero Post)**: ครอบคลุม 8 คอลัมน์ 2 แถว (`lg:col-span-8 lg:row-span-2`) แสดงภาพพื้นหลังเต็มใบพร้อม Gradient ดำซ้อนทับ หัวข้อและเนื้อหาขนาดใหญ่
  - **การ์ดข่าวขนาดกลาง (Spotlight Posts)**: ครอบคลุม 4 คอลัมน์ (`lg:col-span-4`) วางประกบด้านข้างและแถวล่าง
  - **ระบบกรองหมวดหมู่ (Category Filter Tabs)**: มีแท็บปุ่มด้านบนสำหรับเลือกดูข่าวตามหมวดหมู่ได้ทันที
  - **การรองรับ Mobile**: แปลงเป็น 1 คอลัมน์บนมือถือ (`grid-cols-1`) พร้อมขนาด Thumbnail ที่พอดีกับจอทัชสกรีน

---

## 8. ส่วนวิดีโอแนะนำคณะ (Faculty Video Section)

- **ตำแหน่งการจัดวาง**: วางต่อจากส่วน `BentoGridSection` โดยตรง
- **ขนาดและมิติที่กะทัดรัด (Compact & Balanced Sizing)**:
  - ขนาดความกว้างจำกัดไว้ที่ `max-w-5xl mx-auto` เพื่อคุมสัดส่วน 16:9 ให้ความสูงของตัววิดีโอลดลงพอดีกับระดับสายตา ไม่สูงเกินไปจนกินพื้นที่หน้าจอ (ลดความสูงลงจากจอ 1280px เดิม ~720px เหลือ ~576px)
  - ระยะขอบบน-ล่าง (Padding) ปรับให้กะทัดรัดลง: `py-10 sm:py-14 lg:py-16`
- **การจัดวางส่วนหัวแบบแยกซ้าย-ขวา (Split Header Layout)**:
  - **ฝั่งซ้าย (Left Column)**:
    - ป้าย Tagline: "FACULTY INTRODUCTION VIDEO"
    - หัวข้อหลักขนาดพอเหมาะ (`text-2xl sm:text-3xl lg:text-4xl font-black leading-[1.2]`): แนะนำคณะครุศาสตร์ มรภ.ราชนครินทร์
    - แถบดาวมาตรฐาน 5.0 ดาว: มาตรฐานวิชาชีพครูระดับดีเยี่ยม (คุรุสภา)
  - **ฝั่งขวา (Right Column)**:
    - คำบรรยายสรุปแนะนำวิสัยทัศน์และบรรยากาศการเรียนรู้ของคณะ
    - ปุ่มการกระทำ 2 ปุ่มวางคู่กัน:
      1. **ปุ่มสมัครเรียน**: ปุ่มทึบสีดำเข้ม (`bg-slate-950 hover:bg-emerald-700 text-white`) เชื่อมโยงสู่ระบบรับสมัคร
      2. **ปุ่มดูหลักสูตร**: ปุ่มมีเส้นขอบ (`border border-slate-300 hover:border-slate-800 text-slate-800`) พร้อมลูกศรชี้ขวา เชื่อมโยงสู่หน้าเกี่ยวกับคณะ/หลักสูตร
- **กล่องแสดงวิดีโอ (Video Player Box)**:
  - สัดส่วนมาตรฐาน 16:9 (`aspect-video`) ขอบโค้งมนสวยงาม (`rounded-2xl sm:rounded-3xl`) คุมขนาดให้อยู่ใน `max-w-5xl`
  - ฝัง YouTube Iframe (`https://www.youtube.com/embed/PaVLW-AxSg4?si=cqfRx6UPzfMcgzcl`) แบบทางการโดยตรง (Direct Official YouTube Embed) พร้อม `loading="lazy"`
  - แสดงภาพปกและปุ่มเล่นทางการของ YouTube ที่คมชัดจริงโดยตรง ไม่ใช้ภาพดึงภายนอก (ป้องกันปัญหาภาพสีเทา 404 ของ YouTube maxresdefault)
  - ผู้ใช้งานสามารถคลิกเล่นวิดีโอ ดูชื่อคลิป และปรับระดับเสียง/เต็มจอได้ทันทีอย่างราบรื่น

---

## 9. การ์ดและตารางแสดงข้อมูล (Card & Grid Rules)

- **Grid Responsive Pattern**:
  - 1 คอลัมน์บนมือถือ: `grid-cols-1`
  - 2 คอลัมน์บนแท็บเล็ต: `sm:grid-cols-2`
  - 3 หรือ 4 คอลัมน์บนหน้าจอใหญ่: `lg:grid-cols-3` หรือ `lg:grid-cols-4`
- การ์ดทุกใบต้องใช้ขอบมนที่สม่ำเสมอ (`rounded-xl` หรือ `rounded-2xl`) พร้อมขอบเรียบหรู `border border-slate-200`
- เอฟเฟกต์การเลื่อนเมาส์: ใช้ `hover:shadow-md` หรือ `hover:-translate-y-0.5` ร่วมกับ `transition-all duration-200`

---

## 10. ฟอร์มและปุ่มกด (Forms & Interaction Rules)

- ช่องกรอกข้อมูล (Text Field, Select, Textarea) ต้องขยายเต็มความกว้างในจอมือถือ (`w-full`)
- ปุ่มกด (Action Buttons):
  - บนมือถือ: ให้ขยายเต็มความกว้าง (`w-full sm:w-auto`) เพื่อให้ใช้นิ้วโป้งแตะได้สะดวก
  - ความสูงของปุ่มต้องไม่ต่ำกว่า 40px - 44px (`!min-h-[40px]` หรือ `!min-h-[44px]`)
- ปุ่มไอคอน (Icon Buttons) ต้องกำหนด `aria-label` ให้ครบถ้วนเพื่อรองรับ Accessibility

---

## 11. ประสบการณ์การสัมผัสและการตอบสนอง (Touch UX Optimization)

1. **เปิดใช้งาน `touch-action: manipulation`**: กำหนดให้กับ button, a, input, select, textarea ใน `style.css` เพื่อตัด Delay 300ms ของเบราว์เซอร์มือถือ
2. **ปิด Tap Highlight สีเทา/น้ำเงินของเบราว์เซอร์**: กำหนด `-webkit-tap-highlight-color: transparent` ในระดับ HTML เพื่อให้ความรู้สึกสมูทเหมือน Native App
3. **รูปภาพและสื่อ**: กำหนด `max-width: 100%` และ `height: auto` เสมอ ห้ามเกิดภาพล้นขอบจอ
4. **เบอร์โทรศัพท์และอีเมล**: ต้องใช้ลิงก์ `tel:` และ `mailto:` เพื่อให้ผู้ใช้สามารถกดโทรหรือส่งเมลได้ทันทีจากสมาร์ทโฟน

---

## 12. ระบบการเลื่อนหน้าจอแบบนุ่มนวล (Lenis Smooth Inertia Scrolling System)

- ติดตั้งและเปิดใช้งานไลบรารี **Lenis (Studio Freight)** ระดับสากลใน `App.vue`
- คุณสมบัติการเลื่อนแบบเนียนตา (Inertia Momentum):
  - ควบคุมการหมุนลูกกลิ้งเมาส์ (Smooth Wheel) และ Trackpad ให้มีความเฉื่อยที่นุ่มนวล (`duration: 1.2s`, `easing exponential`) ตัดการกระตุกเป็นสเต็ปแบบค่าเริ่มต้นของเบราว์เซอร์
  - ทำงานสอดประสานกับ `scrollBehavior` ใน Vue Router เพื่อให้การเปลี่ยนหน้าหรือเลื่อนหา Anchor ID เลื่อนอย่างนุ่มนวล
  - ปุ่มเลื่อนลง `#explore-content` ใน Hero Section สั่งการผ่าน `window.__lenis.scrollTo()` อย่างแม่นยำ ไม่กระตุกหรือกระโดดข้ามเฟรม

---

## 13. มาตรฐาน Page Hero Banner (PageHeroBanner Component)

ทุกหน้าย่อย (Inner Pages) ต้องใช้ Component กลาง `PageHeroBanner.vue` เป็น Hero Section บนสุดของหน้า **ห้ามใช้ Hero Block แบบ Inline ซ้ำในแต่ละ View**

### โครงสร้าง Component
- **ไฟล์**: `frontend/src/components/PageHeroBanner.vue`
- **Background**: `bg-slate-950` พร้อม Emerald glow blur และ subtle grid overlay
- **โลโก้**: แสดง `edu-logo-border-white.png` พร้อม glow ring เสมอ — วางทางขวา (Desktop) หรือซ้ายล่าง (Mobile)

### Props ที่รองรับ
| Prop | Type | ความหมาย |
|---|---|---|
| `badge` | `string` | ข้อความใน Badge Pill เล็กด้านบน |
| `badge-icon` | `string` | MDI icon สำหรับ Badge (เช่น `mdi-school-outline`) |
| `title` | `string` | หัวข้อหลัก (สีขาว) |
| `title-highlight` | `string` | ส่วนหัวข้อที่ไฮไลต์ (สีเขียว `emerald-400`) |
| `subtitle` | `string` | คำอธิบายย่อ (สีเทา `slate-400`) |

### การใช้งาน (Template Pattern)
```vue
<script setup lang="ts">
import PageHeroBanner from '@/components/PageHeroBanner.vue'
</script>

<template>
  <div class="min-h-screen bg-white">
    <PageHeroBanner
      badge="Page Badge"
      badge-icon="mdi-icon-name"
      title="หัวข้อ"
      title-highlight="ส่วนไฮไลต์"
      subtitle="คำอธิบายหน้า"
    />
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
      <!-- เนื้อหาหน้า -->
    </div>
  </div>
</template>
```

### หน้าที่ใช้งาน PageHeroBanner แล้ว
- `AboutView.vue` → ภาพรวมคณะ
- `FacultyHistoryView.vue` → ประวัติคณะ
- `FacultyPhilosophyView.vue` → ปรัชญา วิสัยทัศน์ อัตลักษณ์ พันธกิจ
- `FacultyManagementView.vue` → คณะผู้บริหาร
- `FacultyCommitteeView.vue` → คณะกรรมการคณะครุศาสตร์
- `FacultyItaView.vue` → การประเมินคุณธรรมและความโปร่งใส (ITA)
- `FacultyRegulationsView.vue` → ข้อกฎหมายสำหรับบุคลากร
- `CurriculumListView.vue` → รายการหลักสูตร (ปริญญาตรี, ป.บัณฑิต, ปริญญาโท)
- `CurriculumDetailView.vue` → รายละเอียดหลักสูตร (Data Science / สาขาวิชา)
- `SdgsView.vue` → SDGs
- `StudentServicesView.vue` → งานบริการนักศึกษา
- `PlanningView.vue` → งานวางแผน
- `StudentActivitiesView.vue` → งานกิจกรรมนักศึกษา
- `ContactView.vue` → ติดต่อเรา

---

## 14. มาตรฐานแกลเลอรีและหน้าต่างดูภาพขยาย (Gallery Strip & Lightbox Modal)

### 14.1 การแสดงผลแถบภาพด้านบน (Gallery Strip)
- จัดวางแบบ Grid 4 รูป (`grid-cols-2 md:grid-cols-4 gap-1 sm:gap-2`) อัตราส่วนภาพ `aspect-[4/3]`
- ขอบมน `rounded-lg` พร้อม Gradient Overlay ด้านล่าง และ Badge ระบุกิจกรรม
- เมื่อผู้ใช้คลิกที่รูปภาพหรือปุ่มขยาย (`mdi-fullscreen`) จะเปิด Lightbox ของรูปนั้นทันที

### 14.2 การแสดงผลหน้าต่างขยายภาพ (Lightbox Modal)
- **Overlay โปร่งแสง (Translucent Overlay)**: ใช้พื้นหลัง `bg-slate-950/65 backdrop-blur-xs` เพื่อให้ยังมองเห็นหน้าเว็บด้านหลังลางๆ ได้อย่างมีมิติ ไม่ทึบดำสนิท
- **การเลื่อนดูภาพ (Navigation)**:
  - มีปุ่มลูกศร ซ้าย-ขวา ทั้งในแถบภาพและใน Lightbox (`mdi-chevron-left`, `mdi-chevron-right`) พร้อม Hover Effect
  - รองรับการกดแป้นพิมพ์คีย์บอร์ด: ลูกศรซ้าย (ภาพก่อนหน้า), ลูกศรขวา (ภาพถัดไป), ปุ่ม Esc (ปิดหน้าต่าง)
  - มีแถบรูปตัวอย่าง (Thumbnails Bar) ด้านล่างพร้อมกรอบเน้นสีเขียว (`border-emerald-400 scale-105`) สำหรับรูปปัจจุบัน
  - แสดงตัวนับลำดับภาพชัดเจน เช่น `1 / 4` และชื่อรูปภาพ

