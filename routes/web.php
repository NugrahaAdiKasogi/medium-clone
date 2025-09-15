<?php

use App\Http\Controllers\ClapController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/@{user:username}', [\App\Http\Controllers\PublicProfileController::class, 'show'])
    ->name('profile.show');
    
Route::get('/',  [PostController::class, 'index'])
    ->name('dashboard');

Route::get('/@{username}/{post:slug}', [PostController::class, 'show'])
    ->name('post.show');
    
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/category/{category}',[PostController::class,'category'])
        ->name('post.byCategory');
    
    Route::get('/post/create', [PostController::class, 'create'])
        ->name('post.create');

    Route::get('/post/{post:slug}', [PostController::class, 'edit'])
        ->name('post.edit');

    Route::put('/post/{post}', [PostController::class,'update'])
        ->name('post.update');

    Route::post('/post/store', [PostController::class, 'store'])
        ->name('post.store');

    Route::delete('/post/{post}', [PostController::class, 'destroy'])
        ->name('post.destroy');

    Route::get('/my-posts', [PostController::class, 'myPosts'])
        ->name('myPosts');

    Route::get('/myposts/category/{category}', [PostController::class, 'myPostsByCategory'])
        ->name('myPosts.byCategory');


    Route::post('/follow/{user}', [FollowerController::class, 'followUnfollow'])
        ->name('follow');

    Route::post('/clap/{post}', [ClapController::class, 'toggleClap'])
        ->name('post.clap');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
