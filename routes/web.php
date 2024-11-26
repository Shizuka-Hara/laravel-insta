<?php
/**
 * Routes related to Admin users
 */
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\CategoriesController;

/**
 * Routes related to regular users
 */
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Auth::routes();
/**
 *  Auth -- Authentication --> to authenticate users
 */

Route::group(['middleware' => 'auth'], function(){
    
    Route::get('/', [HomeController::class, 'index'])->name('index');//homepage
    Route::get('/people', [HomeController::class, 'search'])->name('search');

    /**
     *  Route to ope the create.blade.php (create post page)
     */
    Route::get('post/create', [PostController::class, 'create'])->name('post.create');

    /**
     *  Route to insert post details
     */
    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');

    /**
     *  Route to show specific post details
     */
    Route::get('/post/{id}/show', [PostController::class, 'show'])->name('post.show');

    /**
     *  Route to open the edit page
     */
    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');

    /**
     *  ROute to perform the actual updating of data
     */
    Route::patch('/post/{id}/update', [PostController::class, 'update'])->name('post.update');

    /**
     *  Route to delete/destroy the post
     */
    Route::delete('/post/{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');

    /**
     *  Comment section 
     */
    Route::post('/comment/{post_id}/store', [CommentController::class, 'store'])->name('comment.store');

    Route::delete('/comment/{id}/destroy', [CommentController::class, 'destroy'])->name('comment.destroy');

    /**
     *  Users profile section
     */
    Route::get('/profile/{user_id}/show', [ProfileController::class, 'show'])->name('profile.show');

    /**
     *  Route use to open the edit.blade.php (Edit Page)
     */
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    /**
     *  Route use to perform the actual action of updating the user details
     */
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    /**
     *  Routes related to follower/followers
     */
    Route::get('/profile/{id}/followers', [ProfileController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{id}/following', [ProfileController::class, 'following'])->name('profile.following');

    /**
     *  Route related to likes
     */
    # Store the like
    Route::post('/like/{post_id}/store', [LikeController::class, 'store'])->name('like.store');

    /**
     *  Delete or unlike
     */
    Route::delete('/like/{post_id}/destroy', [LikeController::class, 'destroy'])->name('like.destroy');

    /**
     *  Routes related to follow/unfollow
     */
    Route::post('/follow/{user_id}/store', [FollowController::class, 'store'])->name('follow.store');

    Route::delete('/follow/{user_id}/destroy', [FollowController::class, 'destroy'])->name('follow.destroy');



/**
 *  Route for admin
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function(){
    #Admin User
    Route::get('/users', [UsersController::class, 'index'])->name('users'); //admin.users
    Route::delete('/users/{user_id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');
    Route::patch('/users/{user_id}/activate', [UsersController::class, 'activate'])->name('users.activate');
    
    #Admin Posts
    Route::get('/posts', [PostsController::class, 'index'])->name('posts');
    Route::delete('/posts/{post_id}/hide', [PostsController::class, 'hide'])->name('posts.hide');
    Route::patch('/posts/{post_id}/unhide', [PostsController::class, 'unhide'])->name('posts.unhide');
   
    # Categories
    #Admin Categories
    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');
    Route::post('/categories/store', [CategoriesController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category_id}/update', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category_id}/destroy', [CategoriesController::class, 'delete'])->name('categories.delete');

    });
    

});