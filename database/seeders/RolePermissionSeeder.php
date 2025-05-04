<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Define roles
        $roles = [
            'admin',
            'editor',
            'user',
        ];

        // Define permissions
        $permissions = [
            'manage users',
            'edit articles',
            'view articles',
        ];

        // Create roles
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create permissions
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Assign permissions to roles
        $rolePermissions = [
            'admin'  => $permissions, // Admin gets all permissions
            'editor' => ['edit articles', 'view articles'],
            'user'   => ['view articles'],
        ];

        foreach ($rolePermissions as $roleName => $rolePerms) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($rolePerms);
            }
        }
    }
}
