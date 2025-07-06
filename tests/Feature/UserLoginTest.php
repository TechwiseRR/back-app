<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::create([
            'username' => 'loginuser',
            'email' => 'loginuser@example.com',
            'password' => bcrypt('password123'),
            'avatar' => null,
            'bio' => 'Bio de test',
            'registrationDate' => now(),
            'updateDate' => now(),
            'isEmailVerified' => true,
            'roleId' => null,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'loginuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::create([
            'username' => 'loginuser2',
            'email' => 'loginuser2@example.com',
            'password' => bcrypt('password123'),
            'avatar' => null,
            'bio' => 'Bio de test',
            'registrationDate' => now(),
            'updateDate' => now(),
            'isEmailVerified' => true,
            'roleId' => null,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'loginuser2@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['error']);
    }
} 