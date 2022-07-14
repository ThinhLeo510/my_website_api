<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table='products';
    protected $timestamps= true;
    protected $fillable=[
        'name',
        'category_id',
        'price',
        'quantity',
        'discount_price',
        'thumbnail',
        'discount_id',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    




}
