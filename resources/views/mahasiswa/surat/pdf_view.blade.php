{{-- resources/views/mahasiswa/surat/pdf_view.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pengantar Judul TA - {{ $pengajuan->mahasiswa->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: black;
            font-size: 12pt;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 4px double black;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
            width: 100%;
        }

        .kop-surat .logo {
            width: 85px;
            height: 85px;
            position: absolute;
            top: 0;
            left: 0;
        }

        .kop-surat .kop-text h4 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
        }

        .kop-surat .kop-text h5 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
        }

        .kop-surat .kop-text p {
            font-size: 10pt;
            margin: 0;
        }

        .judul-surat {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 3rem;
            margin-bottom: 2rem;
            font-size: 14pt;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 0.5rem;
            vertical-align: middle;
        }

        .table thead th {
            text-align: center;
        }

        .tanda-tangan {
            margin-top: 4rem;
            width: 100%;
        }

        .tanda-tangan .spacer {
            width: 60%;
        }

        .tanda-tangan .signature-block {
            width: 40%;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <img src="{{ public_path('assets/img/logo-uho.png') }}" alt="Logo" class="logo">
        <div class="kop-text">
            <h4>KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN</h4>
            <h5>UNIVERSITAS HALU OLEO</h5>
            <h5>JURUSAN TEKNIK INFORMATIKA</h5>
            <p>Gedung F.Teknik Lt. 3, Kampus Hijau Bumi Tridharma Anduonohu, Kendari, 93232</p>
            <p>Telepon (0401) 3194347, Website: http://ti.eng.uho.ac.id</p>
        </div>
    </div>

    <h4 class="judul-surat">PENGANTAR JUDUL TUGAS AKHIR</h4>

    <table class="table table-info-mahasiswa">
        <tbody>
            <tr>
                <th width="30%">Nama</th>
                <td>{{ $pengajuan->mahasiswa->name }}</td>
            </tr>
            <tr>
                <th>NIM</th>
                <td>{{ $pengajuan->mahasiswa->nomor_induk ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <table class="table table-pengajuan">
        <thead>
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
        <thead>
            <tr>
                <th>Dosen Pembimbing</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">
                    @if ($pengajuan->pembimbing && $pengajuan->pembimbing->dosen)
                        {{ $pengajuan->pembimbing->dosen->name }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <table class="tanda-tangan">
        <tr>
            <td class="spacer"></td>
            <td class="signature-block">
                <p>Kendari, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p>Ketua Jurusan Teknik Informatika,</p>
                <br><br><br>
                <p style="font-weight: bold; text-decoration: underline;">SAHRUL, S.T., M.T</p>
                <p>NIP. 19760222 201812 1 001</p>
            </td>
        </tr>
    </table>
</body>

</html>
