<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pages;
use Illuminate\Support\Str;

class PageResourceController extends Controller
{
    public function index()
    {
        $page = Pages::latest()->get();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Pages not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'All Pages retrieved successfully',
            'data' => $page,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string', // sesuai nama kolom di DB
            'status' => 'required|in:draft,published',
        ]);

        $page = Pages::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . time(),
            'body' => $validated['body'],
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pages Created Successfully',
            'data' => $page
        ]);
    }

    public function show($id)
    {
        $page = Pages::where('id', $id)->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'pages not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pages retrieved successfully',
            'data' => $page,
        ]);
    }

    public function update(Request $request, Pages $page)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:draft,published',
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        }

        $page->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pages Created Successfully',
            'data' => $page
        ]);        
    }

    public function destroy(Pages $page)
    {
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pages deleted Successfully',
            'data' => null
        ]);
    }
}
