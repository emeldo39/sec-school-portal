<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicTerm extends Model
{
    protected $fillable = [
        'name', 'academic_year', 'start_date', 'end_date', 'is_current',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'is_current' => 'boolean',
        ];
    }

    public static function current(): ?self
    {
        return static::where('is_current', true)->first();
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'term_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'term_id');
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'term_id');
    }
}
