<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\User;
use Illuminate\Support\Collection;

class ExamService
{
    public function __construct(
        private QuestionService $questionService
    ) {}

    public function startExam(User $user, int $questionCount = 15): Exam
    {
        $questions = $this->questionService->getRandomQuestions($questionCount);

        return $this->createExamFromQuestions($user, $questions);
    }

    public function startProITECExam(User $user): Exam
    {
        $subjectCounts = [
            'Língua Portuguesa' => 20,
            'Matemática' => 20,
            'Ética e Cidadania' => 10,
        ];

        $questions = collect();
        foreach ($subjectCounts as $subject => $count) {
            $questions = $questions->concat($this->questionService->getRandomQuestions($count, $subject));
        }

        $questions = $questions->unique('id')->values();

        return $this->createExamFromQuestions($user, $questions);
    }

    private function createExamFromQuestions(User $user, $questions): Exam
    {
        $exam = Exam::create([
            'user_id' => $user->id,
            'score' => 0,
            'total_questions' => $questions->count(),
        ]);

        foreach ($questions as $question) {
            ExamAnswer::create([
                'exam_id' => $exam->id,
                'question_id' => $question->id,
                'selected_option' => null,
                'is_correct' => false,
            ]);
        }

        return $exam->load('answers.question');
    }

    public function submitExam(Exam $exam, array $answers): Exam
    {
        $score = 0;

        foreach ($exam->answers as $answer) {
            $selected = $answers[$answer->question_id] ?? null;

            if ($selected) {
                $isCorrect = $selected === $answer->question->correct_option;
                if ($isCorrect) $score++;

                $answer->update([
                    'selected_option' => $selected,
                    'is_correct' => $isCorrect,
                ]);
            }
        }

        $exam->update([
            'score' => $score,
            'finished_at' => now(),
        ]);

        return $exam->fresh(['answers.question']);
    }

    public function getUserExams(User $user): Collection
    {
        return $user->exams()
            ->whereNotNull('finished_at')
            ->orderByDesc('created_at')
            ->get();
    }

    public function getUserStats(User $user): array
    {
        $exams = $this->getUserExams($user);

        if ($exams->isEmpty()) {
            return [
                'total_exams' => 0,
                'avg_score' => 0,
                'best_score' => 0,
                'total_questions_answered' => 0,
            ];
        }

        $totalQuestions = $exams->sum('total_questions');
        $totalCorrect = $exams->sum('score');

        return [
            'total_exams' => $exams->count(),
            'avg_score' => $totalQuestions > 0 ? round(($totalCorrect / $totalQuestions) * 100, 1) : 0,
            'best_score' => $exams->max(fn ($e) => $e->percentage()),
            'total_questions_answered' => $totalQuestions,
        ];
    }

    /**
     * Percentage of correct answers per subject, across all of the
     * user's finished exams, used for the per-discipline breakdown
     * shown on the dashboard.
     */
    public function getUserSubjectStats(User $user): array
    {
        $answers = ExamAnswer::whereHas('exam', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereNotNull('finished_at');
            })
            ->with('question')
            ->get();

        return $answers
            ->groupBy(fn ($answer) => $answer->question->subject)
            ->map(function ($group) {
                $total = $group->count();
                $correct = $group->where('is_correct', true)->count();

                return [
                    'total' => $total,
                    'correct' => $correct,
                    'percentage' => $total > 0 ? round(($correct / $total) * 100, 1) : 0,
                ];
            })
            ->sortByDesc('percentage')
            ->toArray();
    }
}
