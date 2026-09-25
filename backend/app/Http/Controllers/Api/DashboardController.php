<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Personnel;
use App\Models\Department;
use App\Models\Curriculum;
use App\Models\CarouselSlide;
use App\Models\RegulationItem;
use App\Models\ItaItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalPosts = Post::count();
        $totalPersonnel = Personnel::count();
        $departmentsCount = Department::count();
        $featuredPosts = Post::where('featured', true)->count();
        $totalCurricula = Curriculum::count();
        $totalCarousels = CarouselSlide::count();
        $totalRegulations = RegulationItem::count();
        $totalIta = ItaItem::count();

        // Calculate total views and get top viewed posts
        $posts = Post::select('id', 'title', 'category', 'views', 'date', 'thumbnail', 'created_at')->get();
        $totalViews = 0;
        
        $postsWithNumericViews = $posts->map(function ($p) use (&$totalViews) {
            $raw = (string)($p->views ?? '0');
            $num = (int)preg_replace('/[^0-9]/', '', $raw);
            if (str_contains(strtoupper($raw), 'K')) {
                $num = (float)str_replace(['K', 'k'], '', $raw) * 1000;
            }
            $totalViews += $num;
            return [
                'id' => $p->id,
                'title' => $p->title,
                'category' => $p->category,
                'views' => $num,
                'views_formatted' => $raw,
                'date' => $p->date,
                'thumbnail' => $p->thumbnail,
                'created_at' => $p->created_at,
            ];
        });

        // Top 5 viewed posts
        $topViewedPosts = $postsWithNumericViews->sortByDesc('views')->values()->take(5);

        // Recent posts & recent personnel
        $recentPosts = Post::latest()->take(5)->get();
        $recentPersonnel = Personnel::latest()->take(5)->get();

        // Categories breakdown
        $categoriesStats = Post::selectRaw('category, count(*) as count')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        // SDGs distribution from posts
        $allSdgs = Post::whereNotNull('sdgs')->pluck('sdgs');
        $sdgCounts = [];
        foreach ($allSdgs as $sdgList) {
            if (is_array($sdgList)) {
                foreach ($sdgList as $sdgId) {
                    $id = (int)$sdgId;
                    if ($id >= 1 && $id <= 17) {
                        $sdgCounts[$id] = ($sdgCounts[$id] ?? 0) + 1;
                    }
                }
            }
        }
        ksort($sdgCounts);
        
        $sdgsStats = [];
        foreach ($sdgCounts as $id => $cnt) {
            $sdgsStats[] = [
                'sdg_id' => $id,
                'count' => $cnt,
            ];
        }

        // Personnel by department
        $departmentPersonnelStats = Department::withCount('personnels')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($dept) {
                return [
                    'id' => $dept->id,
                    'slug' => $dept->slug,
                    'name' => $dept->name,
                    'count' => $dept->personnels_count,
                ];
            });

        // Academic titles breakdown (ผู้ช่วยศาสตราจารย์, รองศาสตราจารย์, อาจารย์, etc.)
        $academicTitlesStats = Personnel::selectRaw('academic_title, count(*) as count')
            ->whereNotNull('academic_title')
            ->where('academic_title', '!=', '')
            ->groupBy('academic_title')
            ->orderByDesc('count')
            ->get();

        // Curricula by degree level (bachelor, grad-diploma, master)
        $curriculaStats = Curriculum::selectRaw('degree_level, count(*) as count')
            ->whereNotNull('degree_level')
            ->groupBy('degree_level')
            ->orderByDesc('count')
            ->get();

        return response()->json([
            'total_posts' => $totalPosts,
            'total_personnel' => $totalPersonnel,
            'departments_count' => $departmentsCount,
            'featured_posts' => $featuredPosts,
            'total_views' => $totalViews,
            'total_curricula' => $totalCurricula,
            'total_carousels' => $totalCarousels,
            'total_regulations' => $totalRegulations,
            'total_ita' => $totalIta,
            'recent_posts' => $recentPosts,
            'recent_personnel' => $recentPersonnel,
            'top_viewed_posts' => $topViewedPosts,
            'categories_stats' => $categoriesStats,
            'sdgs_stats' => $sdgsStats,
            'department_personnel_stats' => $departmentPersonnelStats,
            'academic_titles_stats' => $academicTitlesStats,
            'curricula_stats' => $curriculaStats,
        ]);
    }
}
