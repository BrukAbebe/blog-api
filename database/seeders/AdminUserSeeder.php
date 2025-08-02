<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure admin role exists for sanctum guard
        if (!Role::where('name', 'admin')->where('guard_name', 'sanctum')->exists()) {
            throw new \Exception('Admin role does not exist for sanctum guard. Run RolesAndPermissionsSeeder first.');
        }

        // Create or update admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('87654321'),
            ]
        );

        // Assign admin role (uses sanctum guard from User model)
        $admin->assignRole('admin');
    }
}