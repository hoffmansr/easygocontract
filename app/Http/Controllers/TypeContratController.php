<?php

namespace App\Http\Controllers;

use App\Models\Types_contrat;
use App\Models\Types_contractant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeContratController extends Controller
{
                // Liste
            public function index()
        {
            $types_contrats = Types_contrat::where('societe_id', Auth::user()->societe_id)->get();
             $typeContractant = Types_contractant::where('societe_id', Auth::user()->societe_id)->get();
            return view('types_contrats.index', compact('types_contrats', 'typeContractant'));
        }

        // Créer
        public function create()
        {
            $typeContractants = Types_contractant::where('societe_id', Auth::user()->societe_id)->get();
            return view('types_contrats.create', compact('typeContractants'));
        }

        public function store(Request $request)
        {
            $data = $request->validate([
                'libelle' => 'required|string|max:255',
                'type_contractant' => 'nullable|exists:types_contractants,id',
               
            ]);

            $data['societe_id'] = Auth::user()->societe_id;
            $data['actif'] = $request->has('actif') ? 1 : 0;


            Types_contrat::create($data);

            return redirect()->route('types_contrats.index')->with('success', 'Type de contrat ajouté.');
        }

        // Edit
        public function edit(Types_contrat $types_contrat)
        {
            if ($types_contrat->societe_id != Auth::user()->societe_id) abort(403);

            $typeContractants = Types_contractant::where('societe_id', Auth::user()->societe_id)->get();

            return view('types_contrats.edit', compact('types_contrat', 'typeContractants'));
        }

        public function update(Request $request, Types_contrat $types_contrat)
        {
            if ($types_contrat->societe_id != Auth::user()->societe_id) abort(403);

            $data = $request->validate([
                'libelle' => 'required|string|max:255',
                'type_contractant' => 'nullable|exists:types_contractants,id',
                'actif' => 'required|boolean',
            ]);

            $types_contrat->update($data);

            return redirect()->route('types_contrats.index')->with('success', 'Type de contrat mis à jour.');
        }

        // Show
        public function show(Types_contrat $types_contrat)
        {
            if ($types_contrat->societe_id != Auth::user()->societe_id) abort(403);
            return view('types_contrats.show', compact('types_contrat'));
        }

        // Supprimer
        public function destroy(Types_contrat $types_contrat)
        {
            if ($types_contrat->societe_id != Auth::user()->societe_id) abort(403);

            $types_contrat->delete();

            return redirect()->route('types_contrats.index')->with('success', 'Type de contrat supprimé.');
        }
}
