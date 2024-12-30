<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $primaryKey = 'vendor_id';

    protected $fillable = [
        'user_id',
        'vendor_name',
        'vendor_phone',
        'vendor_email',
        'vendor_address'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function interactions()
    {
        return $this->hasMany(CustomerInteraction::class, 'vendor_id', 'vendor_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'vendor_id', 'vendor_id');
    }

    public function priceQuotations()
    {
        return $this->hasMany(PriceQuotation::class, 'vendor_id', 'vendor_id');
    }
}