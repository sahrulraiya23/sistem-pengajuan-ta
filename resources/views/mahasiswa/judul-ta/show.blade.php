<!DOCTYPE html>
<html lang="en">

@include('mahasiswa.css')

<body class="nav-fixed">
    @include('mahasiswa.nav')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('mahasiswa.sidenav')

        </div>
        <div id="layoutSidenav_content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Detail Pengajuan Judul</h5>
                                    <a href="{{ route('mahasiswa.judul-ta.index') }}"
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

                                <h6 class="border-bottom pb-2 mb-3">Informasi Pengajuan</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="40%">Status</th>
                                                <td>
                                                    @if ($pengajuan->status == 'submitted')
                                                        <span class="badge bg-info">Diajukan</span>
                                                    @elseif($pengajuan->status == 'approved')
                                                        <span class="badge bg-success">Disetujui</span>
                                                    @elseif($pengajuan->status == 'rejected')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @elseif($pengajuan->status == 'finalized')
                                                        <span class="badge bg-primary">Difinalisasi</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Pengajuan</th>
                                                <td>{{ $pengajuan->created_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <h6 class="border-bottom pb-2 mb-3">Judul yang Diajukan</h6>
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="20%">Judul Pilihan 1</th>
                                                <td>{{ $pengajuan->judul1 }}</td>
                                            </tr>
                                            <tr>
                                                <th>Judul Pilihan 2</th>
                                                <td>{{ $pengajuan->judul2 }}</td>
                                            </tr>
                                            <tr>
                                                <th>Judul Pilihan 3</th>
                                                <td>{{ $pengajuan->judul3 }}</td>
                                            </tr>
                                            @if ($pengajuan->judul_approved)
                                                <tr>
                                                    <th>Judul yang Disetujui</th>
                                                    <td><strong
                                                            class="text-success">{{ $pengajuan->judul_approved }}</strong>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($pengajuan->judul_revisi)
                                                <tr>
                                                    <th>Judul Revisi</th>
                                                    <td><strong
                                                            class="text-primary">{{ $pengajuan->judul_revisi }}</strong>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($pengajuan->alasan_penolakan)
                                                <tr>
                                                    <th>Alasan Penolakan</th>
                                                    <td><strong
                                                            class="text-danger">{{ $pengajuan->alasan_penolakan }}</strong>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>

                                @if ($pembimbing)
                                    <h6 class="border-bottom pb-2 mb-3">Dosen Pembimbing</h6>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <th width="40%">Nama Dosen</th>
                                                    <td>{{ $pembimbing->dosen->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email</th>
                                                    <td>{{ $pembimbing->dosen->email }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                @if ($pengajuan->status == 'approved' || $pengajuan->status == 'finalized')
                                    <div class="row mb-4">
                                        <div class="col-12 d-flex justify-content-between">
                                            <a href="{{ route('mahasiswa.revisi.show', $pengajuan->id) }}"
                                                class="btn btn-primary">
                                                <i class="bi bi-pencil"></i> Kirim Revisi
                                            </a>

                                            @if ($surat)
                                                <a href="{{ route('mahasiswa.surat.show', $pengajuan->id) }}"
                                                    class="btn btn-success">
                                                    <i class="bi bi-file-earmark-text"></i> Lihat Surat Tugas
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if ($revisi->isNotEmpty())
                                    <h6 class="border-bottom pb-2 mb-3">Riwayat Revisi</h6>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Tanggal</th>
                                                            <th>Dari</th>
                                                            <th>Catatan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($revisi as $key => $item)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                                                <td>
                                                                    @if ($item->role_type == 'mahasiswa')
                                                                        <span class="badge bg-info">Mahasiswa</span>
                                                                    @else
                                                                        <span class="badge bg-warning">Dosen</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $item->catatan }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('mahasiswa.footer')


        </div>
    </div>
    @include('mahasiswa.script')

</body>

</html>
