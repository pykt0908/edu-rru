<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('executive_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('executive_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('executive_categories')->onDelete('cascade');
            $table->foreignId('personnel_id')->nullable()->constrained('personnels')->onDelete('set null');
            $table->string('position'); // e.g. คณบดีคณะครุศาสตร์, รองคณบดีฝ่ายวิชาการ
            $table->string('position_suffix')->nullable();
            $table->string('custom_name')->nullable();
            $table->string('custom_avatar')->nullable();
            $table->string('custom_email')->nullable();
            $table->string('custom_phone')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('executive_members');
        Schema::dropIfExists('executive_categories');
    }
};
