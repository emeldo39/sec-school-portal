<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Single-row config table for CA vs Exam weighting
        Schema::create('score_weight', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('ca_weight')->default(40);
            $table->unsignedTinyInteger('exam_weight')->default(60);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('score_weight');
    }
};
