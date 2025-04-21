<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\Revisi;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import

class RevisiController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'isi_revisi' => 'required|string',
            'tanggal_revisi' => 'required|date',
            'status' => 'required|string|in:pending,completed',
        ]);

        $judulTA = JudulTA::findOrFail($id);

        $revisi = new Revisi();
        $revisi->judul_ta_id = $id;
        $revisi->dosen_id = Auth::user()->id; // Gantilah dengan Auth::user()->id
        $revisi->isi_revisi = $request->isi_revisi;
        $revisi->tanggal_revisi = $request->tanggal_revisi;
        $revisi->status = $request->status;
        $revisi->save();

        return redirect()->back()->with('success', 'Revisi berhasil ditambahkan');
    }
}
