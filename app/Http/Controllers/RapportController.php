<?php

namespace App\Http\Controllers;

use App\Services\PaiementService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Location;
use App\Models\Paiement;
use App\Models\Immeuble;
use App\Models\Locataire;
use Carbon\Carbon;

class RapportController extends Controller
{
    protected $paiementService;

    public function __construct(PaiementService $paiementService)
    {
        $this->paiementService = $paiementService;
    }

    public function rapportMensuel(Request $request): JsonResponse
    {
        $request->validate([
            'mois' => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2020|max:2030'
        ]);

        $rapport = $this->paiementService->genererRapportMensuel(
            $request->mois, 
            $request->annee
        );

        return response()->json([
            'success' => true,
            'data' => $rapport,
            'message' => 'Rapport mensuel généré avec succès'
        ]);
    }

    public function tableauDeBord(): JsonResponse
    {
        $stats = [
            'total_proprietes' => Immeuble::count(),
            'proprietes_louees' => Immeuble::where('statut', 'loue')->count(),
            'proprietes_disponibles' => Immeuble::where('statut', 'disponible')->count(),
            'total_locataires' => Locataire::count(),
            'locations_actives' => Location::where('statut', 'active')->count(),
            'revenus_mois_actuel' => Paiement::pourMois(Carbon::now()->month, Carbon::now()->year)
                                           ->where('statut', 'paye')
                                           ->sum('montant'),
            'paiements_en_retard' => Paiement::enRetard()->count(),
            'locations_expire_bientot' => Location::expireeBientot(30)->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Tableau de bord récupéré avec succès'
        ]);
    }

    public function retardsParLocataire(): JsonResponse
    {
        $locataires = Locataire::with(['locationActive.immeuble'])->get();
        $retards = [];

        foreach ($locataires as $locataire) {
            $retardsLocataire = $this->paiementService->calculerRetards($locataire);
            if (!empty($retardsLocataire)) {
                $retards[] = [
                    'locataire' => $locataire,
                    'retards' => $retardsLocataire,
                    'total_retard' => array_sum(array_column($retardsLocataire, 'montant_attendu'))
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $retards,
            'message' => 'Retards par locataire récupérés avec succès'
        ]);
    }
}