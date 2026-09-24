<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regulation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->string('name');
            $table->string('short_name', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('icon', 100)->default('mdi-file-document-outline');
            $table->string('color', 100)->default('bg-sky-100 text-sky-700');
            $table->string('badge_class', 100)->default('bg-blue-50 text-blue-700 border-blue-200');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulation_categories');
    }
};
