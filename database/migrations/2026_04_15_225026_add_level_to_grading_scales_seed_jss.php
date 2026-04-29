<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add level column — default SSS so all existing records become SSS grades
        Schema::table('grading_scales', function (Blueprint $table) {
            $table->enum('level', ['JSS', 'SSS'])->default('SSS')->after('id');
        });

        // 2. Mark all existing rows as SSS (already defaulted, but explicit)
        DB::table('grading_scales')->update(['level' => 'SSS']);

        // 3. Seed JSS grading scale
        $jss = [
            ['level' => 'JSS', 'grade' => 'A', 'min_score' => 90, 'max_score' => 100, 'remark' => 'Excellent'],
            ['level' => 'JSS', 'grade' => 'B', 'min_score' => 80, 'max_score' =>  89, 'remark' => 'Very Good'],
            ['level' => 'JSS', 'grade' => 'C', 'min_score' => 70, 'max_score' =>  79, 'remark' => 'Credit'],
            ['level' => 'JSS', 'grade' => 'D', 'min_score' => 60, 'max_score' =>  69, 'remark' => 'Satisfactory'],
            ['level' => 'JSS', 'grade' => 'E', 'min_score' => 50, 'max_score' =>  59, 'remark' => 'Fair'],
            ['level' => 'JSS', 'grade' => 'F', 'min_score' =>  0, 'max_score' =>  49, 'remark' => 'Fail'],
        ];

        $now = now();
        foreach ($jss as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }
        DB::table('grading_scales')->insert($jss);
    }

    public function down(): void
    {
        // Remove JSS rows first, then drop the column
        DB::table('grading_scales')->where('level', 'JSS')->delete();

        Schema::table('grading_scales', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
