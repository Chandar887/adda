<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
            'id',
            'name',
            'sku',
            'category',
            'description',
            'status',
            'price',
            'sale_price',
    ];

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
