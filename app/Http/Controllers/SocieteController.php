<?php

namespace App\Http\Controllers;
use App\Models\Societe;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class SocieteController extends Controller
{

    public function create()
    {
        return view('societes.create');
    }

    public function index()
    {
        $societes = Societe::with('users')->get();

        return view('societes.index', compact('societes'));
    }
    public function store(Request $request)
    {
        $request->validate([
            // Société
            'pays' => 'required',
            'ville' => 'required',
            'adresse' => 'required',
            'raison_sociale' => 'required',
            'ice' => 'required|unique:societes,ice',
            'logo' => 'nullable|image',
            'statut' => 'required|in:active,suspendue',
            // Utilisateur
            'user_name' => 'required',
            'user_email' => 'required|email|unique:users,email',
            'user_password' => 'required|min:8|confirmed',
            
        ]);
            $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Création société
        $societe = Societe::create([
            'pays' => $request->pays,
            'ville' => $request->ville,
            'adresse' => $request->adresse,
            'raison_sociale' => $request->raison_sociale,
            'ice' => $request->ice,
            'logo' => $logoPath,
            'statut' => $request->statut,
        ]);

        // Création premier utilisateur abonné
        $user = User::create([
            'name' => $request->user_name,
            'email' => $request->user_email,
            'password' =>Hash::make($request->user_password),
            'societe_id' => $societe->id,
        ]);
    

        // (Optionnel) envoi mail avec identifiants

        return redirect()->route('societes.index')->with('success', 'Société et utilisateur créés avec succès !');
    }

        public function edit(Societe $societe)
    {
        $user = $societe->users->first();
        return view('societes.edit', compact('societe', 'user'));
    }


    public function show( Societe $societe ){
        $user = $societe->users->first();

        return view('societes.show', compact('societe', 'user'));
    }

public function update(Request $request, $id)
{
    $societe = Societe::findOrFail($id);

    $request->validate([
        'pays' => 'required',
        'ville' => 'required',
        'adresse' => 'required',
        'raison_sociale' => 'required',
        'ice' => 'required|unique:societes,ice,' . $societe->id,
        'logo' => 'nullable|image',
        'statut' => 'required|in:active,suspendue',
    ]);

    $societe->update([
        'pays' => $request->pays,
        'ville' => $request->ville,
        'adresse' => $request->adresse,
        'raison_sociale' => $request->raison_sociale,
        'ice' => $request->ice,
        'statut' => $request->statut,
    ]);

    if ($request->hasFile('logo')) {
        // Supprimer l'ancien logo si nécessaire
        if ($societe->logo) {
            Storage::disk('public')->delete($societe->logo);
        }
        $societe->logo = $request->file('logo')->store('logos', 'public');
        $societe->save();
    }

    return redirect()->route('societes.index')->with('success', 'Société mise à jour avec succès !');   
    

}
       public function destroy(Societe $societe)
{
    // Supprime d'abord tous les types contractants liés
    $societe->typesContractants()->delete();
    $societe->users()->delete();

    // Puis supprime la société
    $societe->delete();

    return redirect()->route('societes.index')->with('success', 'Société supprimée avec succès.');
}

     }
