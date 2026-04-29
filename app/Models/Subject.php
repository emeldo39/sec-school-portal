<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'code', 'level'];

    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
