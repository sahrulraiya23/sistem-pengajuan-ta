<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Judul Tugas Akhir - {{ $pengajuan->mahasiswa->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Times New Roman', Times, serif;
        }

        .paper {
            background: white;
            max-width: 21cm;
            min-height: 29.7cm;
            margin: 2rem auto;
            padding: 2.5cm 2cm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: black;
        }

        .kop-surat {
            display: flex;
            flex-direction: row;
            align-items: center;
            border-bottom: 4px double black;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .kop-surat .logo {
            width: 85px;
            height: 85px;
            margin-right: 25px;
        }

        .kop-surat .kop-text {
            text-align: center;
            flex-grow: 1;
        }

        .kop-surat .kop-text h4,
        .kop-surat .kop-text h5,
        .kop-surat .kop-text p {
            margin: 0;
            line-height: 1.4;
        }

        .kop-surat .kop-text h4 {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .kop-surat .kop-text h5 {
            font-size: 13pt;
            font-weight: bold;
        }

        .kop-surat .kop-text p {
            font-size: 10pt;
        }

        .judul-surat {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 3rem;
            margin-bottom: 2rem;
            font-size: 14pt;
        }

        .table-info-mahasiswa td,
        .table-info-mahasiswa th {
            padding: 0.35rem 0.75rem;
            font-size: 12pt;
        }

        .table-pengajuan th,
        .table-pengajuan td {
            vertical-align: middle;
            padding: 0.75rem;
            font-size: 12pt;
        }

        .tanda-tangan {
            margin-top: 4rem;
            font-size: 12pt;
        }

        @media print {
            body {
                background-color: white;
            }

            .paper {
                margin: 0;
                box-shadow: none;
                padding: 1.5cm;
            }

            .btn-container {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container btn-container text-center my-3">
        <a href="{{ route('mahasiswa.surat.download', $pengajuan->id) }}" class="btn btn-primary">
            Download PDF
        </a>
        <a href="{{ route('mahasiswa.judul-ta.show', $pengajuan->id) }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="paper">
        <div class="kop-surat">
            <img src="{{ asset('assets/img/logo-uho.png') }}" alt="Logo Universitas" class="logo">
            <div class="kop-text">
                <h4>KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN</h4>
                <h5>UNIVERSITAS HALU OLEO</h5>
                <h5>JURUSAN TEKNIK INFORMATIKA</h5>
                <p>Gedung F.Teknik Lt. 3, Kampus Hijau Bumi Tridharma Anduonohu, Kendari, 93232</p>
                <p>Telepon (0401) 3194347, Website: http://ti.eng.uho.ac.id</p>
            </div>
        </div>

        <h4 class="judul-surat">PENGAJUAN JUDUL TUGAS AKHIR</h4>

        <table class="table table-bordered table-info-mahasiswa mb-4">
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

        <table class="table table-bordered table-pengajuan mb-3">
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

        <table class="table table-bordered table-pengajuan">
            <thead class="text-center">
                <tr>
                    <th>Dosen Pembimbing</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        @if ($pengajuan->pembimbing && $pengajuan->pembimbing->dosen)
                            {{ $pengajuan->pembimbing->dosen->name }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="row tanda-tangan">
            <div class="col-7"></div>
            <div class="col-5 text-start">
                <p>Kendari, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p>Ketua Jurusan Teknik Informatika,</p>
                <br>
                <br>
                <br>
                <p class="fw-bold text-decoration-underline">Test</p>
                <p>NIP. 123456789</p>
            </div>
        </div>
    </div>
</body>

</html>
