<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductFactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get store user
        $store = User::where('email', 'store@example.com')->first();

        if (!$store) {
            $this->command->error('Store user not found. Please run UserSeeder first.');
            return;
        }

        // Check if subcategories exist
        $subcategoriesCount = \App\Models\Category::whereNotNull('parent_id')->count();

        if ($subcategoriesCount === 0) {
            $this->command->error('No subcategories found. Please run CategorySeeder first.');
            return;
        }

        $this->command->info("Creating 100 products using factory...");

        // Create 100 products using factory
        Product::factory()
            ->count(100)
            ->create();

        $this->command->info('100 products created successfully using factory!');
    }
}
