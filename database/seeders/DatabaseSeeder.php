<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SchoolSettingSeeder::class,
            GradingScaleSeeder::class,
            ScoreWeightSeeder::class,
            SchoolClassSeeder::class,
            SubjectSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
