<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;



class UserController extends Controller
{
    public function __construct()
{
    $this->middleware('permission:Créer des utilisateurs')->only(['create']);
    $this->middleware('permission:Modifier des utilisateurs')->only(['edit']);
    $this->middleware('permission:Supprimer des utilisateurs')->only(['destroy']);
   
}

    /**
     * Liste des utilisateurs
     */
    public function index()
    {
        $users = User::with('roles')->where('societe_id', auth()->user()->societe_id)->get();
        return view('users.index', compact('users'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
       $societeId = auth()->user()->societe_id;
       $roles = Role::where('societe_id', $societeId)->get();

        $userTypes = ['admin','client' ,'collaborateur' ]; 
        return view('users.create', compact('roles', 'userTypes'));
    }

    /**
     * Enregistrement d’un utilisateur
     */
    public function store(Request $request)
    {
        // 1. Validation
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'prenom'     => 'nullable|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role'       => 'required|string|exists:roles,name',
            'user_type'  => 'nullable|string|max:255',
            'actif'      => 'nullable|boolean',
        ]);

        // 2. Gestion de la photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
        }

        // 3. Création de l’utilisateur
        $user = User::create([
            'name'       => $data['name'],
            'prenom'     => $data['prenom'] ?? null,
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'photo'      => $photoPath,
            'user_type'  => $data['user_type'] ?? null,
            'actif'      => $request->has('actif') ? 1 : 0,
            'societe_id' => auth()->user()->societe_id, // 🔑 rattacher à la société courante
        ]);

        // 4. Attribution du rôle
        $user->assignRole($data['role']);

        // 5. Redirection
        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }



        /**
     * Formulaire d’édition
     */
    public function edit(User $user)
    {
       $societeId = auth()->user()->societe_id;
       $roles = Role::where('societe_id', $societeId)->get();

        $userTypes = ['admin','client' ,'collaborateur']; 
        return view('users.edit', compact('user', 'roles', 'userTypes'));
    }

    /**
     * Mise à jour d’un utilisateur
     */
    public function update(Request $request, User $user)
    {
        // 1. Validation
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'prenom'     => 'nullable|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|string|min:8|confirmed',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role'       => 'required|string|exists:roles,name',
            'user_type'  => 'nullable|string|max:255',
            'actif'      => 'nullable|boolean',
        ]);

            // 2. Gestion de la photo
            if ($request->hasFile('photo')) {
                // Supprimer l’ancienne photo si besoin
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                $user->photo = $request->file('photo')->store('users', 'public');
            }

            // 3. Mise à jour des champs
            $user->name       = $data['name'];
            $user->prenom     = $data['prenom'] ?? null;
            $user->email      = $data['email'];
            $user->user_type  = $data['user_type'] ?? null;
            $user->actif      = $request->has('actif') ? 1 : 0;

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            // 4. Mise à jour du rôle
            $user->syncRoles([$data['role']]);

            // 5. Redirection
            return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
        }
        public function show(User $user)
        {
            return view('users.show', compact('user'));
        }

    

        /**
         * Suppression d’un utilisateur
         */
        public function destroy(User $user)
        {
            // Supprimer la photo si elle existe
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $user->delete();

            return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
        }

        public function activate(User $user)
        {
            $user->update(['actif' => true]);
            return back()->with('success', 'Utilisateur activé.');
        }

        public function deactivate(User $user)
        {
            $user->update(['actif' => false]);
            return back()->with('success', 'Utilisateur désactivé.');
        }
}
