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
        Schema::table('scores', function (Blueprint $table) {
            $table->decimal('project', 5, 2)->nullable()->after('college_quiz');
            $table->decimal('take_home_2', 5, 2)->nullable()->after('weekly_exercise_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn(['project', 'take_home_2']);
        });
    }
};
