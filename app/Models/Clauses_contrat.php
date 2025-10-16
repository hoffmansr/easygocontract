<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clauses_contrat extends Model
{
    use HasFactory;
    protected $fillable = ['contrat_id','modele_contrat_id'];

    public function modeleContrat() {
        return $this->belongsTo(Modeles_contrat::class,'modele_contrat_id');
    }

    public function contrat() {
        return $this->belongsTo(Contrat::class,'contrat_id');
    }

    public function clausier()
{
    return $this->belongsTo(Clausier::class);
}
}
