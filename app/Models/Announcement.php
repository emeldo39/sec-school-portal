<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title', 'body', 'posted_by', 'target', 'class_id'];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
