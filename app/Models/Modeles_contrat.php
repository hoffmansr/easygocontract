<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modeles_contrat extends Model
{
    use HasFactory;
    protected $table = 'modeles_contrats';

    protected $fillable = [
        'societe_id',
        'langue',
        'titre',
        'description',
        'type_contrat',
        'fichier_modele',
        'html_contenu',
        'actif',
        'variables'
    ];
        protected $casts = [
        'variables' => 'array',
    ];

    // Relation avec la société
    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    // Relation avec type de contrat
    public function typeContrat()
    {
        return $this->belongsTo(Types_contrat::class, 'type_contrat');
    }

     public function clauses() {
        return $this->hasMany(Clauses_contrat::class,'modele_contrat_id');
    }
}
