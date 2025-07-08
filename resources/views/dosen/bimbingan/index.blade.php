<!DOCTYPE html>
<html lang="en">

@include('template.css')

<body class="nav-fixed">
    @include('template.nav')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('template.sidenav')
        </div>

        <div id="layoutSidenav_content">
            <div class="container mt-4">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Daftar Bimbingan Tugas Akhir</h5>
                            </div>

                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if ($pengajuan->isEmpty())
                                    <div class="alert alert-info">
                                        Belum ada pengajuan judul yang perlu Anda tindaklanjuti.
                                    </div>
                                @else
                                    {{-- TAB NAVIGATION --}}
                                    <ul class="nav nav-tabs mb-3" id="dosenBimbinganTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="menunggu-saran-tab" data-bs-toggle="tab"
                                                data-bs-target="#menunggu-saran" type="button" role="tab"
                                                aria-controls="menunggu-saran" aria-selected="true">
                                                Menunggu Saran Anda
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="revisi-mahasiswa-tab" data-bs-toggle="tab"
                                                data-bs-target="#revisi-mahasiswa" type="button" role="tab"
                                                aria-controls="revisi-mahasiswa" aria-selected="false">
                                                Revisi (Mahasiswa Merevisi)
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="diajukan-kembali-tab" data-bs-toggle="tab"
                                                data-bs-target="#diajukan-kembali" type="button" role="tab"
                                                aria-controls="diajukan-kembali" aria-selected="false">
                                                Diajukan Kembali (Menunggu Persetujuan Anda)
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="disetujui-dosen-tab" data-bs-toggle="tab"
                                                data-bs-target="#disetujui-dosen" type="button" role="tab"
                                                aria-controls="disetujui-dosen" aria-selected="false">
                                                Disetujui Dosen
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="selesai-tab" data-bs-toggle="tab"
                                                data-bs-target="#selesai" type="button" role="tab"
                                                aria-controls="selesai" aria-selected="false">
                                                Selesai / Ditolak
                                            </button>
                                        </li>
                                    </ul>

                                    {{-- TAB CONTENT --}}
                                    <div class="tab-content" id="dosenBimbinganTabContent">
                                        {{-- Tab Menunggu Saran Anda --}}
                                        <div class="tab-pane fade show active" id="menunggu-saran" role="tabpanel"
                                            aria-labelledby="menunggu-saran-tab">
                                            @php
                                                $filtered = $pengajuan->filter(function ($item) {
                                                    return $item->status ==
                                                        \App\Models\JudulTA::STATUS_APPROVED_FOR_CONSULTATION;
                                                });
                                            @endphp
                                            @if ($filtered->isEmpty())
                                                <div class="alert alert-info">
                                                    Tidak ada pengajuan dengan status ini.
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-bordered"
                                                        id="datatablesSimple-menunggu-saran" width="100%"
                                                        cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Mahasiswa</th>
                                                                <th>NIM</th>
                                                                <th>Judul Pilihan 1</th>
                                                                <th>Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($filtered as $item)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $item->mahasiswa->name ?? '-' }}</td>
                                                                    <td>{{ $item->mahasiswa->nim ?? '-' }}</td>
                                                                    <td>
                                                                        @php
                                                                            $displayJudul = $item->judul1;
                                                                            if (
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_APPROVED ||
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_FINALIZED
                                                                            ) {
                                                                                $displayJudul = $item->judul_approved;
                                                                            } elseif (
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_REJECTED &&
                                                                                $item->alasan_penolakan
                                                                            ) {
                                                                                $displayJudul =
                                                                                    $item->judul1 .
                                                                                    ' <small class="text-danger d-block">(Ditolak: ' .
                                                                                    Str::limit(
                                                                                        $item->alasan_penolakan,
                                                                                        50,
                                                                                    ) .
                                                                                    ')</small>';
                                                                            }
                                                                        @endphp
                                                                        {!! $displayJudul !!}
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $badgeClass = '';
                                                                            $statusText = '';
                                                                            switch ($item->status) {
                                                                                case \App\Models\JudulTA::STATUS_APPROVED_FOR_CONSULTATION:
                                                                                    $badgeClass = 'bg-info';
                                                                                    $statusText = 'Menunggu Saran Anda';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_REVISED:
                                                                                    $badgeClass = 'bg-danger';
                                                                                    $statusText =
                                                                                        'Revisi (Mhs Merevisi)';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_SUBMIT_REVISED:
                                                                                    $badgeClass = 'bg-primary';
                                                                                    $statusText =
                                                                                        'Diajukan Kembali (Menunggu Anda)';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_APPROVED:
                                                                                    $badgeClass = 'bg-success';
                                                                                    $statusText = 'Disetujui Dosen';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_REJECTED:
                                                                                    $badgeClass = 'bg-dark';
                                                                                    $statusText = 'Ditolak';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_FINALIZED:
                                                                                    $badgeClass = 'bg-success';
                                                                                    $statusText = 'Finalisasi';
                                                                                    break;
                                                                                default:
                                                                                    $badgeClass = 'bg-secondary';
                                                                                    $statusText = ucfirst(
                                                                                        str_replace(
                                                                                            '_',
                                                                                            ' ',
                                                                                            $item->status,
                                                                                        ),
                                                                                    );
                                                                                    break;
                                                                            }
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{ route('dosen.bimbingan.show', $item->id) }}"
                                                                            class="btn btn-info btn-sm">Detail &
                                                                            Proses</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Tab Revisi (Mahasiswa Merevisi) --}}
                                        <div class="tab-pane fade" id="revisi-mahasiswa" role="tabpanel"
                                            aria-labelledby="revisi-mahasiswa-tab">
                                            @php
                                                $filtered = $pengajuan->filter(function ($item) {
                                                    return $item->status == \App\Models\JudulTA::STATUS_REVISED;
                                                });
                                            @endphp
                                            @if ($filtered->isEmpty())
                                                <div class="alert alert-info">
                                                    Tidak ada pengajuan dengan status ini.
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-bordered"
                                                        id="datatablesSimple-revisi-mahasiswa" width="100%"
                                                        cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Mahasiswa</th>
                                                                <th>NIM</th>
                                                                <th>Judul Pilihan 1</th>
                                                                <th>Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($filtered as $item)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $item->mahasiswa->name ?? '-' }}</td>
                                                                    <td>{{ $item->mahasiswa->nim ?? '-' }}</td>
                                                                    <td>
                                                                        @php
                                                                            $displayJudul = $item->judul1;
                                                                            if (
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_APPROVED ||
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_FINALIZED
                                                                            ) {
                                                                                $displayJudul = $item->judul_approved;
                                                                            } elseif (
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_REJECTED &&
                                                                                $item->alasan_penolakan
                                                                            ) {
                                                                                $displayJudul =
                                                                                    $item->judul1 .
                                                                                    ' <small class="text-danger d-block">(Ditolak: ' .
                                                                                    Str::limit(
                                                                                        $item->alasan_penolakan,
                                                                                        50,
                                                                                    ) .
                                                                                    ')</small>';
                                                                            }
                                                                        @endphp
                                                                        {!! $displayJudul !!}
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $badgeClass = '';
                                                                            $statusText = '';
                                                                            switch ($item->status) {
                                                                                case \App\Models\JudulTA::STATUS_APPROVED_FOR_CONSULTATION:
                                                                                    $badgeClass = 'bg-info';
                                                                                    $statusText = 'Menunggu Saran Anda';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_REVISED:
                                                                                    $badgeClass = 'bg-danger';
                                                                                    $statusText =
                                                                                        'Revisi (Mhs Merevisi)';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_SUBMIT_REVISED:
                                                                                    $badgeClass = 'bg-primary';
                                                                                    $statusText =
                                                                                        'Diajukan Kembali (Menunggu Anda)';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_APPROVED:
                                                                                    $badgeClass = 'bg-success';
                                                                                    $statusText = 'Disetujui Dosen';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_REJECTED:
                                                                                    $badgeClass = 'bg-dark';
                                                                                    $statusText = 'Ditolak';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_FINALIZED:
                                                                                    $badgeClass = 'bg-success';
                                                                                    $statusText = 'Finalisasi';
                                                                                    break;
                                                                                default:
                                                                                    $badgeClass = 'bg-secondary';
                                                                                    $statusText = ucfirst(
                                                                                        str_replace(
                                                                                            '_',
                                                                                            ' ',
                                                                                            $item->status,
                                                                                        ),
                                                                                    );
                                                                                    break;
                                                                            }
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{ route('dosen.bimbingan.show', $item->id) }}"
                                                                            class="btn btn-info btn-sm">Detail &
                                                                            Proses</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Tab Diajukan Kembali (Menunggu Persetujuan Anda) --}}
                                        <div class="tab-pane fade" id="diajukan-kembali" role="tabpanel"
                                            aria-labelledby="diajukan-kembali-tab">
                                            @php
                                                $filtered = $pengajuan->filter(function ($item) {
                                                    return $item->status == \App\Models\JudulTA::STATUS_SUBMIT_REVISED;
                                                });
                                            @endphp
                                            @if ($filtered->isEmpty())
                                                <div class="alert alert-info">
                                                    Tidak ada pengajuan dengan status ini.
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-bordered"
                                                        id="datatablesSimple-diajukan-kembali" width="100%"
                                                        cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Mahasiswa</th>
                                                                <th>NIM</th>
                                                                <th>Judul Pilihan 1</th>
                                                                <th>Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($filtered as $item)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $item->mahasiswa->name ?? '-' }}</td>
                                                                    <td>{{ $item->mahasiswa->nim ?? '-' }}</td>
                                                                    <td>
                                                                        @php
                                                                            $displayJudul = $item->judul1;
                                                                            if (
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_APPROVED ||
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_FINALIZED
                                                                            ) {
                                                                                $displayJudul = $item->judul_approved;
                                                                            } elseif (
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_REJECTED &&
                                                                                $item->alasan_penolakan
                                                                            ) {
                                                                                $displayJudul =
                                                                                    $item->judul1 .
                                                                                    ' <small class="text-danger d-block">(Ditolak: ' .
                                                                                    Str::limit(
                                                                                        $item->alasan_penolakan,
                                                                                        50,
                                                                                    ) .
                                                                                    ')</small>';
                                                                            }
                                                                        @endphp
                                                                        {!! $displayJudul !!}
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $badgeClass = '';
                                                                            $statusText = '';
                                                                            switch ($item->status) {
                                                                                case \App\Models\JudulTA::STATUS_APPROVED_FOR_CONSULTATION:
                                                                                    $badgeClass = 'bg-info';
                                                                                    $statusText = 'Menunggu Saran Anda';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_REVISED:
                                                                                    $badgeClass = 'bg-danger';
                                                                                    $statusText =
                                                                                        'Revisi (Mhs Merevisi)';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_SUBMIT_REVISED:
                                                                                    $badgeClass = 'bg-primary';
                                                                                    $statusText =
                                                                                        'Diajukan Kembali (Menunggu Anda)';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_APPROVED:
                                                                                    $badgeClass = 'bg-success';
                                                                                    $statusText = 'Disetujui Dosen';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_REJECTED:
                                                                                    $badgeClass = 'bg-dark';
                                                                                    $statusText = 'Ditolak';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_FINALIZED:
                                                                                    $badgeClass = 'bg-success';
                                                                                    $statusText = 'Finalisasi';
                                                                                    break;
                                                                                default:
                                                                                    $badgeClass = 'bg-secondary';
                                                                                    $statusText = ucfirst(
                                                                                        str_replace(
                                                                                            '_',
                                                                                            ' ',
                                                                                            $item->status,
                                                                                        ),
                                                                                    );
                                                                                    break;
                                                                            }
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{ route('dosen.bimbingan.show', $item->id) }}"
                                                                            class="btn btn-info btn-sm">Detail &
                                                                            Proses</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Tab Disetujui Dosen --}}
                                        <div class="tab-pane fade" id="disetujui-dosen" role="tabpanel"
                                            aria-labelledby="disetujui-dosen-tab">
                                            @php
                                                $filtered = $pengajuan->filter(function ($item) {
                                                    return $item->status == \App\Models\JudulTA::STATUS_APPROVED;
                                                });
                                            @endphp
                                            @if ($filtered->isEmpty())
                                                <div class="alert alert-info">
                                                    Tidak ada pengajuan dengan status ini.
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-bordered"
                                                        id="datatablesSimple-disetujui-dosen" width="100%"
                                                        cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Mahasiswa</th>
                                                                <th>NIM</th>
                                                                <th>Judul Pilihan 1</th>
                                                                <th>Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($filtered as $item)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $item->mahasiswa->name ?? '-' }}</td>
                                                                    <td>{{ $item->mahasiswa->nim ?? '-' }}</td>
                                                                    <td>
                                                                        @php
                                                                            $displayJudul = $item->judul1;
                                                                            if (
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_APPROVED ||
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_FINALIZED
                                                                            ) {
                                                                                $displayJudul = $item->judul_approved;
                                                                            } elseif (
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_REJECTED &&
                                                                                $item->alasan_penolakan
                                                                            ) {
                                                                                $displayJudul =
                                                                                    $item->judul1 .
                                                                                    ' <small class="text-danger d-block">(Ditolak: ' .
                                                                                    Str::limit(
                                                                                        $item->alasan_penolakan,
                                                                                        50,
                                                                                    ) .
                                                                                    ')</small>';
                                                                            }
                                                                        @endphp
                                                                        {!! $displayJudul !!}
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $badgeClass = '';
                                                                            $statusText = '';
                                                                            switch ($item->status) {
                                                                                case \App\Models\JudulTA::STATUS_APPROVED_FOR_CONSULTATION:
                                                                                    $badgeClass = 'bg-info';
                                                                                    $statusText = 'Menunggu Saran Anda';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_REVISED:
                                                                                    $badgeClass = 'bg-danger';
                                                                                    $statusText =
                                                                                        'Revisi (Mhs Merevisi)';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_SUBMIT_REVISED:
                                                                                    $badgeClass = 'bg-primary';
                                                                                    $statusText =
                                                                                        'Diajukan Kembali (Menunggu Anda)';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_APPROVED:
                                                                                    $badgeClass = 'bg-success';
                                                                                    $statusText = 'Disetujui Dosen';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_REJECTED:
                                                                                    $badgeClass = 'bg-dark';
                                                                                    $statusText = 'Ditolak';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_FINALIZED:
                                                                                    $badgeClass = 'bg-success';
                                                                                    $statusText = 'Finalisasi';
                                                                                    break;
                                                                                default:
                                                                                    $badgeClass = 'bg-secondary';
                                                                                    $statusText = ucfirst(
                                                                                        str_replace(
                                                                                            '_',
                                                                                            ' ',
                                                                                            $item->status,
                                                                                        ),
                                                                                    );
                                                                                    break;
                                                                            }
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{ route('dosen.bimbingan.show', $item->id) }}"
                                                                            class="btn btn-info btn-sm">Detail &
                                                                            Proses</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Tab Selesai / Ditolak --}}
                                        <div class="tab-pane fade" id="selesai" role="tabpanel"
                                            aria-labelledby="selesai-tab">
                                            @php
                                                $filtered = $pengajuan->filter(function ($item) {
                                                    return $item->status == \App\Models\JudulTA::STATUS_FINALIZED ||
                                                        $item->status == \App\Models\JudulTA::STATUS_REJECTED;
                                                });
                                            @endphp
                                            @if ($filtered->isEmpty())
                                                <div class="alert alert-info">
                                                    Tidak ada pengajuan dengan status ini.
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="datatablesSimple-selesai"
                                                        width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Mahasiswa</th>
                                                                <th>NIM</th>
                                                                <th>Judul Pilihan 1</th>
                                                                <th>Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($filtered as $item)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $item->mahasiswa->name ?? '-' }}</td>
                                                                    <td>{{ $item->mahasiswa->nim ?? '-' }}</td>
                                                                    <td>
                                                                        @php
                                                                            $displayJudul = $item->judul1;
                                                                            if (
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_APPROVED ||
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_FINALIZED
                                                                            ) {
                                                                                $displayJudul = $item->judul_approved;
                                                                            } elseif (
                                                                                $item->status ==
                                                                                    \App\Models\JudulTA::STATUS_REJECTED &&
                                                                                $item->alasan_penolakan
                                                                            ) {
                                                                                $displayJudul =
                                                                                    $item->judul1 .
                                                                                    ' <small class="text-danger d-block">(Ditolak: ' .
                                                                                    Str::limit(
                                                                                        $item->alasan_penolakan,
                                                                                        50,
                                                                                    ) .
                                                                                    ')</small>';
                                                                            }
                                                                        @endphp
                                                                        {!! $displayJudul !!}
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $badgeClass = '';
                                                                            $statusText = '';
                                                                            switch ($item->status) {
                                                                                case \App\Models\JudulTA::STATUS_APPROVED_FOR_CONSULTATION:
                                                                                    $badgeClass = 'bg-info';
                                                                                    $statusText = 'Menunggu Saran Anda';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_REVISED:
                                                                                    $badgeClass = 'bg-danger';
                                                                                    $statusText =
                                                                                        'Revisi (Mhs Merevisi)';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_SUBMIT_REVISED:
                                                                                    $badgeClass = 'bg-primary';
                                                                                    $statusText =
                                                                                        'Diajukan Kembali (Menunggu Anda)';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_APPROVED:
                                                                                    $badgeClass = 'bg-success';
                                                                                    $statusText = 'Disetujui Dosen';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_REJECTED:
                                                                                    $badgeClass = 'bg-dark';
                                                                                    $statusText = 'Ditolak';
                                                                                    break;
                                                                                case \App\Models\JudulTA::STATUS_FINALIZED:
                                                                                    $badgeClass = 'bg-success';
                                                                                    $statusText = 'Finalisasi';
                                                                                    break;
                                                                                default:
                                                                                    $badgeClass = 'bg-secondary';
                                                                                    $statusText = ucfirst(
                                                                                        str_replace(
                                                                                            '_',
                                                                                            ' ',
                                                                                            $item->status,
                                                                                        ),
                                                                                    );
                                                                                    break;
                                                                            }
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{ route('dosen.bimbingan.show', $item->id) }}"
                                                                            class="btn btn-info btn-sm">Detail &
                                                                            Proses</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </div> {{-- End tab-content --}}
                                @endif
                            </div> {{-- End card-body --}}
                        </div> {{-- End card --}}
                    </div> {{-- End col-md-12 --}}
                </div> {{-- End row --}}
            </div> {{-- End container --}}
            @include('template.footer')
        </div> {{-- End layoutSidenav_content --}}
    </div> {{-- End layoutSidenav --}}
    @include('template.script')

</body>

</html>
