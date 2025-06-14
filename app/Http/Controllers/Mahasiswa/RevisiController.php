<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\Revisi;
use App\Models\DosenPembimbing;

class RevisiController extends Controller
{
    public function show($id)
    {
        $pengajuan = JudulTA::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $revisi = Revisi::where('judul_ta_id', $id)
            ->with('user')
            ->latest()
            ->get();

        return view('mahasiswa.revisi.show', compact('pengajuan', 'revisi', 'id'));
    }


    public function store(Request $request, $id)
    {
        // 1. Validasi input dari form
        $request->validate([
            'catatan' => 'required|string',
            'judul_revisi' => 'required|string|max:255',
        ]);

        // 2. Cari pengajuan judul TA milik mahasiswa yang sedang login
        $pengajuan = JudulTA::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // 3. Ambil data dosen pembimbing dari relasi
        $pembimbing = DosenPembimbing::where('judul_ta_id', $id)->first();

        // 4. Pastikan pembimbing sudah ada sebelum melanjutkan
        if (!$pembimbing) {
            return redirect()->back()->with('error', 'Tidak dapat mengirim revisi, Dosen Pembimbing belum ditentukan.');
        }

        // 5. Update tabel `judul_ta` dengan judul revisi dan status baru
        $pengajuan->update([
            'judul_revisi' => $request->judul_revisi,
            'status' => 'menunggu_review_revisi',
        ]);

        // 6. Buat entri baru di tabel `revisi` dan PASTIKAN `dosen_id` disertakan
        Revisi::create([
            'judul_ta_id' => $id,
            'user_id' => Auth::id(),
            'dosen_id' => $pembimbing->dosen_id, // <-- INI BAGIAN PALING PENTING
            'role_type' => 'mahasiswa',
            'catatan' => $request->catatan,
        ]);

        // 7. Redirect kembali ke halaman detail dengan pesan sukses
        return redirect()->route('mahasiswa.judul-ta.show', $id)
            ->with('success', 'Usulan Revisi berhasil dikirim dan menunggu persetujuan dosen.');
    }
}
