<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSecretaire
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'SECRETARY') {
            return $next($request);
        }

        // Redirect to the appropriate dashboard based on role
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('error', 'Accès réservé au secrétariat.');
        }

        return redirect()->route('login');
    }
}
