<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Product;

class ProjectsTableSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah ada data di tabel terkait
        if(Vendor::count() == 0) {
            echo "No vendors found. Please run VendorsTableSeeder first.\n";
            return;
        }

        if(Customer::count() == 0) {
            echo "No customers found. Please run CustomersTableSeeder first.\n";
            return;
        }

        if(Product::count() == 0) {
            echo "No products found. Please run ProductsTableSeeder first.\n";
            return;
        }

        $vendors = Vendor::all();
        $customers = Customer::all();
        $products = Product::all();

        // Create projects
        for($i = 0; $i < 15; $i++) {
            Project::create([
               'vendor_id' => $vendors->whereBetween('id', [1, 10])->random()->id,

                'customer_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'project_header' => "Project " . fake()->word(),
                'project_value' => fake()->numberBetween(10000000, 100000000),
                'project_duration_start' => fake()->dateTimeBetween('-3 months', 'now'),
                'project_duration_end' => fake()->dateTimeBetween('now', '+6 months'),
                'project_detail' => fake()->paragraph()
            ]);
        }
    }
}