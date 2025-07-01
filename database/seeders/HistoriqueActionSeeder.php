<?php

namespace Database\Seeders;

use App\Models\HistoriqueAction;
use App\Models\Ressource;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class HistoriqueActionSeeder extends Seeder
{
    /**
     * Remplit la base de données avec des actions simulées sur les ressources :
     * création, modification ou suppression.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR'); // Génère des données factices en français
        $users = User::all();
        $ressources = Ressource::all();

        // On génère 10 actions historiques simulées
        for ($i = 0; $i < 10; $i++) {
            $action = $faker->randomElement(['Création', 'Modification', 'Suppression']);

            // Génère une description plus explicite en fonction de l'action
            $description = match ($action) {
                'Création' => 'Une nouvelle ressource a été ajoutée.',
                'Modification' => 'Des informations ont été mises à jour.',
                'Suppression' => 'La ressource a été supprimée de la base.',
                default => 'Action inconnue.',
            };

            HistoriqueAction::create([
                'action' => $action, // Type d’action effectuée
                'description' => $description, // Détail compréhensible de l’action
                'actionDate' => $faker->dateTimeThisYear(), // Date aléatoire dans l’année en cours
                'userId' => $users->isNotEmpty() ? $users->random()->id : null, // Utilisateur lié à l’action
                'ressourceId' => $ressources->isNotEmpty() ? $ressources->random()->id : null, // Ressource concernée
            ]);
        }
    }
}

