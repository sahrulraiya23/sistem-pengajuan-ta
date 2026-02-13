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
    public function index(Request $request)
    {
        // 1. Logika Notifikasi (dipertahankan dari kode Anda)
        if (Auth::user()) {
            Auth::user()->unreadNotifications
                ->where('type', 'App\Notifications\DosenSaranDitunjukNotification')
                ->markAsRead();
        }

        $dosenId = Auth::id();

        // 2. Membangun SATU QUERY Tunggal yang Efisien
        //    Asumsi: relasi pada model JudulTA Anda bernama 'pembimbings' (plural)
        $query = JudulTA::with(['mahasiswa', 'dosenSarans', 'pembimbings'])
            ->where(function ($q) use ($dosenId) {
                // Kondisi A: Dosen ini adalah salah satu Dosen Saran
                $q->whereHas('dosenSarans', function ($subq) use ($dosenId) {
                    $subq->where('user_id', $dosenId); // Sesuaikan foreign key jika perlu
                })
                    // ATAU Kondisi B: Dosen ini adalah salah satu Dosen Pembimbing
                    ->orWhereHas('pembimbings', function ($subq) use ($dosenId) {
                        $subq->where('dosen_id', $dosenId); // Sesuaikan foreign key jika perlu
                    });
            })
            ->latest();

        // 3. Menerapkan FILTER dari Request (INI BAGIAN KUNCI YANG DIPERBAIKI)
        // Filter berdasarkan pencarian nama mahasiswa
        if ($request->filled('search')) {
            $query->whereHas('mahasiswa', function ($q_mhs) use ($request) {
                $q_mhs->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            // Jika dosen memilih status dari dropdown, filter akan dijalankan pada query
            $query->where('status', $request->status);
        } else {
            // Jika tidak ada filter, tampilkan status default yang relevan untuk kedua peran
            $query->whereIn('status', [
                JudulTA::STATUS_APPROVED_FOR_CONSULTATION,
                JudulTA::STATUS_REVISED,
                JudulTA::STATUS_SUBMIT_REVISED,
                JudulTA::STATUS_FINALIZED,
            ]);
        }

        // 4. Eksekusi Query dan Kirim ke View
        $pengajuan = $query->get();

        return view('dosen.bimbingan.index', compact('pengajuan'));
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
        $pengajuan = JudulTA::with(['mahasiswa', 'dosenSarans', 'pembimbings.dosen'])->findOrFail($id);

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
    // File: app/Http/Controllers/Dosen/BimbinganController.php

    public function processInitialLecturerReview(Request $request, $id)
    {
        $request->validate([
            'judul_pilihan_dosen_saran' => 'required|string|max:255',
            'catatan_initial_revisi' => 'required|string|max:1000',
        ]);

        $userDosen = Auth::user();

        // Pastikan pengajuan ada
        $pengajuan = JudulTA::findOrFail($id);

        // Cek apakah dosen ini benar-benar dosen saran untuk judul ini
        $isDosenSaran = DB::table('judul_ta_dosen_sarans')
            ->where('judul_ta_id', $pengajuan->id)
            ->where('user_id', $userDosen->id)
            ->exists();

        if (!$isDosenSaran) {
            abort(403, 'Anda bukan dosen saran untuk judul ini.');
        }

        // Validasi Status Pengajuan
        if ($pengajuan->status !== JudulTA::STATUS_APPROVED_FOR_CONSULTATION) {
            return redirect()->back()->with('error', 'Status pengajuan tidak valid untuk direview.');
        }

        DB::transaction(function () use ($request, $pengajuan, $userDosen) {
            // 1. UPDATE DATA PIVOT (Hanya untuk dosen yang login)
            // Update dulu agar perhitungan di bawah sudah menyertakan dosen ini
            DB::table('judul_ta_dosen_sarans')
                ->where('judul_ta_id', $pengajuan->id)
                ->where('user_id', $userDosen->id)
                ->update([
                    'judul_pilihan' => $request->judul_pilihan_dosen_saran,
                    'catatan' => $request->catatan_initial_revisi,
                    'status_persetujuan' => 'approved',
                    'updated_at' => now(),
                ]);

            // 2. HITUNG JUMLAH SECARA MANUAL & AKURAT DARI DATABASE
            $totalDosenSaran = DB::table('judul_ta_dosen_sarans')
                ->where('judul_ta_id', $pengajuan->id)
                ->count();

            $jumlahDosenSetuju = DB::table('judul_ta_dosen_sarans')
                ->where('judul_ta_id', $pengajuan->id)
                ->where('status_persetujuan', 'approved')
                ->count();

            // 3. LOGIKA PENENTUAN STATUS UTAMA
            // Syarat Mutlak:
            // A. Harus ada minimal 2 dosen saran (Mencegah update jika data dosen cuma 1)
            // B. Jumlah yang setuju harus SAMA DENGAN total dosen

            $syaratLengkap = ($totalDosenSaran >= 2) && ($jumlahDosenSetuju == $totalDosenSaran);

            if ($syaratLengkap) {

                // Ambil semua catatan dosen untuk digabung
                $allReviews = DB::table('judul_ta_dosen_sarans')
                    ->join('users', 'judul_ta_dosen_sarans.user_id', '=', 'users.id')
                    ->where('judul_ta_id', $pengajuan->id)
                    ->select('users.name', 'judul_ta_dosen_sarans.catatan', 'judul_ta_dosen_sarans.judul_pilihan')
                    ->get();

                $catatanGabungan = "";
                // Kita ambil judul pilihan dari input dosen terakhir sebagai judul sementara
                $judulFinal = $request->judul_pilihan_dosen_saran;

                foreach ($allReviews as $review) {
                    $catatanGabungan .= "Review Dosen " . $review->name . ": " . $review->catatan . "\n\n";
                }

                // Update Tabel Utama JudulTA ke Status REVISI
                $pengajuan->update([
                    'judul_approved' => $judulFinal,
                    'status' => JudulTA::STATUS_REVISED,
                    'catatan_dosen_saran' => $catatanGabungan,
                ]);

                // Buat Entri History Revisi
                $revisiBaru = Revisi::create([
                    'judul_ta_id' => $pengajuan->id,
                    'user_id' => $userDosen->id,
                    'dosen_id' => $userDosen->id,
                    'role_type' => 'dosen',
                    'catatan' => "Konsultasi Awal Selesai. " . $catatanGabungan,
                ]);

                // Notifikasi ke Mahasiswa
                if ($pengajuan->mahasiswa) {
                    // Gunakan variabel $revisiBaru agar lebih aman
                    $pengajuan->mahasiswa->notify(new RevisiBaruNotification($revisiBaru));
                }
            }
            // ELSE: Jika belum lengkap, status utama TIDAK BERUBAH.
        });

        // Feedback ke Dosen
        // Hitung ulang sisa dosen untuk pesan flash message
        $sisa = DB::table('judul_ta_dosen_sarans')
            ->where('judul_ta_id', $pengajuan->id)
            ->where('status_persetujuan', '!=', 'approved')
            ->count();

        if ($sisa > 0) {
            return redirect()->route('dosen.bimbingan.index')
                ->with('success', 'Review Anda berhasil disimpan. Menunggu ' . $sisa . ' dosen saran lagi.');
        } else {
            return redirect()->route('dosen.bimbingan.index')
                ->with('success', 'Semua dosen telah setuju. Status pengajuan diperbarui menjadi Revisi.');
        }
    }
    /**
     * Memproses pengajuan judul yang diajukan kembali oleh mahasiswa setelah revisi.
     */
    public function processReSubmittedTitle(Request $request, $id)
    {
        $request->validate([
            'tindakan' => 'required|in:approve_resubmission,reject_resubmission',
            'judul_approved_by_dosen' => 'nullable|required_if:tindakan,approve_resubmission|string|max:255',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $userDosen = Auth::user();
        $pengajuan = JudulTA::findOrFail($id);

        // Pastikan statusnya benar (Mahasiswa sudah mengajukan kembali)
        if ($pengajuan->status !== JudulTA::STATUS_SUBMIT_REVISED) {
            return redirect()->back()->with('error', 'Pengajuan belum diajukan kembali oleh mahasiswa.');
        }

        DB::transaction(function () use ($request, $pengajuan, $userDosen) {
            // 1. TENTUKAN STATUS BARU UNTUK DOSEN INI (DI PIVOT)
            $statusPivot = ($request->tindakan == 'approve_resubmission') ? 'approved' : 'rejected';

            // Simpan keputusan dosen ini ke tabel pivot
            DB::table('judul_ta_dosen_sarans')
                ->where('judul_ta_id', $pengajuan->id)
                ->where('user_id', $userDosen->id)
                ->update([
                    'status_persetujuan' => $statusPivot,
                    'judul_pilihan' => $request->judul_approved_by_dosen, // Update judul final pilihan dosen
                    'catatan' => $request->catatan,
                    'updated_at' => now(),
                ]);

            // 2. LOGIKA PENENTUAN NASIB JUDUL (LANJUT ATAU TOLAK)

            // Skenario A: Jika Dosen Menolak
            if ($statusPivot == 'rejected') {
                // Jika SALAH SATU dosen menolak, status langsung dikembalikan ke REVISI
                // Mahasiswa harus perbaiki lagi.
                $pengajuan->update([
                    'status' => JudulTA::STATUS_REVISED,
                    'catatan_dosen_saran' => "Dosen " . $userDosen->name . " meminta perbaikan ulang: " . $request->catatan,
                ]);

                // Kirim notifikasi ke mahasiswa (bahwa ditolak/revisi lagi)
                // ...
            }

            // Skenario B: Jika Dosen Menyetujui
            else {
                // Cek apakah SEMUA dosen sudah setuju?
                $totalDosen = DB::table('judul_ta_dosen_sarans')->where('judul_ta_id', $pengajuan->id)->count();
                $jumlahSetuju = DB::table('judul_ta_dosen_sarans')
                    ->where('judul_ta_id', $pengajuan->id)
                    ->where('status_persetujuan', 'approved')
                    ->count();

                // Syarat Final: Minimal 2 dosen & Semua harus setuju
                if ($totalDosen >= 2 && $jumlahSetuju == $totalDosen) {

                    // FINALISASI: Ubah status utama jadi APPROVED (Siap untuk Kajur/Administrasi)
                    $pengajuan->update([
                        'status' => JudulTA::STATUS_APPROVED, // Atau STATUS_FINALIZED tergantung alur Anda
                        'judul_approved' => $request->judul_approved_by_dosen,
                        'catatan_dosen_saran' => "Kedua dosen telah menyetujui revisi.",
                    ]);

                    // Notifikasi Sukses ke Mahasiswa
                    // ...
                }
            }
        });

        // Feedback pesan ke layar
        if ($request->tindakan == 'reject_resubmission') {
            return redirect()->route('dosen.bimbingan.index')->with('warning', 'Anda meminta mahasiswa untuk merevisi kembali.');
        }

        // Cek sisa dosen jika di-approve
        $sisa = DB::table('judul_ta_dosen_sarans')
            ->where('judul_ta_id', $pengajuan->id)
            ->where('status_persetujuan', '!=', 'approved')
            ->count();

        if ($sisa > 0) {
            return redirect()->route('dosen.bimbingan.index')->with('success', 'Persetujuan disimpan. Menunggu ' . $sisa . ' dosen lagi.');
        } else {
            return redirect()->route('dosen.bimbingan.index')->with('success', 'Judul Telah Disetujui Sepenuhnya!');
        }
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
