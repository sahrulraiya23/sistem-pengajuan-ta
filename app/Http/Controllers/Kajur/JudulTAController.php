<?php

namespace App\Http\Controllers\Kajur;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\User;

use App\Notifications\DosenSaranDitunjukNotification;

// Hapus model yang tidak digunakan di tahap ini
// use App\Models\DosenPembimbing; 
// use App\Models\SuratTA;

class JudulTAController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan judul.
     */
    public function index()
    {
        // Menandai semua notifikasi terkait pengajuan sebagai "telah dibaca"
        Auth::user()->unreadNotifications->where('type', 'App\Notifications\JudulSubmittedNotification')->markAsRead();

        // Ambil pengajuan yang statusnya masih 'pending' untuk ditindaklanjuti
        $pengajuan = JudulTA::with('mahasiswa')
            ->where('status', 'submitted')
            ->latest()
            ->get();

        return view('kajur.judul-ta.index', compact('pengajuan'));
    }

    /**
     * Menampilkan detail satu pengajuan judul.
     */
    public function show(Request $request, $id)
    {
        // Logika untuk menandai notifikasi spesifik sebagai terbaca (sudah bagus!)
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
    public function processSubmission(Request $request, $id)
    {
        // ... (validasi tidak berubah) ...

        $pengajuan = JudulTA::findOrFail($id);

        if ($pengajuan->status !== 'submitted') {
            return redirect()->route('kajur.judul-ta.show', $pengajuan->id)->with('error', 'Tindakan tidak dapat dilakukan karena proposal ini sudah diproses sebelumnya.');
        }

        if ($request->tindakan == 'tunjuk_dosen') {
            $pengajuan->update([
                'status' => 'approved',
                'dosen_saran_id' => $request->dosen_saran_id,
                'catatan_kajur' => $request->catatan,
            ]);

            // 👇 2. LOGIKA UNTUK MENGIRIM NOTIFIKASI
            // Cari data user dari dosen yang baru ditunjuk
            $dosenSaran = User::find($request->dosen_saran_id);

            // Jika dosen ditemukan, kirim notifikasi kepadanya
            if ($dosenSaran) {
                $dosenSaran->notify(new DosenSaranDitunjukNotification($pengajuan));
            }
            // --- SELESAI ---

            return redirect()->route('kajur.judul-ta.index')
                ->with('success', 'Dosen Saran berhasil ditunjuk dan notifikasi telah dikirim.');
        } elseif ($request->tindakan == 'tolak') {
            // ... (bagian ini tidak berubah) ...
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }
}
