<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingCampaign extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'marketing_campaigns';

    protected $primaryKey = 'campaign_id';


    public $incrementing = true;

    // Tipe data primary key
    protected $keyType = 'int';

    // Fields yang dapat diisi (mass assignable)
    protected $fillable = [
        'campaign_name',
        'start_date',
        'end_date',
        'description',
        'name_included',
    ];

    public $timestamps = true;
    public function detail()
    {
        return $this->hasOne(MarketingDetail::class, 'campaign_id');
    }
    public function scheduled()
    {
        return $this->hasOne(ScheduledHistory::class, 'campaign_id');
    }
}
