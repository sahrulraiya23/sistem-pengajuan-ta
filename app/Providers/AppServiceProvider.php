<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ---- AWAL BLOK KODE BARU ----
        // Bagikan notifikasi ke semua view jika user sudah login
        View::composer('*', function ($view) {
            if (Auth::check()) {
                // Ambil notifikasi yang belum dibaca
                $view->with('unreadNotifications', Auth::user()->unreadNotifications);
            } else {
                // Jika tidak login, kirim koleksi kosong
                $view->with('unreadNotifications', collect());
            }
        });
        // ---- AKHIR BLOK KODE BARU ----
    }
}
