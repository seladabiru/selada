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

        // Jika user adalah admin, langsung landing di dashboard/ringkasan studio website
        if ($request->user()->isAdmin()) {
            $intended = session()->get('url.intended');
            // Jika ada intended URL khusus ke modul admin tertentu, izinkan
            if ($intended && str_contains($intended, '/admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }
            // Bersihkan intended URL jika sebelumnya menunjuk ke halaman siswa/root
            session()->forget('url.intended');
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('songs.index'));
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
