<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Types_pieces_jointe extends Model
{
    use HasFactory;

     protected $table = 'types_pieces_jointes';
    // Autoriser l'assignation en masse pour ces champs
    protected $fillable = [
        'libelle',
        'societe_id',
    ];
}
