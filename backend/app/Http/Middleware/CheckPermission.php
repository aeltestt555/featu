<?php
// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::user()->hasPermission($permission)) {
            return response()->json([
                'error' => 'You do not have permission to access this resource'
            ], 403);
        }

        return $next($request);
    }
}
