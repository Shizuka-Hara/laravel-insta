<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Like; //this represents the likes table

class LikeController extends Controller
{
    private $like;

    public function __construct(Like $like){
        $this->like = $like;
    }

    /**
     *  Method use to save the likes of the user
     */
    public function store($post_id){
        $this->like->user_id = Auth::user()->id; //owner of the like
        $this->like->post_id = $post_id; //the post being liked
        $this->like->save();

        return redirect()->back();
    }

    /**
     *  Method use to destroy/unlike
     */
    public function destroy($post_id){
        $this->like
        ->where('user_id', Auth::user()->id)
        ->where('post_id', $post_id)
        ->delete(); //delete the Auth user id and post id in the likes table

        return redirect()->back();
    }
}
