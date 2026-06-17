<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    )
    {
        if(!auth()->check())
        {
            return redirect('/login');
        }

        $currentRole = strtolower(trim((string) auth()->user()->role));
        $allowedRoles = array_map(
            fn ($role) => strtolower(trim((string) $role)),
            $roles
        );

        if(!in_array($currentRole, $allowedRoles, true))
        {
            abort(403);
        }

        return $next($request);
    }
}