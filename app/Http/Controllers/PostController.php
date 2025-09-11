<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $posts = Post::orderBy('created_at', 'DESC')->paginate(5);
        return view("post.index", [
            'posts' => $posts
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
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'content' => 'required|string',
            'image' => ['required','image', 'mimes:jpeg,png,jpg,svg', 'max:2048'], // Optional image upload
            'category_id' => ['required','exists:categories,id'], // Optional category
            'published_at' => ['nullable','datetime'],
        ]);

        $image = $data['image'];
        unset($data['image']);
        $data['user_id'] = Auth::id();    // Assuming you have user authentication
        $data['slug'] = Str::slug($data['title']);    // Assuming you have user authentication
        $imagePath = $image -> store('posts', 'public');
        $data['image'] = $imagePath;

        Post::create($data);
        return redirect()->route('dashboard')->with('success', 'Post created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
