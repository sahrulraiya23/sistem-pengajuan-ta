<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <--- WAJIB DITAMBAHKAN UNTUK AKSES TABEL PIVOT
use App\Models\JudulTA; 
use App\Models\Revisi;
use App\Models\DosenPembimbing;
use App\Models\SuratTA;
use App\Models\User; 

use App\Notifications\PengajuanJudulNotification; 
use App\Notifications\ReSubmissionToDosenNotification; // Pastikan notifikasi ini ada atau buat dulu
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class JudulTAController extends Controller
{
    public function index()
    {
        $pengajuan = JudulTA::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('mahasiswa.index', compact('pengajuan'));
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
            'file_pendukung1' => 'nullable|file|mimes:pdf,doc,docx|max:2048', 
            'file_pendukung2' => 'nullable|file|mimes:pdf,doc,docx|max:2048', 
        ]);

        $filePendukung1Path = null;
        if ($request->hasFile('file_pendukung1')) {
            $filePendukung1Path = $request->file('file_pendukung1')->store('files_pendukung', 'public');
        }

        $filePendukung2Path = null;
        if ($request->hasFile('file_pendukung2')) {
            $filePendukung2Path = $request->file('file_pendukung2')->store('files_pendukung', 'public');
        }

        $judulTA = JudulTA::create([
            'user_id' => Auth::id(),
            'judul1' => $request->judul1,
            'judul2' => $request->judul2,
            'judul3' => $request->judul3,
            'file_pendukung1' => $filePendukung1Path, 
            'file_pendukung2' => $filePendukung2Path, 
            'status' => JudulTA::STATUS_SUBMITTED,
        ]);

        $kajurUsers = User::where('role', 'kajur')->get();
        if ($kajurUsers->isNotEmpty()) {
            Notification::send($kajurUsers, new PengajuanJudulNotification($judulTA));
        }

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

    /**
     * Mahasiswa mengajukan kembali judul setelah konsultasi/revisi dengan dosen saran.
     */
    public function reSubmitAfterConsultation(Request $request, $id)
    {
        // 1. Validasi input judul_revisi
        $request->validate([
            'judul_revisi' => 'required|string|max:255', 
        ]);

        $pengajuan = JudulTA::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Pastikan status saat ini adalah 'revisi' sebelum bisa diajukan kembali
        if ($pengajuan->status !== JudulTA::STATUS_REVISED) {
            return redirect()->back()->with('error', 'Pengajuan hanya bisa diajukan kembali setelah menerima revisi.');
        }

        // 2. Perbarui status utama dan simpan judul_revisi
        $pengajuan->update([
            'status' => JudulTA::STATUS_SUBMIT_REVISED, 
            'judul_revisi' => $request->judul_revisi,   
        ]);

        // -----------------------------------------------------------
        // 3. RESET STATUS DOSEN DI TABEL PIVOT (BAGIAN TERPENTING)
        // -----------------------------------------------------------
        // Kita ubah status_persetujuan semua dosen terkait menjadi 'pending'
        // agar mereka harus melakukan approval ulang terhadap judul revisi ini.
        DB::table('judul_ta_dosen_sarans')
            ->where('judul_ta_id', $pengajuan->id)
            ->update([
                'status_persetujuan' => 'pending', 
                'updated_at' => now(),
            ]);

        // 4. Kirim notifikasi ke SEMUA DOSEN SARAN (Update untuk Many-to-Many)
        // Karena sekarang relasinya banyak ke banyak, kita loop.
        if(method_exists($pengajuan, 'dosenSarans')) {
            foreach($pengajuan->dosenSarans as $dosen) {
                // Pastikan Anda sudah membuat class ReSubmissionToDosenNotification
                // Notification::send($dosen, new ReSubmissionToDosenNotification($pengajuan));
            }
        }

        return redirect()->route('mahasiswa.judul-ta.index')
            ->with('success', 'Pengajuan judul berhasil diajukan kembali. Menunggu persetujuan ulang dari kedua dosen.');
    }
}