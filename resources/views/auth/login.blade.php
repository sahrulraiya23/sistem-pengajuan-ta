<x-guest-layout>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div class="w-full bg-white rounded-lg shadow-lg md:mt-0 sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">

                <div class="text-center">
                    <img src="{{ asset('assets/img/logo-uho.png') }}" alt="Logo UHO" class="h-14 w-auto mx-auto mb-4">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                        Selamat Datang Kembali
                    </h1>
                    <p class="text-sm text-gray-500">Silakan login ke akun Anda</p>
                </div>

                <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Blok Nomor Induk yang Baru --}}
                    <div>
                        <x-input-label for="nomor_induk" value="Nomor Induk (NIM/NIDN)" />
                        <x-text-input id="nomor_induk" class="block mt-1 w-full" type="text" name="nomor_induk"
                            :value="old('nomor_induk')" required autofocus />
                        <x-input-error :messages="$errors->get('nomor_induk')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" value="Password" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Lupa
                                password?</a>
                        @endif
                    </div>

                    <x-primary-button class="w-full justify-center !py-3">
                        {{ __('Log In') }}
                    </x-primary-button>


                    <p class="text-sm text-center font-light text-gray-500">
                        Belum punya akun? <a href="{{ route('register') }}"
                            class="font-medium text-blue-600 hover:underline">Daftar</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
