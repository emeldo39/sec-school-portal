<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            // JSS
            ['name' => 'JSS1A', 'level' => 'JSS'],
            ['name' => 'JSS1B', 'level' => 'JSS'],
            ['name' => 'JSS2A', 'level' => 'JSS'],
            ['name' => 'JSS2B', 'level' => 'JSS'],
            ['name' => 'JSS3A', 'level' => 'JSS'],
            ['name' => 'JSS3B', 'level' => 'JSS'],
            // SSS
            ['name' => 'SS1A',  'level' => 'SSS'],
            ['name' => 'SS1B',  'level' => 'SSS'],
            ['name' => 'SS2A',  'level' => 'SSS'],
            ['name' => 'SS2B',  'level' => 'SSS'],
            ['name' => 'SS3A',  'level' => 'SSS'],
            ['name' => 'SS3B',  'level' => 'SSS'],
        ];

        foreach ($classes as $class) {
            SchoolClass::firstOrCreate(['name' => $class['name']], $class);
        }
    }
}
