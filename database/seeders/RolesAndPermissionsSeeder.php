<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        
        // Define permissions
        $permissions = [
            'create events',
            'edit events',
            'delete events',
            'view events',
            'manage users',
            'create reservations',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles and assign permissions
        $roles = [
            'admin' => Permission::all(), // all permissions
            'organizer' => [
                'create events',
                'edit events',
                'delete events',
                'view events',
            ],
            'user' => [
                'view events',
                'create reservations',
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            if ($perms instanceof \Illuminate\Support\Collection) {
                $role->syncPermissions($perms);
            } else {
                $role->syncPermissions($perms);
            }
        }
    }
}
