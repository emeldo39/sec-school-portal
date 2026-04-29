<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreWeight extends Model
{
    protected $table = 'score_weight';

    protected $fillable = ['ca_weight', 'exam_weight'];

    public static function current(): self
    {
        return static::firstOrCreate([], ['ca_weight' => 40, 'exam_weight' => 60]);
    }
}
