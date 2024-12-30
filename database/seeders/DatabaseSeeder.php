<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            CustomersTableSeeder::class,
            VendorsTableSeeder::class,
            ProductsTableSeeder::class,
            ProjectsTableSeeder::class,
            SalesTableSeeder::class,
            SalesDetailsTableSeeder::class,
            PurchasesTableSeeder::class,
            PurchaseDetailsTableSeeder::class,
            MarketingCampaignsTableSeeder::class,
            CustomerInteractionsTableSeeder::class,
            SystemSettingsTableSeeder::class,
            PriceQuotationsTableSeeder::class,
            ShippingTableSeeder::class,
        ]);
    }
}