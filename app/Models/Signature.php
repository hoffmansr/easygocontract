<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = [
        'contrat_id',
        'representant_legal_id',
        'societe_id',
        'signed_at',
        'signature_image',
        'external_signature_url',
        'external_status',
    ];
    public function representant()
    {
        return $this->belongsTo(Representants_legaux::class, 'representant_legal_id');
    }

    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }
}
    