<?php

namespace App\Services;

use App\Models\Paiement;
use App\Models\Location;
use App\Models\Locataire;
use Carbon\Carbon;

class PaiementService
{
    public function genererPaiementsManquants(Location $location)
    {
        $paiments = [];
        $dateDebut = $location->date_debut;
        $dateFin = $location->date_fin ?? Carbon::now();
        
        $periode = $dateDebut->copy();
        
        while ($periode->lte($dateFin)) {
            $paiementExiste = Paiement::where('location_id', $location->id)
                                    ->where('mois_concerne', $periode->month)
                                    ->where('annee_concernee', $periode->year)
                                    ->exists();
            
            if (!$paiementExiste) {
                $paiments[] = [
                    'location_id' => $location->id,
                    'locataire_id' => $location->locataire_id,
                    'mois_concerne' => $periode->month,
                    'annee_concernee' => $periode->year,
                    'montant_attendu' => $location->montant_mensuel,
                    'statut' => $periode->lt(Carbon::now()) ? 'en_retard' : 'en_attente'
                ];
            }
            
            $periode->addMonth();
        }
        
        return $paiments;
    }

    public function calculerRetards(Locataire $locataire)
    {
        $locations = $locataire->locations()->where('statut', 'active')->get();
        $retards = [];
        
        foreach ($locations as $location) {
            $paimensManquants = $this->genererPaiementsManquants($location);
            $retards = array_merge($retards, array_filter($paimensManquants, function($p) {
                return $p['statut'] === 'en_retard';
            }));
        }
        
        return $retards;
    }

    public function genererRapportMensuel($mois, $annee)
    {
        $paiements = Paiement::pourMois($mois, $annee)
                            ->with(['locataire', 'location.immeuble.proprietaire'])
                            ->get();
        
        $totalPaye = $paiements->where('statut', 'paye')->sum('montant');
        $totalAttendu = Paiement::pourMois($mois, $annee)->sum('montant');
        $nombreRetards = $paiements->where('statut', 'en_retard')->count();
        
        return [
            'mois' => $mois,
            'annee' => $annee,
            'total_paye' => $totalPaye,
            'total_attendu' => $totalAttendu,
            'taux_recouvrement' => $totalAttendu > 0 ? ($totalPaye / $totalAttendu) * 100 : 0,
            'nombre_retards' => $nombreRetards,
            'paiements' => $paiements
        ];
    }
}