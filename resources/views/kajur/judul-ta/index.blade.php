```blade
{{-- resources/views/kajur/judul-ta/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Daftar Pengajuan Judul Tugas Akhir</h5>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="submitted-tab" data-bs-toggle="tab"
                                    data-bs-target="#submitted" type="button" role="tab" aria-controls="submitted"
                                    aria-selected="true">Menunggu Persetujuan</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved"
                                    type="button" role="tab" aria-controls="approved"
                                    aria-selected="false">Disetujui</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected"
                                    type="button" role="tab" aria-controls="rejected"
                                    aria-selected="false">Ditolak</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="finalized-tab" data-bs-toggle="tab" data-bs-target="#finalized"
                                    type="button" role="tab" aria-controls="finalized"
                                    aria-selected="false">Difinalisasi</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="submitted" role="tabpanel"
                                aria-labelledby="submitted-tab">
                                @php
                                    $submittedPengajuan = $pengajuan->where('status', 'submitted');
                                @endphp

                                @if ($submittedPengajuan->isEmpty())
                                    <div class="alert alert-info">
                                        Tidak ada pengajuan yang menunggu persetujuan.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Judul Pilihan 1</th>
                                                    <th>Tanggal Pengajuan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($submittedPengajuan as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $item->mahasiswa->name }}</td>
                                                        <td>{{ $item->judul1 }}</td>
                                                        <td>{{ $item->created_at->format('d M Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('kajur.judul-ta.show', $item->id) }}"
                                                                class="btn btn-sm btn-info">Detail</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                                @php
                                    $approvedPengajuan = $pengajuan->where('status', 'approved');
                                @endphp

                                @if ($approvedPengajuan->isEmpty())
                                    <div class="alert alert-info">
                                        Tidak ada pengajuan yang disetujui.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Judul yang Disetujui</th>
                                                    <th>Tanggal Disetujui</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($approvedPengajuan as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $item->mahasiswa->name }}</td>
                                                        <td>{{ $item->judul_approved }}</td>
                                                        <td>{{ $item->updated_at->format('d M Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('kajur.judul-ta.show', $item->id) }}"
                                                                class="btn btn-sm btn-info">Detail</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                                @php
                                    $rejectedPengajuan = $pengajuan->where('status', 'rejected');
                                @endphp

                                @if ($rejectedPengajuan->isEmpty())
                                    <div class="alert alert-info">
                                        Tidak ada pengajuan yang ditolak.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Judul Pilihan 1</th>
                                                    <th>Alasan Penolakan</th>
                                                    <th>Tanggal Ditolak</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($rejectedPengajuan as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $item->mahasiswa->name }}</td>
                                                        <td>{{ $item->judul1 }}</td>
                                                        <td>{{ Str::limit($item->alasan_penolakan, 50) }}</td>
                                                        <td>{{ $item->updated_at->format('d M Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('kajur.judul-ta.show', $item->id) }}"
                                                                class="btn btn-sm btn-info">Detail</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="finalized" role="tabpanel" aria-labelledby="finalized-tab">
                                @php
                                    $finalizedPengajuan = $pengajuan->where('status', 'finalized');
                                @endphp

                                @if ($finalizedPengajuan->isEmpty())
                                    <div class="alert alert-info">
                                        Tidak ada pengajuan yang telah difinalisasi.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Judul Final</th>
                                                    <th>Tanggal Finalisasi</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($finalizedPengajuan as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $item->mahasiswa->name }}</td>
                                                        <td>
                                                            @if ($item->judul_revisi)
                                                                {{ $item->judul_revisi }}
                                                            @else
                                                                {{ $item->judul_approved }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->updated_at->format('d M Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('kajur.judul-ta.show', $item->id) }}"
                                                                class="btn btn-sm btn-info">Detail</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```
