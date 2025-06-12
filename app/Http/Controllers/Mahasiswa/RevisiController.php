<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\Revisi;

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
        $request->validate([
            'catatan' => 'required|string',
            'judul_revisi' => 'required|string|max:255',
        ]);

        $pengajuan = JudulTA::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$pengajuan->dosen_pembimbing_1_id) {
            return redirect()->back()->with('error', 'Tidak dapat mengirim revisi, Dosen Pembimbing belum ada.');
        }

        // Perhatikan: Kita perlu cara untuk menyimpan usulan judul baru dari mahasiswa.
        // Kita bisa gabungkan di dalam catatan.
        $catatan_lengkap = "Usulan Judul Baru: " . $request->judul_revisi . "\n\n" . "Catatan: " . $request->catatan;

        Revisi::create([
            'judul_ta_id' => $id,
            'user_id' => Auth::id(),
            'role_type' => 'mahasiswa',
            // Simpan catatan yang sudah digabung
            'catatan' => $catatan_lengkap,
            'dosen_id' => $pengajuan->dosen_pembimbing_1_id,
        ]);

        // HAPUS BAGIAN UPDATE INI. JANGAN UPDATE JUDUL DULU.
        // $pengajuan->update([
        //     'judul' => $request->judul_revisi,
        // ]);

        return redirect()->route('mahasiswa.revisi.show', $id)
            ->with('success', 'Usulan Revisi berhasil dikirim dan menunggu persetujuan dosen.');
    }
}
