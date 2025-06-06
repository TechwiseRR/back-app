<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'create_resource', 'description' => 'Autorisation de créer des ressources'],
            ['name' => 'update_resource', 'description' => 'Autorisation de modifier des ressources'],
            ['name' => 'delete_resource', 'description' => 'Autorisation de supprimer des ressources'],
            ['name' => 'view_resource', 'description' => 'Autorisation de visualiser des ressources'],
            ['name' => 'manage_users', 'description' => 'Autorisation de gérer les utilisateurs'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
