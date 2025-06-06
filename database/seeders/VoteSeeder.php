<?php

namespace Database\Seeders;

use App\Models\Ressource;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();
        $ressources = Ressource::all();

        // Vérifie qu'il y a des utilisateurs et des ressources dans la base
        if ($users->isEmpty() || $ressources->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            // Calcule le nombre max de ressources pouvant être sélectionnées
            $maxRessourcesToVoteOn = min($ressources->count(), 5);

            // Si le maximum est supérieur à zéro, sélectionne des ressources
            if ($maxRessourcesToVoteOn > 0) {
                $randomRessources = $ressources->random(rand(1, $maxRessourcesToVoteOn));

                // Crée des votes uniques pour chaque ressource
                foreach ($randomRessources as $ressource) {
                    Vote::create([
                        'type' => $faker->randomElement(['like', 'dislike']),
                        'creation_date' => now(),
                        'user_id' => $user->id,
                        'ressource_id' => $ressource->id,
                    ]);
                }
            }
        }
    }
}
