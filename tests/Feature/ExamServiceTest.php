<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\Question;
use App\Models\User;
use App\Services\ExamService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_submit_exam_scores_only_correct_answers()
    {
        $user = User::factory()->create();
        $service = app(ExamService::class);

        $q1 = Question::factory()->create(['correct_option' => 'a']);
        $q2 = Question::factory()->create(['correct_option' => 'b']);

        $exam = Exam::create(['user_id' => $user->id, 'score' => 0, 'total_questions' => 2]);
        ExamAnswer::create(['exam_id' => $exam->id, 'question_id' => $q1->id, 'selected_option' => null, 'is_correct' => false]);
        ExamAnswer::create(['exam_id' => $exam->id, 'question_id' => $q2->id, 'selected_option' => null, 'is_correct' => false]);

        $result = $service->submitExam($exam, [
            $q1->id => 'a', // correct
            $q2->id => 'a', // wrong (correct is 'b')
        ]);

        $this->assertEquals(1, $result->score);
        $this->assertNotNull($result->finished_at);
    }

    public function test_user_stats_are_computed_only_from_finished_exams()
    {
        $user = User::factory()->create();
        $service = app(ExamService::class);

        Exam::create(['user_id' => $user->id, 'score' => 8, 'total_questions' => 10, 'finished_at' => now()]);
        // Unfinished exam must not count towards the stats
        Exam::create(['user_id' => $user->id, 'score' => 0, 'total_questions' => 5, 'finished_at' => null]);

        $stats = $service->getUserStats($user);

        $this->assertEquals(1, $stats['total_exams']);
        $this->assertEquals(80.0, $stats['avg_score']);
        $this->assertEquals(10, $stats['total_questions_answered']);
    }

    public function test_subject_stats_group_correctness_by_question_subject()
    {
        $user = User::factory()->create();
        $service = app(ExamService::class);

        $exam = Exam::create(['user_id' => $user->id, 'score' => 2, 'total_questions' => 3, 'finished_at' => now()]);

        $math1 = Question::factory()->subject('Matemática')->create();
        $math2 = Question::factory()->subject('Matemática')->create();
        $portuguese = Question::factory()->subject('Língua Portuguesa')->create();

        ExamAnswer::create(['exam_id' => $exam->id, 'question_id' => $math1->id, 'selected_option' => 'a', 'is_correct' => true]);
        ExamAnswer::create(['exam_id' => $exam->id, 'question_id' => $math2->id, 'selected_option' => 'a', 'is_correct' => false]);
        ExamAnswer::create(['exam_id' => $exam->id, 'question_id' => $portuguese->id, 'selected_option' => 'a', 'is_correct' => true]);

        $stats = $service->getUserSubjectStats($user);

        $this->assertEquals(50.0, $stats['Matemática']['percentage']);
        $this->assertEquals(100.0, $stats['Língua Portuguesa']['percentage']);
    }
}
