<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\DosenPembimbing;
use App\Models\Revisi;
use App\Models\SuratTA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use App\Notifications\RevisiBaruNotification; // Pastikan untuk mengimpor Auth

class BimbinganController extends Controller
{
    public function index()
    {
        // Menggunakan Auth::user()->id untuk mendapatkan ID dosen
        $bimbingan = DosenPembimbing::where('dosen_id', Auth::user()->id)
            ->with('judulTA', 'judulTA.mahasiswa')
            ->latest()
            ->get();


        return view('dosen.bimbingan.index', compact('bimbingan'));
    }
    public function create()
    {
        // Tampilan untuk membuat pengajuan bimbingan
        return view('dosen.bimbingan.create');
    }

    public function show($id)
    {
        // 1. Mengecek apakah dosen yang sedang login adalah pembimbing untuk TA ini.
        //    firstOrFail() akan otomatis menampilkan error 404 jika tidak ditemukan.
        DosenPembimbing::where('judul_ta_id', $id)
            ->where('dosen_id', Auth::user()->id)
            ->firstOrFail();

        // 2. Mengambil data Judul Tugas Akhir beserta data Mahasiswa-nya.
        //    Variabel $pengajuan ini sudah berisi semua info yang kita butuhkan tentang TA & Mahasiswa.
        $pengajuan = JudulTA::with('mahasiswa')->findOrFail($id);

        // 3. Mengambil SEMUA data revisi untuk judul tugas akhir tersebut.
        //    Variabel $revisi akan berisi daftar revisi yang bisa kita tampilkan di view.
        $revisi = Revisi::where('judul_ta_id', $id)
            ->with('user') // 'user' di sini adalah relasi ke model User (dosen yang memberi revisi)
            ->latest()
            ->get();

        // Catatan: Blok kode yang menyebabkan error sudah dihapus.
        // Pengiriman notifikasi sebaiknya dilakukan di method terpisah saat revisi DIBUAT,
        // bukan saat halaman ini DITAMPILKAN untuk menghindari notifikasi berulang.

        // 4. Mengirim data ke view.
        return view('dosen.bimbingan.show', compact('pengajuan', 'revisi'));
    }
    public function finalize(Request $request, $id)
    {
        return redirect()->back()->with('info', 'Fungsi ini tidak lagi digunakan.');
    }
}
