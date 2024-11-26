<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; //represents the users table
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller

{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * Search for the user details, so we can display the details into show.blade.php
     */
    public function show($id){
        $user = $this->user->findOrFail($id); //"SELECT * FROM users";
        return view('users.profile.show')->with('user', $user);
    }

    /**
     * Search for the details of the login user and display it in the edit.blade.php (edit page)
     */
    public function edit(){
        $user = $this->user->findOrFail(Auth::user()->id);
        //Same as: "SELECT * FROM users WHERE id = Auth::user()->id";

        return view('users.profile.edit')->with('user', $user);
    }

    /**
     * Method to use to update user details
     */
    public function update(Request $request){
        #1. Validate the date from the form
        $request->validate([
            'name' => 'required|min:1|max:150',
            'email' => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'avatar' =>'mimes:jpeg,jpg,gif,png|max:1048',
            'introduction' => 'max:100'
        ]);

        #2. Save the new user details
        $user = $this->user->findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;

        # Check if the user uploaded an image/avatar
        if ($request->avatar) {
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }
        $user->save();

        # Redirect
        return redirect()->route('profile.show', Auth::user()->id); //profile page
    }

    /**
     * Use this method to get the information of the follower
     */
    public function followers($user_id){

        // The $user_id is the ID of the user that we want to view
        $user = $this->user->findOrFail($user_id);
        return view('users.profile.followers')->with('user', $user);
        /**
         * Note: Anyone can view anyone followers list.
         */
    }

    /**
     * Use this method to get the the users that the user is following
     */
    public function following($user_id){
        $user = $this->user->findOrFail($user_id);
        return view('users.profile.following')->with('user', $user);
    }
}
