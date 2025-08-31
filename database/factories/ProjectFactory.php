<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
final class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-6 months', '+1 month');
        $deadline = fake()->optional(0.7)->dateTimeBetween($startDate, '+1 year');

        return [
            'name' => fake()->sentence(3, false),
            'description' => fake()->optional(0.8)->paragraph(3),
            'start_date' => $startDate->format('Y-m-d'),
            'deadline' => $deadline?->format('Y-m-d'),
        ];
    }

    /**
     * Indicate the project is urgent with a near deadline.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'deadline' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'name' => 'Urgent: ' . $attributes['name'],
        ]);
    }

    /**
     * Indicate the project is a long-term project.
     */
    public function longTerm(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'deadline' => fake()->dateTimeBetween('+6 months', '+2 years')->format('Y-m-d'),
        ]);
    }
}
