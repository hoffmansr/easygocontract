<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Representants_legaux;
use Illuminate\Support\Facades\Auth;

class RepresentantsLegauxController extends Controller
{
    // public function index()
    // {
    //     $representants = RepresentantLegal::where('societe_id', Auth::user()->societe_id)->get();
    //     return view('parametrage.representants', compact('representants'));
    // }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
        ]);
        $data['societe_id'] = Auth::user()->societe_id;

        Representants_legaux::create($data);

        return redirect()->back()->with('success', 'Représentant légal ajouté avec succès.');
    }

    public function update(Request $request, Representants_legaux $representant)
    {
        if ($representant->societe_id != Auth::user()->societe_id) abort(403);

        $data = $request->validate([
            'libelle' => 'required|string|max:255',
        ]);

        $representant->update($data);

        return redirect()->back()->with('success', 'Représentant légal mis à jour.');
    }

    public function destroy(Representants_legaux $representant)
    {
        if ($representant->societe_id != Auth::user()->societe_id) abort(403);

        $representant->delete();

        return redirect()->back()->with('success', 'Représentant légal supprimé.');
    }
}
