<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';
    
    protected $fillable = [
        'user_id',
        'customer_name', 
        'customer_email',
        'customer_phone',
        'customer_address'
    ];
    public function projects()
    {
        return $this->hasMany(Project::class, 'customer_id', 'customer_id');
    }
    public function leads()
    {
        return $this->hasOne(Lead::class, 'customer_id', 'customer_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function interactions()
    {
        return $this->hasMany(CustomerInteraction::class, 'customer_id');
    }
   
}