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
     *
     * @param \App\Models\JudulTA $judulTA
     */
    public function __construct(JudulTA $judulTA)
    {
        $this->judulTA = $judulTA;
    }

    /**
     * Menentukan channel pengiriman notifikasi.
     * Kita akan menggunakan 'database' untuk menyimpannya.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Mendapatkan representasi array dari notifikasi.
     * Data inilah yang akan disimpan di kolom 'data' pada tabel notifications.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Pastikan relasi 'mahasiswa' ada di model JudulTA Anda
        $mahasiswaName = $this->judulTA->mahasiswa ? $this->judulTA->mahasiswa->name : 'Seorang mahasiswa';

        return [
            'judul_id' => $this->judulTA->id,
            'judul' => $this->judulTA->judul,
            'mahasiswa_name' => $mahasiswaName,
            'message' => 'Mahasiswa ' . $mahasiswaName . ' telah mengajukan judul baru.',
            // URL ini akan digunakan saat notifikasi di klik
            'url' => route('kajur.judul-ta.show', $this->judulTA->id),
        ];
    }
}
