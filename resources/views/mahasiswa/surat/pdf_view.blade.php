<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pengajuan Judul Tugas Akhir - {{ $pengajuan->mahasiswa->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: black;
            font-size: 12pt;
        }

        /* Tata Letak Utama */
        .paper {
            width: 100%;
        }

        /* Kop Surat - Menggunakan tabel untuk layout yang stabil di PDF */
        .kop-surat-table {
            width: 100%;
            border-bottom: 4px double black;
            margin-bottom: 2rem;
            border-spacing: 0;
        }

        .kop-surat-table .logo {
            width: 85px;
            height: 85px;
        }

        .kop-surat-table .kop-text {
            text-align: center;
        }

        .kop-surat-table h4,
        .kop-surat-table h5,
        .kop-surat-table p {
            margin: 0;
            line-height: 1.4;
        }

        .kop-surat-table h4 {
            font-size: 14pt;
            font-weight: bold;
        }

        .kop-surat-table h5 {
            font-size: 13pt;
            font-weight: bold;
        }

        .kop-surat-table p {
            font-size: 10pt;
        }

        /* Judul Surat */
        .judul-surat {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 14pt;
            margin-top: 3rem;
            margin-bottom: 2rem;
        }

        /* Tabel Konten */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 0.75rem;
            vertical-align: middle;
            font-size: 12pt;
        }

        .table thead th {
            text-align: center;
        }

        .table-info-mahasiswa th {
            width: 30%;
            text-align: left;
            padding: 0.35rem 0.75rem;
        }

        .table-info-mahasiswa td {
            padding: 0.35rem 0.75rem;
        }

        /* Tanda Tangan - Menggunakan tabel untuk layout */
        .tanda-tangan-table {
            width: 100%;
            margin-top: 4rem;
            border-collapse: collapse;
        }

        .tanda-tangan-table td {
            border: none;
            padding: 0;
            vertical-align: top;
            font-size: 12pt;
        }

        .signature-block {
            text-align: left;
        }

        .signature-block p {
            margin: 0;
            line-height: 1.5;
        }

        .nama-pejabat {
            margin-top: 60px;
            /* Memberi ruang untuk TTD */
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="paper">
        <table class="kop-surat-table">
            <tr>
                <td style="width:15%; text-align:center;">
                    {{-- PERBAIKAN: Menggunakan public_path() untuk logo --}}
                    <img src="{{ public_path('assets/img/logo-uho.png') }}" alt="Logo Universitas" class="logo">
                </td>
                <td class="kop-text">
                    <h4>KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN</h4>
                    <h5>UNIVERSITAS HALU OLEO</h5>
                    <h5>JURUSAN TEKNIK INFORMATIKA</h5>
                    <p>Gedung F.Teknik Lt. 3, Kampus Hijau Bumi Tridharma Anduonohu, Kendari, 93232</p>
                    <p>Telepon (0401) 3194347, Website: http://ti.eng.uho.ac.id</p>
                </td>
            </tr>
        </table>

        <h4 class="judul-surat">PENGAJUAN JUDUL TUGAS AKHIR</h4>

        <table class="table table-info-mahasiswa">
            <tbody>
                <tr>
                    <th>Nama</th>
                    <td>{{ $pengajuan->mahasiswa->name }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>{{ $pengajuan->mahasiswa->nomor_induk ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <table class="table table-pengajuan">
            <thead class="text-center">
                <tr>
                    <th>Judul</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="height: 80px;">{{ $pengajuan->judul_approved }}</td>
                </tr>
            </tbody>
        </table>

        <table class="table table-pengajuan">
            <thead class="text-center">
                <tr>
                    <th>Dosen Pembimbing</th>
                </tr>
            </thead>
            <tbody>
                {{-- Pastikan relasi bernama 'pembimbing', bukan 'pembimbings' --}}
                @forelse ($pengajuan->pembimbings as $p)
                    <tr>
                        <td class="text-center">
                            {{ $p->dosen->name ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center">-</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table class="tanda-tangan-table">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%;" class="signature-block">
                    <p>Kendari, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p>Ketua Jurusan Teknik Informatika,</p>
                    <p class="nama-pejabat">Test</p>
                    <p>NIP. 123456789</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
