<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('term_id')->constrained('academic_terms')->cascadeOnDelete();
            $table->date('next_term_begins')->nullable();
            $table->json('psychomotor')->nullable();
            $table->json('affective')->nullable();
            $table->text('form_master_remarks')->nullable();
            $table->text('house_master_remarks')->nullable();
            $table->text('principal_remarks')->nullable();
            $table->string('token', 64)->unique();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'term_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_publications');
    }
};
