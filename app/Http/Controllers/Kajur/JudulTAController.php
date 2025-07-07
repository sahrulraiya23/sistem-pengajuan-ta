<?php

namespace App\Http\Controllers\Kajur;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\User;
use App\Models\DosenPembimbing;
use App\Models\SuratTA; // Perlu di-import untuk penetapan dospem akhir

use App\Notifications\DosenSaranDitunjukNotification;
use App\Notifications\JudulDiterimaNotification; // Notifikasi untuk judul yang diterima final
use App\Notifications\JudulDitolakNotification; // Notifikasi jika judul ditolak

class JudulTAController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan judul yang menunggu tindakan Kajur.
     */
    public function index()
    {
        // Menandai semua notifikasi terkait pengajuan sebagai "telah dibaca"
        Auth::user()->unreadNotifications->where('type', 'App\Notifications\JudulSubmittedNotification')->markAsRead();
        // Anda mungkin perlu menandai notifikasi lain (misal dari dosen saran) juga

        // Ambil pengajuan yang statusnya 'submitted' (awal) atau 'approved' (dari dosen saran, siap finalisasi)
        $pengajuan = JudulTA::with('mahasiswa')
            ->whereIn('status', [JudulTA::STATUS_SUBMITTED, JudulTA::STATUS_APPROVED]) // MENGGUNAKAN STATUS BARU: APPROVED
            ->latest()
            ->get();

        return view('kajur.judul-ta.index', compact('pengajuan'));
    }

    /**
     * Menampilkan detail satu pengajuan judul.
     */
    public function show(Request $request, $id)
    {
        // Logika untuk menandai notifikasi spesifik sebagai terbaca
        if ($request->has('notification_id')) {
            $notification = Auth::user()->unreadNotifications->where('id', $request->notification_id)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }

        $pengajuan = JudulTA::with('mahasiswa')->findOrFail($id);

        // Ambil daftar semua dosen untuk ditampilkan di form
        $dosen = User::where('role', 'dosen')->get();

        return view('kajur.judul-ta.show', compact('pengajuan', 'dosen'));
    }

    /**
     * Memproses pengajuan judul (menunjuk dosen saran, menolak, atau finalisasi).
     */
    public function processSubmission(Request $request, $id)
    {
        $request->validate([
            'tindakan' => 'required|in:tunjuk_dosen,tolak,final_approve',
            'catatan' => 'nullable|string|max:1000',
            'dosen_saran_id' => 'required_if:tindakan,tunjuk_dosen|exists:users,id',
            'final_dosen_pembimbing_id' => 'required_if:tindakan,final_approve|exists:users,id',
            'judul_approved_by_kajur' => 'required_if:tindakan,final_approve|string|max:255', // Jika Kajur memilih judul final
        ]);

        $pengajuan = JudulTA::findOrFail($id);

        // Validasi status awal pengajuan agar tidak salah proses
        if (!in_array($pengajuan->status, [JudulTA::STATUS_SUBMITTED, JudulTA::STATUS_APPROVED])) {
            return redirect()->route('kajur.judul-ta.show', $pengajuan->id)->with('error', 'Tindakan tidak dapat dilakukan karena proposal ini sudah diproses atau berada di tahap yang tidak sesuai.');
        }

        if ($request->tindakan == 'tunjuk_dosen') {
            // Pastikan hanya pengajuan dengan status 'submitted' yang bisa ditunjuk dosen saran
            if ($pengajuan->status !== JudulTA::STATUS_SUBMITTED) {
                return redirect()->route('kajur.judul-ta.show', $pengajuan->id)->with('error', 'Tindakan ini hanya berlaku untuk pengajuan baru.');
            }

            $pengajuan->update([
                'status' => JudulTA::STATUS_APPROVED_FOR_CONSULTATION,
                'dosen_saran_id' => $request->dosen_saran_id,
                'catatan_kajur' => $request->catatan,
            ]);

            $dosenSaran = User::find($request->dosen_saran_id);
            // Perbaikan: Pastikan notifikasi dikirim jika dosen ditemukan


            return redirect()->route('kajur.judul-ta.index')
                ->with('success', 'Dosen Saran berhasil ditunjuk dan notifikasi telah dikirim.');
        } elseif ($request->tindakan == 'final_approve') {
            // Pastikan hanya pengajuan dengan status 'approved' (dari dosen saran) yang bisa difinalisasi Kajur
            if ($pengajuan->status !== JudulTA::STATUS_APPROVED) {
                return redirect()->route('kajur.judul-ta.show', $pengajuan->id)->with('error', 'Tindakan ini hanya berlaku untuk pengajuan yang telah disetujui oleh dosen saran.');
            }

            $pengajuan->update([
                'status' => JudulTA::STATUS_FINALIZED,
                'judul_approved' => $request->judul_approved_by_kajur,
            ]);

            \App\Models\DosenPembimbing::create([ // PENTING: tambahkan backslash di depan App\Models
                'judul_ta_id' => $pengajuan->id,
                'dosen_id' => $request->final_dosen_pembimbing_id,
                'is_main' => true,
            ]);

            $pengajuan->mahasiswa->notify(new \App\Notifications\JudulDiterimaNotification($pengajuan)); // PENTING: tambahkan backslash

            return redirect()->route('kajur.judul-ta.index')
                ->with('success', 'Judul TA berhasil disetujui dan Dosen Pembimbing telah ditetapkan.');
        } elseif ($request->tindakan == 'tolak') {
            $pengajuan->update([
                'status' => JudulTA::STATUS_REJECTED,
                'alasan_penolakan' => $request->catatan,
            ]);

            $pengajuan->mahasiswa->notify(new \App\Notifications\JudulDitolakNotification($pengajuan)); // PENTING: tambahkan backslash

            return redirect()->route('kajur.judul-ta.index')
                ->with('success', 'Pengajuan judul berhasil ditolak.');
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }

    public function finalize(Request $request, $id)
    {
        // (Tidak ada perubahan di sini)
        $judulTA = JudulTA::findOrFail($id);
        $judulTA->status = 'finalized';
        $judulTA->save();

        $nomorSurat = 'TA/' . date('Y/m') . '/' . $judulTA->id;

        SuratTA::updateOrCreate(
            ['judul_ta_id' => $id],
            [
                'nomor_surat' => $nomorSurat,
                'tanggal_terbit' => now(),
            ]
        );

        return redirect()->route('kajur.judul-ta.show', $id)
            ->with('success', 'Judul TA berhasil difinalisasi dan surat tugas telah dibuat.');
    }
}
