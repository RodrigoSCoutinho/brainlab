<?php

namespace App\Services;

use App\Models\Essay;
use App\Models\Exam;
use App\Models\PracticeAttempt;
use App\Models\User;
use Carbon\Carbon;

class GamificationService
{
    const XP_PER_CORRECT_ANSWER = 10;
    const XP_PER_LEVEL = 100;
    const DAILY_GOAL = 10;
    const STARTING_HEARTS = 5;

    /**
     * Record a single practice attempt for a user.
     */
    public function recordAttempt(User $user, int $questionId, string $subject, bool $isCorrect): PracticeAttempt
    {
        return PracticeAttempt::create([
            'user_id' => $user->id,
            'question_id' => $questionId,
            'subject' => $subject,
            'is_correct' => $isCorrect,
        ]);
    }

    /**
     * Total XP earned across all subjects.
     */
    public function getTotalXp(User $user): int
    {
        return $this->correctAttempts($user)->count() * self::XP_PER_CORRECT_ANSWER;
    }

    /**
     * XP, level and progress towards the next level, grouped by subject.
     */
    public function getSubjectLevels(User $user): array
    {
        return PracticeAttempt::where('user_id', $user->id)
            ->get()
            ->groupBy('subject')
            ->map(function ($attempts) {
                $xp = $attempts->where('is_correct', true)->count() * self::XP_PER_CORRECT_ANSWER;

                return [
                    'xp' => $xp,
                    'level' => intdiv($xp, self::XP_PER_LEVEL) + 1,
                    'xp_into_level' => $xp % self::XP_PER_LEVEL,
                    'xp_for_next_level' => self::XP_PER_LEVEL,
                    'attempts' => $attempts->count(),
                    'correct' => $attempts->where('is_correct', true)->count(),
                ];
            })
            ->toArray();
    }

    /**
     * Current and longest daily practice streak, based on distinct
     * calendar days with at least one practice attempt.
     */
    public function getStreak(User $user): array
    {
        $dates = PracticeAttempt::where('user_id', $user->id)
            ->selectRaw('DATE(created_at) as day')
            ->distinct()
            ->pluck('day')
            ->map(fn ($d) => Carbon::parse($d)->startOfDay())
            ->sortByDesc(fn ($d) => $d->timestamp)
            ->values();

        if ($dates->isEmpty()) {
            return ['current' => 0, 'longest' => 0, 'practiced_today' => false];
        }

        $today = Carbon::today();
        $practicedToday = $dates->first()->isSameDay($today);

        // Current streak: consecutive days counting back from today or yesterday.
        $current = 0;
        $cursor = $dates->first()->isSameDay($today) || $dates->first()->isSameDay($today->copy()->subDay())
            ? $dates->first()
            : null;

        if ($cursor) {
            foreach ($dates as $date) {
                if ($date->isSameDay($cursor)) {
                    $current++;
                    $cursor = $cursor->copy()->subDay();
                } else {
                    break;
                }
            }
        }

        // Longest streak: scan every distinct day, oldest to newest.
        $ascending = $dates->sortBy(fn ($d) => $d->timestamp)->values();
        $longest = 1;
        $run = 1;
        for ($i = 1; $i < $ascending->count(); $i++) {
            if ($ascending[$i]->diffInDays($ascending[$i - 1]) === 1) {
                $run++;
            } else {
                $run = 1;
            }
            $longest = max($longest, $run);
        }

        return [
            'current' => $current,
            'longest' => max($longest, $current),
            'practiced_today' => $practicedToday,
        ];
    }

    /**
     * Progress towards today's practice goal.
     */
    public function getDailyGoal(User $user, int $goal = self::DAILY_GOAL): array
    {
        $answeredToday = PracticeAttempt::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        return [
            'goal' => $goal,
            'answered' => $answeredToday,
            'percentage' => $goal > 0 ? min(100, round(($answeredToday / $goal) * 100)) : 0,
            'completed' => $answeredToday >= $goal,
        ];
    }

    /**
     * Achievements, always returned in a fixed order, each flagged
     * as unlocked or not based on the user's current activity.
     */
    public function getAchievements(User $user): array
    {
        $totalAttempts = PracticeAttempt::where('user_id', $user->id)->count();
        $totalCorrect = $this->correctAttempts($user)->count();
        $streak = $this->getStreak($user);
        $examsCount = Exam::where('user_id', $user->id)->whereNotNull('finished_at')->count();
        $essaysCount = Essay::where('user_id', $user->id)->count();

        return [
            [
                'key' => 'first_question',
                'name' => 'Primeira Questão',
                'description' => 'Responda sua primeira questão no modo prática.',
                'icon' => 'fa-shoe-prints',
                'unlocked' => $totalAttempts >= 1,
            ],
            [
                'key' => 'fifty_correct',
                'name' => '50 Acertos',
                'description' => 'Acerte 50 questões no modo prática.',
                'icon' => 'fa-bullseye',
                'unlocked' => $totalCorrect >= 50,
            ],
            [
                'key' => 'hundred_questions',
                'name' => '100 Questões',
                'description' => 'Responda 100 questões no modo prática, certas ou erradas.',
                'icon' => 'fa-layer-group',
                'unlocked' => $totalAttempts >= 100,
            ],
            [
                'key' => 'week_streak',
                'name' => 'Sequência de 7 Dias',
                'description' => 'Pratique por 7 dias seguidos.',
                'icon' => 'fa-fire',
                'unlocked' => $streak['longest'] >= 7,
            ],
            [
                'key' => 'first_exam',
                'name' => 'Primeiro Simulado',
                'description' => 'Conclua seu primeiro simulado cronometrado.',
                'icon' => 'fa-clipboard-check',
                'unlocked' => $examsCount >= 1,
            ],
            [
                'key' => 'first_essay',
                'name' => 'Primeira Redação',
                'description' => 'Envie sua primeira redação para correção.',
                'icon' => 'fa-pen-fancy',
                'unlocked' => $essaysCount >= 1,
            ],
        ];
    }

    private function correctAttempts(User $user)
    {
        return PracticeAttempt::where('user_id', $user->id)->where('is_correct', true);
    }
}
