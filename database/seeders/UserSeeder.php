<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@example.com',
            'phone' => '01234567890',
            'password' => Hash::make('1234'),
            'gender' => 'male',
            'age' => 30,
            'balance' => 10000.00,
            'code' => '1234567891',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Store User

        $store = User::create([
            'name' => 'متجر النظام',
            'email' => 'store@example.com',
            'phone' => '01234567891',
            'password' => Hash::make('1234'),
            'gender' => 'male',
            'age' => 30,
            'balance' => 10000.00,
            'code' => '1234567892',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Moderator User
        $moderator = User::create([
            'name' => 'مشرف النظام',
            'email' => 'moderator@example.com',
            'phone' => '01234567892',
            'password' => Hash::make('1234'),
            'gender' => 'female',
            'age' => 25,
            'balance' => 5000.00,
            'code' => '1234567893',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Regular User
        $user = User::create([
            'name' => 'مستخدم عادي',
            'email' => 'user@example.com',
            'phone' => '01234567893',
            'password' => Hash::make('1234'),
            'gender' => 'male',
            'age' => 22,
            'balance' => 1000.00,
            'code' => '1234567894',
            'active' => true,
            'email_verified_at' => now(),
        ]);



        // Assign Roles to Users
        $admin->addRole('admin');
        $store->addRole('store');
        $moderator->addRole('moderator');
        $user->addRole('user');

        // Assign Permissions to Admin (all permissions automatically)
        $allPermissions = Permission::all()->pluck('name')->toArray();
        $admin->syncPermissions($allPermissions);

        // Assign Permissions to Store
        $store->givePermission('view-dashboard');
        $store->givePermission('view-users');

        // Assign Permissions to Moderator
        $moderator->givePermission('view-dashboard');
        $moderator->givePermission('view-users');
        $moderator->givePermission('user-update');

        // Assign Permissions to User
        $user->givePermission('view-dashboard');

    }
}
