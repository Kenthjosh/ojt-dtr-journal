<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsInternUser
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (! auth()->check()) {
            // let unauthenticated users reach login page
            return $next($request);
        }

        if (! $user || $user->role !== 'intern') {
            abort(403, 'Unauthorized.');

            return null;
        }

        if ($user->role !== 'intern') {
            return null;
        }

        return $next($request);
    }
}
