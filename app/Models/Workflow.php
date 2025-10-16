<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    use HasFactory;

    protected $fillable = ['societe_id', 'libelle', 'statut'];

    public function etapes()
    {
        return $this->hasMany(Workflow_etape::class)->orderBy('niveau');
    }
}
