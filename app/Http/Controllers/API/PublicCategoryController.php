<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class PublicCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        if (!$categories) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'List of categories',
            'data' => $categories,
        ]);
    }

    public function showById($id)
    {
        $category = Category::where('id', $id)->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category detail',
            'data' => $category,
        ]);
    }

    public function showBySlug($slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category detail',
            'data' => $category,
        ]);
    }
}
