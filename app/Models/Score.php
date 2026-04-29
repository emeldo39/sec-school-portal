<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        // Core identifiers
        'student_id', 'subject_id', 'class_id', 'term_id',

        // Legacy fields (kept for backward compat, no longer used in scoring)
        'ca_score',      // legacy JSS 1st CA
        'ca_score_2',    // legacy JSS 2nd CA
        'mid_term',      // legacy mid-term

        // Unified 6-component CA fields (both JSS and SSS)
        'weekly_exercise_1',  // 1st Weekly Exercise /10
        'take_home',          // Take Home Assignment /10
        'college_quiz',       // College Quiz /10
        'project',            // Project /10
        'weekly_exercise_2',  // 2nd Weekly Exercise /10
        'take_home_2',        // 2nd Take Home Assignment /10
        'summary_ca',         // auto-sum of 6 CA components /60

        // Common
        'exam_score',
        'total_score',

        // Workflow
        'status', 'submitted_by', 'reviewed_by',
        'remarks', 'subject_remark',
        'submitted_at', 'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'ca_score'          => 'float',
            'ca_score_2'        => 'float',
            'mid_term'          => 'float',
            'weekly_exercise_1' => 'float',
            'take_home'         => 'float',
            'college_quiz'      => 'float',
            'project'           => 'float',
            'weekly_exercise_2' => 'float',
            'take_home_2'       => 'float',
            'summary_ca'        => 'float',
            'exam_score'        => 'float',
            'total_score'       => 'float',
            'submitted_at'      => 'datetime',
            'reviewed_at'       => 'datetime',
        ];
    }

    // ── Relationships ────────────────────────────────────────────

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function term()
    {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ── Helpers ──────────────────────────────────────────────────

    /** Whether this score row belongs to a JSS class */
    public function isJss(): bool
    {
        return $this->schoolClass && $this->schoolClass->level === 'JSS';
    }

    /**
     * Compute and persist summary_ca and total_score.
     * Both JSS and SSS use the same 6-component CA structure:
     *   summary_ca = weekly_exercise_1 + take_home + college_quiz
     *              + project + weekly_exercise_2 + take_home_2   (max /60)
     *   total_score = summary_ca + exam_score                    (max /100)
     */
    public function computeTotal(): void
    {
        $this->summary_ca = round(
            ($this->weekly_exercise_1 ?? 0) +
            ($this->take_home         ?? 0) +
            ($this->college_quiz      ?? 0) +
            ($this->project           ?? 0) +
            ($this->weekly_exercise_2 ?? 0) +
            ($this->take_home_2       ?? 0),
            2
        );

        $this->total_score = round(
            ($this->summary_ca ?? 0) +
            ($this->exam_score ?? 0),
            2
        );
    }

    // ── Computed attributes ──────────────────────────────────────

    public function getGradeAttribute(): string
    {
        if ($this->total_score === null) return '-';
        $level = $this->relationLoaded('schoolClass')
            ? ($this->schoolClass?->level ?? 'SSS')
            : 'SSS';
        return GradingScale::gradeFor($this->total_score, $level);
    }

    public function getGradeRemarkAttribute(): string
    {
        if ($this->total_score === null) return '-';
        $level = $this->relationLoaded('schoolClass')
            ? ($this->schoolClass?->level ?? 'SSS')
            : 'SSS';
        return GradingScale::remarkFor($this->total_score, $level);
    }
}
