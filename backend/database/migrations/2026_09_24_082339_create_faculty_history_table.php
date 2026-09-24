<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty_history', function (Blueprint $table) {
            $table->id();
            $table->string('year', 10)->index();       // พ.ศ. เช่น 2483
            $table->string('title');                    // หัวข้อเหตุการณ์
            $table->text('detail')->nullable();         // รายละเอียด
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculty_history');
    }
};
