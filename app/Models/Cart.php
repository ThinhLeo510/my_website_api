<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table='carts';
    protected $timestamps=true;
    protected $fillable=[
        'user_id',
        'product_id',
        'quantity',
        'price_total'
    ];

    // public function user(){
    //     return $this->belongsTo(User::class);
    // }

    // public function product(){
    //     return $this->hasMany(Product::class);
    // }



}
