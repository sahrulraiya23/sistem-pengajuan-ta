<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\SuratTA;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratController extends Controller
{
    public function show($id)
    {
        // 1. Cari data SuratTA berdasarkan ID yang dikirim dari tombol.
        // Gunakan findOrFail untuk otomatis menampilkan halaman 404 jika ID tidak ditemukan.
        $surat = SuratTA::findOrFail($id);

        // 2. Ambil data JudulTA yang berelasi dengan SuratTA yang ditemukan.
        // Kita juga memuat relasi mahasiswa dan pembimbing dosen untuk ditampilkan di view.
        $pengajuan = JudulTA::with(['mahasiswa', 'pembimbings.dosen'])->findOrFail($surat->judul_ta_id);

        // 3. Tampilkan view dengan data yang sudah benar.
        // Variabel $pengajuan dan $surat sekarang sudah berisi data yang benar dan siap digunakan.
        return view('mahasiswa.surat.show', compact('pengajuan', 'surat'));
    }
    public function downloadPDF($id)
    {
        // 1. Ambil data pengajuan dan relasinya berdasarkan ID JudulTA
        $pengajuan = JudulTA::with(['mahasiswa', 'pembimbings.dosen'])->findOrFail($id);

        // 2. Ambil data surat yang terkait
        $surat = SuratTA::where('judul_ta_id', $id)->first();

        // 3. Jika surat belum ada, kembalikan dengan pesan error
        if (!$surat) {
            return redirect()->route('mahasiswa.judul-ta.show', $id)
                ->with('error', 'Surat tugas akhir belum tersedia untuk diunduh.');
        }

        // 4. Load view untuk PDF dengan data yang diperlukan
        // Pastikan Anda memiliki file view di 'resources/views/mahasiswa/surat/pdf_view.blade.php'
        $pdf = Pdf::loadView('mahasiswa.surat.pdf_view', compact('pengajuan', 'surat'));
        $pdf->setPaper('a4', 'portrait');

        // 5. Buat nama file yang akan diunduh
        $fileName = 'Surat_Tugas_TA_' . ($pengajuan->mahasiswa->nomor_induk ?? $pengajuan->mahasiswa->id) . '.pdf';

        // 6. Langsung unduh file PDF di browser
        return $pdf->stream($fileName);
    }

    // public function downloadPDF($id)
    // {
    //     // 1. Ambil data yang sama dengan fungsi show()
    //     $pengajuan = JudulTA::with(['mahasiswa', 'pembimbing.dosen'])->findOrFail($id);
    //     $surat = SuratTA::where('judul_ta_id', $id)->first();

    //     if (!$surat) {
    //         return redirect()->route('mahasiswa.judul-ta.show', $id)
    //             ->with('error', 'Surat tugas akhir belum tersedia untuk diunduh.');
    //     }

    //     $pdf = PDF::loadView('mahasiswa.surat.pdf_view', compact('pengajuan', 'surat'));
    //     $pdf->setPaper('a4', 'portrait');

    //     // 4. Buat nama file dan mulai download
    //     $fileName = 'Pengajuan_Judul_TA' . ($pengajuan->mahasiswa->nomor_induk ?? $pengajuan->mahasiswa->id) . '.pdf';
    //     return $pdf->stream($fileName);
    // }
}
