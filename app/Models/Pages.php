<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Traits\HasUuid;

class Pages extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['title', 'slug', 'body', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            $page->slug = Str::slug($page->title);
        });
    }
}
