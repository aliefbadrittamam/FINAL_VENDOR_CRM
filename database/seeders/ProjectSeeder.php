<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\Customer;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $vendors = Vendor::all();
        $customers = Customer::all();

        for($i = 0; $i < 20; $i++) {
            Project::create([
                'vendor_id' => $vendors->random()->id,
                'customer_id' => $customers->random()->id,
                'project_header' => fake()->sentence(),
                'project_value' => fake()->numberBetween(10000000, 100000000),
                'project_duration_start' => fake()->dateTimeBetween('-6 months', '+1 month'),
                'project_duration_end' => fake()->dateTimeBetween('+1 month', '+1 year'),
                'project_detail' => fake()->paragraph(),
            ]);
        }
    }
}