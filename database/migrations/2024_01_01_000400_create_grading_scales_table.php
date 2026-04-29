<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grading_scales', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('min_score');
            $table->unsignedTinyInteger('max_score');
            $table->string('grade', 5);       // A, B, C, D, E, F
            $table->string('remark', 50);     // Excellent, Good, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_scales');
    }
};
