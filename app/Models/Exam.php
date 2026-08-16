<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'score',
        'total_questions',
        'finished_at',
    ];

    protected $casts = [
        'finished_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function percentage(): float
    {
        if ($this->total_questions === 0) return 0;
        return round(($this->score / $this->total_questions) * 100, 1);
    }

    public function subjectResults(): array
    {
        return $this->answers
            ->groupBy(fn ($answer) => $answer->question->subject)
            ->map(fn ($group, $subject) => [
                'subject' => $subject,
                'questions' => $group->count(),
                'correct' => $group->where('is_correct', true)->count(),
                'percentage' => $group->count()
                    ? round(($group->where('is_correct', true)->count() / $group->count()) * 100, 1)
                    : 0,
            ])
            ->values()
            ->toArray();
    }
}
