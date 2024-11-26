<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    
    /**
     *  A Post belongs to a user
     *  Use this method to get the owner of the post
     */
    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     *  One to many relationship
     *  Use this method to get the categories under a post
     */
    public function categoryPost(){
        return $this->hasMany(CategoryPost::class);
    }

    /**
     *  Use to get all the comments of a post.
     *  With this, we can display all the comments of the post.
     */
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    /**
     *  Use thie method to get all the likes of the post
     */
    public function likes(){
        return $this->hasMany(Like::class);
    }

    /**
     *  This method is going to check if the user already like the post
     *  Note: This method returns TRUE if the Auth user already liked the post
     */
    public function isLiked(){
        return $this->likes()->where('user_id', Auth::user()->id)->exists(); //True of False?
        /**
         *  First we get all the likes from the table [ $this->likes() ], and
         * then in the 'user_id' column, we check if the ID of the Auth user exists.
         */
    }
}
