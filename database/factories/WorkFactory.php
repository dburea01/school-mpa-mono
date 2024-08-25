<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Work>
 */
class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = Carbon::now();

        $givenAt = fake()->date('d/m/Y');

        $expectedAt = $now->addDays(random_int(1, 10))->format('d/m/Y');

        return [
            'title' => fake()->sentence(2),
            'given_at' => $givenAt,
            'expected_at' => $expectedAt,
            'estimated_duration' => random_int(10, 120),
            'instruction' => fake()->sentence(),
            'comment' => fake()->sentence(),
            'note_min' => 0,
            'note_max' => random_int(10, 20),
            'note_increment' => fake()->boolean() ? fake()->randomElement([0.25, 0.5, 1]) : null,
        ];
    }
}
