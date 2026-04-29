<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'action', 'description', 'ip_address', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, string $description = ''): void
    {
        if (auth()->check()) {
            static::create([
                'user_id'     => auth()->id(),
                'action'      => $action,
                'description' => $description,
                'ip_address'  => request()->ip(),
                'created_at'  => now(),
            ]);
        }
    }
}
