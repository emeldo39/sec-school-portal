<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupNotice extends Model
{
    protected $fillable = [
        'title', 'image', 'link_url', 'link_text', 'is_active', 'show_once',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'show_once'  => 'boolean',
    ];

    public static function active(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
