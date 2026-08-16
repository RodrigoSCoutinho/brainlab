<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EssayLineComment extends Model
{
    protected $fillable = [
        'essay_id',
        'professor_id',
        'line_number',
        'comment',
        'type',
    ];

    public function essay()
    {
        return $this->belongsTo(Essay::class);
    }

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
