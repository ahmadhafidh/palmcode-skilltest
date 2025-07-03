<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;

class PostResourceController extends Controller
{
    public function index()
    {
         $posts = Post::with('categories')
            ->where('status', 'published')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'All Post retrieved successfully',
            'data' => $posts,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $post = new Post([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'status' => $request->status,
            'excerpt' => Str::limit(strip_tags($request->content), 150),
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        // Upload image
        if ($request->hasFile('image')) {
            $filename = 'images/post/' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/post'), $filename);
            $post->image = $filename;
        }

        $post->save();

        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post Created Successfully',
            'data' => $post
        ]);
    }

    public function show($id)
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

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:draft,published',
            'image' => 'nullable|image',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        }

        if (isset($validated['content'])) {
            $validated['excerpt'] = Str::limit(strip_tags($validated['content']), 150);
        }

        if (isset($validated['status'])) {
            $validated['published_at'] = $validated['status'] === 'published' ? now() : null;
        }

        if ($request->hasFile('image')) {
            $filename = 'images/post/' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/post'), $filename);
            $validated['image'] = $filename;
        }

        $post->update($validated);

        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post Updated Successfully',
            'data' => $post->fresh()->load('categories'),
        ]);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Pages deleted Successfully',
            'data' => null
        ]);
    }
}
