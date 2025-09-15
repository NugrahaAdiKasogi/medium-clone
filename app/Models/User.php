<?php

namespace App\Models;

/**
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $following
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $followers
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany following()
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany followers()
 */

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'image',
        'bio',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
    $this
        ->addMediaConversion('avatar')
        ->width(128)
        ->height(128);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile();
    }

     //Relationship One to Many with Post

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function imageUrl(){
        if(!$this->hasMedia('avatar')){
            return asset('images/default-avatar.jpg');
        }
        $media = $this->getFirstMedia('avatar');
        if($media->hasGeneratedConversion('avatar')){
            return $media->getUrl('avatar');
        }
        return $media->getUrl();
    }

    public function isFollowedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->followers()->where('follower_id', $user->id)->exists();
    }


    public function getRouteKeyName()
    {
        return 'username';
    }

    public function claps()
    {
        // 'claps' adalah nama pivot table-mu
        return $this->belongsToMany(Post::class, 'claps');
    }

    public function hasClapped(Post $post)
    {
            return $post->isClappedBy($this);
    }

}
