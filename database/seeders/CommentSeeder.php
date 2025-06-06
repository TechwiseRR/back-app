<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Ressource;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommentSeeder extends Seeder
{
    /**
     * Remplit la base de données avec des commentaires factices
     * pour chaque ressource disponible.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR'); // Générateur de données factices en français
        $users = User::all();
        $ressources = Ressource::all();

        // On vérifie qu’il y a bien des utilisateurs et des ressources à commenter
        if ($users->isEmpty() || $ressources->isEmpty()) {
            return;
        }

        foreach ($ressources as $ressource) {
            // On attribue entre 1 et 10 commentaires à chaque ressource
            for ($i = 0; $i < rand(1, 10); $i++) {
                Comment::create([
                    // Un contenu de commentaire un peu plus naturel et varié
                    'content' => $faker->randomElement([
                        'Merci pour cette ressource, elle m’a beaucoup aidé !',
                        'Je ne suis pas d’accord avec certains points abordés.',
                        'Très bon document, bien structuré.',
                        'Quelqu’un a-t-il essayé cette méthode ? Des retours ?',
                        'Excellent travail, bravo à l’auteur.',
                        'Je trouve le sujet très intéressant, merci du partage.',
                        'Je pense qu’il manque quelques explications.',
                        'La ressource est utile, mais la mise en page pourrait être améliorée.',
                        'J’ai appris pas mal de choses grâce à cette ressource.',
                        'Des recommandations de lecture complémentaire ?'
                    ]),
                    'creation_date' => now(),
                    'modification_date' => now(),
                    'user_id' => $users->random()->id, // Auteur aléatoire
                    'ressource_id' => $ressource->id,   // Ressource ciblée
                ]);
            }
        }
    }
}
