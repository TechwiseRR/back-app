<?php

namespace Database\Seeders;

use App\Models\Relation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class RelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();

        foreach ($users as $user) {
            // Chaque utilisateur aura quelques relations aléatoires
            $relatedUsers = $users->where('id', '!=', $user->id)->random(rand(1, 5)); // Eviter de se relier à lui-même

            foreach ($relatedUsers as $relatedUser) {
                Relation::create([
                    'type' => $faker->randomElement(['friend', 'follow', 'block']),
                    'creation_date' => now(),
                    'user_id' => $user->id,
                    'related_user_id' => $relatedUser->id,
                ]);
            }
        }
    }
}

