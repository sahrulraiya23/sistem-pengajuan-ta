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
    public function markAsRead(Request $request, DatabaseNotification $notification)
    {
        // Pastikan notifikasi ini milik pengguna yang sedang login untuk keamanan
        if (Auth::id() === $notification->notifiable_id) {
            $notification->markAsRead();
        }

        // Redirect ke URL asli dari notifikasi, atau ke dashboard jika tidak ada
        return redirect($request->input('redirect', '/dashboard'));
    }
}
