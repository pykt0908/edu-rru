<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regulation_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category', 50)->default('act');  // act|regulation|rule|announcement
            $table->string('year', 10)->nullable();
            $table->string('effective_date', 100)->nullable();
            $table->string('file_size', 50)->nullable();
            $table->string('file_url')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulation_items');
    }
};
