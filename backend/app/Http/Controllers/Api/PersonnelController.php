<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Personnel::query();

        if ($request->filled('department_id') && $request->query('department_id') !== 'all') {
            $query->where('department_id', $request->query('department_id'));
        }

        if ($request->filled('personnel_type') && $request->query('personnel_type') !== 'all') {
            $query->where('personnel_type', $request->query('personnel_type'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('role_title', 'like', "%{$search}%")
                  ->orWhere('academic_title', 'like', "%{$search}%")
                  ->orWhere('department_name', 'like', "%{$search}%");
            });
        }

        // If requested grouped by department (for public faculty personnel page)
        if ($request->boolean('grouped')) {
            $departments = Department::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $grouped = [];
            foreach ($departments as $dept) {
                $deptMembers = Personnel::where('department_id', $dept->slug)
                    ->orderBy('is_head', 'desc')
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                $grouped[] = [
                    'id' => $dept->slug,
                    'name' => $dept->name,
                    'degreeTitle' => $dept->degree_title,
                    'head_personnel_id' => $dept->head_personnel_id,
                    'members' => $deptMembers->values(),
                ];
            }

            // Also include office/support personnel if any exist
            $officeMembers = Personnel::where('department_id', 'office')
                ->orWhere('personnel_type', 'staff')
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            if ($officeMembers->isNotEmpty()) {
                $grouped[] = [
                    'id' => 'office',
                    'name' => 'สำนักงานคณบดีคณะครุศาสตร์',
                    'degreeTitle' => 'บุคลากรสายสนับสนุนวิชาการ',
                    'head_personnel_id' => null,
                    'members' => $officeMembers->values(),
                ];
            }

            return response()->json($grouped);
        }

        $query->orderBy('sort_order', 'asc')->orderBy('is_head', 'desc')->orderBy('id', 'asc');

        $personnel = $query->get();

        return response()->json([
            'data' => $personnel,
            'total' => $personnel->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'name_en'           => 'nullable|string|max:255',
            'academic_title'    => 'nullable|string|max:255',
            'role_title'        => 'required|string|max:255',
            'department_id'     => 'required|string',
            'department_name'   => 'nullable|string',
            'avatar'            => 'nullable|string',
            'degrees'           => 'nullable|string',
            'email'             => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:100',
            'office_room'       => 'nullable|string|max:255',
            'office_hours'      => 'nullable|string|max:255',
            'bio'               => 'nullable|string',
            'education_history' => 'nullable|array',
            'expertise'         => 'nullable|array',
            'publications'      => 'nullable|array',
            'courses'           => 'nullable|array',
            'work_experience'   => 'nullable|array',
            'study_visits'      => 'nullable|array',
            'is_head'           => 'nullable|boolean',
            'personnel_type'    => 'nullable|string|in:teacher,staff',
            'sort_order'        => 'nullable|integer',
        ]);

        if (empty($validated['personnel_type'])) {
            $validated['personnel_type'] = 'teacher';
        }

        // Auto fill department_name if empty
        if (empty($validated['department_name'])) {
            if ($validated['department_id'] === 'office') {
                $validated['department_name'] = 'สำนักงานคณบดีคณะครุศาสตร์';
            } else {
                $dept = Department::where('slug', $validated['department_id'])->first();
                $validated['department_name'] = $dept ? $dept->name : $validated['department_id'];
            }
        }

        if (!isset($validated['sort_order'])) {
            $maxOrder = Personnel::where('department_id', $validated['department_id'])->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        $validated['slug_id'] = Str::slug($validated['name_en'] ?? $validated['name']) . '-' . rand(100, 999);

        // If marked as head, reset other members in this department
        if (!empty($validated['is_head'])) {
            Personnel::where('department_id', $validated['department_id'])->update(['is_head' => false]);
        }

        $personnel = Personnel::create($validated);

        if (!empty($validated['is_head'])) {
            Department::where('slug', $validated['department_id'])->update(['head_personnel_id' => $personnel->id]);
        }

        return response()->json([
            'message' => 'เพิ่มข้อมูลบุคลากรสำเร็จ',
            'data' => $personnel
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $personnel = Personnel::where('id', $id)
            ->orWhere('slug_id', $id)
            ->firstOrFail();

        return response()->json($personnel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $personnel = Personnel::where('id', $id)
            ->orWhere('slug_id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'name'              => 'sometimes|required|string|max:255',
            'name_en'           => 'nullable|string|max:255',
            'academic_title'    => 'nullable|string|max:255',
            'role_title'        => 'sometimes|required|string|max:255',
            'personnel_type'    => 'nullable|string|in:teacher,staff',
            'department_id'     => 'sometimes|required|string',
            'department_name'   => 'nullable|string',
            'avatar'            => 'nullable|string',
            'degrees'           => 'nullable|string',
            'email'             => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:100',
            'office_room'       => 'nullable|string|max:255',
            'office_hours'      => 'nullable|string|max:255',
            'bio'               => 'nullable|string',
            'education_history' => 'nullable|array',
            'expertise'         => 'nullable|array',
            'publications'      => 'nullable|array',
            'courses'           => 'nullable|array',
            'work_experience'   => 'nullable|array',
            'study_visits'      => 'nullable|array',
            'is_head'           => 'nullable|boolean',
            'sort_order'        => 'nullable|integer',
        ]);

        if (isset($validated['department_id']) && empty($validated['department_name'])) {
            if ($validated['department_id'] === 'office') {
                $validated['department_name'] = 'สำนักงานคณบดีคณะครุศาสตร์';
            } else {
                $dept = Department::where('slug', $validated['department_id'])->first();
                $validated['department_name'] = $dept ? $dept->name : $validated['department_id'];
            }
        }

        $deptId = $validated['department_id'] ?? $personnel->department_id;

        // If is_head is updated to true
        if (isset($validated['is_head']) && $validated['is_head']) {
            Personnel::where('department_id', $deptId)->where('id', '!=', $personnel->id)->update(['is_head' => false]);
            Department::where('slug', $deptId)->update(['head_personnel_id' => $personnel->id]);
        } elseif (isset($validated['is_head']) && !$validated['is_head']) {
            Department::where('slug', $deptId)->where('head_personnel_id', $personnel->id)->update(['head_personnel_id' => null]);
        }

        $personnel->update($validated);

        return response()->json([
            'message' => 'แก้ไขข้อมูลบุคลากรสำเร็จ',
            'data' => $personnel->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $personnel = Personnel::where('id', $id)
            ->orWhere('slug_id', $id)
            ->firstOrFail();

        // If this person was department head, unset it
        Department::where('head_personnel_id', $personnel->id)->update(['head_personnel_id' => null]);

        $personnel->delete();

        return response()->json([
            'message' => 'ลบข้อมูลบุคลากรสำเร็จ'
        ]);
    }

    /**
     * Reorder personnel within a department.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'orders'              => 'required|array',
            'orders.*.id'         => 'required|integer|exists:personnels,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $item) {
            Personnel::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'จัดลำดับบุคลากรสำเร็จ']);
    }
}
