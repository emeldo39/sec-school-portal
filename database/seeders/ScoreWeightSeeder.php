<?php

namespace Database\Seeders;

use App\Models\ScoreWeight;
use Illuminate\Database\Seeder;

class ScoreWeightSeeder extends Seeder
{
    public function run(): void
    {
        ScoreWeight::firstOrCreate([], ['ca_weight' => 40, 'exam_weight' => 60]);
    }
}
