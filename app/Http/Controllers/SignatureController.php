<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrat;
use App\Models\Signature;
use App\Models\Representants_legaux;

class SignatureController extends Controller
    {
       public function marquerSignature(Request $request, $id)
{
    $contrat = Contrat::with(['signatures.representant', 'workflowEtapes'])->findOrFail($id);

    $request->validate([
        'signature_entity_id' => 'required|exists:representants_legaux,id',
    ]);

    $signataire = Representants_legaux::findOrFail($request->signature_entity_id);

    // Vérifie que ce représentant a le droit de signer ce contrat
    if (!$contrat->peutEtreSignePar($signataire)) {
        return back()->with('error', "Ce représentant n'est pas autorisé à signer ce contrat.");
    }

    // On récupère la société du signataire
    $societe_id = $signataire->societe_id;
   if (in_array($contrat->statut, ['signé', 'approuvé', 'actif','annulé','expiré','renouvelé'])) {
                return back()->with('error', "Impossible d'approuver un contrat déjà {$contrat->statut}.");
            }


    // Enregistrement ou mise à jour de la signature
    Signature::updateOrCreate(
        [
            'contrat_id' => $contrat->id,
            'representant_legal_id' => $signataire->id,
            'societe_id' => $societe_id,
        ],
        [
            'signed_at' => now(),
            // 'signature_image' => $request->signature_image ?? null,
        ]
    );

    // Vérifier si toutes les signatures attendues sont collectées
    if ($contrat->toutesLesSignaturesCollectees()) {
        $contrat->update(['statut' => 'signé']);
    }

    //  Vérifier si toutes les étapes du workflow sont validées
    $allEtapesValidees = $contrat->workflowEtapes()
        ->where('statut', '!=', 'approuvé')
        ->count() === 0;

    if ($allEtapesValidees) {
        $contrat->update(['statut' => 'actif']);
    }

    return back()->with('success', 'Signature enregistrée avec succès !' . ($allEtapesValidees ? ' Le contrat est maintenant actif.' : ''));
}

    }