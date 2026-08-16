<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'essay_id',
        'analyzed_by',
        'analysis_type',
        'score',
        'feedback',
        'competency_1',
        'competency_2',
        'competency_3',
        'competency_4',
        'competency_5',
    ];

    public function essay()
    {
        return $this->belongsTo(Essay::class);
    }

    public function analyzer()
    {
        return $this->belongsTo(User::class, 'analyzed_by');
    }

    public function totalScore(): int
    {
        return ($this->competency_1 ?? 0)
             + ($this->competency_2 ?? 0)
             + ($this->competency_3 ?? 0)
             + ($this->competency_4 ?? 0)
             + ($this->competency_5 ?? 0);
    }
}
