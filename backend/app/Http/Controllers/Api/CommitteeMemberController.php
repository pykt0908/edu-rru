<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommitteeMember;
use Illuminate\Http\Request;

class CommitteeMemberController extends Controller
{
    public function index(Request $request)
    {
        // Auto-seed default committee members if table is empty
        if (CommitteeMember::count() === 0) {
            $defaults = [
                ['sort_order' => 1, 'name' => 'ผู้ช่วยศาสตราจารย์ ดร.ลินดา นาคโปย', 'position' => 'คณบดี', 'is_active' => true],
                ['sort_order' => 2, 'name' => 'ผู้ช่วยศาสตราจารย์ ดร.ทัศนีย์ รอดมั่นคง', 'position' => 'รองคณบดี', 'is_active' => true],
                ['sort_order' => 3, 'name' => 'อาจารย์วรุตม์ กิจเจริญ', 'position' => 'รองคณบดี', 'is_active' => true],
                ['sort_order' => 4, 'name' => 'รองศาสตราจารย์ ดร. มนตรี แย้มกสิกร', 'position' => 'ผู้ทรงคุณวุฒิภายนอก', 'is_active' => true],
                ['sort_order' => 5, 'name' => 'ผู้ช่วยศาสตราจารย์ ดร.ดวงใจ ชนะสิทธิ์', 'position' => 'ผู้ทรงคุณวุฒิภายนอก', 'is_active' => true],
                ['sort_order' => 6, 'name' => 'นางสาวสุทธิษา สมนา', 'position' => 'ผู้แทนประธานสาขาวิชา', 'is_active' => true],
                ['sort_order' => 7, 'name' => 'อาจารย์ชาญณรงค์ คำเพชร', 'position' => 'ผู้แทนประธานสาขาวิชา', 'is_active' => true],
                ['sort_order' => 8, 'name' => 'อาจารย์ ดร.คทาวุธ กุลศิริรัตน์', 'position' => 'ผู้แทนประธานสาขาวิชา', 'is_active' => true],
                ['sort_order' => 9, 'name' => 'ผู้ช่วยศาสตราจารย์ ดร.อังคณา กุลนภาดล', 'position' => 'ผู้แทนคณาจารย์', 'is_active' => true],
                ['sort_order' => 10, 'name' => 'นางวัลยา วงศ์ณรัตน์', 'position' => 'เลขานุการ', 'is_active' => true],
                ['sort_order' => 11, 'name' => 'ผู้ช่วยศาสตราจารย์ ดร.อดิเรก เยาว์วงค์', 'position' => 'ผู้แทนคณาจารย์', 'is_active' => true],
                ['sort_order' => 12, 'name' => 'ผู้ช่วยศาสตราจารย์ ดร.จิราภรณ์ พจนาอารีย์วงศ์', 'position' => 'ผู้แทนคณาจารย์', 'is_active' => true],
                ['sort_order' => 13, 'name' => 'ผู้ช่วยศาสตราจารย์ ดร.อังคณา กรัณยาธิกุล', 'position' => 'ผู้ทรงคุณวุฒิภายนอก', 'is_active' => true],
                ['sort_order' => 14, 'name' => 'นางสาวปิยนันต์ ต่อแสงธรรม', 'position' => 'ผู้ช่วยเลขานุการ', 'is_active' => true],
            ];

            foreach ($defaults as $d) {
                CommitteeMember::create($d);
            }
        }

        $query = CommitteeMember::query();

        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        return response()->json($members);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'position'   => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        if (!isset($validated['sort_order'])) {
            $maxOrder = CommitteeMember::max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $member = CommitteeMember::create($validated);
        return response()->json($member, 201);
    }

    public function update(Request $request, $id)
    {
        $member = CommitteeMember::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'sometimes|required|string|max:255',
            'position'   => 'sometimes|required|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        $member->update($validated);
        return response()->json($member);
    }

    public function destroy($id)
    {
        $member = CommitteeMember::findOrFail($id);
        $member->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'orders'              => 'required|array',
            'orders.*.id'         => 'required|integer|exists:committee_members,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $item) {
            CommitteeMember::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Reordered successfully']);
    }
}
