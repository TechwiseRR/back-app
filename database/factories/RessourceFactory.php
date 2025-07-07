<?php

namespace Database\Factories;

use App\Models\Ressource;
use App\Models\User;
use App\Models\Category;
use App\Models\TypeRessource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ressource>
 */
class RessourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ressource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'content' => $this->faker->paragraphs(3, true),
            'url' => $this->faker->optional()->url(),
            'publication_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'validation_date' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'is_validated' => $this->faker->boolean(),
            'tags' => $this->faker->optional()->randomElement([json_encode($this->faker->words(3)), null]),
            'category_id' => Category::factory(),
            'type_ressource_id' => TypeRessource::factory(),
            'user_id' => User::factory(),
        ];
    }
}
