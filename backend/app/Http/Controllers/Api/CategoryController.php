<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::orderBy('sort_order')->orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'badge_class' => 'nullable|string|max:200',
            'color_hex'   => 'nullable|string|max:20',
            'sort_order'  => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name'], '-', 'th');
        if (empty($validated['slug'])) {
            $validated['slug'] = 'cat-' . uniqid();
        }

        $cat = Category::create($validated);
        return response()->json(['message' => 'สร้างหมวดหมู่สำเร็จ', 'data' => $cat], 201);
    }

    public function update(Request $request, $id)
    {
        $cat = Category::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:100',
            'badge_class' => 'nullable|string|max:200',
            'color_hex'   => 'nullable|string|max:20',
            'sort_order'  => 'nullable|integer',
        ]);

        if (isset($validated['name'])) {
            $newSlug = Str::slug($validated['name'], '-', 'th');
            $validated['slug'] = empty($newSlug) ? $cat->slug : $newSlug;
        }

        $cat->update($validated);
        return response()->json(['message' => 'แก้ไขหมวดหมู่สำเร็จ', 'data' => $cat]);
    }

    public function destroy($id)
    {
        $cat = Category::findOrFail($id);
        $cat->delete();
        return response()->json(['message' => 'ลบหมวดหมู่สำเร็จ']);
    }
}
