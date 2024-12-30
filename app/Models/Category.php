<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'category', 
        'description',
    ];
    // Definisikan relasi one-to-many dengan Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
