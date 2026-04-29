<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super_admin',
        ]);
        $admin = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);
        $customer = Role::create([
            'name' => 'Customer',
            'slug' => 'customer',
        ]);

        // All permissions organized by feature
        $permissions = [
            // User Management
            'manage-users',
            'manage-roles',
            'manage-permissions',
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Book Management
            'manage-books',
            'view-books',
            'create-books',
            'edit-books',
            'delete-books',
            'publish-books',
            'unpublish-books',
            
            // Author Management
            'manage-authors',
            'view-authors',
            'create-authors',
            'edit-authors',
            'delete-authors',
            
            // Category Management
            'manage-categories',
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',
            
            // Order Management
            'manage-orders',
            'view-all-orders',
            'create-orders',
            'edit-orders',
            'delete-orders',
            'process-orders',
            'cancel-orders',
            'view-own-orders',
            
            // Reviews & Ratings
            'manage-reviews',
            'create-reviews',
            'edit-reviews',
            'delete-reviews',
            'moderate-reviews',
            
            // Reports & Analytics
            'view-reports',
            'view-sales-reports',
            'view-user-reports',
            'export-reports',
            
            // Settings
            'manage-settings',
            'view-settings',
            'edit-settings',
            
            // Media/Library
            'manage-media',
            'upload-files',
            'delete-files',
            
            // Dashboard
            'view-dashboard',
            'view-admin-dashboard',
            
            // Profile (for all users)
            'view-profile',
            'edit-profile',
            'change-password',
            'manage-profile-image',
        ];

        foreach ($permissions as $p) {
            Permission::create([
                'name' => ucwords(str_replace('-', ' ', $p)), 
                'slug' => $p
            ]);
        }

        // Super Admin - gets everything
        $superAdmin->permissions()->attach(Permission::all());

        // Admin - gets everything except super-admin only permissions
        $admin->permissions()->attach(
            Permission::whereNotIn('slug', [
                'manage-users',
                'manage-roles',
                'manage-permissions',
                'delete-users'
            ])->pluck('permission_id')
        );

        // Customer - limited permissions
        $customer->permissions()->attach(
            Permission::whereIn('slug', [
                'view-books',
                'create-orders',
                'view-own-orders',
                'create-reviews',
                'edit-reviews',
                'delete-reviews',
                'view-profile',
                'edit-profile',
                'change-password',
                'manage-profile-image'
            ])->pluck('permission_id')
        );
    }
}