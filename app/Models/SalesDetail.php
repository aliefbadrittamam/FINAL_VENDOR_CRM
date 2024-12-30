<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    protected $primaryKey = 'sale_detail_id';
    public $timestamps = false;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'subtotal',
        'updated_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2'
    ];

    public function sale()
    {
        return $this->belongsTo(Sales::class, 'sale_id', 'sale_id');
    }

  
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}