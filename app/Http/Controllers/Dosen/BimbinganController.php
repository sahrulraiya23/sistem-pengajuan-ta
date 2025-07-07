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
use App\Models\User; // Perlu di-import untuk notifikasi

use App\Notifications\RevisiBaruNotification;
use App\Notifications\DosenSaranDitunjukNotification;
use App\Notifications\PengajuanJudulNotification; // Notifikasi ke Kajur (jika digunakan)
use App\Notifications\JudulDitolakNotification; // Notifikasi ke mahasiswa jika dosen tolak
use App\Notifications\ReSubmissionToDosenNotification; // Notifikasi ke dosen setelah mahasiswa re-submit

class BimbinganController extends Controller
{
    public function index()
    {
        Auth::user()->unreadNotifications->where('type', 'App\Notifications\DosenSaranDitunjukNotification')->markAsRead();
        $bimbingan = JudulTA::where('dosen_saran_id', Auth::id())
            ->whereIn('status', [
                JudulTA::STATUS_APPROVED_FOR_CONSULTATION, // Menunggu dosen saran memberikan feedback awal
                JudulTA::STATUS_REVISED,                     // Setelah dosen saran memberikan feedback, mahasiswa sedang merevisi
                JudulTA::STATUS_SUBMIT_REVISED               // Mahasiswa mengajukan kembali ke dosen saran
            ])
            ->with('mahasiswa')
            ->latest()
            ->get();

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

        if (
            $pengajuan->dosen_saran_id !== Auth::id() &&
            (! $pengajuan->pembimbing || $pengajuan->pembimbing->dosen_id !== Auth::id())
        ) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        }

        $revisi = Revisi::where('judul_ta_id', $id)
            ->with('user')
            ->latest()
            ->get();

        return view('dosen.bimbingan.show', compact('pengajuan', 'revisi'));
    }
    public function processInitialLecturerReview(Request $request, $id)
    {
        $request->validate([
            'judul_pilihan_dosen_saran' => 'required|string|max:255', // Judul yang dipilih oleh dosen saran
            'catatan_initial_revisi' => 'required|string|max:1000', // Revisi awal yang diberikan dosen
        ]);

        $pengajuan = JudulTA::where('id', $id)
            ->where('dosen_saran_id', Auth::id()) // Pastikan dosen ini yang berhak memproses
            ->firstOrFail();

        // Hanya proses jika statusnya APPROVED_FOR_CONSULTATION
        if ($pengajuan->status !== JudulTA::STATUS_APPROVED_FOR_CONSULTATION) {
            return redirect()->back()->with('error', 'Pengajuan ini tidak dalam status untuk persetujuan awal dosen saran.');
        }

        // 1. Setel judul yang disetujui oleh dosen saran
        $pengajuan->update([
            'judul_approved' => $request->judul_pilihan_dosen_saran,
            'status' => JudulTA::STATUS_REVISED, // Langsung ke status revisi setelah disetujui dan diberi catatan
            'catatan_dosen_saran' => $request->catatan_initial_revisi, // Catatan ini bisa disimpan di sini
        ]);

        // 2. Buat entri revisi pertama di tabel revisi
        $revisi = new Revisi();
        $revisi->judul_ta_id = $pengajuan->id;
        $revisi->user_id = Auth::id(); // ID dosen
        $revisi->dosen_id = Auth::id(); // ID dosen
        $revisi->role_type = 'dosen';
        $revisi->catatan = $request->catatan_initial_revisi;
        $revisi->save();

        // 3. Kirim notifikasi ke mahasiswa
        // 3. Kirim notifikasi ke mahasiswa
        if ($pengajuan->mahasiswa) {
            // PERBAIKAN: Hanya berikan objek $revisi ke konstruktor notifikasi
            $pengajuan->mahasiswa->notify(new RevisiBaruNotification($revisi)); // <-- Ini benar
        }

        return redirect()->route('dosen.bimbingan.index')
            ->with('success', 'Judul berhasil disetujui dan revisi awal telah dikirim ke mahasiswa.');
    }



    public function processReSubmittedTitle(Request $request, $id)
    {
        $request->validate([
            'tindakan' => 'required|in:approve_resubmission,reject_resubmission',
            'catatan' => 'nullable|string|max:1000',
            'judul_approved_by_dosen' => 'required_if:tindakan,approve_resubmission|string|max:255', // Jika dosen menyetujui, mungkin ada judul final dari dosen
        ]);

        $pengajuan = JudulTA::where('id', $id)
            ->where('dosen_saran_id', Auth::id()) // Pastikan dosen ini yang berhak memproses
            ->firstOrFail();

        // Pastikan statusnya memang 'submit_revisi'
        if ($pengajuan->status !== JudulTA::STATUS_SUBMIT_REVISED) {
            return redirect()->back()->with('error', 'Tindakan tidak dapat dilakukan karena status pengajuan tidak sesuai.');
        }

        if ($request->tindakan == 'approve_resubmission') {
            $pengajuan->update([
                'status' => JudulTA::STATUS_APPROVED, // MENGGUNAKAN STATUS: approved
                'judul_approved' => $request->judul_approved_by_dosen, // Simpan judul yang disetujui dosen
                'catatan_dosen_saran' => $request->catatan, // Simpan catatan dosen saran
            ]);

            // Kirim notifikasi ke Kajur bahwa judul sudah disetujui dosen dan siap untuk finalisasi
            $kajurUsers = User::where('role', 'kajur')->get();
            if ($kajurUsers->isNotEmpty()) {
                Notification::send($kajurUsers, new PengajuanJudulNotification($pengajuan)); // Bisa jadi notifikasi yang berbeda untuk Kajur
            }

            return redirect()->route('dosen.bimbingan.index')
                ->with('success', 'Pengajuan judul berhasil disetujui dan diajukan ke Ketua Jurusan.');
        } elseif ($request->tindakan == 'reject_resubmission') {
            $pengajuan->update([
                'status' => JudulTA::STATUS_REVISED, // Kembali ke status revisi
                'alasan_penolakan' => $request->catatan, // Gunakan kolom alasan_penolakan
                'catatan_dosen_saran' => $request->catatan, // Simpan catatan dosen saran
            ]);

            // Kirim notifikasi ke mahasiswa bahwa pengajuan kembali ditolak
            if ($pengajuan->mahasiswa) {
                $pengajuan->mahasiswa->notify(new JudulDitolakNotification($pengajuan));
            }

            return redirect()->route('dosen.bimbingan.index')
                ->with('success', 'Pengajuan judul berhasil ditolak dan status kembali menjadi "revisi".');
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }

    public function finalize(Request $request, $id)
    {
        return redirect()->back()->with('info', 'Fungsi ini tidak lagi digunakan.');
    }
}
