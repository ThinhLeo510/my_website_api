<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory;
    // use SoftDeletes;
    protected $table='discounts';
    public $timestamps=true;
    protected $fillable=[
        'code',
        'percent',
        'description',
        'start_date',
        'end_date',
    ];

    public function product(){
        return $this->hasMany(Product::class,'discount_id');
    }
}
