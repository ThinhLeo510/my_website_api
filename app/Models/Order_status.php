<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_status extends Model
{
    use HasFactory;
    protected $table='orders_status';
    // public $timestamps=true;
    protected $fillable=[
        'name'
    ];
}
