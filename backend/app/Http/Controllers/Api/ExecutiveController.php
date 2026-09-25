<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExecutiveCategory;
use App\Models\ExecutiveMember;
use App\Models\Personnel;
use Illuminate\Http\Request;

class ExecutiveController extends Controller
{
    /**
     * Get all executive categories with their members.
     */
    public function index(Request $request)
    {
        // Auto-seed default executive categories and members if empty
        if (ExecutiveCategory::count() === 0) {
            $this->seedDefaults();
        }

        $query = ExecutiveCategory::query();

        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }

        $categories = $query->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->with(['members' => function ($q) use ($request) {
                if ($request->boolean('active_only')) {
                    $q->where('is_active', true);
                }
                $q->orderBy('sort_order', 'asc')
                  ->orderBy('id', 'asc')
                  ->with('personnel');
            }])
            ->get();

        return response()->json($categories);
    }

    /**
     * Create a new category.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        if (!isset($validated['sort_order'])) {
            $maxOrder = ExecutiveCategory::max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $category = ExecutiveCategory::create($validated);

        return response()->json($category->load('members.personnel'), 201);
    }

    /**
     * Update an executive category.
     */
    public function updateCategory(Request $request, $id)
    {
        $category = ExecutiveCategory::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $category->update($validated);

        return response()->json($category->load('members.personnel'));
    }

    /**
     * Delete an executive category.
     */
    public function destroyCategory($id)
    {
        $category = ExecutiveCategory::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'ลบหมวดหมู่ผู้บริหารเรียบร้อยแล้ว']);
    }

    /**
     * Reorder executive categories.
     */
    public function reorderCategories(Request $request)
    {
        $validated = $request->validate([
            'orders'              => 'required|array',
            'orders.*.id'         => 'required|integer|exists:executive_categories,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $item) {
            ExecutiveCategory::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'จัดลำดับหมวดหมู่สำเร็จ']);
    }

    /**
     * Create an executive member.
     */
    public function storeMember(Request $request)
    {
        $validated = $request->validate([
            'category_id'     => 'required|integer|exists:executive_categories,id',
            'personnel_id'    => 'nullable|integer|exists:personnels,id',
            'position'        => 'required|string|max:255',
            'position_suffix' => 'nullable|string|max:255',
            'custom_name'     => 'nullable|string|max:255',
            'custom_avatar'   => 'nullable|string|max:1000',
            'custom_email'    => 'nullable|string|max:255',
            'custom_phone'    => 'nullable|string|max:100',
            'sort_order'      => 'nullable|integer',
            'is_active'       => 'nullable|boolean',
        ]);

        if (!isset($validated['sort_order'])) {
            $maxOrder = ExecutiveMember::where('category_id', $validated['category_id'])->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $member = ExecutiveMember::create($validated);

        return response()->json($member->load(['category', 'personnel']), 201);
    }

    /**
     * Update an executive member.
     */
    public function updateMember(Request $request, $id)
    {
        $member = ExecutiveMember::findOrFail($id);

        $validated = $request->validate([
            'category_id'     => 'sometimes|required|integer|exists:executive_categories,id',
            'personnel_id'    => 'nullable|integer|exists:personnels,id',
            'position'        => 'sometimes|required|string|max:255',
            'position_suffix' => 'nullable|string|max:255',
            'custom_name'     => 'nullable|string|max:255',
            'custom_avatar'   => 'nullable|string|max:1000',
            'custom_email'    => 'nullable|string|max:255',
            'custom_phone'    => 'nullable|string|max:100',
            'sort_order'      => 'nullable|integer',
            'is_active'       => 'nullable|boolean',
        ]);

        $member->update($validated);

        return response()->json($member->fresh()->load(['category', 'personnel']));
    }

    /**
     * Delete an executive member.
     */
    public function destroyMember($id)
    {
        $member = ExecutiveMember::findOrFail($id);
        $member->delete();

        return response()->json(['message' => 'ลบรายชื่อผู้บริหารเรียบร้อยแล้ว']);
    }

    /**
     * Reorder executive members.
     */
    public function reorderMembers(Request $request)
    {
        $validated = $request->validate([
            'orders'                => 'required|array',
            'orders.*.id'           => 'required|integer|exists:executive_members,id',
            'orders.*.sort_order'   => 'required|integer',
            'orders.*.category_id'  => 'nullable|integer|exists:executive_categories,id',
        ]);

        foreach ($validated['orders'] as $item) {
            $updateData = ['sort_order' => $item['sort_order']];
            if (isset($item['category_id'])) {
                $updateData['category_id'] = $item['category_id'];
            }
            ExecutiveMember::where('id', $item['id'])->update($updateData);
        }

        return response()->json(['message' => 'จัดลำดับผู้บริหารสำเร็จ']);
    }

    /**
     * Seed initial default categories and members if none exist.
     */
    protected function seedDefaults(): void
    {
        $catDean = ExecutiveCategory::create([
            'title'       => 'คณบดี',
            'description' => 'ผู้บริหารสูงสุดของคณะครุศาสตร์',
            'sort_order'  => 1,
            'is_active'   => true,
        ]);

        $catVice = ExecutiveCategory::create([
            'title'       => 'รองคณบดี',
            'description' => 'รองคณบดีฝ่ายต่างๆ ขับเคลื่อนยุทธศาสตร์คณะ',
            'sort_order'  => 2,
            'is_active'   => true,
        ]);

        $catAssistant = ExecutiveCategory::create([
            'title'       => 'ผู้ช่วยคณบดี',
            'description' => 'ผู้ช่วยคณบดีสนับสนุนการดำเนินงานภารกิจเฉพาะด้าน',
            'sort_order'  => 3,
            'is_active'   => true,
        ]);

        $catOffice = ExecutiveCategory::create([
            'title'       => 'หัวหน้าสำนักงานคณบดี',
            'description' => 'กำกับดูแลงานบริหารทั่วไปและสนับสนุนการศึกษา',
            'sort_order'  => 4,
            'is_active'   => true,
        ]);

        // Try linking to available personnels
        $allPersonnel = Personnel::orderBy('id', 'asc')->get();

        if ($allPersonnel->count() > 0) {
            // Dean
            ExecutiveMember::create([
                'category_id'  => $catDean->id,
                'personnel_id' => $allPersonnel->get(0)?->id,
                'position'     => 'คณบดีคณะครุศาสตร์',
                'sort_order'   => 1,
                'is_active'    => true,
            ]);

            // Vice Deans
            $vicePositions = [
                'รองคณบดีฝ่ายวิชาการและวิจัย',
                'รองคณบดีฝ่ายบริหารและแผนงาน',
                'รองคณบดีฝ่ายกิจการนักศึกษา',
                'รองคณบดีฝ่ายประกันคุณภาพการศึกษา',
            ];
            foreach ($vicePositions as $idx => $pos) {
                $person = $allPersonnel->get($idx + 1);
                if ($person) {
                    ExecutiveMember::create([
                        'category_id'  => $catVice->id,
                        'personnel_id' => $person->id,
                        'position'     => $pos,
                        'sort_order'   => $idx + 1,
                        'is_active'    => true,
                    ]);
                }
            }

            // Assistant Deans
            $asstPositions = [
                'ผู้ช่วยคณบดีฝ่ายประกันคุณภาพ',
                'ผู้ช่วยคณบดีฝ่ายเทคโนโลยีสารสนเทศ',
            ];
            foreach ($asstPositions as $idx => $pos) {
                $person = $allPersonnel->get($idx + 5);
                if ($person) {
                    ExecutiveMember::create([
                        'category_id'  => $catAssistant->id,
                        'personnel_id' => $person->id,
                        'position'     => $pos,
                        'sort_order'   => $idx + 1,
                        'is_active'    => true,
                    ]);
                }
            }

            // Office Head
            $officePerson = $allPersonnel->get(7) ?? $allPersonnel->last();
            if ($officePerson) {
                ExecutiveMember::create([
                    'category_id'  => $catOffice->id,
                    'personnel_id' => $officePerson->id,
                    'position'     => 'หัวหน้าสำนักงานคณบดี',
                    'sort_order'   => 1,
                    'is_active'    => true,
                ]);
            }
        } else {
            // Fallback manual members if personnels table was somehow empty
            ExecutiveMember::create([
                'category_id' => $catDean->id,
                'position'    => 'คณบดีคณะครุศาสตร์',
                'custom_name' => 'ผศ.ดร.สมชาย ใจดี',
                'sort_order'  => 1,
                'is_active'   => true,
            ]);
        }
    }
}
