<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class NotificationSeeder extends Seeder
{
    /**
     * Remplit la base de données avec des notifications simulées
     * pour chaque utilisateur.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR'); // Données en français
        $users = User::all();

        foreach ($users as $user) {
            // Chaque utilisateur reçoit 5 notifications simulées
            for ($i = 0; $i < 5; $i++) {
                $type = $faker->randomElement(['info', 'warning', 'success', 'error']);

                // Titre et message adaptés au type de notification
                $titleMessage = match ($type) {
                    'info' => ['Nouvelle mise à jour disponible', 'Consultez les dernières améliorations apportées à la plateforme.'],
                    'warning' => ['Action requise', 'Votre compte nécessite une vérification supplémentaire.'],
                    'success' => ['Action réussie', 'Votre modification a bien été enregistrée.'],
                    'error' => ['Erreur détectée', 'Une erreur est survenue lors de votre dernière action.'],
                    default => ['Notification', 'Contenu par défaut.'],
                };

                Notification::create([
                    'title' => $titleMessage[0],
                    'message' => $titleMessage[1],
                    'type' => $type, // Type de notification (info, succès, erreur, alerte)
                    'is_read' => $faker->boolean(30), // 30% de chances qu'elle soit lue
                    'creation_date' => $faker->dateTimeThisYear(), // Date réaliste dans l’année
                    'user_id' => $user->id, // Notification associée à l'utilisateur
                ]);
            }
        }
    }
}
