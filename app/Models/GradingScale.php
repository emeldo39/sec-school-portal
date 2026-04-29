<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradingScale extends Model
{
    protected $fillable = ['level', 'min_score', 'max_score', 'grade', 'remark'];

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeForJss($query)
    {
        return $query->where('level', 'JSS');
    }

    public function scopeForSss($query)
    {
        return $query->where('level', 'SSS');
    }

    // ── Static helpers ───────────────────────────────────────────

    /**
     * Look up the grade label for a score at a given class level.
     */
    public static function gradeFor(float $score, string $level = 'SSS'): string
    {
        $scale = static::where('level', $level)
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();
        return $scale ? $scale->grade : '-';
    }

    /**
     * Look up the remark for a score at a given class level.
     */
    public static function remarkFor(float $score, string $level = 'SSS'): string
    {
        $scale = static::where('level', $level)
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();
        return $scale ? $scale->remark : '-';
    }
}
