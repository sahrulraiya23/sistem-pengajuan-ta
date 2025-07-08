<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\SuratTA;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratController extends Controller
{
    public function show($id)
    {
        // Debug 1: Periksa apakah JudulTA ditemukan
        $pengajuan = JudulTA::with(['mahasiswa', 'pembimbing.dosen'])->find($id); // Gunakan find() daripada findOrFail() sementara untuk debugging
        if (!$pengajuan) {
            dd("Error: Judul TA dengan ID {$id} tidak ditemukan.");
        }
        dd('Judul TA ditemukan:', $pengajuan); // Periksa isi $pengajuan

        // Debug 2: Periksa apakah SuratTA ditemukan
        $surat = SuratTA::where('judul_ta_id', $id)->first();
        if (!$surat) {
            dd("Error: Surat TA untuk judul TA ID {$id} tidak ditemukan."); // Jika ini yang muncul, berarti suratnya belum ada di tabel surat_ta
        }
        dd('Surat TA ditemukan:', $surat); // Periksa isi $surat

        // Jika sampai sini, artinya data sudah lengkap dan siap di-render view.
        // Jika masih error "not found", masalahnya di file view mahasiswa.surat.show.blade.php
        return view('mahasiswa.surat.show', compact('pengajuan', 'surat'));
    }

    // public function downloadPDF($id)
    // {
    //     // 1. Ambil data yang sama dengan fungsi show()
    //     $pengajuan = JudulTA::with(['mahasiswa', 'pembimbing.dosen'])->findOrFail($id);
    //     $surat = SuratTA::where('judul_ta_id', $id)->first();

    //     if (!$surat) {
    //         return redirect()->route('mahasiswa.judul-ta.show', $id)
    //             ->with('error', 'Surat tugas akhir belum tersedia untuk diunduh.');
    //     }

    //     $pdf = PDF::loadView('mahasiswa.surat.pdf_view', compact('pengajuan', 'surat'));
    //     $pdf->setPaper('a4', 'portrait');

    //     // 4. Buat nama file dan mulai download
    //     $fileName = 'Pengajuan_Judul_TA' . ($pengajuan->mahasiswa->nomor_induk ?? $pengajuan->mahasiswa->id) . '.pdf';
    //     return $pdf->stream($fileName);
    // }
}
