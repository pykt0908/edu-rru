<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curriculum;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CurriculumController extends Controller
{
    /**
     * Get list of curricula.
     */
    public function index(Request $request)
    {
        // Auto seed default curricula if table is empty
        if (Curriculum::count() === 0) {
            $this->seedDefaults();
        }

        $query = Curriculum::query();

        if ($request->filled('degree_level') && $request->degree_level !== 'all') {
            $query->where('degree_level', $request->degree_level);
        }

        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('title_en', 'like', "%{$s}%")
                  ->orWhere('degree_title', 'like', "%{$s}%")
                  ->orWhere('desc', 'like', "%{$s}%");
            });
        }

        $curricula = $query->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($curricula);
    }

    /**
     * Get a single curriculum by ID or slug.
     */
    public function show($idOrSlug)
    {
        $curriculum = is_numeric($idOrSlug)
            ? Curriculum::find($idOrSlug)
            : Curriculum::where('slug', $idOrSlug)->first();

        if (!$curriculum) {
            return response()->json(['message' => 'ไม่พบข้อมูลหลักสูตรที่ต้องการ'], 404);
        }

        return response()->json($curriculum);
    }

    /**
     * Create a new curriculum.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug'            => 'nullable|string|max:100|unique:curricula,slug',
            'title'           => 'required|string|max:255',
            'title_en'        => 'nullable|string|max:255',
            'degree_level'    => 'required|string|in:bachelor,grad-diploma,master,doctoral',
            'degree_title'    => 'required|string|max:255',
            'degree_title_en' => 'nullable|string|max:255',
            'duration'        => 'nullable|string|max:100',
            'credits'         => 'nullable|string|max:100',
            'desc'            => 'nullable|string',
            'image'           => 'nullable|string|max:500',
            'tags'            => 'nullable|array',
            'highlight'       => 'nullable|boolean',
            'department_id'   => 'nullable|string|max:100',
            'document_url'    => 'nullable|string|max:500',
            'detail_content'  => 'nullable|array',
            'sort_order'      => 'nullable|integer',
            'is_active'       => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $slugBase = Str::slug($validated['title_en'] ?? $validated['title']);
            if (empty($slugBase)) {
                $slugBase = 'curr-' . rand(100, 999);
            }
            $slug = $slugBase;
            $counter = 1;
            while (Curriculum::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $counter++;
            }
            $validated['slug'] = $slug;
        }

        if (!isset($validated['sort_order'])) {
            $maxOrder = Curriculum::where('degree_level', $validated['degree_level'])->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $curriculum = Curriculum::create($validated);

        return response()->json($curriculum, 201);
    }

    /**
     * Update a curriculum.
     */
    public function update(Request $request, $id)
    {
        $curriculum = Curriculum::findOrFail($id);

        $validated = $request->validate([
            'slug'            => 'sometimes|required|string|max:100|unique:curricula,slug,' . $id,
            'title'           => 'sometimes|required|string|max:255',
            'title_en'        => 'nullable|string|max:255',
            'degree_level'    => 'sometimes|required|string|in:bachelor,grad-diploma,master,doctoral',
            'degree_title'    => 'sometimes|required|string|max:255',
            'degree_title_en' => 'nullable|string|max:255',
            'duration'        => 'nullable|string|max:100',
            'credits'         => 'nullable|string|max:100',
            'desc'            => 'nullable|string',
            'image'           => 'nullable|string|max:500',
            'tags'            => 'nullable|array',
            'highlight'       => 'nullable|boolean',
            'department_id'   => 'nullable|string|max:100',
            'document_url'    => 'nullable|string|max:500',
            'detail_content'  => 'nullable|array',
            'sort_order'      => 'nullable|integer',
            'is_active'       => 'nullable|boolean',
        ]);

        $curriculum->update($validated);

        return response()->json($curriculum);
    }

    /**
     * Delete a curriculum.
     */
    public function destroy($id)
    {
        $curriculum = Curriculum::findOrFail($id);
        $curriculum->delete();

        return response()->json(['message' => 'ลบหลักสูตรเรียบร้อยแล้ว']);
    }

    /**
     * Reorder curricula.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'orders'                => 'required|array',
            'orders.*.id'           => 'required|integer|exists:curricula,id',
            'orders.*.sort_order'   => 'required|integer',
            'orders.*.degree_level' => 'nullable|string',
        ]);

        foreach ($validated['orders'] as $item) {
            $data = ['sort_order' => $item['sort_order']];
            if (!empty($item['degree_level'])) {
                $data['degree_level'] = $item['degree_level'];
            }
            Curriculum::where('id', $item['id'])->update($data);
        }

        return response()->json(['message' => 'จัดลำดับหลักสูตรสำเร็จ']);
    }

    /**
     * Seed initial default curricula with gorgeous images and metadata.
     */
    protected function seedDefaults(): void
    {
        $defaults = [
            // Bachelor's Degrees
            [
                'slug'          => 'datascience',
                'degree_level'  => 'bachelor',
                'title'         => 'สาขาวิชาวิทยาการข้อมูล',
                'title_en'      => 'Data Science',
                'degree_title'  => 'วิทยาศาสตรบัณฑิต (วท.บ.)',
                'duration'      => '4 ปี',
                'credits'       => '120 หน่วยกิต',
                'desc'          => 'มุ่งเน้นการสร้างนักวิทยาศาสตร์ข้อมูลและนักวิเคราะห์ข้อมูลที่มีทักษะการคำนวณขั้นสูง ผสานความรู้ด้านเทคโนโลยีและสารสนเทศเพื่อการพัฒนาการศึกษาและสังคม',
                'image'         => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80',
                'tags'          => ['Data Science', 'Machine Learning', 'Big Data'],
                'highlight'     => true,
                'sort_order'    => 1,
                'is_active'     => true,
            ],
            [
                'slug'          => 'early-childhood',
                'degree_level'  => 'bachelor',
                'title'         => 'สาขาวิชาการศึกษาปฐมวัย',
                'title_en'      => 'Early Childhood Education',
                'degree_title'  => 'ครุศาสตรบัณฑิต (ค.บ.)',
                'duration'      => '4 ปี',
                'credits'       => '132 หน่วยกิต',
                'desc'          => 'ผลิตครูปฐมวัยที่มีความรู้ลึกซึ้งด้านพัฒนาการเด็ก มีทักษะการจัดประสบการณ์การเรียนรู้ และจิตวิญญาณความเป็นครูอย่างมืออาชีพ',
                'image'         => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=800&q=80',
                'tags'          => ['จิตวิทยาเด็ก', 'สื่อปฐมวัย', 'นวัตกรรมการเรียนรู้'],
                'highlight'     => false,
                'sort_order'    => 2,
                'is_active'     => true,
            ],
            [
                'slug'          => 'elementary',
                'degree_level'  => 'bachelor',
                'title'         => 'สาขาวิชาการประถมศึกษา',
                'title_en'      => 'Elementary Education',
                'degree_title'  => 'ครุศาสตรบัณฑิต (ค.บ.)',
                'duration'      => '4 ปี',
                'credits'       => '132 หน่วยกิต',
                'desc'          => 'ผลิตครูประถมศึกษาที่มีความเชี่ยวชาญการจัดการเรียนรู้บูรณาการกลุ่มสาระต่างๆ พัฒนาทักษะพื้นฐานและคุณธรรมของผู้เรียนระดับประถม',
                'image'         => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=800&q=80',
                'tags'          => ['การจัดการเรียนรู้', 'จิตวิทยาครู', 'การวิจัยชั้นเรียน'],
                'highlight'     => false,
                'sort_order'    => 3,
                'is_active'     => true,
            ],
            [
                'slug'          => 'thai',
                'degree_level'  => 'bachelor',
                'title'         => 'สาขาวิชาภาษาไทย',
                'title_en'      => 'Thai Language',
                'degree_title'  => 'ครุศาสตรบัณฑิต (ค.บ.)',
                'duration'      => '4 ปี',
                'credits'       => '132 หน่วยกิต',
                'desc'          => 'สร้างครูภาษาไทยที่มีความเชี่ยวชาญด้านภาษา วรรณคดีไทย ศิลปะการสื่อสาร และการจัดการเรียนรู้ภาษาไทยอย่างสร้างสรรค์',
                'image'         => 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?auto=format&fit=crop&w=800&q=80',
                'tags'          => ['ภาษาไทย', 'วรรณคดี', 'วาทศาสตร์'],
                'highlight'     => false,
                'sort_order'    => 4,
                'is_active'     => true,
            ],
            [
                'slug'          => 'english',
                'degree_level'  => 'bachelor',
                'title'         => 'สาขาวิชาภาษาอังกฤษ',
                'title_en'      => 'English Education',
                'degree_title'  => 'ครุศาสตรบัณฑิต (ค.บ.)',
                'duration'      => '4 ปี',
                'credits'       => '132 หน่วยกิต',
                'desc'          => 'พัฒนาครูภาษาอังกฤษที่มีทักษะการสื่อสารระดับสากล เชี่ยวชาญการจัดการเรียนรู้ภาษาอังกฤษเป็นภาษาต่างประเทศตามมาตรฐาน CEFR',
                'image'         => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80',
                'tags'          => ['CEFR', 'English Teaching', 'Global Communication'],
                'highlight'     => false,
                'sort_order'    => 5,
                'is_active'     => true,
            ],
            [
                'slug'          => 'science',
                'degree_level'  => 'bachelor',
                'title'         => 'สาขาวิชาวิทยาศาสตร์ทั่วไป',
                'title_en'      => 'General Science Education',
                'degree_title'  => 'ครุศาสตรบัณฑิต (ค.บ.)',
                'duration'      => '4 ปี',
                'credits'       => '136 หน่วยกิต',
                'desc'          => 'มุ่งเน้นการจัดการเรียนรู้วิทยาศาสตร์เชิงสืบเสาะ การทดลอง และสะเต็มศึกษา (STEM Education) สร้างเสริมทักษะกระบวนการทางวิทยาศาสตร์',
                'image'         => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=800&q=80',
                'tags'          => ['STEM Education', 'การทดลอง', 'นวัตกรรมวิทย์'],
                'highlight'     => false,
                'sort_order'    => 6,
                'is_active'     => true,
            ],
            [
                'slug'          => 'mathematics',
                'degree_level'  => 'bachelor',
                'title'         => 'สาขาวิชาคณิตศาสตร์',
                'title_en'      => 'Mathematics Education',
                'degree_title'  => 'ครุศาสตรบัณฑิต (ค.บ.)',
                'duration'      => '4 ปี',
                'credits'       => '132 หน่วยกิต',
                'desc'          => 'พัฒนาครูคณิตศาสตร์ที่มีทักษะการคิดเชิงตรรกะ การแก้ปัญหา และการนำเทคโนโลยีมาประยุกต์สอนคณิตศาสตร์อย่างเข้าใจง่าย',
                'image'         => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?auto=format&fit=crop&w=800&q=80',
                'tags'          => ['Logic & Proof', 'สถิติประยุกต์', 'GeoGebra'],
                'highlight'     => false,
                'sort_order'    => 7,
                'is_active'     => true,
            ],
            [
                'slug'          => 'social-studies',
                'degree_level'  => 'bachelor',
                'title'         => 'สาขาวิชาสังคมศึกษา',
                'title_en'      => 'Social Studies Education',
                'degree_title'  => 'ครุศาสตรบัณฑิต (ค.บ.)',
                'duration'      => '4 ปี',
                'credits'       => '132 หน่วยกิต',
                'desc'          => 'สร้างครูสังคมศึกษาที่มีความรอบรู้ประวัติศาสตร์ ภูมิศาสตร์ เศรษฐศาสตร์ ศาสนา และความเป็นพลเมืองโลก',
                'image'         => 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?auto=format&fit=crop&w=800&q=80',
                'tags'          => ['ประวัติศาสตร์', 'ภูมิศาสตร์', 'ความเป็นพลเมือง'],
                'highlight'     => false,
                'sort_order'    => 8,
                'is_active'     => true,
            ],
            // Graduate Diploma
            [
                'slug'          => 'grad-dip-teaching',
                'degree_level'  => 'grad-diploma',
                'title'         => 'หลักสูตรประกาศนียบัตรบัณฑิต สาขาวิชาชีพครู',
                'title_en'      => 'Graduate Diploma in Teaching Profession',
                'degree_title'  => 'ประกาศนียบัตรบัณฑิตวิชาชีพครู (ป.บัณฑิต)',
                'duration'      => '1 ปี (3 ภาคการศึกษา)',
                'credits'       => '34 หน่วยกิต',
                'desc'          => 'หลักสูตรสำหรับผู้สำเร็จการศึกษาระดับปริญญาตรีทุกสาขาวิชาที่ต้องการพัฒนาสมรรถนะวิชาชีพครูตามมาตรฐานคุรุสภา พร้อมฝึกประสบการณ์วิชาชีพในสถานศึกษาจริง',
                'image'         => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80',
                'tags'          => ['มาตรฐานคุรุสภา', 'ฝึกสอนในโรงเรียน', 'วิชาชีพครู'],
                'highlight'     => true,
                'sort_order'    => 1,
                'is_active'     => true,
            ],
            // Master's Degrees
            [
                'slug'          => 'curriculum-instruction',
                'degree_level'  => 'master',
                'title'         => 'สาขาวิชาหลักสูตรและการสอน',
                'title_en'      => 'Curriculum and Instruction',
                'degree_title'  => 'ครุศาสตรมหาบัณฑิต (ค.ม.)',
                'duration'      => '2 ปี',
                'credits'       => '36 หน่วยกิต',
                'desc'          => 'พัฒนาผู้เชี่ยวชาญด้านการพัฒนาหลักสูตร การออกแบบนวัตกรรมการจัดการเรียนรู้ขั้นสูง และการวิจัยเพื่อพัฒนาการศึกษาในยุคดิจิทัล',
                'image'         => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=800&q=80',
                'tags'          => ['การพัฒนาหลักสูตร', 'การวิจัยการศึกษา', 'นวัตกรรมการสอน'],
                'highlight'     => false,
                'sort_order'    => 1,
                'is_active'     => true,
            ],
            [
                'slug'          => 'educational-admin',
                'degree_level'  => 'master',
                'title'         => 'สาขาวิชาการบริหารการศึกษา',
                'title_en'      => 'Educational Administration',
                'degree_title'  => 'ครุศาสตรมหาบัณฑิต (ค.ม.)',
                'duration'      => '2 ปี',
                'credits'       => '36 หน่วยกิต',
                'desc'          => 'เสริมสร้างภาวะผู้นำทางการศึกษา การบริหารจัดการสถานศึกษาเชิงยุทธศาสตร์ และการประกันคุณภาพการศึกษาตามมาตรฐานสากล',
                'image'         => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
                'tags'          => ['ภาวะผู้นำทางวิชาการ', 'การบริหารสถานศึกษา', 'การประกันคุณภาพ'],
                'highlight'     => false,
                'sort_order'    => 2,
                'is_active'     => true,
            ],
        ];

        foreach ($defaults as $d) {
            Curriculum::create($d);
        }
    }
}
