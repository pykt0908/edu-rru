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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('slug_id')->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('academic_title')->nullable();
            $table->string('role_title');
            $table->text('avatar')->nullable();
            $table->text('degrees')->nullable();
            $table->string('department_id');
            $table->string('department_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('office_room')->nullable();
            $table->string('office_hours')->nullable();
            $table->json('education_history')->nullable();
            $table->json('expertise')->nullable();
            $table->json('publications')->nullable();
            $table->json('courses')->nullable();
            $table->json('work_experience')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};
