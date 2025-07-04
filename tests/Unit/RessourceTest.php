<?php

namespace Tests\Unit;

use App\Models\Ressource;
use App\Models\User;
use App\Models\Category;
use App\Models\TypeRessource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RessourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test que le modèle Ressource peut être créé avec les attributs fillable.
     */
    public function test_ressource_can_be_created_with_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $typeRessource = TypeRessource::factory()->create();

        $ressource = Ressource::create([
            'title' => 'Test Resource',
            'content' => 'Test content',
            'description' => 'Test description',
            'publication_date' => now(),
            'status' => 'published',
            'validation_date' => now(),
            'category_id' => $category->id,
            'user_id' => $user->id,
            'validator_id' => null,
            'type_ressource_id' => $typeRessource->id,
            'is_validated' => true,
        ]);

        $this->assertInstanceOf(Ressource::class, $ressource);
        $this->assertEquals('Test Resource', $ressource->title);
        $this->assertEquals('Test content', $ressource->content);
        $this->assertEquals('published', $ressource->status);
        $this->assertTrue($ressource->is_validated);
    }

    /**
     * Test la relation avec la catégorie.
     */
    public function test_ressource_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $ressource = Ressource::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $ressource->category);
        $this->assertEquals($category->id, $ressource->category->id);
    }

    /**
     * Test la relation avec l'utilisateur créateur.
     */
    public function test_ressource_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $ressource = Ressource::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $ressource->user);
        $this->assertEquals($user->id, $ressource->user->id);
    }

    /**
     * Test la relation avec le type de ressource.
     */
    public function test_ressource_belongs_to_type(): void
    {
        $typeRessource = TypeRessource::factory()->create();
        $ressource = Ressource::factory()->create(['type_ressource_id' => $typeRessource->id]);

        $this->assertInstanceOf(TypeRessource::class, $ressource->type);
        $this->assertEquals($typeRessource->id, $ressource->type->id);
    }

    /**
     * Test que le modèle utilise HasFactory.
     */
    public function test_ressource_uses_has_factory_trait(): void
    {
        $ressource = new Ressource();
        $this->assertTrue(method_exists($ressource, 'factory'));
    }

    /**
     * Test que la factory peut créer une ressource.
     */
    public function test_ressource_factory_can_create_ressource(): void
    {
        $ressource = Ressource::factory()->create();
        
        $this->assertInstanceOf(Ressource::class, $ressource);
        $this->assertNotEmpty($ressource->title);
        $this->assertNotEmpty($ressource->content);
        $this->assertNotEmpty($ressource->description);
    }
} 