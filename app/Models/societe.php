<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class societe extends Model
{
    use HasFactory;
    protected $fillable = [
        'pays', 'ville', 'adresse', 'raison_sociale', 'ice', 'logo', 'statut'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function typesContractants()
{
    return $this->hasMany(Types_contractant::class, 'societe_id');
}
    public function representantsLegaux()
    {
        return $this->hasMany(Representants_legaux::class, 'societe_id');
    }

    public function typesClausesContrat()
    {
        return $this->hasMany(Types_clauses_contrat::class, 'societe_id');
    }

    public function contractants()
    {
        return $this->hasMany(Contractant::class, 'societe_id');
    }

    public function clausiers()
    {
        return $this->hasMany(Clausier::class, 'societe_id');
    }
}
