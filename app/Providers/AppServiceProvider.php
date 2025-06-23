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
        // Menggunakan closure-based view composer untuk membagikan data ke view spesifik.
        // Kode ini akan dieksekusi setiap kali view 'template.nav' dirender.
        View::composer('template.nav', function ($view) {
            $user = Auth::user();
            $unreadNotifications = [];

            // Pastikan user sudah login dan perannya adalah 'kajur'
            if ($user && $user->role === 'kajur') {
                // Ambil notifikasi yang belum dibaca
                $unreadNotifications = $user->unreadNotifications;
            }

            // Kirim variabel 'unreadNotifications' ke view
            $view->with('unreadNotifications', $unreadNotifications);
        });
    }
}
