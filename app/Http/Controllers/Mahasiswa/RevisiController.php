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

        Revisi::create([
            'judul_ta_id' => $id,
            'user_id' => Auth::id(),
            'role_type' => 'mahasiswa',
            'catatan' => $request->catatan,
        ]);

        $pengajuan->update([
            'judul_revisi' => $request->judul_revisi,
        ]);

        return redirect()->route('mahasiswa.revisi.show', $id)
            ->with('success', 'Revisi berhasil dikirim');
    }
}
