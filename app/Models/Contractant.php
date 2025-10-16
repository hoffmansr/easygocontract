<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Contractant extends Model
{
    use HasFactory, Notifiable;
     protected $fillable = [
        'societe_id',
        'categorie',
        'nom',
        'prenom',
        'raison_sociale',
        'ice',
        'type_contractant',
        'email',
        'ville',
        'adresse',
        'telephone',
    ];

    // Relations optionnelles
    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    public function typeContractant()
    {
        return $this->belongsTo(Types_contractant::class, 'type_contractant');
    }
    
     public function contrats() {
        return $this->belongsToMany(Contrat::class,'contrats_contractants','contractant_id','contrat_id');
    }
}
