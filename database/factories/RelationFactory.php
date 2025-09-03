<?php

namespace Database\Factories;

use App\Models\Relation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Relation>
 */
class RelationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Relation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['friend', 'follow', 'block']),
            'creation_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'user_id' => User::factory(),
            'related_user_id' => User::factory(),
        ];
    }
}
