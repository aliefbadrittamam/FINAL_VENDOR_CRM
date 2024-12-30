<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SaleDetail;
use App\Models\Sale;
use App\Models\Product;

class SalesDetailsTableSeeder extends Seeder
{
    public function run()
    {
        $sales = Sale::all();
        $products = Product::all();

        foreach($sales as $sale) {
            $numberOfProducts = rand(1, 3);
            for($i = 0; $i < $numberOfProducts; $i++) {
                $product = $products->random();
                $quantity = rand(1, 5);
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'subtotal' => $product->product_price * $quantity
                ]);
            }
        }
    }
}