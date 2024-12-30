<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $primaryKey = 'purchase_id';

    protected $fillable = [
        'vendor_id',
        'user_id',
        'project_id',
        'total_amount',
        'purchase_date',
        'status'
    ];

    // Tambahkan casting untuk tanggal
    protected $casts = [
        'purchase_date' => 'datetime'
    ];

    // Relasi ke Project
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    // Relasi ke PurchaseDetail
    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'purchase_id');
    }
}