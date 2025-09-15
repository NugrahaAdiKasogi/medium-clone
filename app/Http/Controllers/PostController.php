<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostEditRequest;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \DB::listen(function ($query) {
            \Log::info($query->sql);
        });
        $user = Auth::user();
        $categories = Category::all();
        $query = Post::with(['user','media'])
            ->where('published_at', '<=', now())
            ->withCount('claps')
            ->latest();

        if ($user) {
            $ids = $user->following()->pluck("users.id");
            $query->whereIn('user_id', $ids);
        }

        $posts = $query->paginate(5);
        return view("post.index", [
            'posts' => $posts,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(); // ambil semua kategori dari DB
        return view('post.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $data = $request->validated();


        $data['user_id'] = Auth::id();    // Assuming you have user authentication

        $post = Post::create($data);
        $post->addMediaFromRequest('image')
            ->toMediaCollection();

        return redirect()->route('myPosts')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $username, Post $post)
    {
        return view('post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('myPosts')->with('error', 'You are not authorized to edit this post.');
        }
        return view('post.edit',[
            'post' => $post,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostEditRequest $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('myPosts')->with('error', 'You are not authorized to update this post.');
        }

        $data = $request->validated();

        $post->update($data);

        if ($data['image'] ?? false) {
            $post->addMediaFromRequest('image')
                ->toMediaCollection();
        }

        return redirect()->route('myPosts')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('myPosts')->with('error', 'You are not authorized to delete this post.');
        }
        $post->delete();
        return redirect()->route('myPosts')->with('success', 'Post deleted successfully.');
    }

    public function category(Category $category)
    {
        $user = Auth::user();
        $query = Post::where('category_id', $category->id)
            ->where('published_at', '<=', now())
            ->with(['user','media'])
            ->withCount('claps')
            ->latest();

        if ($user) {
            $ids = $user->following()->pluck("users.id");
            $query->whereIn('user_id', $ids); // ini jangan array lagi, cukup langsung $ids
        }

        $posts = $query->paginate(5);
        $categories = Category::all(); // 🔑 tambahin ini

        return view("post.index", [
            'posts' => $posts,
            'categories' => $categories
        ]);
    }

    public function myPostsByCategory(Category $category)
{
    $user = Auth::user();
    $categories = Category::all(); // ambil semua kategori dari DB

    $posts = Post::where('user_id', $user->id)
        ->where('category_id', $category->id)
        ->with(['user','media'])
        ->withCount('claps')
        ->latest()
        ->paginate(5);

    return view("post.index", [
        'posts' => $posts,
        'categories' => $categories
    ]);
}

    public function myPosts()
    {
        $user = Auth::user();
        $categories = Category::all(); // ambil semua kategori dari DB

        $posts = Post::where('user_id', $user->id)
            ->with(['user','media'])
            ->withCount('claps')
            ->latest()
            ->paginate(5);

        return view("post.index", [
            'posts' => $posts,
            'categories' => $categories
        ]);
    }
}
