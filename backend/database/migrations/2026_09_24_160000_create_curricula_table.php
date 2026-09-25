<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curricula', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('degree_level', 50)->default('bachelor')->index(); // bachelor, grad-diploma, master, doctoral
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->string('degree_title'); // e.g. ครุศาสตรบัณฑิต (ค.บ.), วิทยาศาสตรบัณฑิต (วท.บ.)
            $table->string('degree_title_en')->nullable();
            $table->string('duration')->default('4 ปี');
            $table->string('credits')->default('120 หน่วยกิต');
            $table->text('desc')->nullable();
            $table->string('image', 500)->nullable();
            $table->json('tags')->nullable();
            $table->boolean('highlight')->default(false);
            $table->string('department_id', 100)->nullable();
            $table->string('document_url', 500)->nullable();
            $table->json('detail_content')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curricula');
    }
};
