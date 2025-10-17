<?php
// database/seeders/RolePermissionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Liste des permissions
        $permissions = [
            'view', 
            'create', 
            'update', 
            'delete'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Liste des rôles
        $roles = [
            'responsable', 
            'gestionnaire', 
            'comptable', 
            'intendant'
        ];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($permissions); // Chaque rôle a toutes les permissions de base
        }
    }
}
