<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'user_id',
        'published_at',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {

        if (!$media) {
            return;
        }
        $this
            ->addMediaConversion('preview')
            ->width(400);
    }
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('default')
            ->singleFile();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
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

    public function claps()
    {
        // 'claps' adalah nama pivot table-mu
        return $this->belongsToMany(User::class, 'claps');
    }

    /**
     * Helper untuk mengecek apakah user tertentu sudah clap (opsional tapi sangat berguna).
     */
    public function isClappedBy(User $user)
    {
        if (!$user) {
            return false;
        }
        return $this->claps()->where('claps.user_id', $user->id)->exists();
    }

    public function imageUrl($conversionName = '')
    {
        $media = $this->getFirstMedia();
        if (!$media) {
            return null;
        }

        if ($media->hasGeneratedConversion($conversionName)) {
            return $media?->getUrl($conversionName);
        }
        return $media->getUrl();
    }

    public function getFormattedPublishedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->published_at)->format('M d, y');
    }
}
