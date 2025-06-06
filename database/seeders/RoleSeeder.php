<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::all();

        $roles = [
            ['roleName' => 'Admin', 'rank' => 1],
            ['roleName' => 'Modérateur', 'rank' => 2],
            ['roleName' => 'Utilisateur', 'rank' => 3],
        ];

        foreach ($roles as $role) {
            $permission = $permissions->random();

            Role::create([
                'roleName' => $role['roleName'],
                'rank' => $role['rank'],
                'permissionId' => $permission->id,
            ]);
        }
    }
}
