<?php

namespace Database\Seeders;

use App\Models\Ressource;
use App\Models\RessourceValidation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RessourceValidationSeeder extends Seeder
{
    /**
     * Remplit la table des validations de ressources avec des données simulées.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR'); // Générateur en français
        $ressources = Ressource::all();
        $validators = User::all();

        // On vérifie qu’il existe des ressources et des utilisateurs pour effectuer les validations
        if ($ressources->isEmpty() || $validators->isEmpty()) {
            return;
        }

        foreach ($ressources as $ressource) {
            $status = $faker->randomElement(['pending', 'approved', 'rejected']);

            // Commentaire généré en fonction du statut de validation
            $comment = match ($status) {
                'approved' => $faker->randomElement([
                    'Contenu clair et bien structuré.',
                    'Bonne ressource, rien à redire.',
                    'Validée sans modification nécessaire.',
                    'Ressource pertinente et conforme.'
                ]),
                'rejected' => $faker->randomElement([
                    'Contenu incomplet ou confus.',
                    'Nécessite des sources supplémentaires.',
                    'Pas conforme aux critères de validation.',
                    'Doit être retravaillée avant publication.'
                ]),
                'pending' => null, // En attente : pas encore de commentaire
            };

            RessourceValidation::create([
                'status' => $status, // État de la validation
                'comment' => $comment, // Commentaire explicatif ou null
                'validation_date' => $faker->dateTimeThisYear(), // Date réaliste
                'ressource_id' => $ressource->id, // Ressource concernée
                'validator_id' => $validators->random()->id, // Utilisateur en charge de la validation
            ]);
        }
    }
}
