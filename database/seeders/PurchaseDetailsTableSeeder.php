<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseDetail;
use App\Models\Purchase;
use App\Models\Product;

class PurchaseDetailsTableSeeder extends Seeder
{
    public function run()
    {
        $purchases = Purchase::all();
        $products = Product::all();

        foreach($purchases as $purchase) {
            $numberOfProducts = rand(1, 4);
            for($i = 0; $i < $numberOfProducts; $i++) {
                $product = $products->random();
                $quantity = rand(1, 10);
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'subtotal' => $product->product_price * $quantity
                ]);
            }
        }
    }
}