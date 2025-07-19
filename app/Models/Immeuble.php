<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Immeuble extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'adresse',
        'type',
        'nombre_pieces',
        'superficie',
        'prix_mensuel',
        'description',
        'statut',
        'proprietaire_id'
    ];

    protected $casts = [
        'prix_mensuel' => 'decimal:2',
        'superficie' => 'decimal:2'
    ];

    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function locationActive()
    {
        return $this->hasOne(Location::class)->where('statut', 'active');
    }

    public function scopeDisponible($query)
    {
        return $query->where('statut', 'disponible');
    }
}