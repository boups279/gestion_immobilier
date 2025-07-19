<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocataireRequest extends FormRequest
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
            'email' => 'required|email|unique:locataires,email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'date_naissance' => 'nullable|date|before:today',
            'profession' => 'nullable|string|max:255',
            'salaire' => 'nullable|numeric|min:0'
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['email'] = 'required|email|unique:locataires,email,' . $this->locataire->id;
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
            'salaire.min' => 'Le salaire doit être positif'
        ];
    }
}