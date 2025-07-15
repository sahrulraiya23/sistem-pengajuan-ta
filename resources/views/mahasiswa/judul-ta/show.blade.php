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
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Status:</strong></div>
                                    <div class="col-md-8">
                                        @php
                                            $badgeClass = '';
                                            $statusText = '';
                                            switch ($pengajuan->status) {
                                                case 'submitted':
                                                    $badgeClass = 'bg-warning';
                                                    $statusText = 'Diajukan';
                                                    break;
                                                case 'approved_for_consultation':
                                                    $badgeClass = 'bg-info';
                                                    $statusText = 'Disetujui Awal (Konsultasi Dosen Saran)';
                                                    break;
                                                case 'revisi':
                                                    $badgeClass = 'bg-danger';
                                                    $statusText = 'Revisi';
                                                    break;
                                                case 're_submitted_after_consultation':
                                                    $badgeClass = 'bg-primary';
                                                    $statusText = 'Diajukan Kembali (Menunggu Validasi Final)';
                                                    break;
                                                case 'finalized':
                                                    $badgeClass = 'bg-success';
                                                    $statusText = 'Finalisasi';
                                                    break;
                                                case 'rejected':
                                                    $badgeClass = 'bg-dark';
                                                    $statusText = 'Ditolak';
                                                    break;
                                                default:
                                                    $badgeClass = 'bg-secondary';
                                                    $statusText = 'Tidak Diketahui';
                                                    break;
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                    </div>
                                </div>

                                {{-- Informasi Dosen Saran --}}
                                @if ($pengajuan->dosen_saran_id)
                                    <div class="row mb-3">
                                        <div class="col-md-4"><strong>Dosen Saran:</strong></div>
                                        <div class="col-md-8">
                                            {{ App\Models\User::find($pengajuan->dosen_saran_id)->name ?? '-' }}</div>
                                    </div>
                                @endif

                                {{-- Catatan Kajur --}}
                                @if ($pengajuan->catatan_kajur)
                                    <div class="row mb-3">
                                        <div class="col-md-4"><strong>Catatan Ketua Jurusan:</strong></div>
                                        <div class="col-md-8">{{ $pengajuan->catatan_kajur }}</div>
                                    </div>
                                @endif

                                {{-- Judul Disetujui Final --}}
                                @if ($pengajuan->status == 'finalized' && $pengajuan->judul_approved)
                                    <div class="row mb-3">
                                        <div class="col-md-4"><strong>Judul Disetujui:</strong></div>
                                        <div class="col-md-8 text-success">
                                            <strong>{{ $pengajuan->judul_approved }}</strong>
                                        </div>
                                    </div>
                                @endif

                                {{-- Dosen Pembimbing Utama --}}
                                @if ($pembimbing)
                                    <h6 class="border-bottom pb-2 mb-3 mt-4">Dosen Pembimbing</h6>
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

                                <h6 class="border-bottom pb-2 mb-3 mt-4">Detail Judul dan File</h6>
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-light py-3">
                                                <h6 class="mb-0 fw-bold text-primary">
                                                    <i class="bi bi-book me-2"></i>Judul yang Diajukan
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <tr>
                                                            <th width="20%" class="text-secondary">Judul Pilihan 1
                                                            </th>
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

                                                {{-- File Pendukung --}}
                                                @if ($pengajuan->file_pendukung1 || $pengajuan->file_pendukung2)
                                                    <div class="mt-4">
                                                        <h6 class="mb-2 fw-bold text-primary">
                                                            <i class="bi bi-paperclip me-2"></i>File Pendukung
                                                        </h6>
                                                        <ul class="list-group list-group-flush">
                                                            @if ($pengajuan->file_pendukung1)
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                                                    File Pendukung 1:
                                                                    <a href="{{ Storage::url($pengajuan->file_pendukung1) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        <i class="bi bi-download me-1"></i> Lihat File 1
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if ($pengajuan->file_pendukung2)
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                                                    File Pendukung 2:
                                                                    <a href="{{ Storage::url($pengajuan->file_pendukung2) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        <i class="bi bi-download me-1"></i> Lihat File 2
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Tombol Aksi (Ajukan Kembali) --}}
                                @if ($pengajuan->status == 'revisi')
                                    <div class="mt-4">
                                        <div class="card mb-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">Ajukan Kembali Judul Setelah Revisi</h6>
                                            </div>
                                            <div class="card-body">
                                                <form
                                                    action="{{ route('mahasiswa.judul-ta.re-submit', $pengajuan->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="judul_revisi_input" class="form-label">Judul yang
                                                            Sudah Direvisi</label>
                                                        <textarea class="form-control @error('judul_revisi') is-invalid @enderror" id="judul_revisi_input" name="judul_revisi"
                                                            rows="3" required>{{ old('judul_revisi', $pengajuan->judul_revisi ?? $pengajuan->judul1) }}</textarea>
                                                        <div class="form-text">Masukkan judul final setelah Anda
                                                            merevisi sesuai saran dosen.</div>
                                                        @error('judul_revisi')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Ajukan Kembali ke
                                                        Dosen Saran</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Tombol Cetak Surat --}}
                                @if ($pengajuan->status == 'finalized' && $surat)
                                    <div class="mt-4">
                                        <a href="{{ route('mahasiswa.surat.show', $surat->id) }}"
                                            class="btn btn-primary">
                                            <i class="bi bi-file-earmark-text"></i> Lihat Surat Tugas
                                        </a>
                                    </div>
                                @endif

                                @if ($revisi->isNotEmpty())
                                    <h6 class="border-bottom pb-2 mb-3 mt-4">Riwayat Revisi</h6>
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
