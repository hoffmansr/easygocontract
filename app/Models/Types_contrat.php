<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Types_contrat extends Model
{
    use HasFactory;
    

    protected $table = 'types_contrats';

    protected $fillable = [
        'libelle',
        'societe_id',
        'type_contractant',
        'actif',
    ];

    // Relation vers TypeContractant
    public function typeContractant()
    {
        return $this->belongsTo(Types_contractant::class, 'type_contractant');
    }
}
