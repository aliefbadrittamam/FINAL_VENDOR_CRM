<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Customer;

class SalesTableSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::all();
        $statuses = ['Pending', 'Processing', 'Completed', 'Cancelled'];

        for($i = 0; $i < 20; $i++) {
            Sale::create([
                'customer_id' => $customers->random()->id,
                'fixed_amount' => fake()->numberBetween(1000000, 10000000),
                'sale_date' => fake()->dateTimeBetween('-6 months', 'now'),
                'status' => fake()->randomElement($statuses)
            ]);
        }
    }
}