<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceQuotation extends Model 
{
    protected $table = 'price_quotations';
    protected $primaryKey = 'price_quotation_id';

    // Tambahkan fillable untuk mass assignment
    protected $fillable = [
        'project_id',
        'vendor_id',
        'amount'
    ];

    // Opsional: Tambahkan casting untuk amount
    protected $casts = [
        'amount' => 'decimal:2'
    ];

    // Relations
    public function project() 
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }
}