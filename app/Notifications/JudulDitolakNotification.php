<?php

namespace App\Notifications;

use App\Models\JudulTA;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JudulDitolakNotification extends Notification
{
    use Queueable;

    protected $judulTA;

    /**
     * Create a new notification instance.
     */
    public function __construct(JudulTA $judulTA)
    {
        $this->judulTA = $judulTA;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'judul_ta_id' => $this->judulTA->id,
            'message' => 'Mohon maaf, judul TA Anda "' . $this->judulTA->judul . '" ditolak. Silakan ajukan judul baru.',
            'path' => route('mahasiswa.judul-ta.show', $this->judulTA->id),
        ];
    }
}
