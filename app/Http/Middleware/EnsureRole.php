<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * Accepts one or more role names as middleware arguments.
     * Example usage: middleware('role:professor,admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, $roles, true)) {
            abort(403, 'Acesso não autorizado para este cargo.');
        }

        return $next($request);
    }
}
