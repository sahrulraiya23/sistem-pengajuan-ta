<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use Illuminate\Http\Request;

class ThesisArchiveController extends Controller
{
    public function index(Request $request)
    {
        $query = Thesis::query();

        // 1. Filter Pencarian (Judul/Penulis)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // 2. Filter Jenis (Skripsi/Tesis/Disertasi)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 3. Filter Tahun
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Ambil data tesis dengan pagination
        $theses = $query->latest()->paginate(9); // 9 item per halaman agar rapi (3x3 grid)

        // Data pendukung untuk Dropdown Filter
        $years = Thesis::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $types = [
            'skripsi' => 'Skripsi',
            'tesis' => 'Tesis',
            'disertasi' => 'Disertasi'
        ];

        return view('mahasiswa.thesis.index', compact('theses', 'years', 'types'));
    }

    public function show($id)
    {
        $thesis = Thesis::findOrFail($id);
        return view('mahasiswa.thesis.show', compact('thesis'));
    }
    public function plagiarismCheck()
    {
        return view('mahasiswa.thesis.plagiarism');
    }

    /**
     * Memproses pengecekan judul
     */
    public function checkPlagiarism(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:500' // Max diperpanjang agar leluasa
        ]);

        $inputTitle = strtolower(trim($request->title));
        
        // Ambil hanya kolom id, title, author, year, type untuk efisiensi
        $allTheses = Thesis::select('id', 'title', 'author', 'year', 'type', 'program_study')->get();

        $similarities = [];

        foreach ($allTheses as $thesis) {
            $existingTitle = strtolower(trim($thesis->title));
            
            // Hitung kemiripan (0-100%)
            $percent = 0;
            similar_text($inputTitle, $existingTitle, $percent);

            // Simpan jika kemiripan di atas 15% (agar hasil tidak terlalu penuh sampah)
            if ($percent > 15) {
                $similarities[] = [
                    'thesis' => $thesis,
                    'percentage' => round($percent, 2)
                ];
            }
        }

        // Urutkan dari persentase tertinggi ke terendah
        usort($similarities, function ($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });

        // Ambil nilai tertinggi
        $maxSimilarity = !empty($similarities) ? $similarities[0]['percentage'] : 0;

        return view('mahasiswa.thesis.plagiarism', [
            'inputTitle' => $request->title,
            'similarities' => $similarities,
            'maxSimilarity' => $maxSimilarity
        ]);
    }

}