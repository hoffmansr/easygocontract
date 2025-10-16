<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Crée le rôle super_admin
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        // Crée l'utilisateur super admin (identifiants fixes)
       $superAdmin = User::updateOrCreate(
        ['email' => env('SUPER_ADMIN_EMAIL')],
        [
            'name' => 'Super Admin',
            'password' => Hash::make(env('SUPER_ADMIN_PASSWORD')),
        ]
    );


        // Assigne le rôle super_admin à l'utilisateur
        $superAdmin->assignRole($role);
    }
}