<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'locataire_id',
        'immeuble_id',
        'date_debut',
        'date_fin',
        'montant_mensuel',
        'caution',
        'statut',
        'conditions_particulieres'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'montant_mensuel' => 'decimal:2',
        'caution' => 'decimal:2'
    ];

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function proprietaire()
    {
        return $this->immeuble->proprietaire();
    }

    public function getDureeEnMoisAttribute()
    {
        if ($this->date_debut && $this->date_fin) {
            return $this->date_debut->diffInMonths($this->date_fin);
        }
        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }

    public function scopeExpireeBientot($query, $jours = 30)
    {
        return $query->where('date_fin', '<=', Carbon::now()->addDays($jours));
    }
}