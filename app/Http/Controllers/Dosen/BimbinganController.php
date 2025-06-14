<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\DosenPembimbing;
use App\Models\Revisi;
use App\Models\SuratTA;
use Illuminate\Support\Facades\Auth; // Pastikan untuk mengimpor Auth

class BimbinganController extends Controller
{
    public function index()
    {
        // Menggunakan Auth::user()->id untuk mendapatkan ID dosen
        $bimbingan = DosenPembimbing::where('dosen_id', Auth::user()->id)
            ->with('judulTA', 'judulTA.mahasiswa')
            ->latest()
            ->get();


        return view('dosen.bimbingan.index', compact('bimbingan'));
    }
    public function create()
    {
        // Tampilan untuk membuat pengajuan bimbingan
        return view('dosen.bimbingan.create');
    }

    public function show($id)
    {
        // Mengecek apakah dosen yang sedang login memiliki judul tugas akhir tersebut
        $bimbingan = DosenPembimbing::where('judul_ta_id', $id)
            ->where('dosen_id', Auth::user()->id)
            ->firstOrFail();

        // Mengambil data judul tugas akhir dan mahasiswa terkait
        $pengajuan = JudulTA::with('mahasiswa')->findOrFail($id);

        // Mengambil data revisi untuk judul tugas akhir tersebut
        $revisi = Revisi::where('judul_ta_id', $id)
            ->with('user')
            ->latest()
            ->get();

        return view('dosen.bimbingan.show', compact('pengajuan', 'revisi'));
    }
    public function finalize(Request $request, $id)
    {
        return redirect()->back()->with('info', 'Fungsi ini tidak lagi digunakan.');
    }
}
