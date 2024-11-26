<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     *  User role
     */
    const ADMIN_ROLE_ID =1; //administrator
    const USER_ROLE_ID = 2; //regular user

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     *  Use this method to get all the post of the user
     */
    public function posts(){
        return $this->hasMany(Post::class)->latest();
    }

    /**
     *  Use this method to get all the followers of a user
     */
    public function followers(){
        return $this->hasMany(Follow::class, 'following_id');
    }

    /**
     *  Use this method to GET ALL THE USERS that the  user is following
     */
    public function following(){
        return $this->hasMany(Follow::class, 'follower_id');
    }

    /**
     *  Check if the AUTH user is already following that user
     */
    public function isFollowed(){
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
        /**
         * Auth::user()->id is the folloser
         * Firstly, get all followers of the User ( $this->followers() ). Then, from that
         * lists, search for the Auth User from the follower_id colum (where('follower_id, Auth::user()->id) )
         */
    }
}
