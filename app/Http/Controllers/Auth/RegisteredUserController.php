<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Menambahkan validator untuk field tambahan.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:mahasiswa,dosen,kajur'],
            'nomor_induk' => ['required', 'string', 'max:20'],
            'program_studi' => ['required', 'string', 'max:100'],
        ]);
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:mahasiswa,dosen,kajur'],
            'nomor_induk' => ['required', 'string', 'max:20'],
            'program_studi' => ['required', 'string', 'max:100'],
        ]);

        // Membuat pengguna baru dengan data yang sudah tervalidasi
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nomor_induk' => $request->nomor_induk,
            'program_studi' => $request->program_studi,
        ]);

        // Menyebarkan event terdaftar
        event(new Registered($user));

        // Melakukan login otomatis setelah registrasi
        Auth::login($user);

        // Mengalihkan pengguna ke dashboard
        return redirect(route('dashboard', absolute: false));
    }
}
