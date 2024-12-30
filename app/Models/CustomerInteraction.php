<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInteraction extends Model
{
    // Karena tabel tidak menggunakan timestamp
    public $timestamps = false;
    
    // Primary key
    protected $primaryKey = 'interaction_id';

    // Fields yang bisa diisi
    protected $fillable = [
        'customer_id',
        'user_id',
        'vendor_id',
        'interaction_type',
        'interaction_date',
        'notes'
    ];

    // Casting tipe data
    protected $casts = [
        'interaction_date' => 'datetime'
    ];

    // Relasi ke customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }
}