<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tag::factory(15)->create();
        $this->call(
            TagSeeder::class
        );

        User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
        ]);

        User::factory(25)->create();

        Project::factory(5)
            ->has(
                Issue::factory()
                    ->count(8)
                    ->hasComments(3)
                    ->hasAttached(Tag::inRandomOrder()->limit(3)->get(), [], 'tags')
                    ->hasAttached(User::inRandomOrder()->limit(2)->get(), [], 'users')
            )
            ->create();

        Project::factory(2)
            ->urgent()
            ->has(
                Issue::factory()
                    ->count(12)
                    ->highPriority()
                    ->open()
                    ->hasComments(5)
                    ->hasAttached(Tag::inRandomOrder()->limit(2)->get(), [], 'tags')
                    ->hasAttached(User::inRandomOrder()->limit(2)->get(), [], 'users')
            )
            ->create();

        Project::factory(3)
            ->longTerm()
            ->has(
                Issue::factory()
                    ->count(6)
                    ->state(fn () => [
                        'status' => fake()->randomElement(['open', 'in_progress', 'closed']),
                        'priority' => fake()->randomElement(['low', 'medium', 'high']),
                    ])
                    ->hasComments(2)
                    ->hasAttached(Tag::inRandomOrder()->limit(2)->get(), [], 'tags')
                    ->hasAttached(User::inRandomOrder()->limit(2)->get(), [], 'users')
            )
            ->create();

        Issue::factory(20)
            ->for(Project::factory())
            ->state(fn () => [
                'status' => fake()->randomElement(['open', 'in_progress', 'closed']),
                'priority' => fake()->randomElement(['low', 'medium', 'high']),
            ])
            ->hasComments(4)
            ->hasAttached(Tag::inRandomOrder()->limit(2)->get(), [], 'tags')
            ->hasAttached(User::inRandomOrder()->limit(2)->get(), [], 'users')
            ->create();

        Issue::factory(8)
            ->for(Project::factory())
            ->overdue()
            ->highPriority()
            ->hasComments(6)
            ->hasAttached(Tag::inRandomOrder()->limit(2)->get(), [], 'tags')
            ->hasAttached(User::inRandomOrder()->limit(2)->get(), [], 'users')
            ->create();

        User::factory(10)
            ->hasComments(8)
            ->create();

        User::factory(6)
            ->hasAttached(
                Issue::factory()
                    ->for(Project::factory())
                    ->count(5)
                    ->hasAttached(Tag::inRandomOrder()->limit(2)->get(), [], 'tags'),
                [],
                'issues'
            )
            ->create();

        Comment::factory(75)
            ->for(Issue::factory())
            ->for(User::factory())
            ->create();
    }
}
