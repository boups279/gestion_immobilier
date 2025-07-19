<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LocataireRequest;

class LocataireController extends Controller
{
    public function index(): JsonResponse
    {
        $locataires = Locataire::with(['locationActive.immeuble', 'paiements'])
                              ->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $locataires,
            'message' => 'Liste des locataires récupérée avec succès'
        ]);
    }

    public function store(LocataireRequest $request): JsonResponse
    {
        try {
            $locataire = Locataire::create($request->validated());
            
            return response()->json([
                'success' => true,
                'data' => $locataire,
                'message' => 'Locataire créé avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du locataire',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Locataire $locataire): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $locataire->load(['locations.immeuble.proprietaire', 'paiements']),
            'message' => 'Locataire récupéré avec succès'
        ]);
    }

    public function update(LocataireRequest $request, Locataire $locataire): JsonResponse
    {
        try {
            $locataire->update($request->validated());
            
            return response()->json([
                'success' => true,
                'data' => $locataire,
                'message' => 'Locataire mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du locataire',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Locataire $locataire): JsonResponse
    {
        try {
            $locataire->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Locataire supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du locataire',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}