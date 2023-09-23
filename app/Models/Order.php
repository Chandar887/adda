<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

     protected $fillable = [
          'id',
          'order_no',
          'product_id',
          'payment_status',
          'total_amount',
          'order_status',
     ];

     public function orderDetails()
     {
          return $this->hasMany(OrderDetail::class);
     }
}
