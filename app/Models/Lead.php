<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    public $timestamps = False;

    protected $fillable = [
        'customer_id',
        'message_count',
        'status',
        'delivered',
        'comments',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
    public function marketingDetails()
    {
        return $this->hasMany(MarketingDetail::class, 'customer_id', 'customer_id');
    }
}

