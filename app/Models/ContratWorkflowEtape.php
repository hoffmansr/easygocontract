<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratWorkflowEtape extends Model
{
    use HasFactory;
    protected $fillable = ['contrat_id', 'workflow_etape_id', 'approver_id', 'statut'];

    public function etape()
    {
        return $this->belongsTo(Workflow_etape::class, 'workflow_etape_id');
    }

    public function contrat()
{
    return $this->belongsTo(Contrat::class);
}

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
