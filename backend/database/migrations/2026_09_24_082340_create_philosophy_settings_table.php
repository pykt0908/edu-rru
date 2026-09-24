<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('philosophy_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();   // เช่น philosophy, vision, identity, philosophy_detail
            $table->longText('value')->nullable();
            $table->timestamps();
        });

        // Missions in a separate table for easy CRUD
        Schema::create('philosophy_missions', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('philosophy_missions');
        Schema::dropIfExists('philosophy_settings');
    }
};
