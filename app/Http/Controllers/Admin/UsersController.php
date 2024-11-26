<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * Get all the users from the users table and sort the result in latest (descending order)
     */
    public function index(){
        # "SELECT * FROM users ORDER BY created_at DESC" ;
        $all_users = $this->user->withTrashed()->latest()->paginate(5);
        return view('admin.users.index')->with('all_users', $all_users);
    }

    /**
     * This method will deactivate (soft delete) a user
     */
    public function deactivate($user_id){
        $this->user->destroy($user_id);
        return redirect()->back();
    }

    /**
     *  This method will activate (un-softdelete) the user
     */
    public function activate($user_id){
        $this->user->onlyTrashed()->findOrFail($user_id)->restore();
        return redirect()->back();
    }
}
