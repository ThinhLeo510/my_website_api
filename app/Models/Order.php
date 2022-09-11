<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table='orders';
    public $timestamps=true;
    protected $fillable=[
        'user_id',
        'firstname',
        'lastname',
        'phone',
        'address',
        'notes',
        'price_total',
        'payment_id',
        'status',
        'shipping_fee',
        'voucher_id',
        'sub_price_total',
    ];

    // relatonship
    public function orders_detail(){
        return $this->hasMany(Order_detail::class,'order_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function order_status(){
        return $this->hasOne(Order_detail::class,'status');
    }

    public function payment(){
        return $this->hasOne(Payment::class,'payment_id');
    }

}
