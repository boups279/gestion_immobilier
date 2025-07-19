<?php

namespace App\Http\Controllers;

use App\Models\Immeuble;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ImmeubleRequest;

class ImmeubleController extends Controller
{
    public function index(): JsonResponse
    {
        $immeubles = Immeuble::with(['proprietaire', 'locationActive.locataire'])
                            ->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $immeubles,
            'message' => 'Liste des immeubles récupérée avec succès'
        ]);
    }

    public function store(ImmeubleRequest $request): JsonResponse
    {
        try {
            $immeuble = Immeuble::create($request->validated());
            
            return response()->json([
                'success' => true,
                'data' => $immeuble->load('proprietaire'),
                'message' => 'Immeuble créé avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'immeuble',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Immeuble $immeuble): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $immeuble->load(['proprietaire', 'locations.locataire', 'locations.paiements']),
            'message' => 'Immeuble récupéré avec succès'
        ]);
    }

    public function update(ImmeubleRequest $request, Immeuble $immeuble): JsonResponse
    {
        try {
            $immeuble->update($request->validated());
            
            return response()->json([
                'success' => true,
                'data' => $immeuble->load('proprietaire'),
                'message' => 'Immeuble mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'immeuble',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Immeuble $immeuble): JsonResponse
    {
        try {
            $immeuble->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Immeuble supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'immeuble',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function disponibles(): JsonResponse
    {
        $immeubles = Immeuble::disponible()
                            ->with('proprietaire')
                            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $immeubles,
            'message' => 'Immeubles disponibles récupérés avec succès'
        ]);
    }
}