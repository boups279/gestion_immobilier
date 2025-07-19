<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Immeuble;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LocationRequest;
use Carbon\Carbon;

class LocationController extends Controller
{
    public function index(): JsonResponse
    {
        $locations = Location::with(['locataire', 'immeuble.proprietaire', 'paiements'])
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $locations,
            'message' => 'Liste des locations récupérée avec succès'
        ]);
    }

    public function store(LocationRequest $request): JsonResponse
    {
        try {
            // Vérifier que l'immeuble est disponible
            $immeuble = Immeuble::find($request->immeuble_id);
            if ($immeuble->statut !== 'disponible') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet immeuble n\'est pas disponible pour la location'
                ], 400);
            }

            $location = Location::create($request->validated());

            // Mettre à jour le statut de l'immeuble
            $immeuble->update(['statut' => 'loue']);

            return response()->json([
                'success' => true,
                'data' => $location->load(['locataire', 'immeuble.proprietaire']),
                'message' => 'Location créée avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Location $location): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $location->load(['locataire', 'immeuble.proprietaire', 'paiements']),
            'message' => 'Location récupérée avec succès'
        ]);
    }

    public function update(LocationRequest $request, Location $location): JsonResponse
    {
        try {
            $location->update($request->validated());

            return response()->json([
                'success' => true,
                'data' => $location->load(['locataire', 'immeuble.proprietaire']),
                'message' => 'Location mise à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Location $location): JsonResponse
    {
        try {
            if ($location->immeuble) {
                $location->immeuble->update(['statut' => 'disponible']);
            } else {
                // Optionnel : log ou message d'alerte
            }

            $location->delete();

            return response()->json([
                'success' => true,
                'message' => 'Location supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function terminer(Location $location): JsonResponse
    {
        try {
            $location->update([
                'statut' => 'terminee',
                'date_fin' => Carbon::now()
            ]);

            $location->immeuble->update(['statut' => 'disponible']);

            return response()->json([
                'success' => true,
                'data' => $location->load(['locataire', 'immeuble']),
                'message' => 'Location terminée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la terminaison de la location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function expireeBientot(): JsonResponse
    {
        $locations = Location::expireeBientot(30)
            ->with(['locataire', 'immeuble.proprietaire'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $locations,
            'message' => 'Locations qui expirent bientôt récupérées avec succès'
        ]);
    }
}
