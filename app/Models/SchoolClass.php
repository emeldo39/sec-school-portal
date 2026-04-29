<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'classes';

    protected $fillable = ['name', 'level'];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function formTeacher()
    {
        return $this->hasOne(User::class, 'form_class_id');
    }

    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'class_id');
    }
}
