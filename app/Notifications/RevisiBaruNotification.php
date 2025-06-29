<?php

namespace App\Notifications;

use App\Models\Revisi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RevisiBaruNotification extends Notification
{
    use Queueable;

    protected $revisi;

    /**
     * Create a new notification instance.
     */
    public function __construct(Revisi $revisi)
    {
        $this->revisi = $revisi;
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
        // $this->revisi->dosen akan mengambil data dosen yang terkait dengan revisi
        // $this->revisi->judulTA akan mengambil data judul TA yang terkait
        $dosenName = $this->revisi->dosen->name;
        $judul = $this->revisi->judulTA->judul;

        return [
            'revisi_id' => $this->revisi->id,
            'message' => "Dosen {$dosenName} menambahkan revisi baru pada judul '{$judul}'.",
            'path' => route('mahasiswa.revisi.show', $this->revisi->id),
        ];
    }
}