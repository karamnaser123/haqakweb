<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laratrust\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'مدير النظام',
                'description' => 'مدير النظام مع جميع الصلاحيات'
            ],
            [
                'name' => 'store',
                'display_name' => 'متجر',
                'description' => 'متجر مع صلاحيات محدودة'
            ],
            [
                'name' => 'moderator',
                'display_name' => 'مشرف',
                'description' => 'مشرف مع صلاحيات محدودة'
            ],
            [
                'name' => 'user',
                'display_name' => 'مستخدم عادي',
                'description' => 'مستخدم عادي مع صلاحيات أساسية'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
