<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Respect de l'ordre des dépendances
        $this->call([
            // Tables de base sans dépendances
            CategorySeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            TypeRessourceSeeder::class,
            UserSeeder::class,
            RessourceSeeder::class,
            HistoriqueActionSeeder::class,
            InfoProfileSeeder::class,
            NotificationSeeder::class,
            RelationSeeder::class,
            CommentSeeder::class,
            VoteSeeder::class,
            RessourceValidationSeeder::class,
        ]);
    }
}
