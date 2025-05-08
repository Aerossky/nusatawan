<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    /**
     * Handle an incoming request.
     * Checks if the authenticated user is a regular user (not admin)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->isAdmin) {
            if (Auth::check() && Auth::user()->isAdmin) {
                return redirect()->route('admin.dashboard')->with('error', 'Halaman ini hanya untuk pengguna biasa.');
            }

            return redirect()->route('auth.login')->with('error', 'Silahkan login terlebih dahulu.');
        }

        return $next($request);
    }
}
