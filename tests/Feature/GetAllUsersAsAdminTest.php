<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetAllUsersAsAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_get_all_users()
    {
        // Créer le rôle Admin
        $adminRole = Role::factory()->create([
            'roleName' => 'Admin',
            'rank' => 1,
        ]);

        // Créer un utilisateur admin avec le bon roleId
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'roleId' => $adminRole->id,
        ]);

        // Créer d'autres utilisateurs
        User::factory()->count(3)->create();

        // Authentifier l'admin pour obtenir un token
        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);
        $response->assertStatus(200);
        $token = $response->json('access_token');

        // Appeler l'endpoint GET /api/users avec le token
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id', 'username', 'email', // adapte selon la structure de ton User
                ]
            ]
        ]);
    }
} 