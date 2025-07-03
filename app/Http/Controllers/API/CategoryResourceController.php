<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryResourceController extends Controller
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
            'message' => 'All Category retrieved successfully',
            'data' => $categories,
        ]);
    }

    public function store(Request $req)
    {
        $v = $req->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
        $cat = Category::create($v);

        return response()->json([
            'success' => true,
            'message' => 'Category Created Successfully',
            'data' => $cat
        ]);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category retrieved successfully',
            'data' => $category
        ]);
    }

    public function update(Request $req, Category $category)
    {
        $v = $req->validate([
            'name' => "required|string|max:255|unique:categories,name,{$category->id}",
        ]);
        $category->update($v);
        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
       
        return response()->json([
            'success' => true,
            'message' => 'Category deleted Successfully',
            'data' => null
        ]);  
    }
}
