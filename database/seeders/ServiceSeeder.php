<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        Service::create([
            'libelle' => 'Repassage',
            'description' => 'Repassage soigné des vêtements',
            'prix_unitaire' => 1000,
            'disponible' => true,
        ]);

        Service::create([
            'libelle' => 'Nettoyage à sec',
            'description' => 'Nettoyage à sec pour les vêtements délicats',
            'prix_unitaire' => 2500,
            'disponible' => true,
        ]);

        Service::create([
            'libelle' => 'Lavage de costume',
            'description' => 'Nettoyage et entretien complet des costumes',
            'prix_unitaire' => 5000,
            'disponible' => true,
        ]);

        Service::create([
            'libelle' => 'Lavage de couette',
            'description' => 'Lavage professionnel des couettes et couvertures',
            'prix_unitaire' => 6000,
            'disponible' => true,
        ]);

        Service::create([
            'libelle' => 'Lavage de chaussures',
            'description' => 'Nettoyage des chaussures et baskets',
            'prix_unitaire' => 3000,
            'disponible' => true,
        ]);

        Service::create([
            'libelle' => 'Détachage',
            'description' => 'Traitement des taches difficiles sur les vêtements',
            'prix_unitaire' => 2000,
            'disponible' => true,
        ]);

        Service::create([
            'libelle' => 'Service express',
            'description' => 'Traitement prioritaire de vos vêtements',
            'prix_unitaire' => 3000,
            'disponible' => true,
        ]);


        Service::create([
            'libelle' => 'Teinture',
            'description' => 'Service de teinture des vêtements',
            'prix_unitaire' => 4000,
            'disponible' => false,
        ]);
    }
}

