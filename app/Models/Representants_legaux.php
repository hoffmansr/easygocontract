<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representants_legaux extends Model
{
    use HasFactory;

    protected $table = 'representants_legaux';

    protected $fillable = [
        'libelle',
        'societe_id',
    ];
}
