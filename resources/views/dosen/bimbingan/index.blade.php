{{-- resources/views/dosen/bimbingan/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Daftar Bimbingan TA - Dosen</title>
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
                                        <div class="page-header-icon"><i data-feather="users"></i></div>
                                        Bimbingan Tugas Akhir
                                    </h1>
                                    <div class="page-header-subtitle">Kelola semua mahasiswa yang Anda bimbing dalam
                                        proses tugas akhir.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="container-xl px-4 mt-n10">
                    <div class="card shadow-sm">
                        <div class="card-body">

                            {{-- FORM FILTER --}}
                            <form method="GET" action="{{ route('dosen.bimbingan.index') }}">
                                <div class="row g-3 align-items-center mb-4">
                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Filter berdasarkan Status:</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="">Semua Status Aktif</option>
                                            
                                            <option value="approved_for_consultation"
                                                {{ request('status') == 'approved_for_consultation' ? 'selected' : '' }}>
                                                Perlu Review Awal
                                            </option>
                                            
                                            <option value="revisi"
                                                {{ request('status') == 'revisi' ? 'selected' : '' }}>
                                                Menunggu Revisi Mahasiswa
                                            </option>
                                            
                                            <option value="submit_revisi"
                                                {{ request('status') == 'submit_revisi' ? 'selected' : '' }}>
                                                Revisi Masuk (Perlu Review)
                                            </option>
                                            
                                            <option value="approved"
                                                {{ request('status') == 'approved' ? 'selected' : '' }}>
                                                Disetujui
                                            </option>
                                            
                                            <option value="finalized"
                                                {{ request('status') == 'finalized' ? 'selected' : '' }}>
                                                Selesai (Final)
                                            </option>
                                            
                                            <option value="rejected"
                                                {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                                Ditolak
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="search" class="form-label">Cari berdasarkan Nama Mahasiswa:</label>
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
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($pengajuan->isEmpty())
                                <div class="alert alert-info text-center">
                                    Tidak ada data bimbingan yang sesuai dengan kriteria Anda.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>NIM</th>
                                                <th>Judul</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuan as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->mahasiswa->name ?? 'N/A' }}</td>
                                                    <td>{{ $item->mahasiswa->nomor_induk ?? 'N/A' }}</td>
                                                    <td>
                                                        {{-- Tampilkan judul approved jika ada, jika tidak tampilkan judul 1 --}}
                                                        {{ $item->judul_approved ?? $item->judul1 }}
                                                    </td>
                                                    <td>
                                                        @switch($item->status)
                                                            @case('approved_for_consultation')
                                                                <span class="badge bg-warning text-dark rounded-pill px-3">
                                                                    <i class="bi bi-exclamation-circle me-1"></i>Perlu Review
                                                                </span>
                                                                @break

                                                            @case('revisi')
                                                                <span class="badge bg-danger rounded-pill px-3">
                                                                    <i class="bi bi-pencil me-1"></i>Revisi Mhs
                                                                </span>
                                                                @break

                                                            @case('submit_revisi')
                                                                <span class="badge bg-info text-dark rounded-pill px-3">
                                                                    <i class="bi bi-arrow-repeat me-1"></i>Cek Revisi
                                                                </span>
                                                                @break

                                                            @case('approved')
                                                                <span class="badge bg-success rounded-pill px-3">
                                                                    <i class="bi bi-check-circle me-1"></i>Disetujui
                                                                </span>
                                                                @break

                                                            @case('finalized')
                                                                <span class="badge bg-success rounded-pill px-3">
                                                                    <i class="bi bi-lock-fill me-1"></i>Final
                                                                </span>
                                                                @break

                                                            @case('rejected')
                                                                <span class="badge bg-dark rounded-pill px-3">
                                                                    <i class="bi bi-x-circle me-1"></i>Ditolak
                                                                </span>
                                                                @break

                                                            @default
                                                                <span class="badge bg-secondary rounded-pill px-3">
                                                                    {{ ucfirst($item->status) }}
                                                                </span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('dosen.bimbingan.show', $item->id) }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="me-1" data-feather="eye"></i> Detail & Proses
                                                        </a>
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