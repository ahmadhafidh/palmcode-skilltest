<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminApiMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return $next($request);
    }
}

