<?php

namespace Tests\Feature;

use App\Models\Ressource;
use App\Models\User;
use App\Models\Category;
use App\Models\TypeRessource;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class RessourceControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $admin;
    protected $category;
    protected $typeRessource;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer les rôles
        $userRole = Role::factory()->create(['rank' => 3]);
        $adminRole = Role::factory()->create(['rank' => 1]);

        // Créer les utilisateurs
        $this->user = User::factory()->create(['roleId' => $userRole->id]);
        $this->admin = User::factory()->create(['roleId' => $adminRole->id]);

        // Créer les données de base
        $this->category = Category::factory()->create();
        $this->typeRessource = TypeRessource::factory()->create();
    }

    /**
     * Test l'affichage de la liste des ressources (route publique).
     */
    public function test_index_returns_paginated_ressources(): void
    {
        // Créer quelques ressources publiées
        Ressource::factory()->count(5)->create([
            'status' => 'published',
            'is_validated' => true,
            'category_id' => $this->category->id,
            'type_ressource_id' => $this->typeRessource->id,
        ]);

        $response = $this->getJson('/api/ressources');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'content',
                            'description',
                            'status',
                            'category',
                            'user',
                            'type'
                        ]
                    ],
                    'pagination' => [
                        'current_page',
                        'last_page',
                        'per_page',
                        'total'
                    ]
                ]);
    }

    /**
     * Test l'affichage d'une ressource spécifique (route publique).
     */
    public function test_show_returns_ressource_details(): void
    {
        $ressource = Ressource::factory()->create([
            'status' => 'published',
            'is_validated' => true,
            'category_id' => $this->category->id,
            'type_ressource_id' => $this->typeRessource->id,
        ]);

        $response = $this->getJson("/api/ressources/{$ressource->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'description',
                'status',
                'category',
                'user',
                'validator',
                'type'
            ]);

    }


    /**
     * Test que les ressources non publiées ne sont pas accessibles publiquement.
     */
    public function test_show_returns_404_for_non_published_ressource(): void
    {
        $ressource = Ressource::factory()->create(['status' => 'draft']);

        $response = $this->getJson("/api/ressources/{$ressource->id}");

        $response->assertStatus(404)
                ->assertJson(['error' => 'Cette ressource n\'est pas disponible']);
    }

    /**
     * Test la création d'une ressource par un utilisateur connecté.
     */
    public function test_store_creates_ressource_for_authenticated_user(): void
    {
        $token = JWTAuth::fromUser($this->user);

        $ressourceData = [
            'title' => 'Test Resource',
            'content' => 'Test content',
            'description' => 'Test description',
            'status' => 'draft',
            'category_id' => $this->category->id,
            'type_ressource_id' => $this->typeRessource->id,
        ];

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
                        ->postJson('/api/ressources', $ressourceData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'title',
                        'content',
                        'description',
                        'status',
                        'user_id',
                        'is_validated'
                    ]
                ]);

        $this->assertDatabaseHas('ressources', [
            'title' => 'Test Resource',
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test que l'authentification est requise pour créer une ressource.
     */
    public function test_authentication_required_for_store(): void
    {
        $response = $this->postJson('/api/ressources', [
            'title' => 'Test Resource',
            'content' => 'Test content',
            'description' => 'Test description',
            'status' => 'draft',
            'category_id' => $this->category->id,
            'type_ressource_id' => $this->typeRessource->id,
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test la mise à jour d'une ressource par son propriétaire.
     */
    public function test_update_ressource_by_owner(): void
    {
        $token = JWTAuth::fromUser($this->user);
        $ressource = Ressource::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated description'
        ];

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
                        ->putJson("/api/ressources/{$ressource->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Ressource mise à jour avec succès'
                ]);

        $this->assertDatabaseHas('ressources', [
            'id' => $ressource->id,
            'title' => 'Updated Title',
            'description' => 'Updated description'
        ]);
    }

    /**
     * Test que l'authentification est requise pour mettre à jour une ressource.
     */
    public function test_authentication_required_for_update(): void
    {
        $ressource = Ressource::factory()->create();

        $response = $this->putJson("/api/ressources/{$ressource->id}", [
            'title' => 'Updated Title',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test la suppression d'une ressource par son propriétaire.
     */
    public function test_destroy_ressource_by_owner(): void
    {
        $token = JWTAuth::fromUser($this->user);
        $ressource = Ressource::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
                        ->deleteJson("/api/ressources/{$ressource->id}");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Ressource supprimée avec succès.']);

        $this->assertDatabaseMissing('ressources', ['id' => $ressource->id]);
    }

    /**
     * Test que l'authentification est requise pour supprimer une ressource.
     */
    public function test_authentication_required_for_destroy(): void
    {
        $ressource = Ressource::factory()->create();

        $response = $this->deleteJson("/api/ressources/{$ressource->id}");

        $response->assertStatus(401);
    }

    /**
     * Test le filtrage des ressources par catégorie.
     */
    public function test_index_filters_by_category(): void
    {
        $category2 = Category::factory()->create();

        Ressource::factory()->create([
            'category_id' => $this->category->id,
            'status' => 'published',
            'is_validated' => true
        ]);
        Ressource::factory()->create([
            'category_id' => $category2->id,
            'status' => 'published',
            'is_validated' => true
        ]);

        $response = $this->getJson("/api/ressources?category_id={$this->category->id}");

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    /**
     * Test la recherche par titre.
     */
    public function test_index_search_by_title(): void
    {
        Ressource::factory()->create([
            'title' => 'Test Resource',
            'status' => 'published',
            'is_validated' => true
        ]);
        Ressource::factory()->create([
            'title' => 'Another Resource',
            'status' => 'published',
            'is_validated' => true
        ]);

        $response = $this->getJson('/api/ressources?search=Test');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    /**
     * Test le filtrage des ressources par statut.
     */
    public function test_index_filters_by_status(): void
    {
        Ressource::factory()->create(['status' => 'published']);
        Ressource::factory()->create(['status' => 'draft']);

        $response = $this->getJson('/api/ressources?status=draft');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    /**
     * Test la création d'une ressource par un administrateur.
     */
    public function test_store_creates_validated_ressource_for_admin(): void
    {
        $token = JWTAuth::fromUser($this->admin);

        $ressourceData = [
            'title' => 'Admin Resource',
            'content' => 'Admin content',
            'description' => 'Admin description',
            'status' => 'published',
            'category_id' => $this->category->id,
            'type_ressource_id' => $this->typeRessource->id,
        ];

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
                        ->postJson('/api/ressources', $ressourceData);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Ressource créée et validée avec succès'
                ]);

        $this->assertDatabaseHas('ressources', [
            'title' => 'Admin Resource',
            'user_id' => $this->admin->id,
            'is_validated' => true
        ]);
    }

    /**
     * Test la validation des données lors de la création.
     */
    public function test_store_validates_required_fields(): void
    {
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
                        ->postJson('/api/ressources', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title', 'content', 'description', 'status', 'category_id', 'type_ressource_id']);
    }

    /**
     * Test que les utilisateurs ne peuvent pas modifier les ressources d'autres utilisateurs.
     */
    public function test_user_cannot_update_other_user_ressource(): void
    {
        $otherUser = User::factory()->create();
        $token = JWTAuth::fromUser($this->user);
        $ressource = Ressource::factory()->create(['user_id' => $otherUser->id]);

        $updateData = ['title' => 'Unauthorized Update'];

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
                        ->putJson("/api/ressources/{$ressource->id}", $updateData);

        $response->assertStatus(403)
                ->assertJson(['error' => 'Vous n\'êtes pas autorisé à modifier cette ressource']);
    }

    /**
     * Test que les utilisateurs ne peuvent pas supprimer les ressources d'autres utilisateurs.
     */
    public function test_user_cannot_destroy_other_user_ressource(): void
    {
        $otherUser = User::factory()->create();
        $token = JWTAuth::fromUser($this->user);
        $ressource = Ressource::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
                        ->deleteJson("/api/ressources/{$ressource->id}");

        $response->assertStatus(403)
                ->assertJson(['error' => 'Vous n\'êtes pas autorisé à supprimer cette ressource']);

        $this->assertDatabaseHas('ressources', ['id' => $ressource->id]);
    }

    /**
     * Test le tri des ressources.
     */
    public function test_index_sorts_ressources(): void
    {
        Ressource::factory()->create(['title' => 'B Resource', 'status' => 'published']);
        Ressource::factory()->create(['title' => 'A Resource', 'status' => 'published']);

        $response = $this->getJson('/api/ressources?sort_by=title&sort_order=asc');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals('A Resource', $data[0]['title']);
        $this->assertEquals('B Resource', $data[1]['title']);
    }

    /**
     * Test la pagination.
     */
    public function test_index_paginates_results(): void
    {
        Ressource::factory()->count(15)->create(['status' => 'published']);

        $response = $this->getJson('/api/ressources?per_page=5');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
        $this->assertEquals(3, $response->json('pagination.last_page'));
    }
}
