<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'description' => 'Can view dashboard'],
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'description' => 'Can manage users'],
            ['name' => 'View Reports', 'slug' => 'view-reports', 'description' => 'Can view reports'],
            ['name' => 'Edit Settings', 'slug' => 'edit-settings', 'description' => 'Can edit settings'],
            ['name' => 'Delete Data', 'slug' => 'delete-data', 'description' => 'Can delete data'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create Roles
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator role with full access'
        ]);

        $managerRole = Role::create([
            'name' => 'Manager',
            'slug' => 'manager',
            'description' => 'Manager role with limited access'
        ]);

        $userRole = Role::create([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'Basic user role'
        ]);

        // Assign Permissions to Roles
        $adminRole->permissions()->attach(Permission::all());
        $managerRole->permissions()->attach(Permission::whereIn('slug', ['view-dashboard', 'view-reports', 'manage-users'])->get());
        $userRole->permissions()->attach(Permission::where('slug', 'view-dashboard')->first());
    }
}
