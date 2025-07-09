<?php

namespace App\Http\Controllers\Kajur;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\User;
use App\Models\DosenPembimbing;
use App\Models\SuratTA; // Diperlukan untuk pembuatan surat tugas
use Illuminate\Support\Facades\DB; // Diperlukan untuk transaksi database

use App\Notifications\DosenSaranDitunjukNotification;
use App\Notifications\JudulDiterimaNotification; // Notifikasi untuk judul yang diterima final/konsultasi
use App\Notifications\JudulDitolakNotification; // Notifikasi jika judul ditolak

class JudulTAController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan judul yang menunggu tindakan Kajur.
     */
    public function index()
    {
        // Menandai semua notifikasi terkait pengajuan sebagai "telah dibaca"
        if (Auth::user()) {
            Auth::user()->unreadNotifications->where('type', 'App\Notifications\JudulSubmittedNotification')->markAsRead();
        }

        // Ambil pengajuan yang statusnya 'submitted' (awal) atau 'approved' (dari dosen saran, siap finalisasi)
        $pengajuan = JudulTA::with(['mahasiswa', 'dosenSarans', 'pembimbing.dosen'])
            ->whereIn('status', [JudulTA::STATUS_SUBMITTED, JudulTA::STATUS_APPROVED_FOR_CONSULTATION]) // Sesuaikan status yang ingin ditampilkan di index Kajur
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
        if ($request->has('notification_id') && Auth::user()) {
            $notification = Auth::user()->unreadNotifications->where('id', $request->notification_id)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }

        $pengajuan = JudulTA::with(['mahasiswa', 'dosenSarans', 'pembimbing.dosen'])->findOrFail($id);

        // Ambil daftar semua dosen (User dengan role 'dosen') untuk ditampilkan di form
        $dosen = User::where('role', 'dosen')->get();

        return view('kajur.judul-ta.show', compact('pengajuan', 'dosen'));
    }

    /**
     * Memproses tindakan pengajuan judul (tunjuk dosen saran, tolak).
     * Finalisasi judul ditangani oleh method 'finalize'.
     */
    public function processSubmission(Request $request, $id)
    {
        // Cari pengajuan judul berdasarkan ID atau batalkan jika tidak ditemukan
        $judulTA = JudulTA::findOrFail($id);

        // Menggunakan transaksi database untuk memastikan semua operasi berhasil atau tidak sama sekali
        return DB::transaction(function () use ($request, $judulTA) {
            $tindakan = $request->input('tindakan');

            switch ($tindakan) {
                case 'tunjuk_dosen':
                    // --- Logika untuk Menunjuk Dosen Saran ---
                    $validated = $request->validate([
                        'dosen_saran_ids' => 'required|array|min:1|max:2',
                        'dosen_saran_ids.*' => 'exists:users,id',
                        'catatan' => 'nullable|string|max:1000',
                    ]);

                    $judulTA->dosenSarans()->detach();
                    $judulTA->dosenSarans()->attach($validated['dosen_saran_ids']);

                    $judulTA->catatan_kajur = $validated['catatan'] ?? null;
                    $judulTA->status = JudulTA::STATUS_APPROVED_FOR_CONSULTATION;
                    $judulTA->save();

                    // Kirim notifikasi
                    foreach ($judulTA->dosenSarans as $dosenSaranUser) {
                        $dosenSaranUser->notify(new DosenSaranDitunjukNotification($judulTA));
                    }
                    $judulTA->mahasiswa->notify(new JudulDiterimaNotification($judulTA));

                    return redirect()->back()->with('success', 'Dosen saran berhasil ditunjuk dan status pengajuan diperbarui.');

                case 'tolak':
                    // --- Logika untuk Menolak Pengajuan ---
                    $validated = $request->validate([
                        'alasan_penolakan' => 'required|string|min:10|max:1000',
                    ]);

                    $judulTA->alasan_penolakan = $validated['alasan_penolakan'];
                    $judulTA->status = JudulTA::STATUS_REJECTED;
                    $judulTA->save();

                    // Kirim notifikasi
                    $judulTA->mahasiswa->notify(new JudulDitolakNotification($judulTA));

                    return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');

                default:
                    // Jika tindakan 'final_approve' dikirim ke sini, akan ditolak.
                    // Ini mengindikasikan bahwa 'final_approve' harus memiliki rutenya sendiri
                    // yang mengarah ke method 'finalize'.
                    return redirect()->back()->with('error', 'Tindakan tidak valid. Silakan gunakan tombol finalisasi.');
            }
        });
    }

    /**
     * Memfinalisasi persetujuan judul dan menetapkan dosen pembimbing utama.
     */
    public function finalize(Request $request, $id)
    {
        $judulTA = JudulTA::findOrFail($id);

        // Menggunakan transaksi database untuk memastikan semua operasi berhasil atau tidak sama sekali
        return DB::transaction(function () use ($request, $judulTA, $id) {
            // --- Logika Validasi dan Finalisasi ---
            $validated = $request->validate([
                'judul_approved_by_kajur_from_select' => 'nullable|string|max:500',
                'judul_approved_by_kajur_manual' => 'nullable|string|max:500',
                'final_dosen_pembimbing_id' => 'required|exists:users,id',
            ]);

            $approvedTitle = $validated['judul_approved_by_kajur_from_select'];
            if (empty($approvedTitle)) {
                $approvedTitle = $validated['judul_approved_by_kajur_manual'];
            }

            if (empty($approvedTitle)) {
                return back()->withErrors(['judul_approved_by_kajur' => 'Judul yang disetujui harus diisi, baik dari pilihan maupun input manual.'])->withInput();
            }

            $judulTA->judul_approved = $approvedTitle;
            $judulTA->status = JudulTA::STATUS_FINALIZED;
            $judulTA->save();

            $judulTA->pembimbing()->updateOrCreate(
                ['judul_ta_id' => $judulTA->id],
                [
                    'dosen_id' => $validated['final_dosen_pembimbing_id'],
                    'tipe_pembimbing' => 'utama',
                ]
            );

            // --- Logika Pembuatan Surat Tugas ---
            $nomorSurat = 'TA/' . date('Y') . '/' . date('m') . '/' . $judulTA->id;
            SuratTA::updateOrCreate(
                ['judul_ta_id' => $id],
                [
                    'nomor_surat' => $nomorSurat,
                    'tanggal_terbit' => now(),
                ]
            );
            // --- Akhir Logika Pembuatan Surat Tugas ---

            // Kirim notifikasi ke mahasiswa
            $judulTA->mahasiswa->notify(new JudulDiterimaNotification($judulTA));

            return redirect()->route('kajur.judul-ta.show', $id)
                ->with('success', 'Judul TA berhasil difinalisasi dan surat tugas telah dibuat.');
        });
    }
}