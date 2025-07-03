<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Traits\HasUuid;

class Post extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['title', 'slug', 'content', 'status', 'published_at', 'excerpt', 'image'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->slug = Str::slug($post->title);
        });

        static::saving(function ($post) {
            if ($post->status === 'published' && !$post->published_at) {
                $post->published_at = now();
            }
            if ($post->status === 'draft') {
                $post->published_at = null;
            }
        });
    }
}
