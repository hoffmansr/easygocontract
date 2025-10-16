<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Types_clauses_contrat extends Model
{
    use HasFactory;

    protected $table = 'types_clauses_contrats';

    protected $fillable = [
        'societe_id',
        'libelle',
    ];
}
