<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\SuratTA;

class SuratController extends Controller
{
    public function show($id)
    {
        $pengajuan = JudulTA::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $surat = SuratTA::where('judul_ta_id', $id)->first();

        if (!$surat) {
            return redirect()->route('mahasiswa.judul-ta.show', $id)
                ->with('error', 'Surat tugas akhir belum tersedia');
        }

        return view('mahasiswa.surat.show', compact('pengajuan', 'surat'));
    }
}
