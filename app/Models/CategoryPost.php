<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    protected $table = 'category_post';
    protected $fillable = ['post_id', 'category_id'];
    public $timestamps = false;

    /**
     *  Use this mothod to get the name of the category
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }

    
}
