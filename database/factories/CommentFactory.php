<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph(),
            'commentDate' => now(),
            'resource_id' => null, // Défini dans le seeder
            'author_id' => null, // Défini dans le seeder
        ];
    }
}
