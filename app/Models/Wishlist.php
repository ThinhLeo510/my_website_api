<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $table ='wishlist';
    public $timestamps=true;
    protected $fillable=[
        'user_id',
        'product_id'
    ];

    // relationship
    public function user(){
        return $this->belongsTo(User::class);
    }
}
