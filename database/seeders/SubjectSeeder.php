<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // Core (both levels)
            ['name' => 'English Language',       'code' => 'ENG',  'level' => 'Both'],
            ['name' => 'Mathematics',             'code' => 'MTH',  'level' => 'Both'],
            ['name' => 'Civic Education',         'code' => 'CIV',  'level' => 'Both'],
            ['name' => 'Physical & Health Edu',   'code' => 'PHE',  'level' => 'Both'],
            // JSS
            ['name' => 'Basic Science',           'code' => 'BSC',  'level' => 'JSS'],
            ['name' => 'Basic Technology',        'code' => 'BTC',  'level' => 'JSS'],
            ['name' => 'Social Studies',          'code' => 'SST',  'level' => 'JSS'],
            ['name' => 'Business Studies',        'code' => 'BUS',  'level' => 'JSS'],
            ['name' => 'Cultural & Creative Arts','code' => 'CCA',  'level' => 'JSS'],
            ['name' => 'Agricultural Science',    'code' => 'AGR',  'level' => 'JSS'],
            ['name' => 'Home Economics',          'code' => 'HEC',  'level' => 'JSS'],
            ['name' => 'Computer Studies',        'code' => 'CMP',  'level' => 'JSS'],
            ['name' => 'French',                  'code' => 'FRN',  'level' => 'JSS'],
            ['name' => 'Igbo Language',           'code' => 'IGB',  'level' => 'JSS'],
            // SS Sciences
            ['name' => 'Physics',                 'code' => 'PHY',  'level' => 'SSS'],
            ['name' => 'Chemistry',               'code' => 'CHM',  'level' => 'SSS'],
            ['name' => 'Biology',                 'code' => 'BIO',  'level' => 'SSS'],
            ['name' => 'Further Mathematics',     'code' => 'FMT',  'level' => 'SSS'],
            // SS Arts
            ['name' => 'Literature in English',   'code' => 'LIT',  'level' => 'SSS'],
            ['name' => 'Government',              'code' => 'GOV',  'level' => 'SSS'],
            ['name' => 'History',                 'code' => 'HIS',  'level' => 'SSS'],
            ['name' => 'Christian Religious Std', 'code' => 'CRS',  'level' => 'SSS'],
            // SS Commercial
            ['name' => 'Economics',               'code' => 'ECO',  'level' => 'SSS'],
            ['name' => 'Financial Accounting',    'code' => 'ACC',  'level' => 'SSS'],
            ['name' => 'Commerce',                'code' => 'COM',  'level' => 'SSS'],
            ['name' => 'Office Practice',         'code' => 'OFP',  'level' => 'SSS'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['code' => $subject['code']], $subject);
        }
    }
}
