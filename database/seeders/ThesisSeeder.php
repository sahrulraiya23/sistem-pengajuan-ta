<?php

namespace Database\Seeders;

use App\Models\Thesis;
use Illuminate\Database\Seeder;

class ThesisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $theses = [
            [
                'title' => 'Rancang Bangun Sistem Informasi Akademik Berbasis Web pada SMA Negeri 1 Kendari',
                'abstract' => 'Penelitian ini bertujuan untuk membangun sistem informasi akademik yang dapat memudahkan pengelolaan data siswa, guru, dan nilai secara efisien berbasis web menggunakan framework Laravel.',
                'type' => 'skripsi',
                'author' => 'Budi Santoso',
                'program_study' => 'Teknik Informatika',
                'year' => 2023,
            ],
            [
                'title' => 'Implementasi Algoritma C4.5 untuk Klasifikasi Penerima Beasiswa Bidikmisi',
                'abstract' => 'Studi ini menerapkan algoritma Data Mining C4.5 untuk membantu pihak kemahasiswaan dalam menentukan kelayakan calon penerima beasiswa berdasarkan kriteria ekonomi dan akademik.',
                'type' => 'skripsi',
                'author' => 'Siti Aminah',
                'program_study' => 'Teknik Informatika',
                'year' => 2024,
            ],
            [
                'title' => 'Sistem Pendukung Keputusan Pemilihan Dosen Terbaik Menggunakan Metode SAW (Simple Additive Weighting)',
                'abstract' => 'Sistem ini dirancang untuk memberikan rekomendasi dosen terbaik berdasarkan penilaian kinerja, kedisiplinan, dan umpan balik mahasiswa menggunakan metode pembobotan SAW.',
                'type' => 'skripsi',
                'author' => 'Rahmat Hidayat',
                'program_study' => 'Teknik Informatika',
                'year' => 2023,
            ],
            [
                'title' => 'Pengembangan Aplikasi E-Commerce Penjualan Kain Tenun Khas Sulawesi Tenggara Berbasis Android',
                'abstract' => 'Membangun aplikasi mobile berbasis Android untuk memperluas jangkauan pasar pengrajin kain tenun lokal dengan fitur pembayaran gateway dan pelacakan pengiriman.',
                'type' => 'skripsi',
                'author' => 'Dewi Lestari',
                'program_study' => 'Teknik Informatika',
                'year' => 2024,
            ],
            [
                'title' => 'Analisis Sentimen Pengguna Twitter Terhadap Kebijakan Kampus Merdeka Menggunakan Naive Bayes Classifier',
                'abstract' => 'Penelitian ini menganalisis opini publik di media sosial Twitter mengenai program Kampus Merdeka untuk mengetahui sentimen positif, negatif, dan netral menggunakan metode Naive Bayes.',
                'type' => 'skripsi',
                'author' => 'Fajar Nugraha',
                'program_study' => 'Teknik Informatika',
                'year' => 2023,
            ],
            [
                'title' => 'Rancang Bangun Keamanan Jaringan Menggunakan Metode Port Knocking pada Router Mikrotik',
                'abstract' => 'Implementasi keamanan jaringan komputer untuk mencegah akses ilegal ke router dengan menerapkan metode Port Knocking pada firewall Mikrotik di Laboratorium Komputer.',
                'type' => 'skripsi',
                'author' => 'Agus Setiawan',
                'program_study' => 'Teknik Informatika',
                'year' => 2022,
            ],
            [
                'title' => 'Sistem Pakar Diagnosa Penyakit Tanaman Cengkeh Menggunakan Metode Certainty Factor',
                'abstract' => 'Aplikasi sistem pakar berbasis web yang membantu petani cengkeh mendiagnosa penyakit tanaman berdasarkan gejala-gejala visual yang diinputkan, menggunakan perhitungan Certainty Factor.',
                'type' => 'skripsi',
                'author' => 'Rina Marlina',
                'program_study' => 'Teknik Informatika',
                'year' => 2024,
            ],
            [
                'title' => 'Implementasi Augmented Reality Sebagai Media Pembelajaran Pengenalan Perangkat Keras Komputer',
                'abstract' => 'Pembuatan aplikasi media pembelajaran interaktif berbasis Android menggunakan teknologi Augmented Reality untuk memvisualisasikan komponen hardware komputer secara 3D.',
                'type' => 'skripsi',
                'author' => 'Dimas Anggara',
                'program_study' => 'Teknik Informatika',
                'year' => 2023,
            ],
            [
                'title' => 'Penerapan Metode K-Means Clustering untuk Pengelompokan Data Penjualan Produk UMKM',
                'abstract' => 'Penelitian ini bertujuan mengelompokkan data transaksi penjualan untuk menemukan pola pembelian pelanggan, sehingga strategi pemasaran UMKM dapat lebih terarah.',
                'type' => 'skripsi',
                'author' => 'Nia Ramadhani',
                'program_study' => 'Teknik Informatika',
                'year' => 2024,
            ],
            [
                'title' => 'Sistem Monitoring Kualitas Air Kolam Ikan Berbasis Internet of Things (IoT) Menggunakan NodeMCU',
                'abstract' => 'Rancang bangun alat monitoring suhu dan pH air kolam secara real-time yang terintegrasi dengan aplikasi mobile melalui koneksi internet menggunakan mikrokontroler NodeMCU ESP8266.',
                'type' => 'skripsi',
                'author' => 'Bayu Pratama',
                'program_study' => 'Teknik Informatika',
                'year' => 2023,
            ],
        ];

        foreach ($theses as $thesis) {
            Thesis::create($thesis);
        }
    }
}