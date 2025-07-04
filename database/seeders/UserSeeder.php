<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();

        $users = [
            [
                'username' => 'admin_user',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'avatar' => null,
                'bio' => 'Administrateur général de la plateforme',
                'registrationDate' => now(),
                'updateDate' => now(),
                'isEmailVerified' => true,
                'roleId' => $roles->where('roleName', 'Admin')->first()->id ?? $roles->random()->id,
            ],
            [
                'username' => 'moderator_user',
                'email' => 'moderator@example.com',
                'password' => bcrypt('password'),
                'avatar' => null,
                'bio' => 'Modérateur chargé de surveiller le contenu',
                'registrationDate' => now(),
                'updateDate' => now(),
                'isEmailVerified' => true,
                'roleId' => $roles->where('roleName', 'Modérateur')->first()->id ?? $roles->random()->id,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Génération d'utilisateurs aléatoires avec une factory
        User::factory(10)->create([
            'roleId' => $roles->where('roleName', 'Utilisateur')->first()->id ?? $roles->random()->id,
        ]);
    }
}
