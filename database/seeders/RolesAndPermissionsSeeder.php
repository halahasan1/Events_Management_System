<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage event_types',

            'manage locations',

            'view users',
            'delete users',

            'create events',
            'edit events',
            'delete events',
            'view events',

            'create reservations',
            'edit reservations',
            'view reservations',
            'delete reservations',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'admin' => Permission::all(),
            'organizer' => [
                'create events',
                'edit events',
                'delete events',
                'view events',
            ],
            'reservation manager' => [
                'edit reservations',
                'view reservations',
            ],
            'user' => [
                'view events',
                'create reservations',
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }
    }
}
