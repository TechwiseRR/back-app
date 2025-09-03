<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Ressource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test que le modèle Category peut être créé avec les attributs fillable.
     */
    public function test_category_can_be_created_with_fillable_attributes(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test description for category',
        ]);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Test Category', $category->name);
        $this->assertEquals('Test description for category', $category->description);
    }

    /**
     * Test la relation avec les ressources.
     */
    public function test_category_has_many_ressources(): void
    {
        $category = Category::factory()->create();
        $ressources = Ressource::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->ressources);
        $this->assertInstanceOf(Ressource::class, $category->ressources->first());
    }

    /**
     * Test que le modèle utilise HasFactory.
     */
    public function test_category_uses_has_factory_trait(): void
    {
        $category = new Category();
        $this->assertTrue(method_exists($category, 'factory'));
    }

    /**
     * Test que la factory peut créer une catégorie.
     */
    public function test_category_factory_can_create_category(): void
    {
        $category = Category::factory()->create();
        
        $this->assertInstanceOf(Category::class, $category);
        $this->assertNotEmpty($category->name);
    }

    /**
     * Test la création d'une catégorie sans description.
     */
    public function test_category_can_be_created_without_description(): void
    {
        $category = Category::create([
            'name' => 'Category Without Description',
        ]);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Category Without Description', $category->name);
        $this->assertNull($category->description);
    }
} 