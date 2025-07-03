<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PublicPostController extends Controller
{
    public function index()
    {
        $posts = Post::with('categories')
            ->where('status', 'published')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List of published posts',
            'data' => $posts,
        ]);
    }

    public function showById($id)
    {
        $post = Post::with('categories')
            ->where('id', $id)
            ->where('status', 'published')
            ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found by ID',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post detail by ID',
            'data' => $post,
        ]);
    }

    public function showBySlug($slug)
    {
        $post = Post::with('categories')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found by slug',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post detail by slug',
            'data' => $post,
        ]);
    }
}
