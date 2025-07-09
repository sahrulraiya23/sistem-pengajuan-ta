<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\DosenPembimbing; // Ini dibutuhkan jika ada model DosenPembimbing
use App\Models\Revisi;
use App\Models\SuratTA; // Ini dibutuhkan jika ada model SuratTA
use App\Models\User; // Perlu di-import untuk mendapatkan data dosen dan mahasiswa

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Diperlukan untuk transaksi database
use Illuminate\Support\Facades\Notification; // Diperlukan untuk mengirim notifikasi ke banyak user (misal: Kajur)

// Import kelas notifikasi yang kamu miliki
use App\Notifications\RevisiBaruNotification;
use App\Notifications\DosenSaranDitunjukNotification; // Mungkin tidak digunakan di controller ini, tapi tetap di-import jika ada di file
use App\Notifications\PengajuanJudulNotification; // Notifikasi ke Kajur
use App\Notifications\JudulDitolakNotification; // Notifikasi ke mahasiswa jika dosen tolak
use App\Notifications\ReSubmissionToDosenNotification; // Mungkin tidak digunakan di controller ini, tapi tetap di-import jika ada di file


class BimbinganController extends Controller
{
    /**
     * Menampilkan daftar pengajuan judul TA yang relevan untuk dosen yang login.
     * Dosen bisa menjadi dosen saran atau dosen pembimbing.
     */
    public function index()
    {
        // Tandai notifikasi dosen saran yang ditunjuk sebagai telah dibaca (jika ada)
        // Notifikasi ini datang dari Kajur ke Dosen, jadi relevan di sini.
        if (Auth::user()) { // Selalu cek Auth::user() sebelum menggunakannya
            Auth::user()->unreadNotifications
                ->where('type', 'App\Notifications\DosenSaranDitunjukNotification')
                ->markAsRead();
            // Jika ada notifikasi lain untuk dosen di halaman index, bisa ditambahkan di sini
        }

        $userDosen = Auth::user(); // Dapatkan user (dosen) yang sedang login

        // Mengambil pengajuan di mana dosen yang login adalah salah satu dosen saran
        $pengajuanSebagaiDosenSaran = JudulTA::whereHas('dosenSarans', function ($query) use ($userDosen) {
            $query->where('user_id', $userDosen->id); // Sesuaikan dengan foreign key di tabel pivot
        })
            ->with('mahasiswa') // Eager load relasi mahasiswa untuk performa
            ->whereIn('status', [
                JudulTA::STATUS_APPROVED_FOR_CONSULTATION, // Menunggu dosen saran memberikan feedback awal
                JudulTA::STATUS_REVISED,                    // Setelah dosen saran memberikan feedback, mahasiswa sedang merevisi
                JudulTA::STATUS_SUBMIT_REVISED              // Mahasiswa mengajukan kembali ke dosen saran
            ])
            ->latest()
            ->get();

        // Mengambil pengajuan di mana dosen yang login adalah dosen pembimbing utama
        $pengajuanSebagaiDosenPembimbing = JudulTA::whereHas('pembimbing', function ($query) use ($userDosen) {
            $query->where('dosen_id', $userDosen->id); // Sesuaikan dengan foreign key di tabel dosen_pembimbing
        })
            ->with('mahasiswa') // Eager load relasi mahasiswa untuk performa
            ->where('status', JudulTA::STATUS_FINALIZED) // Hanya judul yang sudah difinalisasi akan memiliki pembimbing
            ->latest()
            ->get();

        // Gabungkan kedua koleksi (jika ada overlap, Eloquent akan menanganinya)
        $bimbingan = $pengajuanSebagaiDosenSaran->merge($pengajuanSebagaiDosenPembimbing)->unique('id')->sortByDesc('created_at');


        return view('dosen.bimbingan.index', ['pengajuan' => $bimbingan]);
    }

