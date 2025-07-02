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
use App\Notifications\DosenSaranDitunjukNotification; // Pastikan untuk mengimpor Auth

class BimbinganController extends Controller
{
    public function index()
    {
        // Menggunakan Auth::user()->id untuk mendapatkan ID dosen
        // $bimbingan = DosenPembimbing::where('dosen_id', Auth::user()->id)
        //     ->with('judulTA', 'judulTA.mahasiswa')
        //     ->latest()
        //     ->get();
        Auth::user()->unreadNotifications->where('type', 'App\Notifications\DosenSaranDitunjukNotification')->markAsRead();


        // return view('dosen.bimbingan.index', compact('bimbingan'));
        $bimbingan = JudulTA::where('dosen_saran_id', Auth::id())
            ->where('status', 'approved') // atau 'konsultasi' jika Anda berhasil mengubah DB
            ->with('mahasiswa')
            ->latest()
            ->get();

        // Nama variabel di view kita ubah menjadi 'pengajuan' agar konsisten
        return view('dosen.bimbingan.index', ['pengajuan' => $bimbingan]);
    }
    public function create()
    {
        // Tampilan untuk membuat pengajuan bimbingan
        return view('dosen.bimbingan.create');
    }
    public function show($id)
    {
        $pengajuan = JudulTA::with('mahasiswa')->findOrFail($id);
        if ($pengajuan->dosen_saran_id !== Auth::id()) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        }
        $revisi = Revisi::where('judul_ta_id', $id)
            ->with('user')
            ->latest()
            ->get();
        return view('dosen.bimbingan.show', compact('pengajuan', 'revisi'));
    }
    public function finalize(Request $request, $id)
    {
        return redirect()->back()->with('info', 'Fungsi ini tidak lagi digunakan.');
    }
}
