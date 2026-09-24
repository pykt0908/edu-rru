<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads', 'public');
            $url = asset('storage/' . $path);

            return response()->json([
                'message' => 'อัปโหลดไฟล์สำเร็จ',
                'url' => $url,
                'path' => $path
            ]);
        }

        return response()->json(['message' => 'ไม่พบไฟล์'], 400);
    }
}
