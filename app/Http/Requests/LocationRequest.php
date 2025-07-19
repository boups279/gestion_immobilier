<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'locataire_id' => 'required|exists:locataires,id',
            'immeuble_id' => 'required|exists:immeubles,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'nullable|date|after:date_debut',
            'montant_mensuel' => 'required|numeric|min:0',
            'caution' => 'nullable|numeric|min:0',
            'statut' => 'sometimes|in:active,terminee,suspendue',
            'conditions_particulieres' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'locataire_id.required' => 'Le locataire est obligatoire',
            'locataire_id.exists' => 'Le locataire sélectionné n\'existe pas',
            'immeuble_id.required' => 'L\'immeuble est obligatoire',
            'immeuble_id.exists' => 'L\'immeuble sélectionné n\'existe pas',
            'date_debut.required' => 'La date de début est obligatoire',
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé',
            'date_fin.after' => 'La date de fin doit être après la date de début',
            'montant_mensuel.required' => 'Le montant mensuel est obligatoire',
            'montant_mensuel.min' => 'Le montant mensuel doit être positif'
        ];
    }
}