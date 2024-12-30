<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        $customerUsers = User::where('role', 'Customers')->get();

        foreach($customerUsers as $user) {
            Customer::create([
                'user_id' => $user->id,
                'customer_name' => fake()->company,
                'customer_email' => fake()->email,
                'customer_phone' => fake()->phoneNumber,
                'customer_address' => fake()->address,
            ]);
        }
    }
}