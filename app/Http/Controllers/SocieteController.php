<?php

namespace App\Http\Controllers;
use App\Models\Societe;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
// ...

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
        'statut' => 'required|in:actif,suspendu',
        // Utilisateur
        'user_name' => 'required',
        'user_prenom' => 'required',
        'user_email' => 'required|email|unique:users,email',
        'user_password' => 'required|min:8|confirmed',
    ]);

    $logoPath = $request->hasFile('logo')
        ? $request->file('logo')->store('logos', 'public')
        : null;

    // --- Création société ---
    $societe = Societe::create([
        'pays' => $request->pays,
        'ville' => $request->ville,
        'adresse' => $request->adresse,
        'raison_sociale' => $request->raison_sociale,
        'ice' => $request->ice,
        'logo' => $logoPath,
        'statut' => $request->statut,
    ]);

    // --- Création utilisateur principal ---
    $user = User::create([
        'name' => $request->user_name,
        'prenom' => $request->user_prenom,
        'email' => $request->user_email,
        'password' => Hash::make($request->user_password),
        'societe_id' => $societe->id,
    ]);

    // --- Créer un rôle "Admin Société" spécifique à cette société ---
    $roleName = 'Admin Société ' . $societe->id;
    $role = Role::firstOrCreate([
        'name' => $roleName,
        'guard_name' => 'web',
         'societe_id' => $societe->id,
    ]);

    // --- Donner toutes les permissions à ce rôle ---
    $permissions = Permission::pluck('name')->toArray();
    $role->syncPermissions($permissions);

    // --- Assigner le rôle à l'utilisateur ---
    $user->assignRole($role);

    // (Optionnel) envoyer un mail avec identifiants

    return redirect()->route('societes.index')
        ->with('success', 'Société et utilisateur créés avec succès ! Le premier utilisateur est administrateur.');
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
            // Société
            'pays' => 'required',
            'ville' => 'required',
            'adresse' => 'required',
            'raison_sociale' => 'required',
            'ice' => 'required|unique:societes,ice,' . $societe->id,
            'logo' => 'nullable|image',
            'statut' => 'required|in:actif,suspendu',

            // Utilisateur principal
            'user_name' => 'required',
            'user_prenom' => 'required',
            'user_email' => 'required|email|unique:users,email,' . $societe->users()->first()?->id,
            'user_password' => 'nullable|min:8|confirmed',
        ]);

        // --- Gestion du logo
        $logoPath = $societe->logo;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // --- Mise à jour société
        $societe->update([
            'pays' => $request->pays,
            'ville' => $request->ville,
            'adresse' => $request->adresse,
            'raison_sociale' => $request->raison_sociale,
            'ice' => $request->ice,
            'logo' => $logoPath,
            'statut' => $request->statut,
        ]);

        // --- Mise à jour de l’utilisateur principal
        $user = $societe->users()->first(); // ou ->where('role','Admin Société')->first()
        if ($user) {
            $user->update([
                'name' => $request->user_name,
                'prenom' => $request->user_prenom,
                'email' => $request->user_email,
                'password' => $request->filled('user_password')
                    ? Hash::make($request->user_password)
                    : $user->password, // garder l'ancien si vide
            ]);
        }

        return redirect()->route('societes.index')->with('success', 'Société et utilisateur principal mis à jour avec succès !');
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
