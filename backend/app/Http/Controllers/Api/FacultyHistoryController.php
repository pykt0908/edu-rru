<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FacultyHistory;
use Illuminate\Http\Request;

class FacultyHistoryController extends Controller
{
    public function index()
    {
        $items = FacultyHistory::orderBy('year', 'asc')->orderBy('sort_order', 'asc')->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year'       => 'required|string|max:10',
            'title'      => 'required|string|max:255',
            'detail'     => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $item = FacultyHistory::create($validated);
        return response()->json(['message' => 'เพิ่มข้อมูลประวัติคณะสำเร็จ', 'data' => $item], 201);
    }

    public function update(Request $request, $id)
    {
        $item = FacultyHistory::findOrFail($id);

        $validated = $request->validate([
            'year'       => 'sometimes|required|string|max:10',
            'title'      => 'sometimes|required|string|max:255',
            'detail'     => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $item->update($validated);
        return response()->json(['message' => 'อัปเดตข้อมูลประวัติคณะสำเร็จ', 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = FacultyHistory::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'ลบข้อมูลประวัติคณะสำเร็จ']);
    }
}
