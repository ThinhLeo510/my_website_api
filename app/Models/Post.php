<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table='posts';
    public $timestamps=true;

   protected $fillable = [
       'title',
       'content',
       'created_by',
   ];

   public function admin(){
    return $this->belongsTo(Admin::class);
   }

}
