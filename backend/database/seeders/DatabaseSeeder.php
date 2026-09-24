<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Personnel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin User
        User::firstOrCreate(
            ['email' => 'admin@rru.ac.th'],
            [
                'name' => 'Admin EDU RRU',
                'password' => Hash::make('admin1234'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Seed Posts from posts.json
        $postsPath = database_path('data/posts.json');
        if (file_exists($postsPath)) {
            $postsData = json_decode(file_get_contents($postsPath), true);
            foreach ($postsData as $item) {
                Post::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'title' => $item['title'] ?? '',
                        'slug' => Str::slug($item['title'] ?? 'post-' . $item['id']),
                        'desc' => $item['desc'] ?? '',
                        'content' => $item['content'] ?? [],
                        'key_highlights' => $item['keyHighlights'] ?? [],
                        'quote' => $item['quote'] ?? null,
                        'thumbnail' => $item['thumbnail'] ?? '',
                        'gallery' => $item['gallery'] ?? [],
                        'date' => $item['date'] ?? now()->format('d M Y'),
                        'category' => $item['category'] ?? 'ข่าวประชาสัมพันธ์',
                        'category_badge_class' => $item['categoryBadgeClass'] ?? 'bg-emerald-600 text-white',
                        'views' => (string)($item['views'] ?? '0'),
                        'read_time' => $item['readTime'] ?? '3 นาที',
                        'author' => $item['author'] ?? null,
                        'attachments' => $item['attachments'] ?? [],
                        'tags' => $item['tags'] ?? [],
                        'featured' => !empty($item['featured']),
                        'grid_class' => $item['gridClass'] ?? null,
                    ]
                );
            }
        }

        // 3. Seed Personnel from personnel.json
        $personnelPath = database_path('data/personnel.json');
        if (file_exists($personnelPath)) {
            $personnelData = json_decode(file_get_contents($personnelPath), true);
            foreach ($personnelData as $item) {
                Personnel::updateOrCreate(
                    ['slug_id' => $item['id']],
                    [
                        'name' => $item['name'] ?? '',
                        'name_en' => $item['nameEn'] ?? null,
                        'academic_title' => $item['academicTitle'] ?? null,
                        'role_title' => $item['roleTitle'] ?? '',
                        'avatar' => $item['avatar'] ?? null,
                        'degrees' => $item['degrees'] ?? null,
                        'department_id' => $item['departmentId'] ?? 'general',
                        'department_name' => $item['departmentName'] ?? 'ทั่วไป',
                        'email' => $item['email'] ?? null,
                        'phone' => $item['phone'] ?? null,
                        'office_room' => $item['officeRoom'] ?? null,
                        'office_hours' => $item['officeHours'] ?? null,
                        'education_history' => $item['educationHistory'] ?? [],
                        'expertise' => $item['expertise'] ?? [],
                        'publications' => $item['publications'] ?? [],
                        'courses' => $item['courses'] ?? [],
                        'work_experience' => $item['workExperience'] ?? [],
                        'sort_order' => $item['sort_order'] ?? 0,
                    ]
                );
            }
        }

        // 4. Seed Content & Policy Data
        $this->call(ContentSeeder::class);
    }
}
