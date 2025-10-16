<?php

namespace App\Http\Controllers;

use App\Models\Clausier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClausierController extends Controller
{
        public function index()
        {
            $clausiers = Clausier::where('societe_id', Auth::user()->societe_id)->get();
            return view('clausiers.index', compact('clausiers'));
        }

        public function create()
        {
            return view('clausiers.create');
        }

        public function store(Request $request)
        {
            $data = $request->validate([
                'langue' => 'required|string|max:10',
                'type_clause' => 'required|string|max:255',
                'designation' => 'required|string|max:255',
                'contenu' => 'nullable|string',
            ]);

            $data['societe_id'] = Auth::user()->societe_id;

            Clausier::create($data);

            return redirect()->route('clausiers.index')->with('success', 'Clausier ajouté avec succès.');
        }

        public function show(Clausier $clausier)
        {
            if ($clausier->societe_id != Auth::user()->societe_id) abort(403);
            return view('clausiers.show', compact('clausier'));
        }

        public function edit(Clausier $clausier)
        {
            if ($clausier->societe_id != Auth::user()->societe_id) abort(403);
            return view('clausiers.edit', compact('clausier'));
        }

        public function update(Request $request, Clausier $clausier)
        {
            if ($clausier->societe_id != Auth::user()->societe_id) abort(403);

            $data = $request->validate([
                'langue' => 'required|string|max:10',
                'type_clause' => 'required|string|max:255',
                'designation' => 'required|string|max:255',
                'contenu' => 'nullable|string',
            ]);

            $clausier->update($data);

            return redirect()->route('clausiers.index')->with('success', 'Clausier mis à jour avec succès.');
        }

        public function destroy(Clausier $clausier)
        {
            if ($clausier->societe_id != Auth::user()->societe_id) abort(403);

            $clausier->delete();
            return redirect()->route('clausiers.index')->with('success', 'Clausier supprimé avec succès.');
        }
}
