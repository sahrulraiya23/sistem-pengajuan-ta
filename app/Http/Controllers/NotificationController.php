<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Tandai notifikasi sebagai sudah dibaca dan redirect ke URL tujuan.
     */
    // app/Http/Controllers/NotificationController.php

    public function markAsRead(Request $request, DatabaseNotification $notification)
    {
        // Pastikan notifikasi ini milik pengguna yang sedang login
        if (Auth::id() === $notification->notifiable_id) {
            $notification->markAsRead();
        }

        // BAGIAN KUNCI: Ambil 'url' dari data notifikasi dan redirect ke sana
        return redirect($notification->data['url'] ?? url('/dashboard'));
    }
}
