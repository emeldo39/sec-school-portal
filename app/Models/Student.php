<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'admission_number', 'first_name', 'last_name',
        'date_of_birth', 'gender', 'class_id',
        'guardian_name', 'guardian_phone', 'photo', 'status',
    ];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date'];
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
