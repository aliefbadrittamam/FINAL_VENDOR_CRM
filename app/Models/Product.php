<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name', 
        'product_price',
        'description',
        'category_id',
    ];

    protected $casts = [
        'product_price' => 'decimal:2'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_product', 'product_id', 'project_id')
                    ->withPivot(['quantity', 'price_at_time', 'subtotal'])
                    ->withTimestamps();
    }

    public function salesDetails() 
    {
        return $this->hasMany(SalesDetail::class, 'product_id', 'product_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
        // Metode untuk menghitung jumlah penjualan
    public function scopeWithSalesCount($query)
        {
            return $query->addSelect([
                'sales_count' => SalesDetail::selectRaw('COUNT(*)')
                    ->whereColumn('product_id', 'products.product_id')
            ]);
        }
        
}