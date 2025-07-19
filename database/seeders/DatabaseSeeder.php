<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Proprietaire;
use App\Models\Locataire;
use App\Models\Immeuble;
use App\Models\Location;
use App\Models\Paiement;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Créer des utilisateurs
        User::create([
            'name' => 'Admin',
            'email' => 'admin@immobilier.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Agent',
            'email' => 'agent@immobilier.com',
            'password' => bcrypt('password'),
            'role' => 'agent'
        ]);

        // Créer des propriétaires
        $proprietaires = [
            [
                'nom' => 'Diallo',
                'prenom' => 'Mamadou',
                'email' => 'mamadou.diallo@email.com',
                'telephone' => '77 123 45 67',
                'adresse' => 'Plateau, Dakar',
                'date_naissance' => '1975-03-15'
            ],
            [
                'nom' => 'Fall',
                'prenom' => 'Awa',
                'email' => 'awa.fall@email.com',
                'telephone' => '78 234 56 78',
                'adresse' => 'Almadies, Dakar',
                'date_naissance' => '1980-07-22'
            ]
        ];

        foreach ($proprietaires as $proprietaire) {
            Proprietaire::create($proprietaire);
        }

        // Créer des locataires
        $locataires = [
            [
                'nom' => 'Sow',
                'prenom' => 'Fatou',
                'email' => 'fatou.sow@email.com',
                'telephone' => '77 345 67 89',
                'adresse' => 'Medina, Dakar',
                'date_naissance' => '1990-05-10',
                'profession' => 'Enseignante',
                'salaire' => 450000
            ],
            [
                'nom' => 'Ba',
                'prenom' => 'Omar',
                'email' => 'omar.ba@email.com',
                'telephone' => '78 456 78 90',
                'adresse' => 'Parcelles Assainies, Dakar',
                'date_naissance' => '1985-11-28',
                'profession' => 'Ingénieur',
                'salaire' => 750000
            ]
        ];

        foreach ($locataires as $locataire) {
            Locataire::create($locataire);
        }

        // Créer des immeubles
        $immeubles = [
            [
                'nom' => 'Résidence du Plateau',
                'adresse' => 'Avenue Léopold Sédar Senghor, Plateau',
                'type' => 'appartement',
                'nombre_pieces' => 3,
                'superficie' => 85.5,
                'prix_mensuel' => 350000,
                'description' => 'Bel appartement 3 pièces avec vue sur mer',
                'statut' => 'loue',
                'proprietaire_id' => 1
            ],
            [
                'nom' => 'Villa des Almadies',
                'adresse' => 'Route des Almadies',
                'type' => 'maison',
                'nombre_pieces' => 5,
                'superficie' => 200,
                'prix_mensuel' => 650000,
                'description' => 'Villa moderne avec piscine',
                'statut' => 'disponible',
                'proprietaire_id' => 2
            ]
        ];

        foreach ($immeubles as $immeuble) {
            Immeuble::create($immeuble);
        }

        // Créer des locations
        $location = Location::create([
            'locataire_id' => 1,
            'immeuble_id' => 1,
            'date_debut' => Carbon::now()->subMonths(6),
            'date_fin' => Carbon::now()->addMonths(6),
            'montant_mensuel' => 350000,
            'caution' => 700000,
            'statut' => 'active'
        ]);

        // Créer des paiements
        for ($i = 1; $i <= 6; $i++) {
            $date = Carbon::now()->subMonths(7 - $i);
            Paiement::create([
                'location_id' => $location->id,
                'locataire_id' => 1,
                'montant' => 350000,
                'date_paiement' => $date,
                'mois_concerne' => $date->month,
                'annee_concernee' => $date->year,
                'type_paiement' => 'virement',
                'statut' => 'paye'
            ]);
        }
    }
}