<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * $role can be "student" or "faculty"
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403); // or redirect elsewhere
        }

        return $next($request);
    }
}
