<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerInteraction;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vendor;

class CustomerInteractionsTableSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::all();
        $users = User::all();
        $vendors = Vendor::all();
        $types = ['Call', 'Email', 'Meeting', 'Other'];

        for($i = 0; $i < 30; $i++) {
            CustomerInteraction::create([
                'customer_id' => $customers->random()->id,
                'user_id' => $users->random()->id,
                'vendor_id' => $vendors->random()->id,
                'interaction_type' => fake()->randomElement($types),
                'interaction_date' => fake()->dateTimeBetween('-1 month', 'now'),
                'notes' => fake()->sentence
            ]);
        }
    }
}