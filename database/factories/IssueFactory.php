<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(6, false),
            'description' => fake()->optional(0.8)->paragraphs(2, true),
            'status' => fake()->randomElement(['open', 'in_progress', 'closed']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'due_date' => fake()->optional(0.6)->dateTimeBetween('now', '+3 months')?->format('Y-m-d'),
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
        ]);
    }


    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
        ]);
    }


    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
            'title' => 'URGENT: ' . $attributes['title'],
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => fake()->dateTimeBetween('-1 month', '-1 day')->format('Y-m-d'),
            'status' => 'open',
        ]);
    }


    public function withComments(int $count = 3): static
    {
        return $this->hasComments($count);
    }


    public function withTags(int $count = 2): static
    {
        return $this->hasAttached(
            \App\Models\Tag::factory()->count($count),
            [],
            'tags'
        );
    }

    public function withUsers(int $count = 1): static
    {
        return $this->hasAttached(
            \App\Models\User::factory()->count($count),
            [],
            'users'
        );
    }
}
