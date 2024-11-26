<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; //Post table
use Illuminate\Support\Facades\Auth; 
use App\Models\User;

class HomeController extends Controller
{

    private $post;
    private $user;

    public function __construct(Post $post, User $user){
        $this->post =$post;
        $this->user = $user;
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
          //get every posts from the posts table
          // $all_posts = $this->post->latest()->get();
          //Same as: "SELECT * FROM posts ORDER BY created_at DESC";

          $home_posts = $this->getHomePosts();
          $suggested_users = $this->getSuggestedUsers();
  
          return view('users.home')
            ->with('home_posts', $home_posts)
            ->with('suggested_users', $suggested_users);
    }

    /**
     * Get all the users that the AUTH user is not yet following
     */
    private function getSuggestedUsers(){
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach ($all_users as $user){
            if (!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }
        return array_slice($suggested_users, 0, 5);
        /**
         *  array_slice(x,y,z)
         *  x - the array to slice
         *  y - the offeset/starting index
         *  z - length/how many to display?
         */
    }

    /**
     *  Get all the posts of the users that the AUTH (Logged-in user) user is following
     */
    private function getHomePosts(){
        $all_posts = $this->post->latest()->get();
        $home_posts = [];

        foreach ($all_posts as $post){
            if ($post->user->isFollowed() || $post->user->id === Auth::user()->id){
                $home_posts[] = $post;
            }
        }
        return $home_posts;
    }

    /**
     *  Search user
     */
    public function search(Request $request){
        $users = $this->user->where('name', 'like', '%' . $request->search . '%')->get();
        return view('users.search')
            ->with('users', $users)
            ->with('search', $request->search);
    }
}
