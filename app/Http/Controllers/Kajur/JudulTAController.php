<?php

namespace App\Http\Controllers\Kajur;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\User;
use App\Models\DosenPembimbing;

class JudulTAController extends Controller
{
    public function index()
    {
        $pengajuan = JudulTA::with('mahasiswa')
            ->latest()
            ->get();

        return view('kajur.judul-ta.index', compact('pengajuan'));
    }

    public function show($id)
    {
        $pengajuan = JudulTA::with('mahasiswa')->findOrFail($id);

        $dosen = User::where('role', 'dosen')->get();

        return view('kajur.judul-ta.show', compact('pengajuan', 'dosen'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'judul_approved' => 'required|string|max:255',
            'dosen_id' => 'required|exists:users,id',
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

        return redirect()->route('kajur.judul-ta.index')
            ->with('success', 'Pengajuan judul berhasil disetujui dan pembimbing telah ditentukan');
    }

    public function reject(Request $request, $id)
    {
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
}
