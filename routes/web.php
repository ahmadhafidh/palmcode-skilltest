<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Posts\Index as PostIndex;
use App\Livewire\Admin\Posts\Create as PostCreate;
use App\Livewire\Admin\Categories\Index as CategoriesIndex;
use App\Livewire\Admin\Categories\Create as CategoriesCreate;
use App\Livewire\Admin\Pages\Index as PagesIndex;
use App\Livewire\Admin\Pages\Create as PagesCreate;
use App\Livewire\RBAC\RoleIndex;
use App\Livewire\RBAC\AssignRoleToUser;
use App\Livewire\RBAC\PermissionIndex;
use App\Livewire\RBAC\AssignPermissionToRole;
use App\Livewire\RBAC\Users;


Route::view('/', 'welcome');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    Route::get('posts', PostIndex::class)->name('posts');
    Route::get('posts/create', PostCreate::class)->name('posts.create');

    Route::get('categories', CategoriesIndex::class)->name('categories');
    Route::get('categories/create', CategoriesCreate::class)->name('categories.create');

    Route::get('pages', PagesIndex::class)->name('pages');
    Route::get('pages/create', PagesCreate::class)->name('pages.create');

    Route::get('roles', RoleIndex::class)->name('roles');
    Route::get('roles/assign-roles', AssignRoleToUser::class)->name('roles.assign');

    Route::get('permissions', PermissionIndex::class)->name('permissions');
    Route::get('permissions/assign-permissions', AssignPermissionToRole::class)->name('permissions.assign');

    Route::get('users-index', Users::class)->name('users-index');

});

Route::middleware(['auth', 'role:other'])->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    Route::get('posts', PostIndex::class)->name('posts');
    Route::get('posts/create', PostCreate::class)->name('posts.create');

    Route::get('categories', CategoriesIndex::class)->name('categories');
    Route::get('categories/create', CategoriesCreate::class)->name('categories.create');

    Route::get('pages', PagesIndex::class)->name('pages');
    Route::get('pages/create', PagesCreate::class)->name('pages.create');

    Route::get('roles', RoleIndex::class)->name('roles');
    Route::get('roles/assign-roles', AssignRoleToUser::class)->name('roles.assign');

    Route::get('permissions', PermissionIndex::class)->name('permissions');
    Route::get('permissions/assign-permissions', AssignPermissionToRole::class)->name('permissions.assign');

    Route::get('users-index', Users::class)->name('users-index');

});

require __DIR__.'/auth.php';
