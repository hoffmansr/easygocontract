<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{



        public function index()
        {
            $roles = Role::where('societe_id', auth()->user()->societe_id)->paginate(10);
            return view('roles.index', compact('roles'));
        }

        public function create()
    {
        $permissionsByModule = [
            'utilisateurs' => [
                'Gérer les utilisateurs','Créer des utilisateurs','Modifier des utilisateurs','Supprimer des utilisateurs',
                'Voir la liste des utilisateurs','Gérer les rôles et permissions','Assigner des rôles aux utilisateurs',
                'Voir les détails des utilisateurs','Activer/Désactiver des utilisateurs'
            ],
            'Contrats' => [
                'Gérer les contrats','Créer des contrats','Modifier des contrats','Supprimer des contrats',
                'Voir la liste des contrats','Dupliquer des contrats','Archiver des contrats','Voir l\'historique des contrats'
            ],

            'Modèles de contrats' => [
                  'Gérer les modèles de contrats',
                'Créer des modèles',
                'Modifier des modèles',
                'Supprimer des modèles',
                'Voir la liste des modèles'
            ],
            'clauses' => [
                 'Gérer les bibliothèques de clauses',
                'Créer des clauses',
                'Modifier des clauses',
                'Supprimer des clauses',
            ],
            'workflows' => [
                'definir workflows',
                'modifier orkflows',
                'supprimer workflows',
                'valider contrat',
                'rejeter contrat',
                'consulter istorique approbation',
            ],
            'signatures électroniques' => [
                'Gérer les signatures électroniques',
                'Envoyer des contrats à signer',
                'Suivre le statut des signatures',
                'Télécharger les contrats signés',
                'Voir l\'historique des signatures',
                'Annuler des demandes de signature',
                'Archiver les contrats signés'
            ],


            // ... ajoute les autres modules ici comme dans le seeder
        ];

        return view('roles.create', compact('permissionsByModule'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        if (!$user->societe_id) {
            return redirect()->back()->with('error', 'Vous devez être rattaché à une société.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
            'societe_id' => $user->societe_id,
        ]);

        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', 'Rôle créé avec succès.');
        
    }
    

    public function edit($id)
    {
        $role = Role::findById($id);

        if (!$role) {
            return redirect()->route('roles.index')->with('error', 'Rôle non trouvé.');
        }

        $permissionsByModule = [
            'utilisateurs' => [
                'Gérer les utilisateurs','Créer des utilisateurs','Modifier des utilisateurs','Supprimer des utilisateurs',
                'Voir la liste des utilisateurs','Gérer les rôles et permissions','Assigner des rôles aux utilisateurs',
                'Voir les détails des utilisateurs','Activer/Désactiver des utilisateurs'
            ],
            'Contrats' => [
                'Gérer les contrats','Créer des contrats','Modifier des contrats','Supprimer des contrats',
                'Voir la liste des contrats','Dupliquer des contrats','Archiver des contrats','Voir l\'historique des contrats'
            ],

            'Modèles de contrats' => [
                  'Gérer les modèles de contrats',
                'Créer des modèles',
                'Modifier des modèles',
                'Supprimer des modèles',
                'Voir la liste des modèles'
            ],
            'clauses' => [
                 'Gérer les bibliothèques de clauses',
                'Créer des clauses',
                'Modifier des clauses',
                'Supprimer des clauses',
            ],
            'workflows' => [
                'definir workflows',
                'modifier orkflows',
                'supprimer workflows',
                'valider contrat',
                'rejeter contrat',
                'consulter istorique approbation',
            ],
            'signatures électroniques' => [
                'Gérer les signatures électroniques',
                'Envoyer des contrats à signer',
                'Suivre le statut des signatures',
                'Télécharger les contrats signés',
                'Voir l\'historique des signatures',
                'Annuler des demandes de signature',
                'Archiver les contrats signés'
            ],
        ];
        return view('roles.edit', compact('role', 'permissionsByModule'));
            

}

    public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'permissions' => 'array',
            ]);

            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();

            // Synchroniser les permissions
            $role->syncPermissions($request->permissions ?? []);

            return redirect()->route('roles.index')
                ->with('success', 'Rôle mis à jour avec succès.');
        }

public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Détacher les permissions pour éviter erreurs de FK
        $role->permissions()->detach();

        // Supprimer le rôle
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }
  }


