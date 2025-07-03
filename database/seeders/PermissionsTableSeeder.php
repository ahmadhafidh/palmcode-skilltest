<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //permission users
        Permission::create(['name' => 'users index', 'guard_name' => 'web']);
        Permission::create(['name' => 'users create', 'guard_name' => 'web']);
        Permission::create(['name' => 'users edit', 'guard_name' => 'web']);
        Permission::create(['name' => 'users delete', 'guard_name' => 'web']);

        //permission roles
        Permission::create(['name' => 'roles index', 'guard_name' => 'web']);
        Permission::create(['name' => 'roles create', 'guard_name' => 'web']);
        Permission::create(['name' => 'roles edit', 'guard_name' => 'web']);
        Permission::create(['name' => 'roles delete', 'guard_name' => 'web']);

        //permission permissions
        Permission::create(['name' => 'permissions index', 'guard_name' => 'web']);

        //permission categories
        Permission::create(['name' => 'categories index', 'guard_name' => 'web']);

        //permission posts
        Permission::create(['name' => 'posts index', 'guard_name' => 'web']);

        //permission pages
        Permission::create(['name' => 'pages index', 'guard_name' => 'web']);
    }
}