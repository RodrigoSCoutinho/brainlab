<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PracticeHeartsTest extends TestCase
{
    use RefreshDatabase;

    public function test_wrong_answers_deplete_hearts_until_game_over()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create(['correct_option' => 'a']);

        $this->actingAs($user)->get('/practice');

        // Answer wrong 5 times in a row (STARTING_HEARTS = 5)
        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($user)->post('/practice', [
                'question_id' => $question->id,
                'selected_option' => 'b', // wrong, correct is 'a'
                'subject' => $question->subject,
            ])->assertOk();
        }

        // Hearts should now be at 0, so visiting /practice shows the game-over screen
        $response = $this->actingAs($user)->get('/practice');

        $response->assertOk();
        $response->assertViewIs('practice.game-over');
    }

    public function test_restarting_practice_resets_hearts()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create(['correct_option' => 'a']);

        $this->actingAs($user)->get('/practice');

        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($user)->post('/practice', [
                'question_id' => $question->id,
                'selected_option' => 'b',
                'subject' => $question->subject,
            ]);
        }

        $this->actingAs($user)->post('/practice/restart')->assertRedirect();

        $response = $this->actingAs($user)->get('/practice');
        $response->assertViewIs('practice.index');
        $response->assertViewHas('hearts', 5);
    }

    public function test_correct_answers_do_not_cost_a_heart()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create(['correct_option' => 'a']);

        $this->actingAs($user)->get('/practice');

        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($user)->post('/practice', [
                'question_id' => $question->id,
                'selected_option' => 'a', // always correct
                'subject' => $question->subject,
            ])->assertViewHas('hearts', 5);
        }

        $response = $this->actingAs($user)->get('/practice');
        $response->assertViewIs('practice.index');
    }
}
