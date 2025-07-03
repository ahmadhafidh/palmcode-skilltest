<?php

use App\Http\Middleware\AdminApiMiddleware;
use App\Http\Controllers\API\AdminAuthController;
use App\Http\Controllers\API\PostResourceController;
use App\Http\Controllers\API\PageResourceController;
use App\Http\Controllers\API\CategoryResourceController;
use App\Http\Controllers\API\PublicCategoryController;
use App\Http\Controllers\API\PublicPostController;
use App\Http\Controllers\API\PublicPageController;
use Illuminate\Support\Facades\Route;

//AUTH
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/admin/logout', [AdminAuthController::class, 'logout']);


//API SANCTUM
Route::prefix('admin')->middleware(['auth:sanctum', AdminApiMiddleware::class])->group(function () {
    Route::apiResource('posts', PostResourceController::class);
    Route::apiResource('pages', PageResourceController::class);
    Route::apiResource('categories', CategoryResourceController::class);
});

//API PUBLIC
//categories
Route::get('/categories', [PublicCategoryController::class, 'index']);
Route::get('/categories/{id}', [PublicCategoryController::class, 'showById']);
Route::get('/categories/slug/{slug}', [PublicCategoryController::class, 'showBySlug']);

//posts
Route::get('/posts', [PublicPostController::class, 'index']);
Route::get('/posts/{id}', [PublicPostController::class, 'showById']);
Route::get('/posts/slug/{slug}', [PublicPostController::class, 'showBySlug']);

//pages
Route::get('/pages', [PublicPageController::class, 'index']);
Route::get('/pages/{id}', [PublicPageController::class, 'showById']);
Route::get('/pages/slug/{slug}', [PublicPageController::class, 'showBySlug']);







