<?php

namespace Database\Factories;

use App\Models\HistoriqueAction;
use App\Models\User;
use App\Models\Ressource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoriqueAction>
 */
class HistoriqueActionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HistoriqueAction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'action' => $this->faker->randomElement(['create', 'update', 'delete', 'validate', 'reject']),
            'description' => $this->faker->optional()->sentence(),
            'actionDate' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'userId' => User::factory(),
            'ressourceId' => Ressource::factory(),
        ];
    }
}
