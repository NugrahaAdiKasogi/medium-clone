<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClapController extends Controller
{
    public function clap(Post $post)
    {
        $existing = $post->claps()->where('user_id', Auth::id())->first();

        if ($existing) {
            $existing->delete();
        } else {
            $post->claps()->create([
                'user_id' => Auth::id(),
            ]);
        }

        return response()->json([
            'clapCount' => $post->claps()->count(),
        ]);
    }
}
