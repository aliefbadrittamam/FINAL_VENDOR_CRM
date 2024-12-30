<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipping;
use App\Models\PurchaseDetail;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\Customer;

class ShippingTableSeeder extends Seeder
{
    public function run()
    {
        $purchaseDetails = PurchaseDetail::all();
        $projects = Project::all();
        $vendors = Vendor::all();
        $customers = Customer::all();
        $statuses = ['Pending', 'Completed', 'Cancelled'];

        foreach($purchaseDetails as $detail) {
            if(rand(0, 1)) { // 50% chance to create shipping
                Shipping::create([
                    'purchase_detail_id' => $detail->id,
                    'project_id' => $projects->random()->id,
                    'vendor_id' => $vendors->random()->id,
                    'customer_id' => $customers->random()->id,
                    'shipping_status' => fake()->randomElement($statuses),
                    'Number_receipt' => fake()->numberBetween(1000, 9999)
                ]);
            }
        }
    }
}