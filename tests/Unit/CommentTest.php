<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use App\Models\Ressource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test que le modèle Comment peut être créé avec les attributs fillable.
     */
    public function test_comment_can_be_created_with_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $ressource = Ressource::factory()->create();

        $comment = Comment::create([
            'content' => 'Test comment content',
            'creation_date' => now(),
            'user_id' => $user->id,
            'ressource_id' => $ressource->id,
        ]);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals('Test comment content', $comment->content);
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($ressource->id, $comment->ressource_id);
    }

    /**
     * Test la relation avec l'utilisateur.
     */
    public function test_comment_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }

    /**
     * Test la relation avec la ressource.
     */
    public function test_comment_belongs_to_ressource(): void
    {
        $ressource = Ressource::factory()->create();
        $comment = Comment::factory()->create(['ressource_id' => $ressource->id]);

        $this->assertInstanceOf(Ressource::class, $comment->ressource);
        $this->assertEquals($ressource->id, $comment->ressource->id);
    }

    /**
     * Test que le modèle utilise HasFactory.
     */
    public function test_comment_uses_has_factory_trait(): void
    {
        $comment = new Comment();
        $this->assertTrue(method_exists($comment, 'factory'));
    }

    /**
     * Test que la factory peut créer un commentaire.
     */
    public function test_comment_factory_can_create_comment(): void
    {
        $comment = Comment::factory()->create();
        
        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertNotEmpty($comment->content);
        $this->assertNotNull($comment->creation_date);
        $this->assertNotNull($comment->user_id);
        $this->assertNotNull($comment->ressource_id);
    }

    /**
     * Test la création d'un commentaire avec date automatique.
     */
    public function test_comment_creation_date_is_set(): void
    {
        $user = User::factory()->create();
        $ressource = Ressource::factory()->create();

        $comment = Comment::create([
            'content' => 'Comment with auto date',
            'user_id' => $user->id,
            'ressource_id' => $ressource->id,
        ]);
        $comment->refresh();
        $this->assertNotNull($comment->creation_date);
    }
} 