<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClapController extends Controller
{
    public function toggleClap(Post $post)
    {
        // 1. Dapatkan pengguna yang sedang login
        $user = Auth::user();

        // 2. Gunakan metode toggle() yang lebih efisien
        // Ini akan otomatis menambah/menghapus relasi di pivot table
        $user->claps()->toggle($post->id);

        // 3. Siapkan data untuk dikirim kembali ke frontend
        $clapCount = $post->claps()->count();
        $hasClapped = $user->claps()->where('post_id', $post->id)->exists();

        // 4. Kembalikan respons JSON yang lengkap
        return response()->json([
            'clapCount' => $clapCount,
            'hasClapped' => $hasClapped, // Kirim status terbaru!
        ]);
    }
}