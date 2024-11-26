<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class CategoriesController extends Controller
{
    private $category;
    private $post;

    public function __construct(Category $category, Post $post){
        $this->category = $category;
        $this->post = $post;

    }

    /**
     * Get all the category from categories table
     */
    public function index(){
        $all_categories = $this->category->orderBy('updated_at', 'desc')->paginate(10);

        $uncategorized_count = 0;
        #get all the post
        $all_posts = $this->post->all(); //"SELECT * FROM posts";
        foreach ($all_posts as $post){
            if($post->categoryPost->count() == 0){
                $uncategorized_count++; // add 1 for every iteration
            }
        }
        return view('admin.categories.index')
            ->with('all_categories', $all_categories)
            ->with('uncategorized_count', $uncategorized_count);
    }

    /**
     * Store/Insert new category
     */
    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:1|max:50|unique:categories,name'
        ]);

        $this->category->name = ucwords(strtolower($request->name)); 
        //SKYDIVING->strtolower(SKYDIVING)->ucWords(skydiving)->Skydiving
        $this->category->save();

        return redirect()->back();
    }

    /**
     * Method use to update category
     */
    public function update(Request $request, $category_id){
        $request->validate([
            'new_name' => 'required|min:1|max:50|unique:categories,name,' . $category_id
        ]);

        $category = $this->category->findOrFail($category_id);
        $category->name = ucwords(strtolower($request->new_name));
        $category->save();

        return redirect()->back();
    }

    public function delete($category_id){
        $this->category->destroy($category_id);

        return redirect()->back();
    }
    
}

