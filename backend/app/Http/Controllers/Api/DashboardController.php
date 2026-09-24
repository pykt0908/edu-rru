<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Personnel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalPosts = Post::count();
        $totalPersonnel = Personnel::count();
        $departmentsCount = Personnel::distinct('department_id')->count('department_id');
        $featuredPosts = Post::where('featured', true)->count();

        // Calculate total views
        $posts = Post::select('views')->get();
        $totalViews = 0;
        foreach ($posts as $p) {
            $num = (int)preg_replace('/[^0-9]/', '', $p->views);
            if (str_contains($p->views, 'K')) {
                $num = (float)str_replace('K', '', $p->views) * 1000;
            }
            $totalViews += $num;
        }

        $recentPosts = Post::latest()->take(5)->get();
        $recentPersonnel = Personnel::latest()->take(5)->get();
        
        $categoriesStats = Post::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->get();

        return response()->json([
            'total_posts' => $totalPosts,
            'total_personnel' => $totalPersonnel,
            'departments_count' => $departmentsCount,
            'featured_posts' => $featuredPosts,
            'total_views' => $totalViews,
            'recent_posts' => $recentPosts,
            'recent_personnel' => $recentPersonnel,
            'categories_stats' => $categoriesStats
        ]);
    }
}
