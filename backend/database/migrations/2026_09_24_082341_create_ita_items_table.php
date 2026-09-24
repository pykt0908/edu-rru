<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ita_items', function (Blueprint $table) {
            $table->id();
            $table->string('year', 10)->default('2569')->index();  // ปีประเมิน
            $table->string('code', 10);                             // O1, O2, ...
            $table->string('indicator');                            // ตัวชี้วัด
            $table->json('components')->nullable();                 // [{text, subnotes}]
            $table->json('links')->nullable();                      // [{title, url, type}]
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ita_items');
    }
};
