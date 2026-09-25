<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์ (EDU RRU) - RESTful API',
    description: "ระบบ RESTful API ครอบคลุมการทำงานของระบบสารสนเทศ เว็บไซต์หลัก และระบบบริหารจัดการหลังบ้าน (Backoffice) คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์\n\n### ฟังก์ชันหลักของระบบ:\n- **Dashboard & Stats:** สรุปจำนวนข่าวสาร บุคลากร สาขาวิชา จำนวนเข้าชม\n- **Posts & SDGs:** ข่าวสาร กิจกรรม บทความ เชื่อมโยงเป้าหมายการพัฒนาที่ยั่งยืน (SDGs 1-17)\n- **Categories:** บริหารจัดการหมวดหมู่ข่าวสารและสี Badge ประจำหมวดหมู่\n- **Personnel & Departments:** ฐานข้อมูลคณาจารย์ บุคลากร และสาขาวิชาประจำคณะครุศาสตร์\n- **Executives:** โครงสร้างผู้บริหารคณะ การจัดหมวดหมู่ และการจัดเรียงลำดับ\n- **Curricula:** หลักสูตรการศึกษา (ปริญญาตรี ค.บ., ประกาศนียบัตรบัณฑิต ป.บัณฑิต, ปริญญาโท ค.ม.)\n- **Regulations & Law:** ข้อบังคับ ระเบียบ ประกาศ คำสั่งมหาวิทยาลัย และหมวดหมู่กฎหมาย\n- **ITA:** ข้อมูลการประเมินคุณธรรมและความโปร่งใส (Integrity and Transparency Assessment)\n- **Carousel:** ภาพสไลด์แบนเนอร์ประชาสัมพันธ์หน้าแรก พร้อมลิงก์ปลายทาง\n- **Philosophy & Committee:** ปรัชญา วิสัยทัศน์ พันธกิจ และคณะกรรมการประจำคณะ\n- **Uploads:** ระบบจัดเก็บไฟล์ภาพและเอกสารแนบ (Storage Disk)",
    contact: new OA\Contact(
        name: 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
        url: 'https://edu.rru.ac.th',
        email: 'edu@rru.ac.th'
    )
)]
#[OA\Server(
    url: 'http://localhost:8000/api',
    description: 'Local Artisan Server (Default)'
)]
#[OA\Server(
    url: '/api',
    description: 'Current Domain Relative Endpoint'
)]
#[OA\Tag(name: 'Dashboard', description: 'ข้อมูลสรุปภาพรวมและสถิติของระบบ')]
#[OA\Tag(name: 'Posts', description: 'จัดการข่าวสาร กิจกรรม และเป้าหมาย SDGs')]
#[OA\Tag(name: 'Categories', description: 'จัดการหมวดหมู่ข่าวสารและสี Badge')]
#[OA\Tag(name: 'Personnel', description: 'จัดการข้อมูลอาจารย์และบุคลากร')]
#[OA\Tag(name: 'Departments', description: 'จัดการสาขาวิชาและหัวหน้าสาขา')]
#[OA\Tag(name: 'Executives', description: 'จัดการโครงสร้างผู้บริหารและหมวดหมู่ผู้บริหาร')]
#[OA\Tag(name: 'Curricula', description: 'จัดการหลักสูตรการศึกษาของคณะครุศาสตร์')]
#[OA\Tag(name: 'Regulations', description: 'จัดการข้อบังคับ ระเบียบ ประกาศ และหมวดหมู่กฎหมาย')]
#[OA\Tag(name: 'ITA', description: 'จัดการข้อมูลการประเมินคุณธรรมและความโปร่งใส (ITA) รายปี')]
#[OA\Tag(name: 'Carousel', description: 'จัดการภาพแบนเนอร์สไลด์หน้าแรก (Carousel Banner)')]
#[OA\Tag(name: 'Philosophy', description: 'จัดการปรัชญา วิสัยทัศน์ และพันธกิจ')]
#[OA\Tag(name: 'Committee', description: 'จัดการคณะกรรมการประจำคณะ')]
#[OA\Tag(name: 'Uploads', description: 'อัปโหลดไฟล์ รูปภาพ และเอกสาร')]
class OpenApiDoc
{
    // ==========================================
    // 1. DASHBOARD & STATS
    // ==========================================
    #[OA\Get(
        path: '/stats',
        summary: 'ดึงข้อมูลสถิติภาพรวมสำหรับแดชบอร์ด (Dashboard Stats)',
        description: 'ส่งกลับยอดรวมข่าวสาร, คณาจารย์, สาขาวิชา, ยอดเข้าชม, ข่าวสารล่าสุด, บุคลากรล่าสุด และการกระจายตัวของข่าวสารตามหมวดหมู่',
        tags: ['Dashboard'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'ดึงข้อมูลสถิติสำเร็จ',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'total_posts', type: 'integer', example: 12),
                        new OA\Property(property: 'total_personnel', type: 'integer', example: 45),
                        new OA\Property(property: 'departments_count', type: 'integer', example: 8),
                        new OA\Property(property: 'featured_posts', type: 'integer', example: 3),
                        new OA\Property(property: 'total_views', type: 'integer', example: 1250),
                        new OA\Property(property: 'total_curricula', type: 'integer', example: 11),
                        new OA\Property(property: 'total_carousels', type: 'integer', example: 3),
                        new OA\Property(property: 'total_regulations', type: 'integer', example: 12),
                        new OA\Property(property: 'total_ita', type: 'integer', example: 24),
                        new OA\Property(property: 'recent_posts', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'recent_personnel', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'top_viewed_posts', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(
                            property: 'categories_stats',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'category', type: 'string', example: 'ข่าวประชาสัมพันธ์'),
                                    new OA\Property(property: 'count', type: 'integer', example: 7)
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'sdgs_stats',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'sdg_id', type: 'integer', example: 4),
                                    new OA\Property(property: 'count', type: 'integer', example: 5)
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'department_personnel_stats',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'slug', type: 'string', example: 'early-childhood'),
                                    new OA\Property(property: 'name', type: 'string', example: 'สาขาวิชาการศึกษาปฐมวัย'),
                                    new OA\Property(property: 'count', type: 'integer', example: 4)
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'academic_titles_stats',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'academic_title', type: 'string', example: 'ผู้ช่วยศาสตราจารย์'),
                                    new OA\Property(property: 'count', type: 'integer', example: 11)
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'curricula_stats',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'degree_level', type: 'string', example: 'bachelor'),
                                    new OA\Property(property: 'count', type: 'integer', example: 8)
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]
    public function getStats() {}

    // ==========================================
    // 2. POSTS & SDGs
    // ==========================================
    #[OA\Get(
        path: '/posts',
        summary: 'ดึงรายการข่าวสารทั้งหมด (พร้อม Pagination และตัวกรอง)',
        description: 'รองรับการกรองตามหมวดหมู่, การค้นหาคำสำคัญ, การกรองตามเป้าหมาย SDG (1-17) และการแบ่งหน้า',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(name: 'category', in: 'query', description: 'กรองตามชื่อหมวดหมู่ เช่น ข่าวประชาสัมพันธ์, วิชาการ', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'sdg', in: 'query', description: 'กรองข่าวที่สอดคล้องกับเป้าหมาย SDG (ระบุหมายเลข 1 ถึง 17)', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 17, example: 4)),
            new OA\Parameter(name: 'q', in: 'query', description: 'ค้นหาคำสำคัญในชื่อข่าวหรือเนื้อหา', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', description: 'เลขหน้า (Pagination)', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'จำนวนรายการต่อหน้า (ค่าเริ่มต้น 15)', required: false, schema: new OA\Schema(type: 'integer', default: 15)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'ดึงข้อมูลข่าวสารสำเร็จ')
        ]
    )]
    public function getPosts() {}

    #[OA\Get(
        path: '/posts/{id}',
        summary: 'ดูรายละเอียดข่าวสารตาม ID (พร้อมนับยอดเข้าชม +1)',
        description: 'ส่งกลับข้อมูลข่าวสารฉบับเต็ม รวมถึงเนื้อหา HTML, ภาพปก, ภาพแกลเลอรี, ไฟล์แนบ, และรายการ SDGs ที่สอดคล้อง',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', description: 'รหัสข่าวสาร (Post ID)', required: true, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        responses: [
            new OA\Response(response: 200, description: 'พบข้อมูลข่าวสาร'),
            new OA\Response(response: 404, description: 'ไม่พบข่าวสารที่ระบุ')
        ]
    )]
    public function getPostById() {}

    #[OA\Post(
        path: '/posts',
        summary: 'สร้างข่าวสารใหม่',
        description: 'สร้างข่าวสาร/บทความ พร้อมรองรับการกำหนดเป้าหมาย SDGs (อาร์เรย์ของเลข 1-17), ภาพหน้าปก, ภาพแกลเลอรี และไฟล์แนบ',
        tags: ['Posts'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'category'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'เปิดรับสมัครนิสิตใหม่ระดับปริญญาตรี รอบ Portfolio TCAS69'),
                    new OA\Property(property: 'desc', type: 'string', example: 'คณะครุศาสตร์ มรภ.ราชนครินทร์ เปิดรับสมัครนิสิตใหม่ 8 สาขาวิชาเอก'),
                    new OA\Property(property: 'content', type: 'string', example: '<p>รายละเอียดการรับสมัคร...</p>'),
                    new OA\Property(property: 'category', type: 'string', example: 'ข่าวประชาสัมพันธ์'),
                    new OA\Property(property: 'category_badge_class', type: 'string', example: 'bg-slate-600 text-white'),
                    new OA\Property(property: 'thumbnail', type: 'string', example: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644'),
                    new OA\Property(property: 'gallery', type: 'array', items: new OA\Items(type: 'string')),
                    new OA\Property(property: 'key_highlights', type: 'array', items: new OA\Items(type: 'string'), example: ['หลักสูตรผ่านการรับรองจากคุรุสภา', 'มีทุนการศึกษา']),
                    new OA\Property(property: 'quote', type: 'object', properties: [
                        new OA\Property(property: 'text', type: 'string', example: 'มุ่งมั่นสร้างครูดี มีคุณธรรม นำการศึกษา'),
                        new OA\Property(property: 'by', type: 'string', example: 'คณบดีคณะครุศาสตร์')
                    ]),
                    new OA\Property(property: 'tags', type: 'array', items: new OA\Items(type: 'string'), example: ['TCAS69', 'รับสมัครนิสิต']),
                    new OA\Property(property: 'sdgs', type: 'array', items: new OA\Items(type: 'integer'), description: 'เป้าหมาย SDGs ที่สอดคล้อง เช่น [1, 4]', example: [1, 4]),
                    new OA\Property(property: 'featured', type: 'boolean', example: true),
                    new OA\Property(property: 'read_time', type: 'string', example: '3 นาที'),
                    new OA\Property(property: 'date', type: 'string', example: '25 กันยายน 2569'),
                    new OA\Property(property: 'author_name', type: 'string', example: 'ฝ่ายประชาสัมพันธ์ คณะครุศาสตร์'),
                    new OA\Property(property: 'author_role', type: 'string', example: 'คณะครุศาสตร์ มรภ.ราชนครินทร์'),
                    new OA\Property(property: 'attachments', type: 'array', items: new OA\Items(type: 'object'))
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'สร้างข่าวสารสำเร็จ'),
            new OA\Response(response: 422, description: 'ข้อมูลที่ส่งมาไม่ถูกต้อง (Validation Error)')
        ]
    )]
    public function createPost() {}

    #[OA\Put(
        path: '/posts/{id}',
        summary: 'แก้ไขข้อมูลข่าวสาร',
        description: 'อัปเดตข้อมูลข่าวสารตาม ID ที่ระบุ',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [
            new OA\Response(response: 200, description: 'อัปเดตข่าวสารสำเร็จ'),
            new OA\Response(response: 404, description: 'ไม่พบข่าวสาร')
        ]
    )]
    public function updatePost() {}

    #[OA\Delete(
        path: '/posts/{id}',
        summary: 'ลบข่าวสาร',
        description: 'ลบข่าวสารออกจากระบบอย่างถาวร',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'ลบข่าวสารสำเร็จ')
        ]
    )]
    public function deletePost() {}

    // ==========================================
    // 3. CATEGORIES
    // ==========================================
    #[OA\Get(
        path: '/categories',
        summary: 'ดึงรายการหมวดหมู่ข่าวสารทั้งหมด',
        description: 'ส่งกลับหมวดหมู่ทั้งหมด เรียงตาม sort_order เพื่อนำไปแสดงผลทั้งใน Admin และแท็บในหน้าแรก',
        tags: ['Categories'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'ดึงรายการหมวดหมู่สำเร็จ',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'ข่าวประชาสัมพันธ์'),
                            new OA\Property(property: 'slug', type: 'string', example: 'news'),
                            new OA\Property(property: 'badge_class', type: 'string', example: 'bg-slate-600 text-white'),
                            new OA\Property(property: 'color_hex', type: 'string', example: '#475569'),
                            new OA\Property(property: 'sort_order', type: 'integer', example: 1)
                        ]
                    )
                )
            )
        ]
    )]
    public function getCategories() {}

    #[OA\Post(
        path: '/categories',
        summary: 'สร้างหมวดหมู่ข่าวสารใหม่',
        tags: ['Categories'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'บริการวิชาการ'),
                    new OA\Property(property: 'badge_class', type: 'string', example: 'bg-emerald-600 text-white'),
                    new OA\Property(property: 'color_hex', type: 'string', example: '#059669'),
                    new OA\Property(property: 'sort_order', type: 'integer', example: 4)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'สร้างหมวดหมู่สำเร็จ')
        ]
    )]
    public function createCategory() {}

    #[OA\Put(
        path: '/categories/{id}',
        summary: 'แก้ไขหมวดหมู่ข่าวสาร',
        tags: ['Categories'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตหมวดหมู่สำเร็จ')]
    )]
    public function updateCategory() {}

    #[OA\Delete(
        path: '/categories/{id}',
        summary: 'ลบหมวดหมู่ข่าวสาร',
        tags: ['Categories'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบหมวดหมู่สำเร็จ')]
    )]
    public function deleteCategory() {}

    // ==========================================
    // 4. PERSONNEL & DEPARTMENTS
    // ==========================================
    #[OA\Get(
        path: '/personnel',
        summary: 'ดึงรายชื่ออาจารย์และบุคลากรทั้งหมด',
        description: 'รองรับการกรองตามสาขาวิชา และการค้นหาชื่อ-นามสกุล',
        tags: ['Personnel'],
        parameters: [
            new OA\Parameter(name: 'department_id', in: 'query', description: 'กรองตาม ID สาขาวิชา', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'search', in: 'query', description: 'ค้นหาชื่อ อีเมล หรือตำแหน่ง', required: false, schema: new OA\Schema(type: 'string'))
        ],
        responses: [new OA\Response(response: 200, description: 'ดึงรายชื่อบุคลากรสำเร็จ')]
    )]
    public function getPersonnel() {}

    #[OA\Get(
        path: '/personnel/{id}',
        summary: 'ดูข้อมูลอาจารย์/บุคลากรรายบุคคล',
        tags: ['Personnel'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'พบข้อมูล')]
    )]
    public function getPersonnelById() {}

    #[OA\Post(
        path: '/personnel',
        summary: 'เพิ่มข้อมูลอาจารย์/บุคลากรใหม่',
        tags: ['Personnel'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'department_id'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'ผศ.ดร.สมชาย ใจดี'),
                    new OA\Property(property: 'position', type: 'string', example: 'ประธานสาขาวิชาการศึกษาปฐมวัย'),
                    new OA\Property(property: 'academic_rank', type: 'string', example: 'ผู้ช่วยศาสตราจารย์ ดร.'),
                    new OA\Property(property: 'department_id', type: 'integer', example: 1),
                    new OA\Property(property: 'email', type: 'string', example: 'somchai@rru.ac.th'),
                    new OA\Property(property: 'phone', type: 'string', example: '038-500000 ต่อ 1234'),
                    new OA\Property(property: 'avatar', type: 'string', example: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb'),
                    new OA\Property(property: 'education', type: 'array', items: new OA\Items(type: 'string')),
                    new OA\Property(property: 'expertise', type: 'array', items: new OA\Items(type: 'string'))
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'เพิ่มข้อมูลบุคลากรสำเร็จ')]
    )]
    public function createPersonnel() {}

    #[OA\Post(
        path: '/personnel/reorder',
        summary: 'จัดเรียงลำดับอาจารย์/บุคลากรในสาขา',
        tags: ['Personnel'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'order', type: 'array', items: new OA\Items(type: 'integer'), example: [3, 1, 2, 4])
                ]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'จัดเรียงสำเร็จ')]
    )]
    public function reorderPersonnel() {}

    #[OA\Put(
        path: '/personnel/{id}',
        summary: 'แก้ไขข้อมูลอาจารย์/บุคลากร',
        tags: ['Personnel'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตสำเร็จ')]
    )]
    public function updatePersonnel() {}

    #[OA\Delete(
        path: '/personnel/{id}',
        summary: 'ลบข้อมูลอาจารย์/บุคลากร',
        tags: ['Personnel'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบสำเร็จ')]
    )]
    public function deletePersonnel() {}

    // ==========================================
    // 5. DEPARTMENTS
    // ==========================================
    #[OA\Get(
        path: '/departments',
        summary: 'ดึงรายชื่อสาขาวิชาทั้งหมด',
        tags: ['Departments'],
        responses: [new OA\Response(response: 200, description: 'ดึงรายการสาขาสำเร็จ')]
    )]
    public function getDepartments() {}

    #[OA\Post(
        path: '/departments',
        summary: 'สร้างสาขาวิชาใหม่',
        tags: ['Departments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'สาขาวิชาการศึกษาปฐมวัย'),
                    new OA\Property(property: 'name_en', type: 'string', example: 'Early Childhood Education'),
                    new OA\Property(property: 'description', type: 'string', example: 'ผลิตบัณฑิตครูปฐมวัยที่มีสมรรถนะสูง')
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'สร้างสาขาวิชาสำเร็จ')]
    )]
    public function createDepartment() {}

    #[OA\Post(
        path: '/departments/reorder',
        summary: 'จัดเรียงลำดับสาขาวิชา',
        tags: ['Departments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [new OA\Property(property: 'order', type: 'array', items: new OA\Items(type: 'integer'))]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'จัดเรียงสำเร็จ')]
    )]
    public function reorderDepartments() {}

    #[OA\Post(
        path: '/departments/{id}/set-head',
        summary: 'กำหนดหัวหน้าสาขาวิชา',
        tags: ['Departments'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['personnel_id'],
                properties: [new OA\Property(property: 'personnel_id', type: 'integer', example: 5)]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'กำหนดหัวหน้าสาขาสำเร็จ')]
    )]
    public function setDepartmentHead() {}

    #[OA\Put(
        path: '/departments/{id}',
        summary: 'แก้ไขข้อมูลสาขาวิชา',
        tags: ['Departments'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตสาขาสำเร็จ')]
    )]
    public function updateDepartment() {}

    #[OA\Delete(
        path: '/departments/{id}',
        summary: 'ลบสาขาวิชา',
        tags: ['Departments'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบสาขาสำเร็จ')]
    )]
    public function deleteDepartment() {}

    // ==========================================
    // 6. EXECUTIVES (ผู้บริหาร)
    // ==========================================
    #[OA\Get(
        path: '/executives',
        summary: 'ดึงข้อมูลโครงสร้างผู้บริหารคณะทั้งหมด (แบ่งตามหมวดหมู่)',
        description: 'ส่งกลับหมวดหมู่ผู้บริหาร (เช่น คณบดี, รองคณบดี, ผู้ช่วยคณบดี) พร้อมรายชื่อผู้บริหารและข้อมูลอาจารย์ที่เชื่อมโยง',
        tags: ['Executives'],
        responses: [new OA\Response(response: 200, description: 'ดึงโครงสร้างผู้บริหารสำเร็จ')]
    )]
    public function getExecutives() {}

    #[OA\Post(
        path: '/executive-categories',
        summary: 'สร้างหมวดหมู่ผู้บริหารใหม่ (เช่น รองคณบดี)',
        tags: ['Executives'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'รองคณบดี'),
                    new OA\Property(property: 'name_en', type: 'string', example: 'Associate Dean'),
                    new OA\Property(property: 'sort_order', type: 'integer', example: 2)
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'สร้างหมวดหมู่สำเร็จ')]
    )]
    public function createExecutiveCategory() {}

    #[OA\Post(
        path: '/executive-categories/reorder',
        summary: 'จัดเรียงลำดับหมวดหมู่ผู้บริหาร',
        tags: ['Executives'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [new OA\Property(property: 'order', type: 'array', items: new OA\Items(type: 'integer'))]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'จัดเรียงหมวดหมู่สำเร็จ')]
    )]
    public function reorderExecutiveCategories() {}

    #[OA\Put(
        path: '/executive-categories/{id}',
        summary: 'แก้ไขหมวดหมู่ผู้บริหาร',
        tags: ['Executives'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตสำเร็จ')]
    )]
    public function updateExecutiveCategory() {}

    #[OA\Delete(
        path: '/executive-categories/{id}',
        summary: 'ลบหมวดหมู่ผู้บริหาร',
        tags: ['Executives'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบสำเร็จ')]
    )]
    public function deleteExecutiveCategory() {}

    #[OA\Post(
        path: '/executive-members',
        summary: 'เพิ่มผู้บริหารเข้าในหมวดหมู่',
        tags: ['Executives'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['category_id', 'position_title'],
                properties: [
                    new OA\Property(property: 'category_id', type: 'integer', example: 2),
                    new OA\Property(property: 'personnel_id', type: 'integer', nullable: true, example: 5),
                    new OA\Property(property: 'custom_name', type: 'string', nullable: true, example: 'ผศ.ดร.วิชัย พัฒนา'),
                    new OA\Property(property: 'position_title', type: 'string', example: 'รองคณบดีฝ่ายวิชาการและวิจัย')
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'เพิ่มผู้บริหารสำเร็จ')]
    )]
    public function createExecutiveMember() {}

    #[OA\Post(
        path: '/executive-members/reorder',
        summary: 'จัดเรียงลำดับผู้บริหารในหมวดหมู่',
        tags: ['Executives'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [new OA\Property(property: 'order', type: 'array', items: new OA\Items(type: 'integer'))]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'จัดเรียงผู้บริหารสำเร็จ')]
    )]
    public function reorderExecutiveMembers() {}

    #[OA\Put(
        path: '/executive-members/{id}',
        summary: 'แก้ไขข้อมูลผู้บริหาร',
        tags: ['Executives'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตผู้บริหารสำเร็จ')]
    )]
    public function updateExecutiveMember() {}

    #[OA\Delete(
        path: '/executive-members/{id}',
        summary: 'ลบผู้บริหาร',
        tags: ['Executives'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบผู้บริหารสำเร็จ')]
    )]
    public function deleteExecutiveMember() {}

    // ==========================================
    // 7. CURRICULA (หลักสูตร)
    // ==========================================
    #[OA\Get(
        path: '/curricula',
        summary: 'ดึงรายการหลักสูตรการศึกษาทั้งหมด',
        description: 'รองรับการกรองตามระดับปริญญา เช่น bachelor (ป.ตรี), grad-diploma (ป.บัณฑิต), master (ป.โท)',
        tags: ['Curricula'],
        parameters: [
            new OA\Parameter(name: 'degree', in: 'query', description: 'ระดับปริญญา (bachelor, grad-diploma, master)', required: false, schema: new OA\Schema(type: 'string', enum: ['bachelor', 'grad-diploma', 'master'])),
            new OA\Parameter(name: 'active_only', in: 'query', description: 'แสดงเฉพาะหลักสูตรที่เปิดรับสมัคร (1 หรือ 0)', required: false, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        responses: [new OA\Response(response: 200, description: 'ดึงรายการหลักสูตรสำเร็จ')]
    )]
    public function getCurricula() {}

    #[OA\Get(
        path: '/curricula/{idOrSlug}',
        summary: 'ดูรายละเอียดหลักสูตรตาม ID หรือ Slug',
        tags: ['Curricula'],
        parameters: [new OA\Parameter(name: 'idOrSlug', in: 'path', required: true, schema: new OA\Schema(type: 'string', example: 'early-childhood'))],
        responses: [new OA\Response(response: 200, description: 'พบข้อมูลหลักสูตร')]
    )]
    public function getCurriculumBySlug() {}

    #[OA\Post(
        path: '/curricula',
        summary: 'สร้างหลักสูตรใหม่',
        tags: ['Curricula'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title_th', 'degree_level'],
                properties: [
                    new OA\Property(property: 'title_th', type: 'string', example: 'หลักสูตรครุศาสตรบัณฑิต สาขาวิชาการศึกษาปฐมวัย'),
                    new OA\Property(property: 'title_en', type: 'string', example: 'Bachelor of Education in Early Childhood Education'),
                    new OA\Property(property: 'degree_level', type: 'string', enum: ['bachelor', 'grad-diploma', 'master'], example: 'bachelor'),
                    new OA\Property(property: 'total_credits', type: 'string', example: '136 หน่วยกิต'),
                    new OA\Property(property: 'duration_years', type: 'string', example: '4 ปี'),
                    new OA\Property(property: 'image_url', type: 'string', example: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b')
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'สร้างหลักสูตรสำเร็จ')]
    )]
    public function createCurriculum() {}

    #[OA\Post(
        path: '/curricula/reorder',
        summary: 'จัดเรียงลำดับหลักสูตร',
        tags: ['Curricula'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [new OA\Property(property: 'order', type: 'array', items: new OA\Items(type: 'integer'))]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'จัดเรียงหลักสูตรสำเร็จ')]
    )]
    public function reorderCurricula() {}

    #[OA\Put(
        path: '/curricula/{id}',
        summary: 'แก้ไขข้อมูลหลักสูตร',
        tags: ['Curricula'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตหลักสูตรสำเร็จ')]
    )]
    public function updateCurriculum() {}

    #[OA\Delete(
        path: '/curricula/{id}',
        summary: 'ลบหลักสูตร',
        tags: ['Curricula'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบหลักสูตรสำเร็จ')]
    )]
    public function deleteCurriculum() {}

    // ==========================================
    // 8. REGULATIONS & LAW
    // ==========================================
    #[OA\Get(
        path: '/regulations',
        summary: 'ดึงรายการระเบียบ ข้อบังคับ ประกาศ และกฎหมาย',
        tags: ['Regulations'],
        parameters: [
            new OA\Parameter(name: 'category', in: 'query', description: 'รหัสหมวดหมู่กฎหมาย เช่น act, regulation, announcement', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'year', in: 'query', description: 'ปี พ.ศ. เช่น 2567', required: false, schema: new OA\Schema(type: 'string'))
        ],
        responses: [new OA\Response(response: 200, description: 'ดึงข้อมูลสำเร็จ')]
    )]
    public function getRegulations() {}

    #[OA\Post(
        path: '/regulations',
        summary: 'เพิ่มระเบียบ/ข้อบังคับใหม่',
        tags: ['Regulations'],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 201, description: 'สร้างระเบียบสำเร็จ')]
    )]
    public function createRegulation() {}

    #[OA\Put(
        path: '/regulations/{id}',
        summary: 'แก้ไขระเบียบ/ข้อบังคับ',
        tags: ['Regulations'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตสำเร็จ')]
    )]
    public function updateRegulation() {}

    #[OA\Delete(
        path: '/regulations/{id}',
        summary: 'ลบระเบียบ/ข้อบังคับ',
        tags: ['Regulations'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบสำเร็จ')]
    )]
    public function deleteRegulation() {}

    #[OA\Get(
        path: '/regulation-categories',
        summary: 'ดึงหมวดหมู่ข้อบังคับและระเบียบ',
        tags: ['Regulations'],
        responses: [new OA\Response(response: 200, description: 'ดึงหมวดหมู่สำเร็จ')]
    )]
    public function getRegulationCategories() {}

    #[OA\Post(
        path: '/regulation-categories',
        summary: 'สร้างหมวดหมู่ข้อบังคับใหม่',
        tags: ['Regulations'],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 201, description: 'สร้างหมวดหมู่สำเร็จ')]
    )]
    public function createRegulationCategory() {}

    #[OA\Put(
        path: '/regulation-categories/{id}',
        summary: 'แก้ไขหมวดหมู่ข้อบังคับ',
        tags: ['Regulations'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตสำเร็จ')]
    )]
    public function updateRegulationCategory() {}

    #[OA\Delete(
        path: '/regulation-categories/{id}',
        summary: 'ลบหมวดหมู่ข้อบังคับ',
        tags: ['Regulations'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบสำเร็จ')]
    )]
    public function deleteRegulationCategory() {}

    // ==========================================
    // 9. ITA (Integrity & Transparency Assessment)
    // ==========================================
    #[OA\Get(
        path: '/ita',
        summary: 'ดึงข้อมูลการประเมิน ITA ตามปีงบประมาณ',
        tags: ['ITA'],
        parameters: [
            new OA\Parameter(name: 'year', in: 'query', description: 'ปี พ.ศ. ของ ITA เช่น 2567', required: false, schema: new OA\Schema(type: 'string', example: '2567'))
        ],
        responses: [new OA\Response(response: 200, description: 'ดึงข้อมูล ITA สำเร็จ')]
    )]
    public function getIta() {}

    #[OA\Post(
        path: '/ita',
        summary: 'เพิ่มรายการการประเมิน ITA',
        tags: ['ITA'],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 201, description: 'เพิ่มรายการ ITA สำเร็จ')]
    )]
    public function createIta() {}

    #[OA\Post(
        path: '/ita/reorder',
        summary: 'จัดเรียงลำดับรายการ ITA',
        tags: ['ITA'],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'จัดเรียงสำเร็จ')]
    )]
    public function reorderIta() {}

    #[OA\Put(
        path: '/ita/{id}',
        summary: 'แก้ไขรายการ ITA',
        tags: ['ITA'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตสำเร็จ')]
    )]
    public function updateIta() {}

    #[OA\Delete(
        path: '/ita/{id}',
        summary: 'ลบรายการ ITA',
        tags: ['ITA'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบสำเร็จ')]
    )]
    public function deleteIta() {}

    #[OA\Get(
        path: '/ita-years',
        summary: 'ดึงรายการปีงบประมาณที่มีข้อมูล ITA',
        tags: ['ITA'],
        responses: [new OA\Response(response: 200, description: 'ดึงรายการปีสำเร็จ')]
    )]
    public function getItaYears() {}

    #[OA\Post(
        path: '/ita-years',
        summary: 'เพิ่มปีงบประมาณ ITA',
        tags: ['ITA'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['year'],
                properties: [new OA\Property(property: 'year', type: 'string', example: '2568')]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'เพิ่มปีสำเร็จ')]
    )]
    public function createItaYear() {}

    #[OA\Put(
        path: '/ita-years/{id}',
        summary: 'แก้ไขปีงบประมาณ ITA',
        tags: ['ITA'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตสำเร็จ')]
    )]
    public function updateItaYear() {}

    #[OA\Delete(
        path: '/ita-years/{id}',
        summary: 'ลบปีงบประมาณ ITA',
        tags: ['ITA'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบสำเร็จ')]
    )]
    public function deleteItaYear() {}

    // ==========================================
    // 10. CAROUSEL SLIDES
    // ==========================================
    #[OA\Get(
        path: '/carousel-slides',
        summary: 'ดึงภาพสไลด์แบนเนอร์หน้าแรก (Carousel Banner)',
        description: 'รองรับการดึงเฉพาะสไลด์ที่เปิดใช้งาน (active_only=1) เพื่อแสดงผลใน Hero Carousel',
        tags: ['Carousel'],
        parameters: [
            new OA\Parameter(name: 'active_only', in: 'query', description: '1 เพื่อดึงเฉพาะภาพที่เปิดใช้งาน', required: false, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        responses: [new OA\Response(response: 200, description: 'ดึงรายการภาพสไลด์สำเร็จ')]
    )]
    public function getCarouselSlides() {}

    #[OA\Post(
        path: '/carousel-slides',
        summary: 'เพิ่มภาพแบนเนอร์สไลด์ใหม่',
        tags: ['Carousel'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['image_url'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'ประชาสัมพันธ์กิจกรรมครุศาสตร์'),
                    new OA\Property(property: 'image_url', type: 'string', example: 'https://edu.rru.ac.th/banner1.jpg'),
                    new OA\Property(property: 'link_url', type: 'string', nullable: true, example: 'https://www.facebook.com/edurru'),
                    new OA\Property(property: 'target', type: 'string', enum: ['_self', '_blank'], default: '_self', example: '_blank'),
                    new OA\Property(property: 'sort_order', type: 'integer', example: 1),
                    new OA\Property(property: 'is_active', type: 'boolean', default: true)
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'เพิ่มภาพสไลด์สำเร็จ')]
    )]
    public function createCarouselSlide() {}

    #[OA\Post(
        path: '/carousel-slides/reorder',
        summary: 'จัดเรียงลำดับภาพสไลด์แบนเนอร์',
        tags: ['Carousel'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [new OA\Property(property: 'order', type: 'array', items: new OA\Items(type: 'integer'))]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'จัดเรียงสำเร็จ')]
    )]
    public function reorderCarouselSlides() {}

    #[OA\Put(
        path: '/carousel-slides/{id}',
        summary: 'แก้ไขภาพแบนเนอร์สไลด์',
        tags: ['Carousel'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตสำเร็จ')]
    )]
    public function updateCarouselSlide() {}

    #[OA\Delete(
        path: '/carousel-slides/{id}',
        summary: 'ลบภาพแบนเนอร์สไลด์',
        tags: ['Carousel'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบสำเร็จ')]
    )]
    public function deleteCarouselSlide() {}

    // ==========================================
    // 11. PHILOSOPHY & VISION
    // ==========================================
    #[OA\Get(
        path: '/philosophy',
        summary: 'ดึงข้อมูลปรัชญา วิสัยทัศน์ เอกลักษณ์ อัตลักษณ์ และพันธกิจ',
        tags: ['Philosophy'],
        responses: [new OA\Response(response: 200, description: 'ดึงข้อมูลสำเร็จ')]
    )]
    public function getPhilosophy() {}

    #[OA\Post(
        path: '/philosophy',
        summary: 'อัปเดตข้อมูลปรัชญา วิสัยทัศน์ และอัตลักษณ์',
        tags: ['Philosophy'],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตสำเร็จ')]
    )]
    public function updatePhilosophy() {}

    #[OA\Post(
        path: '/philosophy/missions',
        summary: 'เพิ่มพันธกิจของคณะครุศาสตร์',
        tags: ['Philosophy'],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 201, description: 'เพิ่มพันธกิจสำเร็จ')]
    )]
    public function storeMission() {}

    #[OA\Put(
        path: '/philosophy/missions/{id}',
        summary: 'แก้ไขพันธกิจ',
        tags: ['Philosophy'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตสำเร็จ')]
    )]
    public function updateMission() {}

    #[OA\Delete(
        path: '/philosophy/missions/{id}',
        summary: 'ลบพันธกิจ',
        tags: ['Philosophy'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบสำเร็จ')]
    )]
    public function destroyMission() {}

    // ==========================================
    // 12. COMMITTEE MEMBERS
    // ==========================================
    #[OA\Get(
        path: '/committee-members',
        summary: 'ดึงรายชื่อคณะกรรมการประจำคณะครุศาสตร์',
        tags: ['Committee'],
        responses: [new OA\Response(response: 200, description: 'ดึงรายชื่อสำเร็จ')]
    )]
    public function getCommitteeMembers() {}

    #[OA\Post(
        path: '/committee-members',
        summary: 'เพิ่มคณะกรรมการประจำคณะ',
        tags: ['Committee'],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 201, description: 'เพิ่มคณะกรรมการสำเร็จ')]
    )]
    public function createCommitteeMember() {}

    #[OA\Post(
        path: '/committee-members/reorder',
        summary: 'จัดเรียงลำดับคณะกรรมการ',
        tags: ['Committee'],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'จัดเรียงสำเร็จ')]
    )]
    public function reorderCommitteeMembers() {}

    #[OA\Put(
        path: '/committee-members/{id}',
        summary: 'แก้ไขข้อมูลคณะกรรมการ',
        tags: ['Committee'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [new OA\Response(response: 200, description: 'อัปเดตสำเร็จ')]
    )]
    public function updateCommitteeMember() {}

    #[OA\Delete(
        path: '/committee-members/{id}',
        summary: 'ลบคณะกรรมการ',
        tags: ['Committee'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'ลบสำเร็จ')]
    )]
    public function deleteCommitteeMember() {}

    // ==========================================
    // 13. UPLOADS (รูปภาพและเอกสาร)
    // ==========================================
    #[OA\Post(
        path: '/upload',
        summary: 'อัปโหลดไฟล์ รูปภาพ หรือเอกสารแนบ PDF',
        description: 'รองรับไฟล์ภาพ (JPG, PNG, WebP) และไฟล์เอกสาร (PDF, DOCX) ขนาดไม่เกิน 20MB โดยส่งในรูปแบบ multipart/form-data',
        tags: ['Uploads'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['file'],
                    properties: [
                        new OA\Property(property: 'file', type: 'string', format: 'binary', description: 'ไฟล์ที่ต้องการอัปโหลด')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'อัปโหลดไฟล์สำเร็จ ส่งกลับ URL และชื่อไฟล์',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'url', type: 'string', example: 'http://localhost:8000/storage/uploads/example.png'),
                        new OA\Property(property: 'name', type: 'string', example: 'example.png'),
                        new OA\Property(property: 'size', type: 'string', example: '1.2 MB')
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'ไฟล์ไม่ถูกต้องหรือขนาดเกินกำหนด')
        ]
    )]
    public function uploadFile() {}
}
