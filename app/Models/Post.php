<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'slug',
        'content',
        'category_id',
        'user_id',
        'published_at',
    ];

    //Relationship Many to One with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function readTime($wordPerMinutes = 100)
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTimeMinutes = ceil($wordCount / $wordPerMinutes); // Assuming average reading speed of 200 words per minute
        return max(1, $readingTimeMinutes);
    }

    public function claps(){
        return $this->hasMany(Clap::class);
    }

    public function imageUrl()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
    }
}
