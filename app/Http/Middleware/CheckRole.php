<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->role_id !== $role) {
            abort(403, 'Unauthorized access. You need ' . $role . ' role.');
        }

        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect('/login')->with('error', 'Account is deactivated. Contact administrator.');
        }

        return $next($request);
    }
}
