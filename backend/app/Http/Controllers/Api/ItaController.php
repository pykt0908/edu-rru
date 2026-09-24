<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItaItem;
use App\Models\ItaYear;
use Illuminate\Http\Request;

class ItaController extends Controller
{
    public function index(Request $request)
    {
        $query = ItaItem::query();

        if ($request->filled('year')) {
            $query->where('year', $request->input('year'));
        }

        $items = $query->orderBy('sort_order', 'asc')->orderBy('code', 'asc')->get();

        if ($request->boolean('grouped')) {
            $years = ItaYear::where('is_active', true)->orderBy('sort_order', 'asc')->get();
            return response()->json([
                'years' => $years,
                'items' => $items,
            ]);
        }

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year'        => 'required|string|max:10',
            'code'        => 'required|string|max:20',
            'indicator'   => 'required|string|max:255',
            'components'  => 'nullable',
            'links'       => 'nullable|array',
            'sort_order'  => 'nullable|integer',
        ]);

        if (!isset($validated['sort_order'])) {
            $maxOrder = ItaItem::where('year', $validated['year'])->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        $item = ItaItem::create($validated);
        return response()->json(['message' => 'เพิ่มข้อมูล ITA สำเร็จ', 'data' => $item], 201);
    }

    public function reorder(Request $request)
    {
        if ($request->has('ids') && is_array($request->input('ids'))) {
            $ids = $request->input('ids');
            foreach ($ids as $index => $id) {
                ItaItem::where('id', $id)->update(['sort_order' => $index + 1]);
            }
            return response()->json(['message' => 'บันทึกการจัดลำดับตัวชี้วัดสำเร็จ']);
        }

        if ($request->has('orders') && is_array($request->input('orders'))) {
            foreach ($request->input('orders') as $row) {
                if (isset($row['id'], $row['sort_order'])) {
                    ItaItem::where('id', $row['id'])->update(['sort_order' => $row['sort_order']]);
                }
            }
            return response()->json(['message' => 'บันทึกการจัดลำดับตัวชี้วัดสำเร็จ']);
        }

        return response()->json(['message' => 'ข้อมูลไม่ถูกต้อง'], 422);
    }

    public function update(Request $request, $id)
    {
        $item = ItaItem::findOrFail($id);

        $validated = $request->validate([
            'year'        => 'sometimes|required|string|max:10',
            'code'        => 'sometimes|required|string|max:20',
            'indicator'   => 'sometimes|required|string|max:255',
            'components'  => 'nullable',
            'links'       => 'nullable|array',
            'sort_order'  => 'nullable|integer',
        ]);

        $item->update($validated);
        return response()->json(['message' => 'อัปเดตข้อมูล ITA สำเร็จ', 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = ItaItem::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'ลบข้อมูล ITA สำเร็จ']);
    }

    // ─── ITA Years Management ────────────────────────────────────────────────

    public function getYears(Request $request)
    {
        // Auto-seed default years if table is empty
        if (ItaYear::count() === 0) {
            $defaults = [
                ['year' => '2569', 'title' => 'ปีงบประมาณ พ.ศ. 2569', 'is_active' => true, 'sort_order' => 1],
                ['year' => '2568', 'title' => 'ปีงบประมาณ พ.ศ. 2568', 'is_active' => true, 'sort_order' => 2],
                ['year' => '2567', 'title' => 'ปีงบประมาณ พ.ศ. 2567', 'is_active' => true, 'sort_order' => 3],
                ['year' => '2566', 'title' => 'ปีงบประมาณ พ.ศ. 2566', 'is_active' => true, 'sort_order' => 4],
            ];
            foreach ($defaults as $d) {
                ItaYear::create($d);
            }
        }

        $query = ItaYear::withCount('items');

        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }

        $years = $query->orderBy('sort_order', 'asc')->orderBy('year', 'desc')->get();
        return response()->json($years);
    }

    public function storeYear(Request $request)
    {
        $validated = $request->validate([
            'year'       => 'required|string|max:10|unique:ita_years,year',
            'title'      => 'nullable|string|max:255',
            'is_active'  => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if (empty($validated['title'])) {
            $validated['title'] = 'ปีงบประมาณ พ.ศ. ' . $validated['year'];
        }
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }
        if (!isset($validated['sort_order'])) {
            $maxOrder = ItaYear::max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        $year = ItaYear::create($validated);
        return response()->json(['message' => 'เพิ่มปีประเมิน ITA เรียบร้อยแล้ว', 'data' => $year], 201);
    }

    public function updateYear(Request $request, $id)
    {
        $year = ItaYear::findOrFail($id);

        $validated = $request->validate([
            'year'       => 'required|string|max:10|unique:ita_years,year,' . $id,
            'title'      => 'nullable|string|max:255',
            'is_active'  => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $oldYear = $year->year;
        $newYear = $validated['year'];

        // If year string changed, cascade update to ita_items
        if ($oldYear !== $newYear) {
            ItaItem::where('year', $oldYear)->update(['year' => $newYear]);
        }

        $year->update($validated);
        return response()->json(['message' => 'อัปเดตปีประเมิน ITA สำเร็จ', 'data' => $year]);
    }

    public function destroyYear(Request $request, $id)
    {
        $year = ItaYear::findOrFail($id);
        $itemCount = ItaItem::where('year', $year->year)->count();

        if ($itemCount > 0 && !$request->boolean('cascade')) {
            return response()->json([
                'message' => "ไม่สามารถลบปี {$year->year} ได้ เนื่องจากมีตัวชี้วัดอยู่ {$itemCount} รายการ",
                'has_items' => true,
                'items_count' => $itemCount,
            ], 422);
        }

        if ($itemCount > 0 && $request->boolean('cascade')) {
            ItaItem::where('year', $year->year)->delete();
        }

        $year->delete();
        return response()->json(['message' => "ลบปีประเมิน {$year->year} เรียบร้อยแล้ว"]);
    }
}
