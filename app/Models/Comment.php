<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table='comments';
    protected $timestamps= true;
    protected $fillable=[
        'product_id',
        'username',
        'parent_id',
        'content'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
