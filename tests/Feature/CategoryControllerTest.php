<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test l'affichage de la liste des catégories.
     */
    public function test_can_get_categories_list(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * Test l'affichage d'une catégorie spécifique.
     */
    public function test_can_get_specific_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
        ]);
    }

    /**
     * Test la création d'une nouvelle catégorie.
     */
    public function test_can_create_category(): void
    {
        $user = User::factory()->create();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        
        $categoryData = [
            'name' => 'New Category',
            'description' => 'Description for new category',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson('/api/categories', $categoryData);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Category created successfully',
            'data' => [
                'name' => 'New Category',
                'description' => 'Description for new category',
            ]
        ]);

        $this->assertDatabaseHas('categories', $categoryData);
    }

    /**
     * Test la mise à jour d'une catégorie.
     */
    public function test_can_update_category(): void
    {
        $user = User::factory()->create();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $category = Category::factory()->create();
        
        $updateData = [
            'name' => 'Updated Category',
            'description' => 'Updated description',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->putJson("/api/categories/{$category->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Category updated successfully',
            'data' => [
                'name' => 'Updated Category',
                'description' => 'Updated description',
            ]
        ]);

        $this->assertDatabaseHas('categories', $updateData);
    }

    /**
     * Test la suppression d'une catégorie.
     */
    public function test_can_delete_category(): void
    {
        $user = User::factory()->create();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $category = Category::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Category deleted successfully']);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /**
     * Test que la validation fonctionne pour la création.
     */
    public function test_validation_works_for_creation(): void
    {
        $user = User::factory()->create();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson('/api/categories', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }
} 