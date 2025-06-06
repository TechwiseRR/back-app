<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ressource;
use App\Models\TypeRessource;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RessourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $types = TypeRessource::all();
        $categories = Category::all();

        $ressources = [
            [
                'title' => 'Introduction à la pleine conscience',
                'description' => 'Une courte introduction sur les techniques de pleine conscience.',
                'content' => 'Ce contenu explique comment intégrer la pleine conscience dans votre vie quotidienne.', // Ajout de content
                'url' => 'https://example.com/mindfulness',
                'type_ressource_id' => $types->random()->id,
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'publication_date' => now(),
            ],
            [
                'title' => 'Yoga pour débutants',
                'description' => 'Des exercices simples pour développer votre flexibilité et réduire le stress.',
                'content' => 'Ce contenu contient des exercices pratiques de yoga pour les débutants.', // Ajout de content
                'url' => 'https://example.com/yoga-basics',
                'type_ressource_id' => $types->random()->id,
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'publication_date' => now(),
            ],
            [
                'title' => 'Méditation guidée pour mieux dormir',
                'description' => 'Un podcast pour se détendre et optimiser votre sommeil.',
                'content' => 'Ce contenu inclut une méditation guidée pour un meilleur sommeil.', // Ajout de content
                'url' => 'https://example.com/sleep-meditation',
                'type_ressource_id' => $types->random()->id,
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'publication_date' => now(),
            ],
        ];

        foreach ($ressources as $ressource) {
            Ressource::create($ressource);
        }


    }
}
