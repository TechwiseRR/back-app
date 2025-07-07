<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use App\Models\Ressource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test l'affichage de la liste des commentaires.
     */
    public function test_can_get_comments_list(): void
    {
        Comment::factory()->count(3)->create();

        $response = $this->getJson('/api/comments');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * Test l'affichage d'un commentaire spécifique.
     */
    public function test_can_get_specific_comment(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->getJson("/api/comments/{$comment->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $comment->id,
            'content' => $comment->content,
            'user_id' => $comment->user_id,
            'ressource_id' => $comment->ressource_id,
        ]);
    }

    /**
     * Test la création d'un nouveau commentaire.
     */
    public function test_can_create_comment(): void
    {
        $user = User::factory()->create();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $ressource = Ressource::factory()->create();

        $commentData = [
            'content' => 'New comment content',
            'resource_id' => $ressource->id,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson('/api/comments', $commentData);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Commentaire ajouté avec succès',
            'comment' => [
                'content' => 'New comment content',
                'user_id' => $user->id,
                'ressource_id' => $ressource->id,
            ]
        ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'New comment content',
            'user_id' => $user->id,
            'ressource_id' => $ressource->id,
        ]);
    }

    /**
     * Test la mise à jour d'un commentaire.
     */
    public function test_can_update_comment(): void
    {
        $user = User::factory()->create();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $comment = Comment::factory()->create();
        
        $updateData = [
            'content' => 'Updated comment content',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Commentaire mis à jour avec succès',
            'comment' => [
                'content' => 'Updated comment content',
            ]
        ]);

        $this->assertDatabaseHas('comments', $updateData);
    }

    /**
     * Test la suppression d'un commentaire.
     */
    public function test_can_delete_comment(): void
    {
        $user = User::factory()->create();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $comment = Comment::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Commentaire supprimé avec succès']);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    /**
     * Test que la validation fonctionne pour la création.
     */
    public function test_validation_works_for_creation(): void
    {
        $user = User::factory()->create();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson('/api/comments', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content', 'resource_id']);
    }

    /**
     * Test la réponse à un commentaire.
     */
    public function test_can_reply_to_comment(): void
    {
        $user = User::factory()->create();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $parentComment = Comment::factory()->create();

        $replyData = [
            'content' => 'Reply to comment',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson("/api/comments/{$parentComment->id}/reply", $replyData);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Réponse ajoutée',
            'comment' => [
                'content' => 'Reply to comment',
                'user_id' => $user->id,
                'ressource_id' => $parentComment->ressource_id,
            ]
        ]);
    }
} 