<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            if (!Schema::hasColumn('personnels', 'is_head')) {
                $table->boolean('is_head')->default(false)->after('role_title');
            }
            if (!Schema::hasColumn('personnels', 'bio')) {
                $table->text('bio')->nullable()->after('office_hours');
            }
            if (!Schema::hasColumn('personnels', 'study_visits')) {
                $table->json('study_visits')->nullable()->after('work_experience');
            }
        });
    }

    public function down(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            if (Schema::hasColumn('personnels', 'is_head')) {
                $table->dropColumn('is_head');
            }
            if (Schema::hasColumn('personnels', 'bio')) {
                $table->dropColumn('bio');
            }
            if (Schema::hasColumn('personnels', 'study_visits')) {
                $table->dropColumn('study_visits');
            }
        });
    }
};
