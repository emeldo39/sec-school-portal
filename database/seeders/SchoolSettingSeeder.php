<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class SchoolSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'school_name'        => "DIVINE ROYAL INT'L COLLEGE",
            'school_motto'       => 'Light of the World',
            'school_address'     => 'Nkpor, Anambra State, Nigeria',
            'school_phone'       => '08000000000',
            'school_email'       => 'info@divineroyalcollege.edu.ng',
            'school_logo'        => 'school_logo.png',
            'principal_name'     => 'The Principal',
            'about_text'         => "Divine Royal Int'l College is a leading secondary school in Nkpor, Anambra State, committed to academic excellence, moral integrity, and the holistic development of every student.",
            'primary_color'      => '#2A2567',
            'secondary_color'    => '#F4BC67',
            'result_sheet_footer'=> 'This result is subject to correction of any clerical error.',
        ];

        foreach ($settings as $key => $value) {
            SchoolSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
