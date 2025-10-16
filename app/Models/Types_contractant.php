<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Types_contractant extends Model
{
    use HasFactory;
    
    protected $fillable = ['societe_id', 'libelle'];

    protected $table = 'types_contractants';

    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }
}
