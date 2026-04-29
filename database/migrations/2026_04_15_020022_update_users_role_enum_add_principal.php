<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Expand enum to include 'principal'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('principal','admin','teacher') NOT NULL DEFAULT 'teacher'");

        // Rename all existing 'admin' users to 'principal'
        DB::table('users')->where('role', 'admin')->update(['role' => 'principal']);
    }

    public function down(): void
    {
        // Rename 'principal' back to 'admin'
        DB::table('users')->where('role', 'principal')->update(['role' => 'admin']);

        // Shrink enum back
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','teacher') NOT NULL DEFAULT 'teacher'");
    }
};
