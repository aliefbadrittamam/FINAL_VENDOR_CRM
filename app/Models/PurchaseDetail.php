<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $primaryKey = 'purchase_detail_id';

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'subtotal',
        'updated_at'
    ];

    // Relasi ke Purchase
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'purchase_id');
    }

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}