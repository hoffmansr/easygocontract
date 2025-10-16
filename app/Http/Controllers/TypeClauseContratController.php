<?php

namespace App\Http\Controllers;

use App\Models\Types_clauses_contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeClauseContratController extends Controller
{
        // public function index()
        // {
        //     $clauses = TypeClauseContrat::where('societe_id', Auth::user()->societe_id)->get();
        //     return view('clauses.index', compact('clauses'));
        // }

        public function store(Request $request)
        {
            $data = $request->validate([
                'libelle' => 'required|string|max:255',
            ]);

            $data['societe_id'] = Auth::user()->societe_id;

            Types_clauses_contrat::create($data);

            return redirect()->back()->with('success', 'Type de clause ajouté avec succès.');
        }
        

        public function update(Request $request, Types_clauses_contrat $types_clauses_contrat)
        {

            if ($types_clauses_contrat->societe_id != Auth::user()->societe_id) abort(403);

            $data = $request->validate([
                'libelle' => 'required|string|max:255',
            ]);

            $types_clauses_contrat->update($data);

            return redirect()->back()->with('success', 'Type de clause mis à jour.');
        }

        public function destroy(Types_clauses_contrat $types_clauses_contrat)
        {
            if ($types_clauses_contrat->societe_id != Auth::user()->societe_id) abort(403);

            $types_clauses_contrat->delete();

            return redirect()->back()->with('success', 'Type de clause supprimé.');
        }
}
