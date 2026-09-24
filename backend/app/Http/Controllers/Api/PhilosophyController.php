<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PhilosophyMission;
use App\Models\PhilosophySetting;
use Illuminate\Http\Request;

class PhilosophyController extends Controller
{
    public function index()
    {
        $settings = PhilosophySetting::all()->pluck('value', 'key');
        $missions = PhilosophyMission::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        return response()->json([
            'philosophy'        => $settings['philosophy'] ?? '',
            'philosophy_detail' => $settings['philosophy_detail'] ?? '',
            'vision'            => $settings['vision'] ?? '',
            'identity'          => $settings['identity'] ?? '',
            'missions'          => $missions,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'philosophy'        => 'nullable|string',
            'philosophy_detail' => 'nullable|string',
            'vision'            => 'nullable|string',
            'identity'          => 'nullable|string',
            'missions'          => 'nullable|array',
            'missions.*.text'   => 'required|string',
        ]);

        $keys = ['philosophy', 'philosophy_detail', 'vision', 'identity'];
        foreach ($keys as $k) {
            if ($request->has($k)) {
                PhilosophySetting::updateOrCreate(
                    ['key' => $k],
                    ['value' => $request->input($k)]
                );
            }
        }

        // If missions array is provided, sync missions
        if ($request->has('missions')) {
            // Keep existing IDs or recreate
            $submittedMissions = $request->input('missions', []);
            PhilosophyMission::truncate();
            foreach ($submittedMissions as $idx => $m) {
                if (!empty($m['text'])) {
                    PhilosophyMission::create([
                        'text'       => $m['text'],
                        'sort_order' => $m['sort_order'] ?? ($idx + 1),
                    ]);
                }
            }
        }

        return $this->index();
    }

    public function storeMission(Request $request)
    {
        $validated = $request->validate([
            'text'       => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $mission = PhilosophyMission::create($validated);
        return response()->json(['message' => 'เพิ่มพันธกิจสำเร็จ', 'data' => $mission], 201);
    }

    public function updateMission(Request $request, $id)
    {
        $mission = PhilosophyMission::findOrFail($id);

        $validated = $request->validate([
            'text'       => 'sometimes|required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $mission->update($validated);
        return response()->json(['message' => 'อัปเดตพันธกิจสำเร็จ', 'data' => $mission]);
    }

    public function destroyMission($id)
    {
        $mission = PhilosophyMission::findOrFail($id);
        $mission->delete();
        return response()->json(['message' => 'ลบพันธกิจสำเร็จ']);
    }
}
