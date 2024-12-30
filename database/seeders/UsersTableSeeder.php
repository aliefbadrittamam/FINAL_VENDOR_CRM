<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'status' => 'Active',
        ]);

        // Create vendor users
        for($i = 0; $i < 5; $i++) {
            User::create([
                'name' => fake()->name,
                'email' => fake()->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'Vendor',
                'status' => 'Active',
            ]);
        }

        // Create customer users
        for($i = 0; $i < 5; $i++) {
            User::create([
                'name' => fake()->name,
                'email' => fake()->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'Customers',
                'status' => 'Active',
            ]);
        }
    }
}