<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    public $timestamps = false;
    protected $table = 'shipping';
    protected $primaryKey = 'shipping_id';

    protected $fillable = [
        'purchase_detail_id',
        'project_id',
        'vendor_id',
        'customer_id',
        'shipping_status',
        'Number_receipt'
    ];

    public function purchaseDetail()
    {
        return $this->belongsTo(PurchaseDetail::class, 'purchase_detail_id', 'purchase_detail_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}