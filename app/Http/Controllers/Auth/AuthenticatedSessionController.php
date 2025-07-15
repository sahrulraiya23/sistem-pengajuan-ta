<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
// Hapus 'use App\Providers\RouteServiceProvider;' jika tidak digunakan di tempat lain
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
        // Panggil method authenticate dari LoginRequest
        $request->authenticate();

        // Regenerasi session
        $request->session()->regenerate();

        // Redirect ke halaman yang sesuai setelah login
        $user = $request->user();
        $redirect_url = match ($user->role) {
            'admin' => '/admin',
            'dosen' => route('dosen.bimbingan.index'),
            'mahasiswa' => route('mahasiswa.judul-ta.index'),
            'kajur' => route('kajur.judul-ta.index'),
            default => '/', // Diubah dari RouteServiceProvider::HOME
        };

        return redirect()->intended($redirect_url);
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
