<?php

namespace App\Http\Controllers\Kajur;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\SuratTA;
use App\Models\User;
use App\Models\DosenPembimbing;

class JudulTAController extends Controller
{
    public function index()
    {
        // --- LOGIKA BARU: TANDAI SEMUA TELAH DIBACA ---
        // Saat Kajur membuka halaman index, semua notifikasi pengajuan judul
        // akan otomatis dianggap terbaca.
        Auth::user()->unreadNotifications->markAsRead();
        // --- SELESAI LOGIKA BARU ---

        $pengajuan = JudulTA::with('mahasiswa')
            ->latest()
            ->get();

        return view('kajur.judul-ta.index', compact('pengajuan'));
    }

    public function show($id)
    {
        // --- LOGIKA BARU: TANDAI SATU NOTIFIKASI TELAH DIBACA ---
        // Cari notifikasi yang berhubungan dengan pengajuan judul ini.
        $notification = Auth::user()
            ->unreadNotifications
            ->where('data.judul_id', $id) // Mencari notifikasi berdasarkan ID dari URL
            ->first();

        // Jika notifikasi spesifik itu ditemukan, tandai sebagai telah dibaca.
        if ($notification) {
            $notification->markAsRead();
        }
        // --- SELESAI LOGIKA BARU ---

        $pengajuan = JudulTA::with('mahasiswa')->findOrFail($id);
        $dosen = User::where('role', 'dosen')->get();

        return view('kajur.judul-ta.show', compact('pengajuan', 'dosen'));
    }

    public function approve(Request $request, $id)
    {
        // (Tidak ada perubahan di sini)
        $request->validate([
            'judul_approved' => 'required|string|max:255',
            'dosen_id' => 'required|exists:users,id',
        ], [
            'judul_approved.required' => 'Silakan pilih atau tentukan judul yang akan disetujui.',
            'dosen_id.required' => 'Anda wajib memilih dosen pembimbing.',
        ]);

        $pengajuan = JudulTA::findOrFail($id);
        $pengajuan->update([
            'status' => 'approved',
            'judul_approved' => $request->judul_approved,
        ]);

        DosenPembimbing::create([
            'judul_ta_id' => $id,
            'dosen_id' => $request->dosen_id,
        ]);

        return redirect()->route('kajur.judul-ta.show', $id)
            ->with('success', 'Pengajuan judul berhasil disetujui dan pembimbing telah ditentukan.');
    }

    public function reject(Request $request, $id)
    {
        // (Tidak ada perubahan di sini)
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $pengajuan = JudulTA::findOrFail($id);
        $pengajuan->update([
            'status' => 'rejected',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return redirect()->route('kajur.judul-ta.index')
            ->with('success', 'Pengajuan judul berhasil ditolak');
    }

    public function assignPembimbing(Request $request, $id)
    {
        // (Tidak ada perubahan di sini)
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
        ]);

        $pengajuan = JudulTA::findOrFail($id);
        $existingPembimbing = DosenPembimbing::where('judul_ta_id', $id)->first();

        if ($existingPembimbing) {
            $existingPembimbing->update([
                'dosen_id' => $request->dosen_id
            ]);
        } else {
            DosenPembimbing::create([
                'judul_ta_id' => $id,
                'dosen_id' => $request->dosen_id,
            ]);
        }

        return redirect()->route('kajur.judul-ta.show', $id)
            ->with('success', 'Pembimbing berhasil ditentukan');
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
