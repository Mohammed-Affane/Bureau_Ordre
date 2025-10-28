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
// how can i manage the login of all users and roles i have and redirect them to the dashboard of their role
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } else if ($user->hasRole('bo')) {
            return redirect()->intended(route('courriers.create', absolute: false));
        } else if ($user->hasRole('cab')) {
            return redirect()->intended(route('cab.dashboard', absolute: false));
        } else if ($user->hasRole('sg')) {
            return redirect()->intended(route('sg.dashboard', absolute: false));
        } else if ($user->hasRole('chef_division')) {
            return redirect()->intended(route('division.courriers.arrive', absolute: false));
        }
        return redirect()->intended(route('dai.courriers.arrive', absolute: false));
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
