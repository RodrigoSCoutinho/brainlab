<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\Question;
use App\Models\User;
use App\Services\ExamService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression test for a real bug found in production data: every
 * seeded question stored correct_option in uppercase (e.g. "B"),
 * while the practice/exam forms always submit lowercase letters
 * (a, b, c, d). The strict "===" comparison then marked every
 * correctly answered question as wrong, no matter what the student
 * picked. Fixed by lowercasing stored data and comparing
 * case-insensitively; this test locks that behavior in.
 */
class AnswerCaseSensitivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_practice_marks_the_answer_correct_even_if_stored_option_is_uppercase()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create(['correct_option' => 'B']);

        $response = $this->actingAs($user)->post('/practice', [
            'question_id' => $question->id,
            'selected_option' => 'b',
            'subject' => $question->subject,
        ]);

        $response->assertViewHas('isCorrect', true);
    }

    public function test_exam_scores_the_answer_correct_even_if_stored_option_is_uppercase()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create(['correct_option' => 'C']);

        $exam = Exam::create(['user_id' => $user->id, 'score' => 0, 'total_questions' => 1]);
        ExamAnswer::create(['exam_id' => $exam->id, 'question_id' => $question->id, 'selected_option' => null, 'is_correct' => false]);

        $result = app(ExamService::class)->submitExam($exam, [$question->id => 'c']);

        $this->assertEquals(1, $result->score);
    }

    public function test_professor_can_only_submit_lowercase_correct_option()
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $response = $this->actingAs($professor)->post('/professor/questions', [
            'statement' => 'Quanto é 2 + 2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_option' => 'b',
            'subject' => 'Matemática',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('questions', [
            'statement' => 'Quanto é 2 + 2?',
            'correct_option' => 'b',
        ]);
    }
}
