<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('desc', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category') && $request->query('category') !== 'ทั้งหมด') {
            $query->where('category', $request->query('category'));
        }

        // Featured filter
        if ($request->has('featured')) {
            $query->where('featured', filter_var($request->query('featured'), FILTER_VALIDATE_BOOLEAN));
        }

        // SDG filter
        if ($request->filled('sdg')) {
            $sdg = (int)$request->query('sdg');
            $query->whereJsonContains('sdgs', $sdg);
        }

        // Order
        $sortBy = $request->query('sort_by', 'id');
        $sortOrder = $request->query('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination or All
        if ($request->has('page')) {
            $perPage = $request->query('per_page', 10);
            return response()->json($query->paginate($perPage));
        }

        return response()->json([
            'data' => $query->get(),
            'total' => $query->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'content' => 'nullable',
            'category' => 'required|string',
            'category_badge_class' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'gallery' => 'nullable|array',
            'key_highlights' => 'nullable|array',
            'quote' => 'nullable|array',
            'attachments' => 'nullable|array',
            'tags' => 'nullable|array',
            'sdgs' => 'nullable|array',
            'featured' => 'nullable|boolean',
            'author' => 'nullable|array',
            'date' => 'nullable|string',
            'read_time' => 'nullable|string',
        ]);

        if (empty($validated['date'])) {
            $validated['date'] = now()->format('d M Y');
        }

        if (is_string($request->input('content'))) {
            $contentStr = $request->input('content');
            if (str_contains($contentStr, '<') && str_contains($contentStr, '>')) {
                $validated['content'] = $contentStr;
            } else {
                $validated['content'] = array_values(array_filter(explode("\n", str_replace("\r", "", $contentStr))));
            }
        }

        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['views'] = '0';
        if (empty($validated['read_time'])) {
            $validated['read_time'] = '3 นาที';
        }

        $post = Post::create($validated);

        return response()->json([
            'message' => 'สร้างข่าวสารสำเร็จ',
            'data' => $post
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::where('id', $id)->orWhere('slug', $id)->firstOrFail();

        // Increment views
        $currentViews = intval(preg_replace('/[^0-9]/', '', $post->views));
        $post->views = (string)($currentViews + 1);
        $post->saveQuietly();

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'desc' => 'nullable|string',
            'content' => 'nullable',
            'category' => 'sometimes|required|string',
            'category_badge_class' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'gallery' => 'nullable|array',
            'key_highlights' => 'nullable|array',
            'quote' => 'nullable|array',
            'attachments' => 'nullable|array',
            'tags' => 'nullable|array',
            'sdgs' => 'nullable|array',
            'featured' => 'nullable|boolean',
            'author' => 'nullable|array',
            'date' => 'nullable|string',
            'read_time' => 'nullable|string',
            'views' => 'nullable|string',
        ]);

        if ($request->has('content')) {
            $contentInput = $request->input('content');
            if (is_string($contentInput)) {
                if (str_contains($contentInput, '<') && str_contains($contentInput, '>')) {
                    $validated['content'] = $contentInput;
                } else {
                    $validated['content'] = array_values(array_filter(explode("\n", str_replace("\r", "", $contentInput))));
                }
            } else {
                $validated['content'] = $contentInput;
            }
        }

        $post->update($validated);

        return response()->json([
            'message' => 'แก้ไขข่าวสารสำเร็จ',
            'data' => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json([
            'message' => 'ลบข่าวสารสำเร็จ'
        ]);
    }
}
