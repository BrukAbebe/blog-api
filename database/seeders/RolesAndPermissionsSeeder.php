<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create or update permissions
        $permissions = [
            'create posts',
            'edit posts',
            'delete posts',
            'view posts',
            'create comments',
            'edit comments',
            'delete comments',
            'view comments',
            'create likes',
            'delete likes',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'sanctum',
            ]);
        }

        // Create or update roles and assign permissions
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'sanctum',
        ]);
        $admin->syncPermissions($permissions);

        $author = Role::firstOrCreate([
            'name' => 'author',
            'guard_name' => 'sanctum',
        ]);
        $author->syncPermissions($permissions);

        $viewer = Role::firstOrCreate([
            'name' => 'viewer',
            'guard_name' => 'sanctum',
        ]);
        $viewer->syncPermissions([
            'view posts',
            'view comments',
            'create comments',
            'create likes',
            'delete likes',
        ]);
    }
}