<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaiementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'location_id' => 'required|exists:locations,id',
            'locataire_id' => 'required|exists:locataires,id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'mois_concerne' => 'required|integer|min:1|max:12',
            'annee_concernee' => 'required|integer|min:2020|max:2030',
            'type_paiement' => 'required|in:especes,cheque,virement,carte',
            'reference' => 'nullable|string|max:255',
            'statut' => 'sometimes|in:paye,en_retard,annule',
            'remarques' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'location_id.required' => 'La location est obligatoire',
            'location_id.exists' => 'La location sélectionnée n\'existe pas',
            'locataire_id.required' => 'Le locataire est obligatoire',
            'locataire_id.exists' => 'Le locataire sélectionné n\'existe pas',
            'montant.required' => 'Le montant est obligatoire',
            'montant.min' => 'Le montant doit être positif',
            'date_paiement.required' => 'La date de paiement est obligatoire',
            'mois_concerne.required' => 'Le mois concerné est obligatoire',
            'mois_concerne.between' => 'Le mois doit être entre 1 et 12',
            'annee_concernee.required' => 'L\'année concernée est obligatoire',
            'type_paiement.required' => 'Le type de paiement est obligatoire',
            'type_paiement.in' => 'Le type de paiement doit être: espèces, chèque, virement ou carte'
        ];
    }
}