<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        // Auto-seed default departments if table is empty
        if (Department::count() === 0) {
            $defaults = [
                ['slug' => 'early-childhood', 'name' => 'สาขาวิชาการศึกษาปฐมวัย', 'degree_title' => 'ครุศาสตรบัณฑิต (ค.บ.)', 'sort_order' => 1, 'is_active' => true],
                ['slug' => 'data-science', 'name' => 'สาขาวิชาวิทยาการข้อมูลและการวิเคราะห์สถิติ', 'degree_title' => 'วิทยาศาสตรบัณฑิต (วท.บ.)', 'sort_order' => 2, 'is_active' => true],
                ['slug' => 'elementary', 'name' => 'สาขาวิชาการประถมศึกษา', 'degree_title' => 'ครุศาสตรบัณฑิต (ค.บ.)', 'sort_order' => 3, 'is_active' => true],
                ['slug' => 'thai', 'name' => 'สาขาวิชาภาษาไทย', 'degree_title' => 'ครุศาสตรบัณฑิต (ค.บ.)', 'sort_order' => 4, 'is_active' => true],
                ['slug' => 'english', 'name' => 'สาขาวิชาภาษาอังกฤษ', 'degree_title' => 'ครุศาสตรบัณฑิต (ค.บ.)', 'sort_order' => 5, 'is_active' => true],
                ['slug' => 'mathematics', 'name' => 'สาขาวิชาคณิตศาสตร์', 'degree_title' => 'ครุศาสตรบัณฑิต (ค.บ.)', 'sort_order' => 6, 'is_active' => true],
                ['slug' => 'science', 'name' => 'สาขาวิชาวิทยาศาสตร์ทั่วไป', 'degree_title' => 'ครุศาสตรบัณฑิต (ค.บ.)', 'sort_order' => 7, 'is_active' => true],
                ['slug' => 'social-studies', 'name' => 'สาขาวิชาสังคมศึกษา', 'degree_title' => 'ครุศาสตรบัณฑิต (ค.บ.)', 'sort_order' => 8, 'is_active' => true],
                ['slug' => 'curriculum-instruction', 'name' => 'สาขาวิชาหลักสูตรและการสอน (ป.โท)', 'degree_title' => 'ครุศาสตรมหาบัณฑิต (ค.ม.)', 'sort_order' => 9, 'is_active' => true],
            ];

            foreach ($defaults as $d) {
                Department::create($d);
            }
        }

        // Auto-sync chairperson from personnel role_title if head_personnel_id is null
        $allDepts = Department::all();
        foreach ($allDepts as $dept) {
            if (!$dept->head_personnel_id) {
                $head = Personnel::where('department_id', $dept->slug)
                    ->where(function ($q) {
                        $q->where('role_title', 'like', '%ประธาน%')
                          ->orWhere('is_head', true);
                    })
                    ->first();

                if ($head) {
                    $dept->update(['head_personnel_id' => $head->id]);
                    $head->update(['is_head' => true]);
                }
            }
        }

        $query = Department::query()->with('head')->withCount('personnels');

        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }

        if ($request->boolean('with_members')) {
            $query->with(['personnels' => function ($q) {
                $q->orderBy('is_head', 'desc')->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
            }]);
        }

        $departments = $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        return response()->json($departments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:100|unique:departments,slug',
            'degree_title'      => 'nullable|string|max:255',
            'head_personnel_id' => 'nullable|integer|exists:personnels,id',
            'sort_order'        => 'nullable|integer',
            'is_active'         => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $slugBase = Str::slug($validated['name']);
            if (empty($slugBase)) {
                $slugBase = 'dept-' . rand(100, 999);
            }
            $slug = $slugBase;
            $counter = 1;
            while (Department::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $counter++;
            }
            $validated['slug'] = $slug;
        }

        if (!isset($validated['sort_order'])) {
            $maxOrder = Department::max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $department = Department::create($validated);

        if (!empty($validated['head_personnel_id'])) {
            // Set is_head = true for this personnel
            Personnel::where('id', $validated['head_personnel_id'])->update(['is_head' => true]);
        }

        return response()->json($department->load('head'), 201);
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name'              => 'sometimes|required|string|max:255',
            'slug'              => 'sometimes|required|string|max:100|unique:departments,slug,' . $id,
            'degree_title'      => 'nullable|string|max:255',
            'head_personnel_id' => 'nullable|integer',
            'sort_order'        => 'nullable|integer',
            'is_active'         => 'nullable|boolean',
        ]);

        $oldHeadId = $department->head_personnel_id;
        $department->update($validated);

        // If head changed
        if (array_key_exists('head_personnel_id', $validated)) {
            $newHeadId = $validated['head_personnel_id'];
            if ($oldHeadId && $oldHeadId != $newHeadId) {
                Personnel::where('id', $oldHeadId)->update(['is_head' => false]);
            }
            if ($newHeadId) {
                Personnel::where('id', $newHeadId)->update(['is_head' => true]);
            }
        }

        return response()->json($department->load('head'));
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return response()->json(['message' => 'ลบสาขาวิชาเรียบร้อยแล้ว']);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'orders'              => 'required|array',
            'orders.*.id'         => 'required|integer|exists:departments,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $item) {
            Department::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'จัดลำดับสาขาวิชาสำเร็จ']);
    }

    public function setHead(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'personnel_id' => 'nullable|integer|exists:personnels,id',
        ]);

        $personnelId = $validated['personnel_id'] ?? null;

        // Reset all in this department to is_head = false
        Personnel::where('department_id', $department->slug)->update(['is_head' => false]);

        if ($personnelId) {
            Personnel::where('id', $personnelId)->update(['is_head' => true]);
            $department->update(['head_personnel_id' => $personnelId]);
        } else {
            $department->update(['head_personnel_id' => null]);
        }

        return response()->json([
            'message' => 'กำหนดประธานสาขาวิชาเรียบร้อยแล้ว',
            'department' => $department->fresh()->load('head'),
        ]);
    }
}
