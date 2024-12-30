<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Menambahkan import untuk HasFactory

class MarketingDetail extends Model
{
    use HasFactory;

    public $timestamps = false; // perbaiki penulisan "timesatmps" menjadi "timestamps"
    protected $table = 'marketing_details';
    protected $fillable = [
        'campaign_id', 
        'campaign_name', 
        'send_id', 
        'state_id',
        'state',
        'customer_name', 
        'customer_phone',
        'scheduled_date',
        'send_date', 
        'status',
        'customer_id'
    ];

    public function campaign()
    {
        return $this->belongsTo(MarketingCampaign::class, 'campaign_id');
    }
    public function customer()
    {
        return $this->belongsTo(MarketingDetail::class, 'customer_id');
    }
}
