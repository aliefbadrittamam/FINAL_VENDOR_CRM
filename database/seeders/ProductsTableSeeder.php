<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $categories = ['Material', 'Tools', 'Equipment', 'Services'];

        for ($i = 0; $i < 20; $i++) {
            Product::create([
                'product_name' => fake()->word(),
                'product_category' => fake()->randomElement($categories),
                'product_price' => fake()->randomFloat(2, 100000, 5000000),
                'description' => fake()->sentence(),
            ]);
        }
    }
}
