<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            ]
        );
        $user->assignRole($userRole);
    }
}
