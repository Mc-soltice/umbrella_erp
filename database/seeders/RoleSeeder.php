<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'user.toggle-lock',
            'user.view-activity'

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Création des rôles
        $comptable = Role::firstOrCreate(['name' => 'comptable']);
        $intendant = Role::firstOrCreate(['name' => 'intendant']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $responsable = Role::firstOrCreate(['name' => 'responsable']);
        $admin = Role::firstOrCreate(['name' => 'admin']);



        // Attribution de permissions
        $admin->givePermissionTo(Permission::all()); // admin a tout
        $manager->givePermissionTo(['user.view', 'user.update']);
        $intendant->givePermissionTo(['user.view', 'user.update']);
        $responsable->givePermissionTo(['user.view']);

    }
}
