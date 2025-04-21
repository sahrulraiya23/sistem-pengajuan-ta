<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\DosenPembimbing;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = [];
        
        switch ($user->role) {
            case 'mahasiswa':
                // Ambil data pengajuan judul TA
                $pengajuan = JudulTA::where('user_id', $user->id)
                    ->latest()
                    ->get();
                    
                $data['pengajuan'] = $pengajuan;
                break;
                
            case 'kajur':
                // Ambil data pengajuan judul TA yang belum diproses
                $pengajuan = JudulTA::where('status', 'submitted')
                    ->with('mahasiswa')
                    ->latest()
                    ->get();
                    
                $data['pengajuan'] = $pengajuan;
                break;
                
            case 'dosen':
                // Ambil data bimbingan
                $bimbingan = DosenPembimbing::where('dosen_id', $user->id)
                    ->with('judulTA', 'judulTA.mahasiswa')
                    ->latest()
                    ->get();
                    
                $data['bimbingan'] = $bimbingan;
                break;
        }
        
        return view('dashboard', compact('data'));
    }
}