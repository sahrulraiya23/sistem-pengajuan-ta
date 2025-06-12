@section('content')
    @include('template.css')
    @include('template.nav')
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('template.sidenav')
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 py-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-11">
                            <!-- Card utama -->
                            <div class="card shadow border-0 rounded-3 mb-4">
                                <div class="card-header bg-gradient-primary-to-secondary p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 text-white fw-bold">
                                            <i class="bi bi-file-earmark-text me-2"></i>Detail Pengajuan Tugas Akhir
                                        </h5>
                                        <a href="{{ route('kajur.judul-ta.index') }}" class="btn btn-light">
                                            <i class="bi bi-arrow-left me-1"></i>Kembali
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body p-4">
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center"
                                            role="alert">
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            <div>{{ session('success') }}</div>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <!-- Panel Informasi Mahasiswa -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-light py-3">
                                            <h6 class="mb-0 fw-bold text-primary">
                                                <i class="bi bi-person-badge me-2"></i>Informasi Mahasiswa
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <table class="table table-sm table-borderless">
                                                        <tr>
                                                            <th width="40%" class="text-secondary">Nama Mahasiswa</th>
                                                            <td class="fw-medium">{{ $pengajuan->mahasiswa->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-secondary">Email</th>
                                                            <td>{{ $pengajuan->mahasiswa->email }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-secondary">NIM</th>
                                                            <td>{{ $pengajuan->mahasiswa->nomor_induk ?? '-' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-sm table-borderless">
                                                        <tr>
                                                            <th width="40%" class="text-secondary">Status</th>
                                                            <td>
                                                                @if ($pengajuan->status == 'submitted')
                                                                    <span class="badge bg-info rounded-pill px-3">
                                                                        <i class="bi bi-hourglass me-1"></i>Diajukan
                                                                    </span>
                                                                @elseif($pengajuan->status == 'approved')
                                                                    <span class="badge bg-success rounded-pill px-3">
                                                                        <i class="bi bi-check-circle me-1"></i>Disetujui
                                                                    </span>
                                                                @elseif($pengajuan->status == 'rejected')
                                                                    <span class="badge bg-danger rounded-pill px-3">
                                                                        <i class="bi bi-x-circle me-1"></i>Ditolak
                                                                    </span>
                                                                @elseif($pengajuan->status == 'finalized')
                                                                    <span class="badge bg-primary rounded-pill px-3">
                                                                        <i class="bi bi-lock me-1"></i>Difinalisasi
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-secondary">Tanggal Pengajuan</th>
                                                            <td>
                                                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                                                {{ $pengajuan->created_at->format('d M Y H:i') }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Panel Judul yang Diajukan -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-light py-3">
                                            <h6 class="mb-0 fw-bold text-primary">
                                                <i class="bi bi-book me-2"></i>Judul yang Diajukan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <tr>
                                                        <th width="20%" class="text-secondary">Judul Pilihan 1</th>
                                                        <td>{{ $pengajuan->judul1 }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-secondary">Judul Pilihan 2</th>
                                                        <td>{{ $pengajuan->judul2 }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-secondary">Judul Pilihan 3</th>
                                                        <td>{{ $pengajuan->judul3 }}</td>
                                                    </tr>
                                                    @if ($pengajuan->judul_approved)
                                                        <tr class="table-success">
                                                            <th class="text-secondary">Judul yang Disetujui</th>
                                                            <td class="fw-bold text-success">
                                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                                {{ $pengajuan->judul_approved }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($pengajuan->judul_revisi)
                                                        <tr class="table-info">
                                                            <th class="text-secondary">Judul Revisi Terbaru</th>
                                                            <td class="fw-bold text-primary">
                                                                <i class="bi bi-pencil-square me-1"></i>
                                                                {{ $pengajuan->judul_revisi }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($pengajuan->alasan_penolakan)
                                                        <tr class="table-danger">
                                                            <th class="text-secondary">Alasan Penolakan</th>
                                                            <td class="fw-bold text-danger">
                                                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                                                {{ $pengajuan->alasan_penolakan }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($pengajuan->status == 'submitted')
                                        <!-- Panel Aksi Pengajuan -->
                                        <div class="row g-4 mb-4">
                                            <!-- Form Persetujuan -->
                                            <div class="col-md-6">
                                                <div class="card h-100 border-success shadow-sm">
                                                    <div class="card-header bg-success text-white p-3">
                                                        <h6 class="mb-0 fw-bold">
                                                            <i class="bi bi-check-circle me-2"></i>Setujui Pengajuan
                                                        </h6>
                                                    </div>
                                                    <div class="card-body p-4">
                                                        <form
                                                            action="{{ route('kajur.judul-ta.approve', $pengajuan->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="judul_approved" class="form-label fw-medium">
                                                                    Judul yang Disetujui
                                                                </label>
                                                                <select
                                                                    class="form-select form-select-lg mb-2 @error('judul_approved') is-invalid @enderror"
                                                                    id="judul_approved_select">
                                                                    <option value="">-- Pilih dari judul yang diajukan
                                                                        --</option>
                                                                    <option value="{{ $pengajuan->judul1 }}">
                                                                        {{ $pengajuan->judul1 }}</option>
                                                                    <option value="{{ $pengajuan->judul2 }}">
                                                                        {{ $pengajuan->judul2 }}</option>
                                                                    <option value="{{ $pengajuan->judul3 }}">
                                                                        {{ $pengajuan->judul3 }}</option>
                                                                </select>
                                                                <input type="text"
                                                                    class="form-control @error('judul_approved') is-invalid @enderror"
                                                                    id="judul_approved" name="judul_approved"
                                                                    value="{{ old('judul_approved') }}" required>
                                                                @error('judul_approved')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                                <div class="form-text">
                                                                    <i class="bi bi-info-circle me-1"></i>
                                                                    Pilih dari judul yang ada atau tuliskan judul baru
                                                                </div>
                                                            </div>

                                                            <div class="mb-4">
                                                                <label for="dosen_id" class="form-label fw-medium">
                                                                    Dosen Pembimbing
                                                                </label>
                                                                <select
                                                                    class="form-select @error('dosen_id') is-invalid @enderror"
                                                                    id="dosen_id" name="dosen_id" required>
                                                                    <option value="">-- Pilih Dosen Pembimbing --
                                                                    </option>
                                                                    @foreach ($dosen as $d)
                                                                        <option value="{{ $d->id }}"
                                                                            {{ old('dosen_id') == $d->id ? 'selected' : '' }}>
                                                                            {{ $d->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('dosen_id')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="d-grid gap-2">
                                                                <button type="submit" class="btn btn-success btn-lg">
                                                                    <i class="bi bi-check-lg me-2"></i>Setujui Pengajuan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Form Penolakan -->
                                            <div class="col-md-6">
                                                <div class="card h-100 border-danger shadow-sm">
                                                    <div class="card-header bg-danger text-white p-3">
                                                        <h6 class="mb-0 fw-bold">
                                                            <i class="bi bi-x-circle me-2"></i>Tolak Pengajuan
                                                        </h6>
                                                    </div>
                                                    <div class="card-body p-4">
                                                        <form
                                                            action="{{ route('kajur.judul-ta.reject', $pengajuan->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="mb-4">
                                                                <label for="alasan_penolakan"
                                                                    class="form-label fw-medium">Alasan Penolakan</label>
                                                                <textarea class="form-control @error('alasan_penolakan') is-invalid @enderror" id="alasan_penolakan"
                                                                    name="alasan_penolakan" rows="5" required>{{ old('alasan_penolakan') }}</textarea>
                                                                @error('alasan_penolakan')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="d-grid gap-2">
                                                                <button type="submit" class="btn btn-danger btn-lg">
                                                                    <i class="bi bi-x-lg me-2"></i>Tolak Pengajuan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($pengajuan->status == 'approved' || $pengajuan->status == 'finalized')
                                        <!-- Panel Dosen Pembimbing -->
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-header bg-light py-3">
                                                <h6 class="mb-0 fw-bold text-primary">
                                                    <i class="bi bi-person-check me-2"></i>Dosen Pembimbing
                                                </h6>
                                            </div>
                                            <div class="card-body p-4">
                                                <form
                                                    action="{{ route('kajur.judul-ta.assignPembimbing', $pengajuan->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="row align-items-end">
                                                        <div class="col-md-8">
                                                            <label for="dosen_id" class="form-label mb-3 fw-medium">
                                                                Pilih Dosen Pembimbing
                                                            </label>
                                                            <select class="form-select form-select-lg" id="dosen_id"
                                                                name="dosen_id" required>
                                                                @foreach ($dosen as $d)
                                                                    <option value="{{ $d->id }}">
                                                                        {{ $d->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button class="btn btn-primary w-100" type="submit">
                                                                <i class="bi bi-arrow-repeat me-2"></i>Ubah Pembimbing
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        @if ($pengajuan->status == 'approved')
                                            <!-- Panel Finalisasi -->
                                            <div class="card border-primary shadow-sm mb-4">
                                                <div class="card-header bg-primary text-white py-3">
                                                    <h6 class="mb-0 fw-bold">
                                                        <i class="bi bi-shield-check me-2"></i>Finalisasi Judul
                                                    </h6>
                                                </div>
                                                <div class="card-body p-4">
                                                    <form action="{{ route('dosen.bimbingan.finalize', $pengajuan->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin memfinalisasi judul tugas akhir ini?');">
                                                        @csrf
                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-primary btn-lg">
                                                                <i class="bi bi-check-circle me-2"></i>Finalisasi Judul
                                                                Tugas Akhir
                                                            </button>
                                                            <div class="form-text mt-2 text-center">
                                                                <i class="bi bi-info-circle me-1"></i>
                                                                Finalisasi akan mengunci judul dan menerbitkan surat tugas
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    <!-- Panel Riwayat Revisi (jika ada) -->
                                    @if (isset($revisi) && $revisi->isNotEmpty())
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-light py-3">
                                                <h6 class="mb-0 fw-bold text-primary">
                                                    <i class="bi bi-clock-history me-2"></i>Riwayat Revisi
                                                </h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="text-center" width="5%">No</th>
                                                                <th width="15%">Tanggal</th>
                                                                <th width="20%">Dari</th>
                                                                <th>Catatan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($revisi as $key => $item)
                                                                <tr>
                                                                    <td class="text-center">{{ $key + 1 }}</td>
                                                                    <td>
                                                                        <i class="bi bi-calendar-date text-muted me-1"></i>
                                                                        {{ $item->created_at->format('d M Y H:i') }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->user && $item->user->role == 'mahasiswa')
                                                                            <span class="badge bg-info text-dark">
                                                                                <i class="bi bi-person me-1"></i>
                                                                                {{ $item->user->name }} (Mahasiswa)
                                                                            </span>
                                                                        @elseif($item->user)
                                                                            <span class="badge bg-warning text-dark">
                                                                                <i class="bi bi-person-badge me-1"></i>
                                                                                {{ $item->user->name }} (Dosen)
                                                                            </span>
                                                                        @else
                                                                            <span class="badge bg-secondary">
                                                                                User tidak tersedia
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $item->catatan ?? ($item->isi_revisi ?? 'Tidak ada catatan') }}
                                                                    </td>
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
            </main>
            @include('template.footer')
        </div>
    </div>

    @include('template.script')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script untuk dropdown judul
            const judulSelect = document.getElementById('judul_approved_select');
            const judulInput = document.getElementById('judul_approved');

            if (judulSelect && judulInput) {
                judulSelect.addEventListener('change', function() {
                    if (this.value) {
                        judulInput.value = this.value;
                    }
                });
            }

            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
