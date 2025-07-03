<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Traits\HasUuid;

class Category extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['name', 'slug'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($cat) {
            $cat->slug = Str::slug($cat->name);
        });
    }
}
