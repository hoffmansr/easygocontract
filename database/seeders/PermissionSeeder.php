<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Societe;
use Spatie\Permission\PermissionRegistrar;




class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissionsByModule = [
            'utilisateurs' =>
             [
                 'Gérer les utilisateurs',
                'Créer des utilisateurs',
                'Modifier des utilisateurs',
                'Supprimer des utilisateurs',
                'Voir la liste des utilisateurs',
                'Gérer les rôles et permissions',
                'Assigner des rôles aux utilisateurs',
                'Voir les détails des utilisateurs',
                 'Activer/Désactiver des utilisateurs'
           ],
        //  Gestion des contrats
            'Contrats' => [
                'Gérer les contrats',
                'Créer des contrats',
                'Modifier des contrats',
                'Supprimer des contrats',
                'Voir la liste des contrats',
                'Dupliquer des contrats',
                'Archiver des contrats',
                'Voir l\'historique des contrats'
            ],

     //  Gestion  modèles 
             'modèles' => [
                
                'Gérer les modèles de contrats',
                'Créer des modèles',
                'Modifier des modèles',
                'Supprimer des modèles',
                'Voir la liste des modèles'
               
            ],

        //  Gestion clausiers
                'clauses' => [
                'Gérer les bibliothèques de clauses',
                'Créer des clauses',
                'Modifier des clauses',
                'Supprimer des clauses',
               
            ],



            'Workflows d\'approbation' => [
                'definir workflows',
                'modifier orkflows',
                'supprimer workflows',
                'valider contrat',
                'rejeter contrat',
                'consulter istorique approbation',
            ],

           

            'Signature électronique' => [
                'Gérer les signatures électroniques',
                'Envoyer des contrats à signer',
                'Suivre le statut des signatures',
                'Télécharger les contrats signés',
                'Voir l\'historique des signatures',
                'Annuler des demandes de signature',
                'Archiver les contrats signés'
            ],
        ];

       $societes = Societe::all();

       
            foreach ( $permissionsByModule as $module => $permissions) {
                foreach ($permissions as $perm) {
                    Permission::firstOrCreate([
                        'name' => $perm,
                        
                        'guard_name' => 'web',
                    ]);
                }
            }
        

        // vider le cache des permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
