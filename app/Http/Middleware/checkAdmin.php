<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'ADMIN') {
            return $next($request);
        }

        // Redirect to the appropriate dashboard based on role
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'DOCTOR') return redirect()->route('doctor.dashboard');
            if ($role === 'PATIENT') return redirect()->route('patient.dashboard');
            if ($role === 'SECRETARY') return redirect()->route('secretary.dashboard');
        }

        return redirect()->route('login');
    }
}
