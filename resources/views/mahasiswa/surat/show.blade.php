```blade
{{-- resources/views/mahasiswa/surat/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Surat Tugas Akhir</h5>
                            <a href="{{ route('mahasiswa.judul-ta.show', $pengajuan->id) }}"
                                class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="p-4 border rounded bg-light">
                                    <div class="text-center mb-4">
                                        <h4>SURAT TUGAS</h4>
                                        <p>Nomor: {{ $surat->nomor_surat }}</p>
                                        <hr>
                                    </div>

                                    <p>Yang bertanda tangan di bawah ini:</p>
                                    <p class="ms-4">
                                        Ketua Jurusan<br>
                                        Fakultas [Nama Fakultas]<br>
                                        [Nama Universitas]
                                    </p>

                                    <p class="mt-3">Memberikan tugas kepada:</p>
                                    <table class="ms-4 mb-3">
                                        <tr>
                                            <td width="150">Nama</td>
                                            <td>: {{ Auth::user()->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>NIM</td>
                                            <td>: {{ Auth::user()->nim ?? '[NIM]' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jurusan</td>
                                            <td>: [Nama Jurusan]</td>
                                        </tr>
                                    </table>

                                    <p>Untuk mengerjakan Tugas Akhir dengan judul:</p>
                                    <p class="text-center"><strong>"{{ $pengajuan->judul_approved }}"</strong></p>

                                    <p>Dengan dosen pembimbing:</p>
                                    <table class="ms-4 mb-3">
                                        <tr>
                                            <td width="150">Nama</td>
                                            <td>: [Nama Dosen Pembimbing]</td>
                                        </tr>
                                        <tr>
                                            <td>NIDN</td>
                                            <td>: [NIDN Dosen]</td>
                                        </tr>
                                    </table>

                                    <p>Demikian surat tugas ini dibuat untuk dilaksanakan dengan sebaik-baiknya.</p>

                                    <div class="text-end mt-5">
                                        <p>[Kota], {{ date('d F Y') }}</p>
                                        <p class="mb-5">Ketua Jurusan,</p>
                                        <p><strong>[Nama Ketua Jurusan]</strong></p>
                                        <p>NIDN. [NIDN Ketua Jurusan]</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-primary" onclick="window.print()">
                                        <i class="bi bi-printer"></i> Cetak Surat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .card-body .border.rounded.bg-light,
            .card-body .border.rounded.bg-light * {
                visibility: visible;
            }

            .card-body .border.rounded.bg-light {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background-color: white !important;
                border: none !important;
            }

            .btn,
            .alert,
            .badge {
                display: none !important;
            }
        }
    </style>
@endsection
```
