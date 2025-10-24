<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    use HasFactory;
    protected $fillable = [
        'societe_id','type_contrat_id','titre','annee_fiscale_id','date_debut',
        'date_fin','type_renouvelement','description','notes_generales',
        'workflow_id','notif_contractant','statut', 'date_notification',
        'duree_auto_renouvellement','jours_preavis_resiliation','signature_entity_id'
    ];
    protected $casts = [
    'date_debut' => 'date',
    'date_fin' => 'date', // si tu gardes date_fin
];


    public function societe() {
        return $this->belongsTo(Societe::class);
    }

    public function typesContrat() {
        return $this->belongsTo(Types_contrat::class,'type_contrat_id');
    }

    public function contractants() {
        return $this->belongsToMany(Contractant::class,'contrats_contractants','contrat_id','contractant_id');
    }

    public function anneeFiscales() {
        return $this->belongsTo(Annees_fiscale::class,'annee_fiscale_id');
    }

    public function clausesContrat() {
        return $this->hasMany(Clauses_contrat::class,'contrat_id');
    }

    public function documents() {
        return $this->hasMany(Documents_contrat::class,'contrat_id');
    }

    public function workflow() {
        return $this->belongsTo(Workflow::class);
    }

  public function workflowEtapes()
{
    return $this->hasMany(ContratWorkflowEtape::class, 'contrat_id');
}

public function signatures()
{
    return $this->hasMany(Signature::class);
}


   

public function scopeForApprover($query, $userId)
{
    return $query->where('statut', 'en_approbation')
                 ->whereHas('workflow.etapes', fn($q) => $q->where('user_id', $userId));
}

    public function representantLegal() {
        return $this->belongsTo(Representants_legaux::class,'signature_entity_id');
    }


    public function anneeFiscale() {
        return $this->belongsTo(Annees_fiscale::class,'annee_fiscale_id');
    }
    // === MÉTHODES MÉTIER ===

    /** Vérifie si un représentant légal est autorisé à signer ce contrat */
    public function peutEtreSignePar(Representants_legaux $representant)
    {
        // Autorisé si sa société est celle du contrat ou parmi les contractants
        return $this->societe_id === $representant->societe_id
            || $this->contractants()->where('societe_id', $representant->societe_id)->exists();
    }

    /** Vérifie si toutes les signatures attendues ont été collectées */
    public function toutesLesSignaturesCollectees()
    {
        // Par exemple : 1 signature de la société + 1 de chaque contractant
        $signaturesAttendues = 1 + $this->contractants()->count();
        $signaturesRecues = $this->signatures()->count();

        return $signaturesRecues >= $signaturesAttendues;
    }


}
