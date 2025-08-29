<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = ['#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4', '#3b82f6', '#8b5cf6', '#ec4899'];

        return [
            'name' => fake()->unique()->slug(2),
            'color' => fake()->randomElement($colors),
        ];
    }

    /**
     * Create predefined tags.
     */
    public function predefined(): static
    {
        $predefinedTags = [
            ['name' => 'bug', 'color' => '#ef4444'],
            ['name' => 'feature', 'color' => '#22c55e'],
            ['name' => 'enhancement', 'color' => '#3b82f6'],
            ['name' => 'documentation', 'color' => '#8b5cf6'],
            ['name' => 'urgent', 'color' => '#dc2626'],
            ['name' => 'low-priority', 'color' => '#6b7280'],
            ['name' => 'frontend', 'color' => '#06b6d4'],
            ['name' => 'backend', 'color' => '#f97316'],
            ['name' => 'testing', 'color' => '#eab308'],
            ['name' => 'security', 'color' => '#7c2d12'],
        ];

        $tag = fake()->randomElement($predefinedTags);

        return $this->state(fn (array $attributes) => [
            'name' => $tag['name'],
            'color' => $tag['color'],
        ]);
    }

    /**
     * Indicate that the tag should have a random name and color.
     */
    public function random(): static
    {
        $colors = ['#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4', '#3b82f6', '#8b5cf6', '#ec4899'];

        return $this->state(fn (array $attributes) => [
            'name' => fake()->unique()->word(),
            'color' => fake()->randomElement($colors),
        ]);
    }
}
