<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_deactivated_user_cannot_login()
    {
        $user = User::factory()->create([
            'email' => 'deactivated@example.com',
            'password' => bcrypt('password123'),
            'is_active' => 0,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'deactivated@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403)
            ->assertJsonFragment(['error' => 'Compte désactivé']);
    }

    public function test_non_admin_cannot_get_all_users()
    {
        // S'assurer que le rôle existe avec la bonne colonne
        \App\Models\Role::factory()->create(['id' => 2, 'roleName' => 'user', 'rank' => 2]);

        $user = User::factory()->create([
            'roleId' => 2, // non-admin
            'is_active' => 1,
        ]);
        $token = auth()->login($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/users');

        $response->assertStatus(403);
    }
} 