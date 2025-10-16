<?php

namespace App\Http\Controllers;

use App\Models\Annees_fiscale;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnneeFiscaleController extends Controller
{
    /**
     * Afficher la liste des années fiscales.
     */
  

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return view('configuration.annees_fiscales.create');
    }

    /**
     * Enregistrer une nouvelle année fiscale.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'annee' => 'required|digits:4|integer|unique:annees_fiscales,annee,NULL,id,societe_id,' . Auth::user()->societe_id,
        ]);

        Annees_fiscale::create([
            'annee' => $data['annee'],
            'societe_id' => Auth::user()->societe_id,
        ]);

        return redirect()->route('parametrage')
                         ->with('success', 'Année fiscale créée avec succès.');
    }

    /**
     * Afficher le formulaire d'édition.
     */
public function edit(Annees_fiscale $anneeFiscale)
{
    // Vérifier que l'année appartient à la société de l'utilisateur
    if ($anneeFiscale->societe_id != Auth::user()->societe_id) {
        abort(403);
    }

    // Retourner les données pour remplir le modal
    return response()->json([
        'id' => $anneeFiscale->id,
        'annee' => $anneeFiscale->annee,
    ]);
}


    /**
     * Mettre à jour une année fiscale existante.
     */
   public function update(Request $request, Annees_fiscale $anneeFiscale)
{
    // Vérifier que l'année appartient à la société de l'utilisateur
    if ($anneeFiscale->societe_id != Auth::user()->societe_id) {
        abort(403);
    }

    // Validation
    $data = $request->validate([
        'annee' => 'required|digits:4|integer|unique:annees_fiscales,annee,' 
                   . $anneeFiscale->id . ',id,societe_id,' . Auth::user()->societe_id,
    ]);

    // Mise à jour
    $anneeFiscale->update($data);

    // Réponse AJAX
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Année fiscale mise à jour avec succès.',
            'annee' => $anneeFiscale->annee,
        ]);
    }

    // Réponse normale
    return redirect()->route('parametrage')
                     ->with('success', 'Année fiscale mise à jour avec succès.');
}

    /**
     * Supprimer une année fiscale.
     */
    public function destroy(Annees_fiscale $anneeFiscale)
    {
        if ($anneeFiscale->societe_id != Auth::user()->societe_id) {
            abort(403);
        }

        $anneeFiscale->delete();

        return redirect()->route('parametrage')
                         ->with('success', 'Année fiscale supprimée avec succès.');
    }
}
