<?php

namespace Database\Seeders;

use App\Models\InfoProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InfoProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();

        foreach ($users as $user) {
            InfoProfile::create([
                'userId' => $user->id,
                'firstName' => $faker->firstName(),
                'lastName' => $faker->lastName(),
                'address' => $faker->address(),
                'postalCode' => $faker->postcode(),
                'city' => $faker->city(),
                'country' => $faker->country(),
                'updateDate' => now(),
            ]);
        }
    }
}