    /**
     * Metode ini mungkin tidak relevan untuk dosen.
     * Jika dosen tidak membuat pengajuan bimbingan baru, metode ini bisa dihapus.
     */
    public function create()
    {
        // Tampilan untuk membuat pengajuan bimbingan (biasanya mahasiswa yang membuat)
        // Jika dosen tidak membuat, metode ini bisa dihapus atau dialihkan
        return redirect()->route('dosen.bimbingan.index')->with('info', 'Anda tidak dapat membuat pengajuan bimbingan baru dari sini.');
        // return view('dosen.bimbingan.create');
    }

    /**
     * Menampilkan detail satu pengajuan judul untuk dosen.
     */
    public function show($id)
    {
        $userDosen = Auth::user();

        // Muat pengajuan beserta relasi yang dibutuhkan untuk tampilan detail
        $pengajuan = JudulTA::with(['mahasiswa', 'dosenSarans', 'pembimbing.dosen'])->findOrFail($id);

        // Cek apakah dosen yang sedang login adalah salah satu dosen saran yang ditunjuk
        $isDosenSaran = $pengajuan->dosenSarans->contains('id', $userDosen->id);

        // Cek apakah dosen yang sedang login adalah pembimbing utama dari pengajuan ini
        $isPembimbingUtama = ($pengajuan->pembimbing && $pengajuan->pembimbing->dosen_id === $userDosen->id);

        // Jika bukan dosen saran DAN bukan pembimbing utama, maka abort (akses ditolak)
        if (!$isDosenSaran && !$isPembimbingUtama) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        }

        // Ambil semua revisi terkait judul TA ini
        $revisi = Revisi::where('judul_ta_id', $id)
            ->with('user') // Eager load user (mahasiswa/dosen) yang membuat revisi
            ->latest()
            ->get();

