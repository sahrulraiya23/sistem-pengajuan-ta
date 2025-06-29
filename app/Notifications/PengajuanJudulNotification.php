<?php

namespace App\Notifications;

use App\Models\JudulTA;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanJudulNotification extends Notification
{
    use Queueable;

    protected $judulTA;

    /**
     * Membuat instance notifikasi baru.
     */
    public function __construct(JudulTA $judulTA)
    {
        $this->judulTA = $judulTA;
    }

    /**
     * Menentukan channel pengiriman notifikasi.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Mendapatkan representasi array dari notifikasi.
     */
    public function toArray(object $notifiable): array
    {
        $mahasiswaName = $this->judulTA->mahasiswa ? $this->judulTA->mahasiswa->name : 'Seorang mahasiswa';

        return [
            'judul_id' => $this->judulTA->id,
            'message' => 'Mahasiswa ' . $mahasiswaName . ' telah mengajukan judul baru.',

            // =======================================================
            // PERBAIKAN: Ubah kunci 'url' menjadi 'path' agar sesuai dengan view
            // =======================================================
            'path' => route('kajur.judul-ta.show', $this->judulTA->id),
        ];
    }
}
