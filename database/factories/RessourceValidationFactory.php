<?php

namespace Database\Factories;

use App\Models\RessourceValidation;
use App\Models\Ressource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RessourceValidation>
 */
class RessourceValidationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RessourceValidation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'comment' => $this->faker->optional()->sentence(),
            'validation_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'ressource_id' => Ressource::factory(),
            'validator_id' => User::factory(),
        ];
    }
}
