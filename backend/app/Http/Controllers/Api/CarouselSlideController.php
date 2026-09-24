<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CarouselSlide;
use Illuminate\Http\Request;

class CarouselSlideController extends Controller
{
    public function index(Request $request)
    {
        // Auto-seed default slides if table is empty
        if (CarouselSlide::count() === 0) {
            $defaults = [
                [
                    'title'      => 'คณะครุศาสตร์ มหาวิทยาลัยราชภัฏราชนครินทร์',
                    'image_url'  => 'https://placehold.co/1920x800/0F5132/ffffff?text=Banner+Slide+1+(1920x800)',
                    'alt_text'   => 'แบนเนอร์คณะครุศาสตร์ สไลด์ที่ 1',
                    'link_url'   => null,
                    'target'     => '_self',
                    'sort_order' => 1,
                    'is_active'  => true,
                ],
                [
                    'title'      => 'ผลิตและพัฒนาครูมืออาชีพเพื่อชุมชนและสังคม',
                    'image_url'  => 'https://placehold.co/1920x800/157347/ffffff?text=Banner+Slide+2+(1920x800)',
                    'alt_text'   => 'แบนเนอร์คณะครุศาสตร์ สไลด์ที่ 2',
                    'link_url'   => null,
                    'target'     => '_self',
                    'sort_order' => 2,
                    'is_active'  => true,
                ],
                [
                    'title'      => 'การประกันคุณภาพการศึกษาและมาตรฐานวิชาชีพ',
                    'image_url'  => 'https://placehold.co/1920x800/0A3622/ffffff?text=Banner+Slide+3+(1920x800)',
                    'alt_text'   => 'แบนเนอร์คณะครุศาสตร์ สไลด์ที่ 3',
                    'link_url'   => null,
                    'target'     => '_self',
                    'sort_order' => 3,
                    'is_active'  => true,
                ],
                [
                    'title'      => 'หลักสูตรครุศาสตรบัณฑิตที่ทันสมัย',
                    'image_url'  => 'https://placehold.co/1920x800/1e293b/ffffff?text=Banner+Slide+4+(1920x800)',
                    'alt_text'   => 'แบนเนอร์คณะครุศาสตร์ สไลด์ที่ 4',
                    'link_url'   => null,
                    'target'     => '_self',
                    'sort_order' => 4,
                    'is_active'  => true,
                ],
            ];

            foreach ($defaults as $d) {
                CarouselSlide::create($d);
            }
        }

        $query = CarouselSlide::query();

        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }

        $slides = $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        return response()->json($slides);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'image_url'  => 'required|string',
            'alt_text'   => 'nullable|string|max:255',
            'link_url'   => 'nullable|string|max:500',
            'target'     => 'nullable|string|in:_self,_blank',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        if (!isset($validated['sort_order'])) {
            $maxOrder = CarouselSlide::max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        if (empty($validated['target'])) {
            $validated['target'] = '_self';
        }

        $slide = CarouselSlide::create($validated);
        return response()->json(['message' => 'เพิ่มภาพสไลด์แบนเนอร์สำเร็จ', 'data' => $slide], 201);
    }

    public function update(Request $request, $id)
    {
        $slide = CarouselSlide::findOrFail($id);

        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'image_url'  => 'sometimes|required|string',
            'alt_text'   => 'nullable|string|max:255',
            'link_url'   => 'nullable|string|max:500',
            'target'     => 'nullable|string|in:_self,_blank',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        $slide->update($validated);
        return response()->json(['message' => 'อัปเดตภาพสไลด์แบนเนอร์สำเร็จ', 'data' => $slide]);
    }

    public function destroy($id)
    {
        $slide = CarouselSlide::findOrFail($id);
        $slide->delete();
        return response()->json(['message' => 'ลบภาพสไลด์แบนเนอร์สำเร็จ']);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'orders'              => 'required|array',
            'orders.*.id'         => 'required|integer|exists:carousel_slides,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $item) {
            CarouselSlide::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'จัดเรียงลำดับภาพสไลด์สำเร็จ']);
    }
}
