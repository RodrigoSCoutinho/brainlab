<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    public function definition()
    {
        return [
            'statement' => fake()->sentence() . '?',
            'option_a' => fake()->word(),
            'option_b' => fake()->word(),
            'option_c' => fake()->word(),
            'option_d' => fake()->word(),
            'correct_option' => 'a',
            'subject' => fake()->randomElement(['Matemática', 'Língua Portuguesa', 'Ética e Cidadania']),
            'explanation' => fake()->sentence(),
        ];
    }

    public function subject(string $subject)
    {
        return $this->state(fn (array $attributes) => [
            'subject' => $subject,
        ]);
    }
}
