<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Méditation', 'description' => 'Techniques et ressources pour la relaxation et la concentration.'],
            ['name' => 'Nutrition', 'description' => 'Régimes alimentaires et conseils pour une alimentation saine.'],
            ['name' => 'Exercice', 'description' => 'Activités physiques pour améliorer la santé et le bien-être.'],
            ['name' => 'Santé mentale', 'description' => 'Ressources pour maintenir un bon équilibre mental.'],
            ['name' => 'Développement personnel', 'description' => 'Outils pour améliorer vos compétences et votre bien-être global.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
