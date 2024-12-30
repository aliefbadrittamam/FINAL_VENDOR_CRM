<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'status' => 'Active',
        ]);

        \App\Models\User::factory(10)->create()->each(function ($user) {
            $user->update([
                'role' => $user->id % 2 == 0 ? 'Customers' : 'Vendor',
                'status' => 'Active'
            ]);
        });
    }
}