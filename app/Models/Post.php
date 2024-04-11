<?php

namespace App\Models;

use App\Notifications\PostCreated;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'category_id',
        'is_published',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($post) {
                if (is_null($post->user_id)) {
                    $post->user_id = auth()->user()->id;
                }

                $admins = User::where('is_admin', 1)->get();
                $notification = new PostCreated($post);

                Notification::send($admins, $notification);
            }
        );

        static::deleting(
            function ($post) {
                $post->comments()->delete();
                $post->tags()->detach();
            }
        );
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeDrafted($query)
    {
        return $query->where('is_published', false);
    }

    public function getPublishedAttribute()
    {
        return ($this->is_published) ? 'Yes' : 'No';
    }
}
