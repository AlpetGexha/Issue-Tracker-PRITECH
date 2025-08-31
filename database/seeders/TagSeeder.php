<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

final class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Bug', 'color' => '#ef4444'],
            ['name' => 'Feature', 'color' => '#22c55e'],
            ['name' => 'Enhancement', 'color' => '#3b82f6'],
            ['name' => 'Documentation', 'color' => '#8b5cf6'],
            ['name' => 'Question', 'color' => '#f97316'],
            ['name' => 'Help Wanted', 'color' => '#06b6d4'],
            ['name' => 'Good First Issue', 'color' => '#eab308'],
            ['name' => 'Duplicate', 'color' => '#6b7280'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
