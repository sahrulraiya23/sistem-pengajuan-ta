<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\Revisi;
use App\Models\DosenPembimbing;
use App\Models\SuratTA;

class JudulTAController extends Controller
{
    public function index()
    {
        $pengajuan = JudulTA::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('mahasiswa.judul-ta.index', compact('pengajuan'));
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

        JudulTA::create([
            'user_id' => Auth::id(),
            'judul1' => $request->judul1,
            'judul2' => $request->judul2,
            'judul3' => $request->judul3,
            'status' => 'submitted',
        ]);

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
