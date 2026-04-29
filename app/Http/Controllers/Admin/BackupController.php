<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Database\Seeders\GradingScaleSeeder;
use Database\Seeders\SchoolClassSeeder;
use Database\Seeders\ScoreWeightSeeder;
use Database\Seeders\SubjectSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use ZipArchive;

class BackupController extends Controller
{
    private const BACKUP_DIR  = 'app/private/backups';
    private const MAX_BACKUPS = 12;

    // ─────────────────────────────────────────────────────────
    // LIST
    // ─────────────────────────────────────────────────────────

    public function index()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $backups    = $this->listBackups();
        $totalSize  = array_sum(array_column($backups, 'size'));
        $totalCount = count($backups);
        $latest     = $backups[0]['created'] ?? null;

        return view('admin.backup.index', compact('backups', 'totalSize', 'totalCount', 'latest'));
    }

    // ─────────────────────────────────────────────────────────
    // CREATE BACKUP
    // ─────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate(['type' => 'required|in:full,db,files']);

        set_time_limit(300);

        try {
            $filename = $this->createBackup($request->type);
            $this->applyRetention();
            return back()->with('success', "Backup created successfully: {$filename}");
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Backup failed: ' . $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────
    // DOWNLOAD
    // ─────────────────────────────────────────────────────────

    public function download(string $filename)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
            abort(400, 'Invalid filename.');
        }

        $path = $this->backupPath($filename);
        abort_unless(file_exists($path), 404, 'Backup file not found.');

        return response()->download($path);
    }

    // ─────────────────────────────────────────────────────────
    // DELETE BACKUP FILE
    // ─────────────────────────────────────────────────────────

    public function destroy(string $filename)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
            abort(400, 'Invalid filename.');
        }

        $path = $this->backupPath($filename);

        if (file_exists($path)) {
            unlink($path);
        }

        return back()->with('success', 'Backup deleted.');
    }

    // ─────────────────────────────────────────────────────────
    // SOFT CLEAR
    // ─────────────────────────────────────────────────────────

    public function clearSoft(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate(['soft_password' => 'required|string']);

        if (!Hash::check($request->soft_password, auth()->user()->password)) {
            return back()->withErrors(['soft_password' => 'Incorrect password. Clear aborted.'])->withInput();
        }

        set_time_limit(300);

        try {
            $backupFile = $this->createBackup('db');
            $this->applyRetention();
        } catch (\Throwable $e) {
            return back()->withErrors(['soft_password' => 'Auto-backup failed. Clear aborted: ' . $e->getMessage()]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        try {
            DB::table('result_publications')->truncate();
            DB::table('results')->truncate();
            DB::table('scores')->truncate();
            DB::table('attendances')->truncate();
            DB::table('announcements')->truncate();
            DB::table('contact_messages')->truncate();
            DB::table('activity_logs')->truncate();
            DB::table('cache')->truncate();
            DB::table('jobs')->truncate();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        DB::table('activity_logs')->insert([
            'user_id'     => auth()->id(),
            'action'      => 'soft_clear',
            'description' => 'Soft DB clear performed. Auto-backup: ' . $backupFile,
            'ip_address'  => $request->ip(),
            'created_at'  => now(),
        ]);

        return back()->with('success', "Soft clear completed. Auto-backup saved: {$backupFile}");
    }

    // ─────────────────────────────────────────────────────────
    // HARD CLEAR (FULL RESET)
    // ─────────────────────────────────────────────────────────

    public function clearHard(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'hard_password'     => 'required|string',
            'hard_confirm_text' => 'required|string',
        ]);

        if (!Hash::check($request->hard_password, auth()->user()->password)) {
            return back()->withErrors(['hard_password' => 'Incorrect password. Reset aborted.'])->withInput();
        }

        if ($request->hard_confirm_text !== 'RESET') {
            return back()->withErrors(['hard_confirm_text' => 'You must type RESET exactly (all caps).'])->withInput();
        }

        set_time_limit(300);

        try {
            $backupFile = $this->createBackup('db');
            $this->applyRetention();
        } catch (\Throwable $e) {
            return back()->withErrors(['hard_password' => 'Auto-backup failed. Reset aborted: ' . $e->getMessage()]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        try {
            // Transactional data
            DB::table('result_publications')->truncate();
            DB::table('results')->truncate();
            DB::table('scores')->truncate();
            DB::table('attendances')->truncate();
            DB::table('announcements')->truncate();
            DB::table('contact_messages')->truncate();
            DB::table('activity_logs')->truncate();
            DB::table('cache')->truncate();
            DB::table('jobs')->truncate();
            // Operational data
            DB::table('teacher_assignments')->truncate();
            DB::table('users')->where('role', 'teacher')->delete();
            DB::table('students')->truncate();
            DB::table('academic_terms')->truncate();
            // Website content
            DB::table('gallery_items')->truncate();
            DB::table('news_posts')->truncate();
            DB::table('hero_slides')->truncate();
            DB::table('popup_notices')->update([
                'is_active' => false,
                'title'     => null,
                'image'     => null,
                'link_url'  => null,
                'link_text' => null,
            ]);
            // Config tables — wipe and re-seed to restore safe defaults
            DB::table('grading_scales')->truncate();
            DB::table('score_weight')->truncate();
            DB::table('classes')->truncate();
            DB::table('subjects')->truncate();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // Re-seed config tables so the portal remains functional
        (new GradingScaleSeeder())->run();
        (new ScoreWeightSeeder())->run();
        (new SchoolClassSeeder())->run();
        (new SubjectSeeder())->run();

        // Clear Laravel compiled caches
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        DB::table('activity_logs')->insert([
            'user_id'     => auth()->id(),
            'action'      => 'hard_reset',
            'description' => 'Full hard reset performed. Auto-backup: ' . $backupFile,
            'ip_address'  => $request->ip(),
            'created_at'  => now(),
        ]);

        return back()->with('success', "Full reset completed. Portal restored to defaults. Auto-backup saved: {$backupFile}");
    }

    // ═════════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ═════════════════════════════════════════════════════════

    private function backupPath(string $filename = ''): string
    {
        $dir = storage_path(self::BACKUP_DIR);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return $filename ? $dir . DIRECTORY_SEPARATOR . $filename : $dir;
    }

    private function listBackups(): array
    {
        $dir   = $this->backupPath();
        $files = glob($dir . DIRECTORY_SEPARATOR . 'backup_*.zip') ?: [];

        $backups = [];
        foreach ($files as $path) {
            $filename = basename($path);
            preg_match('/^backup_(full|db|files)_/', $filename, $m);
            $backups[] = [
                'filename' => $filename,
                'type'     => $m[1] ?? 'unknown',
                'size'     => filesize($path),
                'created'  => filemtime($path),
            ];
        }

        usort($backups, fn($a, $b) => $b['created'] - $a['created']);

        return $backups;
    }

    private function createBackup(string $type): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename  = "backup_{$type}_{$timestamp}.zip";
        $zipPath   = $this->backupPath($filename);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Could not create ZIP archive. Check storage permissions.');
        }

        if (in_array($type, ['full', 'db'])) {
            $sql = $this->generateDatabaseDump();
            $zip->addFromString('database.sql', $sql);
        }

        if (in_array($type, ['full', 'files'])) {
            $sourceDir = storage_path('app/public');
            if (is_dir($sourceDir)) {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($sourceDir, \RecursiveDirectoryIterator::SKIP_DOTS)
                );
                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        $relative = 'uploads' . DIRECTORY_SEPARATOR . ltrim(substr($file->getRealPath(), strlen($sourceDir)), DIRECTORY_SEPARATOR);
                        $zip->addFile($file->getRealPath(), $relative);
                    }
                }
            }
        }

        $zip->close();

        return $filename;
    }

    private function generateDatabaseDump(): string
    {
        if ($this->execAvailable()) {
            $result = $this->mysqldump();
            if ($result !== null) {
                return $result;
            }
        }

        return $this->pdoDump();
    }

    private function execAvailable(): bool
    {
        if (!function_exists('exec')) {
            return false;
        }

        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));

        return !in_array('exec', $disabled);
    }

    private function mysqldump(): ?string
    {
        $cfg  = config('database.connections.' . config('database.default'));
        $tmp  = $this->backupPath('_tmp_dump_' . time() . '.sql');

        $cmd = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s --single-transaction --routines --triggers %s > %s 2>&1',
            escapeshellarg((string) ($cfg['host'] ?? '127.0.0.1')),
            escapeshellarg((string) ($cfg['port'] ?? '3306')),
            escapeshellarg((string) ($cfg['username'] ?? '')),
            escapeshellarg((string) ($cfg['password'] ?? '')),
            escapeshellarg((string) ($cfg['database'] ?? '')),
            escapeshellarg($tmp)
        );

        exec($cmd, $output, $code);

        if ($code !== 0 || !file_exists($tmp) || filesize($tmp) === 0) {
            @unlink($tmp);
            return null;
        }

        $sql = file_get_contents($tmp);
        @unlink($tmp);

        return $sql ?: null;
    }

    private function pdoDump(): string
    {
        $pdo    = DB::getPdo();
        $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);

        $sql  = "-- School Portal Database Backup\n";
        $sql .= "-- Generated: " . now()->toDateTimeString() . "\n";
        $sql .= "-- Method: PDO\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $create = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
            $sql   .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sql   .= ($create['Create Table'] ?? '') . ";\n\n";

            $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $cols  = '`' . implode('`, `', array_keys($rows[0])) . '`';
                $vals  = [];
                foreach ($rows as $row) {
                    $escaped = array_map(
                        fn($v) => $v === null ? 'NULL' : $pdo->quote((string) $v),
                        array_values($row)
                    );
                    $vals[] = '(' . implode(', ', $escaped) . ')';
                }
                $sql .= "INSERT INTO `{$table}` ({$cols}) VALUES\n";
                $sql .= implode(",\n", $vals) . ";\n\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        return $sql;
    }

    private function applyRetention(): void
    {
        $dir   = $this->backupPath();
        $files = glob($dir . DIRECTORY_SEPARATOR . 'backup_*.zip') ?: [];

        if (count($files) <= self::MAX_BACKUPS) {
            return;
        }

        // Sort oldest first
        usort($files, fn($a, $b) => filemtime($a) - filemtime($b));

        $excess = count($files) - self::MAX_BACKUPS;
        for ($i = 0; $i < $excess; $i++) {
            @unlink($files[$i]);
        }
    }
}
