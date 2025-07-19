<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locataire extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'date_naissance',
        'profession',
        'salaire'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'salaire' => 'decimal:2',
    ];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function locationActive()
    {
        return $this->hasOne(\App\Models\Location::class)
            ->where('statut', 'active');
    }
}
