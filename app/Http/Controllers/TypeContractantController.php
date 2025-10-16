<?php

namespace App\Http\Controllers;

use App\Models\Types_contractant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeContractantController extends Controller
{
    // // Liste
    // public function index()
    // {
    //     $contractants = Types_contractant::where('societe_id', Auth::user()->societe_id)->get();
    //     return view('configuration.contractants.index', compact('contractants'));
    // }

    // Création
    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
        ]);

        $data['societe_id'] = Auth::user()->societe_id;

       Types_contractant::create($data);

        return redirect()->back()->with('success', 'Type de contractant ajouté avec succès.');
    }

        // Mise à jour
        public function update(Request $request, Types_contractant $types_contractant)
        {
            $data = $request->validate([
                'libelle' => 'required|string|max:255',
            ]);

            $types_contractant->update($data);

            return redirect()->back()->with('success', 'Type de contractant mis à jour avec succès.');
        }

        // Suppression
        public function destroy(Types_contractant $types_contractant)
        {
            $types_contractant->delete();
            return redirect()->back()->with('success', 'Type de contractant supprimé avec succès.');
        }
}
