<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware — mirrors Django's role_required() decorator.
 *
 * Usage in routes:  ->middleware('role:ADMIN,PORTER')
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->is_approved || !$user->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('warning', 'Your account is awaiting administrator approval.');
        }

        if (!in_array($user->role, $roles, true)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
