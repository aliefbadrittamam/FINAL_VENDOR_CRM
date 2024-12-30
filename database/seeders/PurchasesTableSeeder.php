<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Project;

class PurchasesTableSeeder extends Seeder
{
    public function run()
    {
        $vendors = Vendor::all();
        $users = User::all();
        $projects = Project::all();
        $statuses = ['Pending', 'Completed', 'Cancelled'];

        for($i = 0; $i < 15; $i++) {
            Purchase::create([
                'vendor_id' => $vendors->random()->id,
                'user_id' => $users->random()->id,
                'project_id' => $projects->random()->id,
                'total_amount' => fake()->numberBetween(5000000, 50000000),
                'purchase_date' => fake()->dateTimeBetween('-3 months', 'now'),
                'status' => fake()->randomElement($statuses)
            ]);
        }
    }
}