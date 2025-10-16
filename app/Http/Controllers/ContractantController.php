<?php

namespace App\Http\Controllers;

use App\Models\Contractant;
use App\Models\Types_contractant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractantController extends Controller
    {
        public function index()
        {
            $contractants = Contractant::where('societe_id', Auth::user()->societe_id)->paginate(10);
            $typeContractant = Types_contractant::where('societe_id', Auth::user()->societe_id)->paginate(10);
            return view('contractants.index', compact('contractants', 'typeContractant'));
        }


        public function create()
        {
           
            $societeId = auth()->user()->societe_id;
            $types = Types_contractant::where('societe_id', $societeId)->get();

            return view('contractants.create', compact('types'));
        }


        public function store(Request $request)
        {
            $data = $request->validate([
                'categorie' => 'required|in:personne physique,personne morale',
                'nom' => 'nullable|string|max:255',
                'prenom' => 'nullable|string|max:255',
                'raison_sociale' => 'nullable|string|max:255',
                'ice' => 'nullable|string|max:255',
                'type_contractant' => 'nullable|exists:types_contractants,id',
                'email' => 'required|email|unique:contractants,email',
                'ville' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',
                'telephone' => 'required|string|max:20',
            ]);

            $data['societe_id'] = Auth::user()->societe_id;

            Contractant::create($data);

            return redirect()->route('contractants.index')->with('success', 'Contractant ajouté avec succès.');
        }

        public function edit(Contractant $contractant)
        {
            if ($contractant->societe_id != Auth::user()->societe_id) abort(403);

           $types = Types_contractant::where('societe_id', Auth::user()->societe_id)->get();
            return view('contractants.edit', compact('contractant', 'types'));
        }


        public function update(Request $request, Contractant $contractant)
        {
            if ($contractant->societe_id != Auth::user()->societe_id) abort(403);

            $data = $request->validate([
                'categorie' => 'required|in:personne physique,personne morale',
                'nom' => 'nullable|string|max:255',
                'prenom' => 'nullable|string|max:255',
                'raison_sociale' => 'nullable|string|max:255',
                'ice' => 'nullable|string|max:255',
                'type_contractant' => 'nullable|exists:types_contractants,id',
                'email' => 'required|email|unique:contractants,email,' . $contractant->id,
                'ville' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',
                'telephone' => 'required|string|max:20',
            ]);

            $contractant->update($data);

            return redirect()->route('contractants.index')->with('success', 'Contractant mis à jour.');
        }

        public function destroy(Contractant $contractant)
        {
            if ($contractant->societe_id != Auth::user()->societe_id) abort(403);

            $contractant->delete();
            return redirect()->route('contractants.index')->with('success', 'Contractant supprimé.');
        }

            // Afficher un contractant
        public function show(Contractant $contractant)
        {
            // Vérification de la société
            if ($contractant->societe_id != Auth::user()->societe_id) {
                abort(403);
            }

            return view('contractants.show', compact('contractant'));
    }
}
