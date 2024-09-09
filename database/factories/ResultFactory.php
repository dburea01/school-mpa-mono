<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $note = random_int(0, 20);
        return [
            'note' => $note == 20 ? $note : $note + fake()->randomElement([0, 0.25, 0.5, 0.75]),
            'comment' => fake()->boolean(20) ? fake()->sentence() : null,
        ];
    }
}
