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
            'file' => 'required|file|max:51200', // max 50MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $ext = strtolower($file->getClientOriginalExtension());
            $sizeBytes = $file->getSize();
            $formattedSize = $this->formatBytes($sizeBytes);

            $path = $file->store('uploads', 'public');
            $url = asset('storage/' . $path);

            $fileType = 'file';
            if (in_array($ext, ['pdf'])) {
                $fileType = 'pdf';
            } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
                $fileType = 'image';
            } elseif (in_array($ext, ['doc', 'docx'])) {
                $fileType = 'word';
            } elseif (in_array($ext, ['xls', 'xlsx'])) {
                $fileType = 'excel';
            }

            return response()->json([
                'message' => 'อัปโหลดไฟล์สำเร็จ',
                'url' => $url,
                'path' => $path,
                'name' => $originalName,
                'original_name' => $originalName,
                'file_size' => $formattedSize,
                'size' => $formattedSize,
                'size_bytes' => $sizeBytes,
                'extension' => $ext,
                'type' => $fileType,
            ]);
        }

        return response()->json(['message' => 'ไม่พบไฟล์'], 400);
    }

    private function formatBytes(int $bytes, int $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
