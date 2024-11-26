<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Follow;

/**
 * Note: The AUTH user is always the follower
 */
class FollowController extends Controller
{
    private $follow;

    public function __construct(Follow $follow){
        $this->follow = $follow;
    }

    /**
     * Method use to insert/save the follow detail
     */
    public function store($user_id){
        $this->follow->follower_id = Auth::user()->id; //the follower
        $this->follow->following_id = $user_id;        //the user being followed
        $this->follow->save();                         //save the details

        return redirect()->back();
    }

    /**
     *  Use this method to destroy/unfollow a user
     */
    public function destroy($user_id){
        $this->follow
            ->where('follower_id', Auth::user()->id)
            ->where('following_id', $user_id)
            ->delete();

        return redirect()->back();
    }
}
