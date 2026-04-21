<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
 public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Récupération du rôle de l'utilisateur
        $role = $request->user()->role;

        // Redirection basée sur le rôle (Version Match - plus propre)
        return match ($role) {
            'ADMIN'     => redirect()->route('admin.dashboard'),
            'PATIENT'   => redirect()->route('patient.dashboard'),
            'SECRETARY' => redirect()->route('secretary.dashboard'),
            'DOCTOR'    => redirect()->route('doctor.dashboard'),
            default     => redirect()->intended(route('dashboard', absolute: false)),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
