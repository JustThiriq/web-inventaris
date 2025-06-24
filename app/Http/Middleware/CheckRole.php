<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (auth()->user()->role !== $role) {
            abort(403, 'Unauthorized access. You need ' . $role . ' role.');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect('/login')->with('error', 'Account is deactivated. Contact administrator.');
        }

        return $next($request);
    }
}
