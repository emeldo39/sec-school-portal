<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'is_form_teacher', 'form_class_id',
        'phone', 'photo', 'signature', 'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'        => 'hashed',
            'is_form_teacher' => 'boolean',
        ];
    }

    // Helpers
    public function isPrincipal(): bool    { return $this->role === 'principal'; }
    public function isAdmin(): bool        { return $this->role === 'admin'; }
    public function isTeacher(): bool      { return $this->role === 'teacher'; }
    public function isActive(): bool       { return $this->status === 'active'; }

    /** True for both principal and admin — use for portal access guards */
    public function hasAdminAccess(): bool { return in_array($this->role, ['principal', 'admin']); }

    // Relationships
    public function formClass()
    {
        return $this->belongsTo(SchoolClass::class, 'form_class_id');
    }

    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    public function attendancesMarked()
    {
        return $this->hasMany(Attendance::class, 'marked_by');
    }

    public function scoresSubmitted()
    {
        return $this->hasMany(Score::class, 'submitted_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
