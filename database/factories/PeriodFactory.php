<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Period>
 */
class PeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 year', 'now');
        $endDate = fake()->dateTimeBetween($startDate, 'now');

        return [
            'name' => 'Année scolaire ' . fake()->sentence(1),
            'start_date' => $startDate->format('d/m/Y'),
            'end_date' => $endDate->format('d/m/Y'),
            'comment' => fake()->sentence(6),
        ];
    }

    public function current(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_current' => 1,
            ];
        });
    }
}
