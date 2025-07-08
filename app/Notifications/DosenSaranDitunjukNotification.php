<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JudulTA; // <-- Tambahkan ini

class DosenSaranDitunjukNotification extends Notification
{
    use Queueable;

    // Tambahkan properti untuk menyimpan data judul
    protected $judulTA;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\JudulTA $judulTA
     * @return void
     */
    public function __construct(JudulTA $judulTA) // <-- Ubah constructor
    {
        $this->judulTA = $judulTA;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Kita akan mengirim notifikasi ke database
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // Ini adalah data yang akan disimpan di tabel 'notifications'
        return [
            'judul_ta_id' => $this->judulTA->id,
            'mahasiswa_name' => $this->judulTA->mahasiswa->name,
            'message' => 'Anda ditunjuk sebagai dosen saran untuk proposal dari ' . $this->judulTA->mahasiswa->name,
            'url' => route('dosen.bimbingan.show', $this->judulTA->id),
        ];
    }
}
