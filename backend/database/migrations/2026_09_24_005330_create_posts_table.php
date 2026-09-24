<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('desc')->nullable();
            $table->json('content')->nullable();
            $table->json('key_highlights')->nullable();
            $table->json('quote')->nullable();
            $table->text('thumbnail')->nullable();
            $table->json('gallery')->nullable();
            $table->string('date')->nullable();
            $table->string('category')->default('ข่าวประชาสัมพันธ์');
            $table->string('category_badge_class')->nullable();
            $table->string('views')->default('0');
            $table->string('read_time')->default('3 นาที');
            $table->json('author')->nullable();
            $table->json('attachments')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('featured')->default(false);
            $table->string('grid_class')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
