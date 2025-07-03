<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pages;

class PublicPageController extends Controller
{
    public function index()
    {
        $pages = Pages::where('status', 'published')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List of published pages',
            'data' => $pages,
        ]);
    }

    public function showById($id)
    {
        $pages = Pages::where('id', $id)
            ->where('status', 'published')
            ->first();

        if (!$pages) {
            return response()->json([
                'success' => false,
                'message' => 'Pages ID not found ',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pages detail by ID',
            'data' => $pages,
        ]);
    }

    public function showBySlug($slug)
    {
        $pages = Pages::where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$pages) {
            return response()->json([
                'success' => false,
                'message' => 'Pages slug not found ',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get Pages detail by slug',
            'data' => $pages,
        ]);
    }
}


