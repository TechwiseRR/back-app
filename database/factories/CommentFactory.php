<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Ressource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(),
            'creation_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'user_id' => User::factory(),
            'ressource_id' => Ressource::factory(),
        ];
    }
}
