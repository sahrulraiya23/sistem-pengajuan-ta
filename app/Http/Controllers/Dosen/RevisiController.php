<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\Revisi;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import

class RevisiController extends Controller
{
    // File: app/Http/Controllers/Dosen/RevisiController.php

    public function store(Request $request, $id)
    {
        // 1. Sesuaikan validasi dengan input form yang benar
        // Dari screenshot form Anda, inputnya bernama 'isi_revisi', jadi kita tetap pakai itu.
        $request->validate([
            'isi_revisi' => 'required|string',
        ]);

        // 2. Pastikan judul TA ada
        $judulTA = JudulTA::findOrFail($id);
        $user = Auth::user();

        // 3. Buat entri revisi baru menggunakan kolom yang benar
        $revisi = new Revisi();
        $revisi->judul_ta_id = $id;
        $revisi->user_id = $user->id; // Menyimpan ID user yang memberikan revisi
        $revisi->dosen_id = $user->id; // Asumsi dosen yang login yang memberikan revisi
        $revisi->role_type = 'dosen'; // Menandakan revisi ini dari dosen
        $revisi->catatan = $request->isi_revisi; // Simpan ke kolom 'catatan'
        $revisi->save();

        return redirect()->back()->with('success', 'Revisi berhasil ditambahkan');
    }
}
