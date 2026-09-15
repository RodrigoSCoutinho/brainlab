<?php

namespace Tests\Feature;

use App\Models\PracticeAttempt;
use App\Models\Question;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamificationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_total_xp_only_counts_correct_attempts()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create();
        $service = app(GamificationService::class);

        $service->recordAttempt($user, $question->id, $question->subject, true);
        $service->recordAttempt($user, $question->id, $question->subject, true);
        $service->recordAttempt($user, $question->id, $question->subject, false);

        $this->assertEquals(20, $service->getTotalXp($user));
    }

    public function test_subject_levels_group_xp_by_subject()
    {
        $user = User::factory()->create();
        $service = app(GamificationService::class);

        $math = Question::factory()->subject('Matemática')->create();
        $portuguese = Question::factory()->subject('Língua Portuguesa')->create();

        for ($i = 0; $i < 12; $i++) {
            $service->recordAttempt($user, $math->id, 'Matemática', true);
        }
        $service->recordAttempt($user, $portuguese->id, 'Língua Portuguesa', true);

        $levels = $service->getSubjectLevels($user);

        // 12 correct * 10 xp = 120 xp -> level 2, 20 xp into the level
        $this->assertEquals(2, $levels['Matemática']['level']);
        $this->assertEquals(20, $levels['Matemática']['xp_into_level']);
        $this->assertEquals(1, $levels['Língua Portuguesa']['level']);
    }

    public function test_streak_breaks_after_a_missed_day()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create();
        $service = app(GamificationService::class);

        // Practiced 5 days ago, then nothing since: streak should be broken (0)
        $this->createAttemptOn($user, $question, now()->subDays(5));

        $streak = $service->getStreak($user);

        $this->assertEquals(0, $streak['current']);
        $this->assertFalse($streak['practiced_today']);
    }

    public function test_streak_counts_consecutive_days_including_today()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create();
        $service = app(GamificationService::class);

        foreach ([2, 1, 0] as $daysAgo) {
            $this->createAttemptOn($user, $question, now()->subDays($daysAgo));
        }

        $streak = $service->getStreak($user);

        $this->assertEquals(3, $streak['current']);
        $this->assertTrue($streak['practiced_today']);
    }

    public function test_daily_goal_progress_reflects_todays_attempts_only()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create();
        $service = app(GamificationService::class);

        $this->createAttemptOn($user, $question, now()->subDay());
        $service->recordAttempt($user, $question->id, $question->subject, true);
        $service->recordAttempt($user, $question->id, $question->subject, false);

        $goal = $service->getDailyGoal($user, 10);

        $this->assertEquals(2, $goal['answered']);
        $this->assertEquals(20, $goal['percentage']);
        $this->assertFalse($goal['completed']);
    }

    public function test_first_question_achievement_unlocks_after_one_attempt()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create();
        $service = app(GamificationService::class);

        $before = collect($service->getAchievements($user))->firstWhere('key', 'first_question');
        $this->assertFalse($before['unlocked']);

        $service->recordAttempt($user, $question->id, $question->subject, true);

        $after = collect($service->getAchievements($user))->firstWhere('key', 'first_question');
        $this->assertTrue($after['unlocked']);
    }

    /**
     * created_at/updated_at are intentionally not mass-assignable on
     * PracticeAttempt, so backdating a fixture for a test has to go
     * through set + save instead of create().
     */
    private function createAttemptOn(User $user, Question $question, $date, bool $isCorrect = true): PracticeAttempt
    {
        $attempt = new PracticeAttempt([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'subject' => $question->subject,
            'is_correct' => $isCorrect,
        ]);
        $attempt->created_at = $date;
        $attempt->updated_at = $date;
        $attempt->save();

        return $attempt;
    }
}
