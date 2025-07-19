<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProprietaireRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:proprietaires,email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'date_naissance' => 'nullable|date|before:today'
        ];

        // Pour la mise à jour, ignorer l'email actuel
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['email'] = 'required|email|unique:proprietaires,email,' . $this->proprietaire->id;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom est obligatoire',
            'prenom.required' => 'Le prénom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email est déjà utilisé',
            'telephone.required' => 'Le téléphone est obligatoire',
            'adresse.required' => 'L\'adresse est obligatoire',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui'
        ];
    }
}