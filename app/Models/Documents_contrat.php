<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents_contrat extends Model
{
    use HasFactory;
    protected $fillable = ['contrat_id','fichier_url'];

    public function contrat() {
        return $this->belongsTo(Contrat::class,'contrat_id');
    }
}
