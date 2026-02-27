<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::whereIn('name', [
            'Super Admin',
            'Admin',
            'Owner',
            'Staff',
        ])->get()->keyBy('name');

        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@springbed.id'],
            [
                'name'      => 'Super Admin',
                'password'  => Hash::make('qawsedrf'),
                'is_active' => true,
            ]
        );
        $superAdmin->syncRoles([$roles['Super Admin']]);

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@springbed.id'],
            [
                'name'      => 'Admin',
                'password'  => Hash::make('qawsedrf'),
                'is_active' => true,
            ]
        );
        $admin->syncRoles([$roles['Admin']]);

        // Owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@springbed.id'],
            [
                'name'      => 'Owner',
                'password'  => Hash::make('qawsedrf'),
                'is_active' => true,
            ]
        );
        $owner->syncRoles([$roles['Owner']]);

        // Staff
        $staff = User::firstOrCreate(
            ['email' => 'staff@springbed.id'],
            [
                'name'      => 'Staff',
                'password'  => Hash::make('qawsedrf'),
                'is_active' => true,
            ]
        );
        $staff->syncRoles([$roles['Staff']]);
    }
}
