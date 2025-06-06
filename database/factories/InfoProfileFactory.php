<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InfoProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'userId' => null, // Défini dans le seeder
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'address' => fake()->address(),
            'postalCode' => fake()->postcode(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'updateDate' => now(),
        ];
    }
}
