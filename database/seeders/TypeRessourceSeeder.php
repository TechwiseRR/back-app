<?php

namespace Database\Seeders;

use App\Models\TypeRessource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeRessourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Article', 'description' => 'Documents écrits pour transmettre des connaissances.'],
            ['name' => 'Vidéo', 'description' => 'Supports visuels pour apprendre ou se relaxer.'],
            ['name' => 'Podcast', 'description' => 'Contenus audio pour se détendre ou s’informer.'],
            ['name' => 'Exercice', 'description' => 'Activités pratiques pour améliorer le bien-être.'],
            ['name' => 'Cours', 'description' => 'Séances éducatives pour approfondir ses connaissances.'],
        ];

        foreach ($types as $type) {
            TypeRessource::create($type);
        }
    }
}
