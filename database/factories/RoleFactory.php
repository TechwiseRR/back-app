<?php

namespace Database\Factories;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        // Récupère une permission existante ou en crée une si besoin
        $permission = Permission::inRandomOrder()->first() ?? Permission::factory()->create();

        return [
            'roleName' => $this->faker->unique()->jobTitle(),
            'rank' => $this->faker->numberBetween(1, 10),
            'permissionId' => $permission->id,
        ];
    }
} 