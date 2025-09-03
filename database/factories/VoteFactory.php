<?php

namespace Database\Factories;

use App\Models\Vote;
use App\Models\User;
use App\Models\Ressource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['like', 'dislike']),
            'creation_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'user_id' => User::factory(),
            'ressource_id' => Ressource::factory(),
        ];
    }
}
