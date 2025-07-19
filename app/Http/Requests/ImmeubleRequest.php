<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImmeubleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'type' => 'required|in:appartement,maison,studio,bureau,commerce',
            'nombre_pieces' => 'required|integer|min:1',
            'superficie' => 'required|numeric|min:1',
            'prix_mensuel' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'statut' => 'sometimes|in:disponible,loue,maintenance',
            'proprietaire_id' => 'required|exists:proprietaires,id'
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom de l\'immeuble est obligatoire',
            'adresse.required' => 'L\'adresse est obligatoire',
            'type.required' => 'Le type d\'immeuble est obligatoire',
            'type.in' => 'Le type doit être: appartement, maison, studio, bureau ou commerce',
            'nombre_pieces.required' => 'Le nombre de pièces est obligatoire',
            'nombre_pieces.min' => 'Le nombre de pièces doit être au minimum 1',
            'superficie.required' => 'La superficie est obligatoire',
            'superficie.min' => 'La superficie doit être positive',
            'prix_mensuel.required' => 'Le prix mensuel est obligatoire',
            'prix_mensuel.min' => 'Le prix mensuel doit être positif',
            'proprietaire_id.required' => 'Le propriétaire est obligatoire',
            'proprietaire_id.exists' => 'Le propriétaire sélectionné n\'existe pas'
        ];
    }
}