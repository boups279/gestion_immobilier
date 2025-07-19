<?php

namespace App\Http\Controllers;

use App\Models\Proprietaire;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProprietaireRequest;

class ProprietaireController extends Controller
{

    public function store(ProprietaireRequest $request): JsonResponse
    {
        try {
            $proprietaire = Proprietaire::create($request->validated());

            return response()->json([
                'success' => true,
                'data' => $proprietaire,
                'message' => 'Propriétaire créé avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du propriétaire',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $proprietaires = Proprietaire::all();

        return response()->json([
            'success' => true,
            'data' => $proprietaires,
            'message' => 'Liste des propriétaires récupérée avec succès'
        ]);
    }


    public function show(Proprietaire $proprietaire): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $proprietaire->load(['immeubles', 'locations.locataire']),
            'message' => 'Propriétaire récupéré avec succès'
        ]);
    }

    public function update(ProprietaireRequest $request, Proprietaire $proprietaire): JsonResponse
    {
        try {
            $proprietaire->update($request->validated());

            return response()->json([
                'success' => true,
                'data' => $proprietaire->load('immeubles'),
                'message' => 'Propriétaire mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du propriétaire',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Proprietaire $proprietaire): JsonResponse
    {
        try {
            $proprietaire->delete();

            return response()->json([
                'success' => true,
                'message' => 'Propriétaire supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du propriétaire',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
