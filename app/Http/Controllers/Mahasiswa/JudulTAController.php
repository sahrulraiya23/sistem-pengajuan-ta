<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\Revisi;
use App\Models\DosenPembimbing;
use App\Models\SuratTA;

use App\Models\User;
use App\Notifications\PengajuanJudulNotification;
use Illuminate\Support\Facades\Notification;

class JudulTAController extends Controller
{
    public function index()
    {
        $pengajuan = JudulTA::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('mahasiswa.index', compact('pengajuan'));
    }

    public function create()
    {
        return view('mahasiswa.judul-ta.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul1' => 'required|string|max:255',
            'judul2' => 'required|string|max:255',
            'judul3' => 'required|string|max:255',
        ]);

        // Method create() akan langsung mengembalikan instance model yang baru dibuat
        // Kita menangkapnya di variabel $judulTA untuk digunakan di notifikasi
        $judulTA = JudulTA::create([
            'user_id' => Auth::id(), // PASTIKAN nama kolom ini benar (mahasiswa_id atau user_id)
            'judul1' => $request->judul1,
            'judul2' => $request->judul2,
            'judul3' => $request->judul3,
            'status' => 'submitted', // Saya ganti 'submitted' menjadi 'diajukan' agar konsisten
        ]);

        // --- MULAI LOGIKA NOTIFIKASI ---
        $kajurUsers = User::where('role', 'kajur')->get();
        if ($kajurUsers->isNotEmpty()) {
            Notification::send($kajurUsers, new PengajuanJudulNotification($judulTA));
        }
        // --- SELESAI LOGIKA NOTIFIKASI ---

        return redirect()->route('mahasiswa.judul-ta.index')
            ->with('success', 'Pengajuan judul tugas akhir berhasil dikirim');
    }

    public function show($id)
    {
        $pengajuan = JudulTA::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $revisi = Revisi::where('judul_ta_id', $id)
            ->with('user')
            ->latest()
            ->get();

        $pembimbing = DosenPembimbing::where('judul_ta_id', $id)
            ->with('dosen')
            ->first();

        $surat = SuratTA::where('judul_ta_id', $id)->first();

        return view('mahasiswa.judul-ta.show', compact('pengajuan', 'revisi', 'pembimbing', 'surat'));
    }
}
