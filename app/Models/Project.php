<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'project_id';

    protected $fillable = [
        'vendor_id',
        'customer_id', 
        'product_id',
        'project_header',
        'project_value',
        'project_duration_start',
        'project_duration_end',
        'project_detail'
    ];

    protected $casts = [
        'project_duration_start' => 'datetime',
        'project_duration_end' => 'datetime',
        'project_value' => 'decimal:2'
    ];

    // Relasi yang sudah ada
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'project_product', 'project_id', 'product_id')
                    ->withPivot(['quantity', 'price_at_time', 'subtotal'])
                    ->withTimestamps();
    }

    // Tambahkan relasi priceQuotations
    public function priceQuotations()
    {
        return $this->hasMany(PriceQuotation::class, 'project_id', 'project_id');
    }

    public function purchase()
{
    return $this->hasOne(Purchase::class, 'project_id', 'project_id');
}
}