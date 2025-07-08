<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA; // Pastikan ini diimpor
use App\Models\Revisi;
use App\Models\DosenPembimbing;
use App\Models\SuratTA;

use App\Models\User; // Pastikan ini diimpor
use App\Notifications\PengajuanJudulNotification; // Untuk notifikasi ke Kajur
use App\Notifications\ReSubmissionToDosenNotification; // Notifikasi ke Dosen Saran
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

        $judulTA = JudulTA::create([
            'user_id' => Auth::id(),
            'judul1' => $request->judul1,
            'judul2' => $request->judul2,
            'judul3' => $request->judul3,
            'status' => JudulTA::STATUS_SUBMITTED, // MENGGUNAKAN KONSTANTA
        ]);

        $kajurUsers = User::where('role', 'kajur')->get();
        if ($kajurUsers->isNotEmpty()) {
            Notification::send($kajurUsers, new PengajuanJudulNotification($judulTA));
        }

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

    /**
     * Mahasiswa mengajukan kembali judul setelah konsultasi/revisi dengan dosen saran.
     */
    public function reSubmitAfterConsultation(Request $request, $id)
    {
        // 1. Validasi input judul_revisi
        $request->validate([
            'judul_revisi' => 'required|string|max:255', // Validasi untuk judul yang direvisi
        ]);

        $pengajuan = JudulTA::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Pastikan status saat ini adalah 'revisi' sebelum bisa diajukan kembali
        if ($pengajuan->status !== JudulTA::STATUS_REVISED) {
            return redirect()->back()->with('error', 'Pengajuan hanya bisa diajukan kembali setelah menerima revisi.');
        }

        // 2. Perbarui status dan simpan judul_revisi
        $pengajuan->update([
            'status' => JudulTA::STATUS_SUBMIT_REVISED, // Menggunakan status yang Anda tentukan
            'judul_revisi' => $request->judul_revisi,   // SIMPAN JUDUL YANG SUDAH DIREVISI DI SINI
        ]);

        // 3. Kirim notifikasi ke DOSEN SARAN yang terkait dengan pengajuan ini
        $dosenSaran = User::find($pengajuan->dosen_saran_id);


        return redirect()->route('mahasiswa.judul-ta.index')
            ->with('success', 'Pengajuan judul berhasil diajukan kembali ke dosen saran untuk ditinjau.');
    }
}