        return view('dosen.bimbingan.show', compact('pengajuan', 'revisi'));
    }

    /**
     * Memproses review awal dari dosen saran terhadap pengajuan judul mahasiswa.
     * Ini terjadi setelah Kajur menunjuk dosen saran.
     */
    public function processInitialLecturerReview(Request $request, $id)
    {
        $request->validate([
            'judul_pilihan_dosen_saran' => 'required|string|max:255', // Judul yang dipilih oleh dosen saran
            'catatan_initial_revisi' => 'required|string|max:1000',   // Revisi awal yang diberikan dosen
        ]);

        $userDosen = Auth::user();

        // Pastikan pengajuan ini ada dan dosen yang login adalah salah satu dosen saran yang ditunjuk
        $pengajuan = JudulTA::whereHas('dosenSarans', function ($query) use ($userDosen) {
            $query->where('user_id', $userDosen->id);
        })
            ->where('id', $id)
            ->firstOrFail();

        // Hanya proses jika statusnya APPROVED_FOR_CONSULTATION
        if ($pengajuan->status !== JudulTA::STATUS_APPROVED_FOR_CONSULTATION) {
            return redirect()->back()->with('error', 'Pengajuan ini tidak dalam status untuk persetujuan awal dosen saran. Status saat ini: ' . $pengajuan->status);
        }

        // Gunakan transaksi database untuk memastikan semua operasi berhasil atau di-rollback
        DB::transaction(function () use ($request, $pengajuan) {
            // 1. Setel judul yang disetujui oleh dosen saran dan perbarui status
            $pengajuan->update([
                'judul_approved' => $request->judul_pilihan_dosen_saran,
                'status' => JudulTA::STATUS_REVISED, // Langsung ke status revisi setelah disetujui dan diberi catatan
                'catatan_dosen_saran' => $request->catatan_initial_revisi, // Catatan ini bisa disimpan di sini
            ]);

            // 2. Buat entri revisi pertama di tabel revisi
            $revisi = new Revisi();
            $revisi->judul_ta_id = $pengajuan->id;
            $revisi->user_id = Auth::id(); // ID dosen yang memberikan revisi
            $revisi->dosen_id = Auth::id(); // Kolom dosen_id di tabel revisi
            $revisi->role_type = 'dosen';
            $revisi->catatan = $request->catatan_initial_revisi;
            $revisi->save();

            // 3. Kirim notifikasi ke mahasiswa
            if ($pengajuan->mahasiswa) {
                $pengajuan->mahasiswa->notify(new RevisiBaruNotification($revisi));
            }
        });

        return redirect()->route('dosen.bimbingan.index')
            ->with('success', 'Judul berhasil disetujui dan revisi awal telah dikirim ke mahasiswa.');
    }

    /**
     * Memproses pengajuan judul yang diajukan kembali oleh mahasiswa setelah revisi.
     */
    public function processReSubmittedTitle(Request $request, $id)
    {
        $request->validate([
            'tindakan' => 'required|in:approve_resubmission,reject_resubmission',
            'catatan' => 'nullable|string|max:1000',
            // Judul disetujui oleh dosen hanya diperlukan jika tindakan adalah 'approve_resubmission'
            'judul_approved_by_dosen' => 'required_if:tindakan,approve_resubmission|string|max:255',
        ]);

        $userDosen = Auth::user();

        // Pastikan pengajuan ini ada dan dosen yang login adalah salah satu dosen saran yang ditunjuk
        $pengajuan = JudulTA::whereHas('dosenSarans', function ($query) use ($userDosen) {
            $query->where('user_id', $userDosen->id);
        })
            ->where('id', $id)
            ->firstOrFail();

        // Pastikan statusnya memang 'submit_revisi'
        if ($pengajuan->status !== JudulTA::STATUS_SUBMIT_REVISED) {
            return redirect()->back()->with('error', 'Tindakan tidak dapat dilakukan karena status pengajuan tidak sesuai. Status saat ini: ' . $pengajuan->status);
        }

        return DB::transaction(function () use ($request, $pengajuan) {
            if ($request->tindakan == 'approve_resubmission') {
                $pengajuan->update([
                    'status' => JudulTA::STATUS_APPROVED, // Status berubah menjadi 'approved' (siap finalisasi oleh Kajur)
                    'judul_approved' => $request->judul_approved_by_dosen, // Simpan judul yang disetujui dosen
                    'catatan_dosen_saran' => $request->catatan, // Simpan catatan dosen saran
                ]);

                // Kirim notifikasi ke Kajur bahwa judul sudah disetujui oleh dosen saran dan siap untuk finalisasi
                $kajurUsers = User::where('role', 'kajur')->get();
                if ($kajurUsers->isNotEmpty()) {
                    Notification::send($kajurUsers, new PengajuanJudulNotification($pengajuan));
                }

                return redirect()->route('dosen.bimbingan.index')
                    ->with('success', 'Pengajuan judul berhasil disetujui oleh dosen saran dan diajukan ke Ketua Jurusan.');
            } elseif ($request->tindakan == 'reject_resubmission') {
                $pengajuan->update([
                    'status' => JudulTA::STATUS_REVISED, // Kembali ke status revisi
                    'alasan_penolakan' => $request->catatan, // Gunakan kolom alasan_penolakan
                    'catatan_dosen_saran' => $request->catatan, // Simpan catatan dosen saran
                ]);

                // Kirim notifikasi ke mahasiswa bahwa pengajuan kembali ditolak
                if ($pengajuan->mahasiswa) {
                    $pengajuan->mahasiswa->notify(new JudulDitolakNotification($pengajuan));
                }

                return redirect()->route('dosen.bimbingan.index')
                    ->with('success', 'Pengajuan judul berhasil ditolak dan status kembali menjadi "revisi".');
            }
        });

        return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }

    /**
     * Method ini tidak digunakan di alur Dosen Saran/Pembimbing.
     * Mungkin ini adalah method placeholder atau sisa dari logika lama.
     * Sebaiknya dihapus atau dialihkan jika tidak ada rute yang menunjuk ke sini.
     */
    public function finalize(Request $request, $id)
    {
        return redirect()->back()->with('info', 'Fungsi ini tidak lagi digunakan oleh Dosen. Finalisasi dilakukan oleh Ketua Jurusan.');
    }
}
