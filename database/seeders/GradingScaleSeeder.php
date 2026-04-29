<?php

namespace Database\Seeders;

use App\Models\GradingScale;
use Illuminate\Database\Seeder;

class GradingScaleSeeder extends Seeder
{
    public function run(): void
    {
        $scales = [
            // JSS — single-letter grades (A–F)
            ['level' => 'JSS', 'grade' => 'A', 'min_score' => 90, 'max_score' => 100, 'remark' => 'Excellent'],
            ['level' => 'JSS', 'grade' => 'B', 'min_score' => 80, 'max_score' =>  89, 'remark' => 'Very Good'],
            ['level' => 'JSS', 'grade' => 'C', 'min_score' => 70, 'max_score' =>  79, 'remark' => 'Credit'],
            ['level' => 'JSS', 'grade' => 'D', 'min_score' => 60, 'max_score' =>  69, 'remark' => 'Satisfactory'],
            ['level' => 'JSS', 'grade' => 'E', 'min_score' => 50, 'max_score' =>  59, 'remark' => 'Fair'],
            ['level' => 'JSS', 'grade' => 'F', 'min_score' =>  0, 'max_score' =>  49, 'remark' => 'Fail'],
            // SSS — numeric grades (A1–F9)
            ['level' => 'SSS', 'grade' => 'A1', 'min_score' => 75, 'max_score' => 100, 'remark' => 'Excellent'],
            ['level' => 'SSS', 'grade' => 'B2', 'min_score' => 70, 'max_score' =>  74, 'remark' => 'Very Good'],
            ['level' => 'SSS', 'grade' => 'B3', 'min_score' => 65, 'max_score' =>  69, 'remark' => 'Good'],
            ['level' => 'SSS', 'grade' => 'C4', 'min_score' => 60, 'max_score' =>  64, 'remark' => 'Credit'],
            ['level' => 'SSS', 'grade' => 'C5', 'min_score' => 55, 'max_score' =>  59, 'remark' => 'Credit'],
            ['level' => 'SSS', 'grade' => 'C6', 'min_score' => 50, 'max_score' =>  54, 'remark' => 'Credit'],
            ['level' => 'SSS', 'grade' => 'D7', 'min_score' => 45, 'max_score' =>  49, 'remark' => 'Pass'],
            ['level' => 'SSS', 'grade' => 'E8', 'min_score' => 40, 'max_score' =>  44, 'remark' => 'Pass'],
            ['level' => 'SSS', 'grade' => 'F9', 'min_score' =>  0, 'max_score' =>  39, 'remark' => 'Fail'],
        ];

        GradingScale::truncate();
        foreach ($scales as $scale) {
            GradingScale::create($scale);
        }
    }
}
