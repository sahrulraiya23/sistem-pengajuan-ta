{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Dashboard</h5>
                    </div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <h4 class="mb-4">Selamat Datang, {{ Auth::user()->name }}!</h4>

                      @if (Auth::user()->role == 'mahasiswa')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Pengajuan Judul Tugas Akhir</h6>
                </div>
                <div class="card-body">
                    @if (isset($data['pengajuan']) && $data['pengajuan']->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['pengajuan'] as $pengajuan)
                                        <tr>
                                            <td>{{ $pengajuan->judul }}</td>
                                            <td>{{ $pengajuan->created_at->format('d-m-Y') }}</td>
                                            <td>
                                                @if ($pengajuan->status == 'pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif($pengajuan->status == 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($pengajuan->status == 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @elseif($pengajuan->status == 'revision')
                                                    <span class="badge bg-info">Revisi</span>
                                                @elseif($pengajuan->status == 'on_progress')
                                                    <span class="badge bg-primary">Sedang Berjalan</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($pengajuan->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- Tambahkan aksi di sini jika diperlukan --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Anda belum mengajukan judul tugas akhir.
                        </div>
                        <a href="{{ route('mahasiswa.judul-ta.create') }}"
                            class="btn btn-primary">Ajukan Judul TA</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">Bimbingan</h6>
                </div>
                <div class="card-body">
                    @if (isset($data['bimbingan']) && $data['bimbingan']->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Dosen</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['bimbingan'] as $bimbingan)
                                        <tr>
                                            <td>{{ $bimbingan->tanggal->format('d-m-Y') }}</td>
                                            <td>{{ $bimbingan->dosen->name }}</td>
                                            <td>
                                                @if ($bimbingan->status == 'pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif($bimbingan->status == 'approved')
                                                    <span class="badge bg-success">Selesai</span>
                                                @elseif($bimbingan->status == 'revision')
                                                    <span class="badge bg-info">Revisi</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($bimbingan->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('bimbingan.show', $bimbingan->id) }}"
                                                    class="btn btn-sm btn-info">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Belum ada jadwal bimbingan.
                        </div>
                        <a href="{{ route('mahasiswa.judul-ta.create') }}"
                            class="btn btn-success">Ajukan Bimbingan</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@elseif(Auth::user()->role == 'dosen')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Pengajuan Judul Mahasiswa</h6>
                </div>
                <div class="card-body">
                    @if (isset($data['pengajuan_mahasiswa']) && $data['pengajuan_mahasiswa']->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['pengajuan_mahasiswa'] as $pengajuan)
                                        <tr>
                                            <td>{{ $pengajuan->mahasiswa->name }}</td>
                                            <td>{{ $pengajuan->judul }}</td>
                                            <td>{{ $pengajuan->created_at->format('d-m-Y') }}</td>
                                            <td>
                                                @if ($pengajuan->status == 'pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif($pengajuan->status == 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($pengajuan->status == 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @elseif($pengajuan->status == 'revision')
                                                    <span class="badge bg-info">Revisi</span>
                                                @elseif($pengajuan->status == 'on_progress')
                                                    <span class="badge bg-primary">Sedang Berjalan</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($pengajuan->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- Tambahkan aksi di sini jika diperlukan --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Belum ada pengajuan judul tugas akhir.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">Jadwal Bimbingan</h6>
                </div>
                <div class="card-body">
                    @if (isset($data['bimbingan_dosen']) && $data['bimbingan_dosen']->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['bimbingan_dosen'] as $bimbingan)
                                        <tr>
                                            <td>{{ $bimbingan->mahasiswa->name }}</td>
                                            <td>{{ $bimbingan->tanggal->format('d-m-Y') }}</td>
                                            <td>
                                                @if ($bimbingan->status == 'pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif($bimbingan->status == 'approved')
                                                    <span class="badge bg-success">Selesai</span>
                                                @elseif($bimbingan->status == 'revision')
                                                    <span class="badge bg-info">Revisi</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($bimbingan->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('dosen.bimbingan.show', $bimbingan->id) }}"
                                                    class="btn btn-sm btn-info">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Belum ada jadwal bimbingan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@elseif(Auth::user()->role == 'admin')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4 bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Mahasiswa</h5>
                    <h2>{{ $data['total_mahasiswa'] ?? 0 }}</h2>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light mt-3">Lihat
                        Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Dosen</h5>
                    <h2>{{ $data['total_dosen'] ?? 0 }}</h2>
                    <a href="{{ route('admin.dosen.index') }}" class="btn btn-light mt-3">Lihat
                        Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Pengajuan Judul</h5>
                    <h2>{{ $data['total_pengajuan'] ?? 0 }}</h2>
                    <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-light mt-3">Lihat
                        Detail</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">Pengajuan Terbaru</h6>
        </div>
        <div class="card-body">
            @if (isset($data['pengajuan_terbaru']) && $data['pengajuan_terbaru']->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Mahasiswa</th>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['pengajuan_terbaru'] as $pengajuan)
                                <tr>
                                    <td>{{ $pengajuan->mahasiswa->name }}</td>
                                    <td>{{ $pengajuan->judul }}</td>
                                    <td>{{ $pengajuan->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($pengajuan->status == 'pending')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($pengajuan->status == 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($pengajuan->status == 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @elseif($pengajuan->status == 'revision')
                                            <span class="badge bg-info">Revisi</span>
                                        @elseif($pengajuan->status == 'on_progress')
                                            <span class="badge bg-primary">Sedang Berjalan</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($pengajuan->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Tambahkan aksi di sini jika diperlukan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    Belum ada pengajuan judul tugas akhir.
                </div>
            @endif
        </div>
    </div>
@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
