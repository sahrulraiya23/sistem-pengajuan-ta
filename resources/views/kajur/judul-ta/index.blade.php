{{-- resources/views/kajur/judul-ta/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Daftar Pengajuan Judul - Kajur</title>
    @include('template.css')
</head>

<body class="nav-fixed">
    @include('template.nav')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('template.sidenav')
        </div>

        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                    <div class="container-xl px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-4">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon"><i data-feather="file-text"></i></div>
                                        Daftar Pengajuan Judul
                                    </h1>
                                    <div class="page-header-subtitle">Kelola semua pengajuan judul tugas akhir dari
                                        mahasiswa.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="container-xl px-4 mt-n10">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            {{-- FORM FILTER --}}
                            <form method="GET" action="{{ route('kajur.judul-ta.index') }}">
                                <div class="row g-3 align-items-center mb-4">
                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Filter berdasarkan Status:</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="pending"
                                                {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="approved"
                                                {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui
                                            </option>
                                            <option value="rejected"
                                                {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                            <option value="revision"
                                                {{ request('status') == 'revision' ? 'selected' : '' }}>Revisi</option>
                                            <option value="finalized"
                                                {{ request('status') == 'finalized' ? 'selected' : '' }}>Difinalisasi
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="search" class="form-label">Cari berdasarkan Nama
                                            Mahasiswa:</label>
                                        <input type="text" name="search" id="search" class="form-control"
                                            placeholder="Masukkan nama mahasiswa..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <hr />
                            {{-- AKHIR FORM FILTER --}}

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($pengajuan->isEmpty())
                                <div class="alert alert-info text-center">
                                    Tidak ada pengajuan yang cocok dengan kriteria filter Anda.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Judul Pilihan 1</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuan as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $item->mahasiswa->name ?? 'N/A' }}</td>
                                                    <td>{{ $item->judul1 }}</td>
                                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        @if ($item->status == 'pending' || $item->status == 'submitted')
                                                            <span class="badge bg-warning">Menunggu</span>
                                                        @elseif($item->status == 'approved')
                                                            <span class="badge bg-success">Disetujui</span>
                                                        @elseif($item->status == 'rejected')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @elseif($item->status == 'finalized')
                                                            <span class="badge bg-primary">Difinalisasi</span>
                                                        @elseif($item->status == 'revision')
                                                            <span class="badge bg-info">Revisi</span>
                                                        @else
                                                            <span
                                                                class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                                                        @endif
                                                    </td>
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
            </main>
            @include('template.footer')
        </div>
    </div>

    @include('template.script')
</body>

</html>
