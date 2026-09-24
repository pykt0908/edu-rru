<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('role_title', 'like', "%{$search}%")
                  ->orWhere('department_name', 'like', "%{$search}%");
            });
        }

        $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');

        $personnel = $query->get();

        // If requested grouped by department (for public faculty personnel page)
        if ($request->boolean('grouped')) {
            $grouped = $personnel->groupBy('department_id')->map(function ($items, $deptId) {
                $first = $items->first();
                return [
                    'id' => $deptId,
                    'name' => $first->department_name,
                    'members' => $items->values()
                ];
            })->values();

            return response()->json($grouped);
        }

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
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'academic_title' => 'nullable|string',
            'role_title' => 'required|string|max:255',
            'department_id' => 'required|string',
            'department_name' => 'required|string',
            'avatar' => 'nullable|string',
            'degrees' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'office_room' => 'nullable|string',
            'office_hours' => 'nullable|string',
            'education_history' => 'nullable|array',
            'expertise' => 'nullable|array',
            'publications' => 'nullable|array',
            'courses' => 'nullable|array',
            'work_experience' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug_id'] = Str::slug($validated['name_en'] ?? $validated['name']) . '-' . rand(100, 999);

        $personnel = Personnel::create($validated);

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
            'name' => 'sometimes|required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'academic_title' => 'nullable|string',
            'role_title' => 'sometimes|required|string|max:255',
            'department_id' => 'sometimes|required|string',
            'department_name' => 'sometimes|required|string',
            'avatar' => 'nullable|string',
            'degrees' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'office_room' => 'nullable|string',
            'office_hours' => 'nullable|string',
            'education_history' => 'nullable|array',
            'expertise' => 'nullable|array',
            'publications' => 'nullable|array',
            'courses' => 'nullable|array',
            'work_experience' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ]);

        $personnel->update($validated);

        return response()->json([
            'message' => 'แก้ไขข้อมูลบุคลากรสำเร็จ',
            'data' => $personnel
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

        $personnel->delete();

        return response()->json([
            'message' => 'ลบข้อมูลบุคลากรสำเร็จ'
        ]);
    }
}
