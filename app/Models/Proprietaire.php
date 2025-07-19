<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proprietaire extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'date_naissance'
    ];

    protected $casts = [
        'date_naissance' => 'date'
    ];

    public function immeubles()
    {
        return $this->hasMany(Immeuble::class);
    }

    public function locations()
    {
        return $this->hasManyThrough(Location::class, Immeuble::class);
    }
}