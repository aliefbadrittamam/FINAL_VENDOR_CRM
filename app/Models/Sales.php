<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $primaryKey = 'sale_id';

    protected $fillable = [
        'customer_id',
        'fixed_amount',
        'sale_date',
        'status'  // Pastikan status bisa diupdate saat convert
    ];

    protected $casts = [
        'sale_date' => 'date',
        'fixed_amount' => 'decimal:2'
    ];

    // Tambahkan konstanta untuk status
    const STATUS_PENDING = 'Pending';
    const STATUS_PROCESSING = 'Processing';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_CONVERTED = 'Converted';

    // Tambahkan list status yang valid
    public static function getValidStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_COMPLETED,
            self::STATUS_CONVERTED,
        ];
    }

    // Helper method untuk cek status
    public function isConverted()
    {
        return $this->status === self::STATUS_CONVERTED;
    }

    // Existing Relations
    public function details()
    {
        return $this->hasMany(SalesDetail::class, 'sale_id', 'sale_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}