<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clausier extends Model
{
    use HasFactory;
     protected $fillable = [
        'societe_id',
        'langue',
        'type_clause',
        'designation',
        'contenu',
    ];

    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    public function clausesContrats()
{
    return $this->hasMany(Clauses_contrat::class);
}

}
