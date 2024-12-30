<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketingCampaign;

class MarketingCampaignsTableSeeder extends Seeder
{
    public function run()
    {
        for($i = 0; $i < 10; $i++) {
            $startDate = fake()->dateTimeBetween('-1 month', '+1 month');
            MarketingCampaign::create([
                'campaign_name' => "Campaign " . fake()->word,
                'start_date' => $startDate,
                'end_date' => fake()->dateTimeBetween($startDate, '+3 months'),
                'description' => fake()->paragraph
            ]);
        }
    }
}