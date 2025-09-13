<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laratrust\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'view-dashboard', 'display_name' => 'View Dashboard', 'description' => 'Can view the main dashboard'],

            // User Management
            ['name' => 'view-users', 'display_name' => 'View Users', 'description' => 'Can view user list'],
            ['name' => 'user-add', 'display_name' => 'Add User', 'description' => 'Can add new users'],
            ['name' => 'user-update', 'display_name' => 'Edit User', 'description' => 'Can edit user information'],
            ['name' => 'user-delete', 'display_name' => 'Delete User', 'description' => 'Can delete users'],
            ['name' => 'user-permission', 'display_name' => 'User Permission', 'description' => 'Can manage user permissions'],

            // Role Management
            ['name' => 'view-roles', 'display_name' => 'View Roles', 'description' => 'Can view role list'],
            ['name' => 'role-add', 'display_name' => 'Add Role', 'description' => 'Can add new roles'],
            ['name' => 'role-update', 'display_name' => 'Edit Role', 'description' => 'Can edit role information'],
            ['name' => 'role-delete', 'display_name' => 'Delete Role', 'description' => 'Can delete roles'],
            ['name' => 'role-permission', 'display_name' => 'Role Permission', 'description' => 'Can manage role permissions'],

            // Product Management
            ['name' => 'view-products', 'display_name' => 'View Products', 'description' => 'Can view product list'],
            ['name' => 'product-add', 'display_name' => 'Add Product', 'description' => 'Can add new products'],
            ['name' => 'product-update', 'display_name' => 'Edit Product', 'description' => 'Can edit product information'],
            ['name' => 'product-delete', 'display_name' => 'Delete Product', 'description' => 'Can delete products'],
            ['name' => 'product-permission', 'display_name' => 'Product Permission', 'description' => 'Can manage product permissions'],

            // Category Management
            ['name' => 'view-categories', 'display_name' => 'View Categories', 'description' => 'Can view category list'],
            ['name' => 'category-add', 'display_name' => 'Add Category', 'description' => 'Can add new categories'],
            ['name' => 'category-update', 'display_name' => 'Edit Category', 'description' => 'Can edit category information'],
            ['name' => 'category-delete', 'display_name' => 'Delete Category', 'description' => 'Can delete categories'],
            ['name' => 'category-permission', 'display_name' => 'Category Permission', 'description' => 'Can manage category permissions'],

            // Discount Management
            ['name' => 'view-discounts', 'display_name' => 'View Discounts', 'description' => 'Can view discount list'],
            ['name' => 'discount-add', 'display_name' => 'Add Discount', 'description' => 'Can add new discounts'],
            ['name' => 'discount-update', 'display_name' => 'Edit Discount', 'description' => 'Can edit discount information'],
            ['name' => 'discount-delete', 'display_name' => 'Delete Discount', 'description' => 'Can delete discounts'],
            ['name' => 'discount-permission', 'display_name' => 'Discount Permission', 'description' => 'Can manage discount permissions'],

            // Favorite Management
            ['name' => 'view-favorites', 'display_name' => 'View Favorites', 'description' => 'Can view favorite list'],
            ['name' => 'favorite-add', 'display_name' => 'Add Favorite', 'description' => 'Can add new favorites'],
            ['name' => 'favorite-delete', 'display_name' => 'Delete Favorite', 'description' => 'Can delete favorites'],
            ['name' => 'favorite-permission', 'display_name' => 'Favorite Permission', 'description' => 'Can manage favorite permissions'],

            // Order Management
            ['name' => 'view-orders', 'display_name' => 'View Orders', 'description' => 'Can view order list'],
            ['name' => 'order-add', 'display_name' => 'Add Order', 'description' => 'Can add new orders'],
            ['name' => 'order-update', 'display_name' => 'Edit Order', 'description' => 'Can edit order information'],
            ['name' => 'order-delete', 'display_name' => 'Delete Order', 'description' => 'Can delete orders'],
            ['name' => 'order-permission', 'display_name' => 'Order Permission', 'description' => 'Can manage order permissions'],

            // Transaction Management
            ['name' => 'view-transactions', 'display_name' => 'View Transactions', 'description' => 'Can view transaction list'],
            ['name' => 'transaction-add', 'display_name' => 'Add Transaction', 'description' => 'Can add new transactions'],
            ['name' => 'transaction-update', 'display_name' => 'Edit Transaction', 'description' => 'Can edit transaction information'],
            ['name' => 'transaction-delete', 'display_name' => 'Delete Transaction', 'description' => 'Can delete transactions'],
            ['name' => 'transaction-permission', 'display_name' => 'Transaction Permission', 'description' => 'Can manage transaction permissions'],

            // Wallet Management
            ['name' => 'view-wallets', 'display_name' => 'View Wallets', 'description' => 'Can view wallet list'],
            ['name' => 'wallet-add', 'display_name' => 'Add Wallet', 'description' => 'Can add new wallets'],
            ['name' => 'wallet-update', 'display_name' => 'Edit Wallet', 'description' => 'Can edit wallet information'],
            ['name' => 'wallet-delete', 'display_name' => 'Delete Wallet', 'description' => 'Can delete wallets'],
            ['name' => 'wallet-permission', 'display_name' => 'Wallet Permission', 'description' => 'Can manage wallet permissions'],

        ];
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
