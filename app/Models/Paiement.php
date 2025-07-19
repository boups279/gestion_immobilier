<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'locataire_id',
        'montant',
        'date_paiement',
        'mois_concerne',
        'annee_concernee',
        'type_paiement',
        'reference',
        'statut',
        'remarques'
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant' => 'decimal:2'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function immeuble()
    {
        return $this->location->immeuble();
    }

    public function scopePourMois($query, $mois, $annee)
    {
        return $query->where('mois_concerne', $mois)
                    ->where('annee_concernee', $annee);
    }

    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'en_retard');
    }
}