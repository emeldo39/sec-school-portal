<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('admission_number', 30)->unique();
            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->foreignId('class_id')->constrained('classes')->restrictOnDelete();
            $table->string('guardian_name', 100)->nullable();
            $table->string('guardian_phone', 20)->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'graduated', 'withdrawn'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
