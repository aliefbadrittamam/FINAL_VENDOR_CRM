<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceQuotationTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('price_quotation')->insert([
            ['project_id' => 1, 'vendor_id' => 1, 'amount' => 9000000],
            ['project_id' => 2, 'vendor_id' => 2, 'amount' => 14000000],
        ]);
    }    
}
