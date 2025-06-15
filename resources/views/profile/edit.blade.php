<x-guest-layout>
    {{-- Kontainer utama yang membuat semua konten terpusat, sama seperti halaman login --}}
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto min-h-screen lg:py-0">

        {{-- Judul Halaman --}}
        <div class="text-center mb-6">
            <a href="{{ url('/') }}" class="flex justify-center mb-4">
                <img src="{{ asset('assets/img/logo-uho.png') }}" alt="Logo UHO" class="h-14 w-auto">
            </a>
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                Pengaturan Akun
            </h1>
            <p class="text-sm text-gray-500">Perbarui informasi akun Anda di bawah ini.</p>
        </div>

        {{-- Area untuk menampung semua kartu --}}
        <div class="w-full sm:max-w-md space-y-6">

            {{-- Kartu 1: Form Update Informasi Profil --}}
            <div class="w-full bg-white rounded-lg shadow-lg p-6 sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Kartu 2: Form Update Password --}}
            <div class="w-full bg-white rounded-lg shadow-lg p-6 sm:p-8">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Kartu 3: Form Hapus Akun --}}
            <div class="w-full bg-white rounded-lg shadow-lg p-6 sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>

        </div>


        {{-- Link Navigasi di Bagian Bawah --}}
        <div class="flex items-center justify-between mt-8 w-full sm:max-w-md">
            @php
                // Logika untuk menentukan URL halaman utama berdasarkan role
                $home_route = 'welcome';
                if (Auth::user()->role === 'mahasiswa') {
                    $home_route = 'mahasiswa.judul-ta.index';
                } elseif (Auth::user()->role === 'dosen') {
                    $home_route = 'dosen.judul-ta.index';
                } elseif (Auth::user()->role === 'kajur') {
                    $home_route = 'kajur.judul-ta.index';
                }
            @endphp

            {{-- Link kembali yang dinamis --}}
            <a href="{{ route($home_route) }}" class="text-sm font-medium text-blue-600 hover:underline">
                Kembali ke Halaman Utama
            </a>

            {{-- Tombol Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-medium text-red-600 hover:underline">
                    Log Out
                </button>
            </form>
        </div>

    </div>
</x-guest-layout>
    