<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── classes: 'SS' → 'SSS' ────────────────────────────────
        // Use VARCHAR as intermediate so we can rename enum values freely
        DB::statement("ALTER TABLE `classes` MODIFY `level` VARCHAR(10) NOT NULL");
        DB::table('classes')->where('level', 'SS')->update(['level' => 'SSS']);
        DB::statement("ALTER TABLE `classes` MODIFY `level` ENUM('JSS','SSS') NOT NULL");

        // ── subjects: 'SS' → 'SSS', 'both' → 'Both' ─────────────
        DB::statement("ALTER TABLE `subjects` MODIFY `level` VARCHAR(10) NOT NULL DEFAULT 'Both'");
        DB::table('subjects')->where('level', 'SS')->update(['level' => 'SSS']);
        DB::table('subjects')->where('level', 'both')->update(['level' => 'Both']);
        DB::statement("ALTER TABLE `subjects` MODIFY `level` ENUM('JSS','SSS','Both') NOT NULL DEFAULT 'Both'");
    }

    public function down(): void
    {
        // Revert subjects
        DB::statement("ALTER TABLE `subjects` MODIFY `level` VARCHAR(10) NOT NULL DEFAULT 'both'");
        DB::table('subjects')->where('level', 'SSS')->update(['level' => 'SS']);
        DB::table('subjects')->where('level', 'Both')->update(['level' => 'both']);
        DB::statement("ALTER TABLE `subjects` MODIFY `level` ENUM('JSS','SS','both') NOT NULL DEFAULT 'both'");

        // Revert classes
        DB::statement("ALTER TABLE `classes` MODIFY `level` VARCHAR(10) NOT NULL");
        DB::table('classes')->where('level', 'SSS')->update(['level' => 'SS']);
        DB::statement("ALTER TABLE `classes` MODIFY `level` ENUM('JSS','SS') NOT NULL");
    }
};
