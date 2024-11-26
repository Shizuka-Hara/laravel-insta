<?php

namespace App\Http\Controllers;

use App\Models\Post; //Post table
use App\Models\Category; // categories table
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // to hadle authentication

class PostController extends Controller
{
    /**
     *  Define properties
     */
    private $category;
    private $post;

    /**
     *  Define constructor　-- Inside our constructor we instatiated object of category and Post class once
     */
    public function __construct(Category $category, Post $post){
        $this->category = $category;
        $this->post = $post;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /**
         * Retrieved all categories from the categories table
         *  because we need it (required) during creating of post.
         */
        $all_categories = $this->category->all();
        //Same as: "SELECT * FROM categories";

        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        #1. validate your data first
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:1048'

        ]);

        #2. Save the post
        $this->post->user_id = Auth::user()->id; //the owner of the post
        $this->post->image = 'data:image/' . $request->image->extension() . ';base64,'. base64_encode(file_get_contents($request->image));
        $this->post->description = $request->description;
        $this->post->save(); // Post 1

        #3. Save the category ids to the categoryPost (Pivot) table
        # $request->category[1,5,6]
        foreach ($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];

            # first loop - 1
            # second loop - 5
            # third loop - 6

            # $category_post[1, 5, 6]

        }

        # $this->post = 1
        $this->post->categoryPost()->createMany($category_post);

        
        #4. Go back to homepage
        return redirect()->route('index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = $this->post->findOrFail($id);
        return view('users.posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        #1. first data
        $post = $this->post->findOrFail($id);

        #If the AUTH user is NOT the owner of the post, redirect to homepage
        if (Auth::user()->id != $post->user->id){
            return redirect()->route('index');
        }

        #2. second data
        /**
         * Get all the categories from the categories table
         */
        $all_categories = $this->category->all();

        #3. third data
        /**
         * Get all the categories of this post, and save it in ana array
         */
        $selected_categories = [];
        foreach ($post->categoryPost as $category_post){
            $selected_categories[] = $category_post->category_id;
        }

        return view('users.posts.edit')
        ->with('post', $post)
        ->with('all_categories', $all_categories)
        ->with('selected_categories', $selected_categories);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        #1. Validate the data from the form
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image' => 'mimes:jpg,jpeg,png,gif|max:1048'
        ]);

        #2. Update the post
        $post = $this->post->findOrFail($id); // SELECT * FROM posts WHERE id = $id
        $post->description = $request->description; // new description coming from the post

        #Check if there are new image uploaded
        if ($request->image){
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }

        $post->save();

        #3. Delete all records from category_post pivot table related to this post
        $post->categoryPost()->delete();
        //Use the relationship Post::categoryPost() to select the records related to a post
        //Equivalent: DELETE FROM category_post WHERE post_id = $id

        #4. Save the new categoryes to the category_post table
        foreach ($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
        }
        $post->categoryPost()->createMany($category_post);

        #5. Redirect to show post page (to confirm the update)
        return redirect()->route('post.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->post->findOrFail($id)->forceDelete(); //same as: DELETE FROM posts WHERE id = $id;
        return redirect()->route('index');
    }
}
