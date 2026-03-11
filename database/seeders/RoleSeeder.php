<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // Create Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@seatify.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        // Optionally create a typical user for testing
        $user = User::firstOrCreate(
            ['email' => 'user@seatify.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
            ],
        );
        $user->assignRole($userRole);

        $newuser = User::firstOrCreate(
            ['email' => 'newuser@seatify.com'],
            [
                'name' => 'New User',
                'password' => Hash::make('password'),
            ],
        );
        $newuser->assignRole($userRole);
    }
}
