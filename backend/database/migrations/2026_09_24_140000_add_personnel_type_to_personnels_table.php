<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            if (!Schema::hasColumn('personnels', 'personnel_type')) {
                $table->string('personnel_type')->default('teacher')->after('role_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            if (Schema::hasColumn('personnels', 'personnel_type')) {
                $table->dropColumn('personnel_type');
            }
        });
    }
};
