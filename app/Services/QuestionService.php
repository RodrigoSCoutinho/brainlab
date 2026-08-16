<?php

namespace App\Services;

use App\Models\Question;

class QuestionService
{
    public function getRandomQuestion(?string $subject = null): ?Question
    {
        $query = Question::query();

        if ($subject) {
            $query->where('subject', $subject);
        }

        return $query->inRandomOrder()->first();
    }

    public function getRandomQuestions(int $count = 15, ?string $subject = null)
    {
        $query = Question::query();

        if ($subject) {
            $query->where('subject', $subject);
        }

        return $query->inRandomOrder()->limit($count)->get();
    }

    public function getAllSubjects(): array
    {
        return Question::distinct()->pluck('subject')->sort()->values()->toArray();
    }
}
