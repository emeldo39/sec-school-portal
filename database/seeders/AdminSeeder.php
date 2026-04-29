<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name'     => 'School Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'principal',
                'status'   => 'active',
            ]
        );
    }
}
