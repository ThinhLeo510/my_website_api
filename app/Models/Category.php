<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table='category';
    public $timestamps= true;
    public $fillable=[
        'name','parent_id'
    ];

    protected $appends = ['level'];

    public function product(){
        return $this->hasMany(Product::class,'category_id');
    }

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function child(){
        return $this->hasMany(Category::class,'parent_id');
    }

    public function getLevelAttribute()
    {
        if (is_null($this->getAttribute('parent_id'))) {
            return 1;
        } else {
            $parent = Category::where('id', $this->getAttribute('parent_id'))->first();
            if (!empty($parent) && empty(Category::where('id', $parent->parent_id)->first())) {
                return 2;
            } else if(!empty($parent) && !empty(Category::where('id', $parent->parent_id)->first())) {
                return 3;
            }
        }
    }


}
