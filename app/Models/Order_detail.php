<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order_detail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='orders_detail';
    public $timestamps=true;
    protected $fillable=[
       'order_id',
       'product_id',
       'product_price',
       'quantity',
       'price_total'
    ];

    // relationship
    
    
}
