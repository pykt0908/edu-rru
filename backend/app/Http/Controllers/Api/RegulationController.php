<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RegulationItem;
use App\Models\RegulationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegulationController extends Controller
{
    public function index(Request $request)
    {
        $query = RegulationItem::query();

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('year') && $request->input('year') !== 'all') {
            $query->where('year', $request->input('year'));
        }

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $items = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'category'       => 'required|string|max:50',
            'year'           => 'nullable|string|max:10',
            'effective_date' => 'nullable|string|max:100',
            'file_size'      => 'nullable|string|max:50',
            'file_url'       => 'nullable|string',
            'description'    => 'nullable|string',
            'sort_order'     => 'nullable|integer',
        ]);

        $item = RegulationItem::create($validated);
        return response()->json(['message' => 'เพิ่มข้อกฎหมายสำเร็จ', 'data' => $item], 201);
    }

    public function update(Request $request, $id)
    {
        $item = RegulationItem::findOrFail($id);

        $validated = $request->validate([
            'title'          => 'sometimes|required|string|max:255',
            'category'       => 'sometimes|required|string|max:50',
            'year'           => 'nullable|string|max:10',
            'effective_date' => 'nullable|string|max:100',
            'file_size'      => 'nullable|string|max:50',
            'file_url'       => 'nullable|string',
            'description'    => 'nullable|string',
            'sort_order'     => 'nullable|integer',
        ]);

        $item->update($validated);
        return response()->json(['message' => 'อัปเดตข้อกฎหมายสำเร็จ', 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = RegulationItem::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'ลบข้อกฎหมายสำเร็จ']);
    }

    // ─── Regulation Categories Management ─────────────────────────────────────

    public function getCategories(Request $request)
    {
        // Auto-seed default categories if table is empty
        if (RegulationCategory::count() === 0) {
            $defaults = [
                [
                    'key'         => 'act',
                    'name'        => 'พระราชบัญญัติ (พ.ร.บ.)',
                    'short_name'  => 'พระราชบัญญัติ',
                    'description' => 'กฎหมายแม่บท พระราชบัญญัติจัดตั้ง และสภาวิชาชีพครู',
                    'icon'        => 'mdi-scale-balance',
                    'color'       => 'bg-violet-100 text-violet-700',
                    'badge_class' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'sort_order'  => 1,
                    'is_active'   => true,
                ],
                [
                    'key'         => 'regulation',
                    'name'        => 'ข้อบังคับมหาวิทยาลัย',
                    'short_name'  => 'ข้อบังคับ',
                    'description' => 'ข้อบังคับ มรภ.ราชนครินทร์ ว่าด้วยการบริหารงานบุคคลและวินัย',
                    'icon'        => 'mdi-file-document-outline',
                    'color'       => 'bg-sky-100 text-sky-700',
                    'badge_class' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'sort_order'  => 2,
                    'is_active'   => true,
                ],
                [
                    'key'         => 'rule',
                    'name'        => 'ระเบียบและแนวปฏิบัติ',
                    'short_name'  => 'ระเบียบ/แนวปฏิบัติ',
                    'description' => 'ระเบียบการลา ค่าตอบแทน ทุนวิจัย และแนวทางการเบิกจ่าย',
                    'icon'        => 'mdi-clipboard-text-outline',
                    'color'       => 'bg-amber-100 text-amber-700',
                    'badge_class' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                    'sort_order'  => 3,
                    'is_active'   => true,
                ],
                [
                    'key'         => 'announcement',
                    'name'        => 'ประกาศมหาวิทยาลัย/คณะ',
                    'short_name'  => 'ประกาศ',
                    'description' => 'ประกาศนโยบาย No Gift Policy, ITA และเกณฑ์ประเมินผลการปฏิบัติงาน',
                    'icon'        => 'mdi-bullhorn-outline',
                    'color'       => 'bg-rose-100 text-rose-700',
                    'badge_class' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'sort_order'  => 4,
                    'is_active'   => true,
                ],
            ];
            foreach ($defaults as $d) {
                RegulationCategory::create($d);
            }
        }

        $query = RegulationCategory::withCount('items');

        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }

        $categories = $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        return response()->json($categories);
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'key'         => 'nullable|string|max:50|unique:regulation_categories,key',
            'short_name'  => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:100',
            'color'       => 'nullable|string|max:100',
            'badge_class' => 'nullable|string|max:100',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        if (empty($validated['key'])) {
            $baseSlug = Str::slug($validated['name']);
            if (empty($baseSlug)) {
                $baseSlug = 'cat-' . time();
            }
            $slug = $baseSlug;
            $counter = 1;
            while (RegulationCategory::where('key', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $validated['key'] = $slug;
        }

        if (empty($validated['short_name'])) {
            $validated['short_name'] = $validated['name'];
        }

        if (!isset($validated['sort_order'])) {
            $maxOrder = RegulationCategory::max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $category = RegulationCategory::create($validated);
        return response()->json(['message' => 'เพิ่มหมวดหมู่ข้อกฎหมายสำเร็จ', 'data' => $category], 201);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = RegulationCategory::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'key'         => 'required|string|max:50|unique:regulation_categories,key,' . $id,
            'short_name'  => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:100',
            'color'       => 'nullable|string|max:100',
            'badge_class' => 'nullable|string|max:100',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $oldKey = $category->key;
        $newKey = $validated['key'];

        // If key changed, cascade update to regulation_items
        if ($oldKey !== $newKey) {
            RegulationItem::where('category', $oldKey)->update(['category' => $newKey]);
        }

        if (empty($validated['short_name'])) {
            $validated['short_name'] = $validated['name'];
        }

        $category->update($validated);
        return response()->json(['message' => 'อัปเดตหมวดหมู่ข้อกฎหมายสำเร็จ', 'data' => $category]);
    }

    public function destroyCategory(Request $request, $id)
    {
        $category = RegulationCategory::findOrFail($id);
        $cascade = $request->boolean('cascade', false);
        $itemCount = RegulationItem::where('category', $category->key)->count();

        if ($itemCount > 0 && !$cascade) {
            return response()->json([
                'message' => "ไม่สามารถลบได้เนื่องจากมีข้อกฎหมายในหมวดนี้อยู่ {$itemCount} รายการ",
                'items_count' => $itemCount,
            ], 422);
        }

        if ($cascade && $itemCount > 0) {
            RegulationItem::where('category', $category->key)->delete();
        }

        $category->delete();
        return response()->json(['message' => 'ลบหมวดหมู่ข้อกฎหมายสำเร็จ']);
    }
}
