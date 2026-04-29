<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultPublication extends Model
{
    protected $fillable = [
        'student_id', 'term_id', 'next_term_begins',
        'psychomotor', 'affective',
        'form_master_remarks', 'house_master_remarks', 'principal_remarks',
        'token', 'published_at', 'published_by',
    ];

    protected function casts(): array
    {
        return [
            'psychomotor'      => 'array',
            'affective'        => 'array',
            'next_term_begins' => 'date',
            'published_at'     => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function term()
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
