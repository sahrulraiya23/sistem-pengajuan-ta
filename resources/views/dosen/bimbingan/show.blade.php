<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Bimbingan Tugas Akhir</title>
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
                <div class="container mt-4">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Detail Bimbingan Tugas Akhir</h5>
                                        <a href="{{ route('dosen.bimbingan.index') }}"
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

                                    @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                    @endif


                                    <h6 class="border-bottom pb-2 mb-3">Informasi Mahasiswa</h6>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <th width="40%">Nama Mahasiswa</th>
                                                    <td>{{ $pengajuan->mahasiswa->name ?? 'Data tidak tersedia' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email</th>
                                                    <td>{{ $pengajuan->mahasiswa->email ?? 'Data tidak tersedia' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>NIM</th>
                                                    <td>{{ $pengajuan->mahasiswa->nomor_induk ?? '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <h6 class="border-bottom pb-2 mb-3">Informasi Tugas Akhir</h6>
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <table class="table table-sm">
                                                <tr>
                                                    <th width="20%">Status</th>
                                                    <th width="20%">Status</th>
                                                    <td>
                                                        @php
                                                        $badgeClass = '';
                                                        $statusText = '';

                                                        switch ($pengajuan->status) {
                                                        case \App\Models\JudulTA::STATUS_DRAFT:
                                                        $badgeClass = 'bg-secondary';
                                                        $statusText = 'Draft';
                                                        break;

                                                        case \App\Models\JudulTA::STATUS_SUBMITTED:
                                                        $badgeClass = 'bg-warning';
                                                        $statusText = 'Diajukan (Menunggu Kajur)';
                                                        break;

                                                        case \App\Models\JudulTA::STATUS_APPROVED_FOR_CONSULTATION:
                                                        $badgeClass = 'bg-info';
                                                        $statusText =
                                                        'Disetujui Awal (Menunggu Saran Anda)';
                                                        break;

                                                        case \App\Models\JudulTA::STATUS_REVISED:
                                                        $badgeClass = 'bg-danger';
                                                        $statusText = 'Revisi (Mahasiswa Merevisi)';
                                                        break;

                                                        case \App\Models\JudulTA::STATUS_SUBMIT_REVISED:
                                                        $badgeClass = 'bg-primary';
                                                        $statusText =
                                                        'Diajukan Kembali (Menunggu Persetujuan Anda)';
                                                        break;

                                                        case \App\Models\JudulTA::STATUS_APPROVED:
                                                        $badgeClass = 'bg-success';
                                                        $statusText =
                                                        'Disetujui Dosen (Menunggu Finalisasi Kajur)';
                                                        break;

                                                        case \App\Models\JudulTA::STATUS_FINALIZED:
                                                        $badgeClass = 'bg-success';
                                                        $statusText = 'Finalisasi';
                                                        break;

                                                        case \App\Models\JudulTA::STATUS_REJECTED:
                                                        $badgeClass = 'bg-dark';
                                                        $statusText = 'Ditolak';
                                                        break;

                                                        default:
                                                        $badgeClass = 'bg-secondary';
                                                        $statusText = ucfirst(
                                                        str_replace('_', ' ', $pengajuan->status),
                                                        );
                                                        break;
                                                        }
                                                        @endphp

                                                        <span
                                                            class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>Tanggal Pengajuan</th>
                                                    <td>{{ $pengajuan->created_at->format('d M Y H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Judul Pilihan 1</th>
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
                                                    <th>Judul Revisi Terbaru</th>
                                                    <td><strong
                                                            class="text-primary">{{ $pengajuan->judul_revisi }}</strong>
                                                    </td>
                                                </tr>
                                                @endif
                                                @if ($pengajuan->catatan_dosen_saran)
                                                <tr>
                                                    <th>Catatan Dosen Saran</th>
                                                    <td>{{ $pengajuan->catatan_dosen_saran }}</td>
                                                </tr>
                                                @endif
                                                @if ($pengajuan->alasan_penolakan && $pengajuan->status == \App\Models\JudulTA::STATUS_REJECTED)
                                                <tr>
                                                    <th>Alasan Ditolak</th>
                                                    <td><strong
                                                            class="text-danger">{{ $pengajuan->alasan_penolakan }}</strong>
                                                    </td>
                                                </tr>
                                                @endif
                                            </table>
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

                                    {{-- FORM UNTUK DOSEN MENYETUJUI/MENOLAK PENGAJUAN KEMBALI (STATUS: submit_revisi) --}}
                                    @if ($pengajuan->status == \App\Models\JudulTA::STATUS_SUBMIT_REVISED)
                                    <h6 class="border-bottom pb-2 mb-3">Proses Pengajuan Kembali dari Mahasiswa</h6>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <form
                                                action="{{ route('dosen.bimbingan.process-resubmission', $pengajuan->id) }}"
                                                method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="tindakan_resubmit"
                                                        class="form-label">Tindakan</label>
                                                    <select
                                                        class="form-select @error('tindakan') is-invalid @enderror"
                                                        id="tindakan_resubmit" name="tindakan" required>
                                                        <option value="">Pilih Tindakan</option>
                                                        <option value="approve_resubmission"
                                                            {{ old('tindakan') == 'approve_resubmission' ? 'selected' : '' }}>
                                                            Setujui Pengajuan Kembali</option>
                                                        <option value="reject_resubmission"
                                                            {{ old('tindakan') == 'reject_resubmission' ? 'selected' : '' }}>
                                                            Tolak Pengajuan Kembali (Kembalikan ke Revisi)</option>
                                                    </select>
                                                    @error('tindakan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3" id="judul_approved_by_dosen_field"
                                                    style="display:none;">
                                                    <label for="judul_approved_by_dosen" class="form-label">Judul
                                                        Disetujui oleh Anda</label>
                                                    <input type="text"
                                                        class="form-control @error('judul_approved_by_dosen') is-invalid @enderror"
                                                        id="judul_approved_by_dosen" name="judul_approved_by_dosen"
                                                        value="{{ old('judul_approved_by_dosen', $pengajuan->judul_revisi ?? $pengajuan->judul1) }}">
                                                    <div class="form-text">Isi dengan judul final yang Anda setujui.
                                                    </div>
                                                    @error('judul_approved_by_dosen')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="catatan_resubmit" class="form-label">Catatan
                                                        (Opsional)</label>
                                                    <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan_resubmit" name="catatan"
                                                        rows="3">{{ old('catatan') }}</textarea>
                                                    @error('catatan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <button type="submit" class="btn btn-primary">Proses</button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- FORM UNTUK DOSEN SARAN MENYETUJUI 1 DARI 3 JUDUL DAN MEMBERIKAN REVISI AWAL (STATUS: approved_for_consultation) --}}
                                    @if ($pengajuan->status == \App\Models\JudulTA::STATUS_APPROVED_FOR_CONSULTATION)
                                    @php
                                    // Ambil data pivot untuk dosen yang sedang login
                                    $myPivot = $pengajuan->dosenSarans->find(auth()->id())->pivot ?? null;
                                    $sudahSetuju = $myPivot && $myPivot->status_persetujuan === 'approved';
                                    @endphp

                                    <h6 class="border-bottom pb-2 mb-3">Persetujuan & Revisi Awal Judul</h6>

                                    @if ($sudahSetuju)
                                    {{-- TAMPILAN JIKA SUDAH MENYETUJUI --}}
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                                        <div>
                                            <strong>Anda sudah memberikan review untuk pengajuan ini.</strong><br>
                                            Status pengajuan akan berubah menjadi "Revisi" setelah dosen saran lainnya juga memberikan persetujuan.
                                        </div>
                                    </div>

                                    {{-- Opsional: Tampilkan apa yang sudah Anda pilih --}}
                                    <div class="card mb-4 bg-light">
                                        <div class="card-body">
                                            <h6 class="fw-bold">Review Anda:</h6>
                                            <ul class="list-unstyled mb-0">
                                                <li><strong>Judul Dipilih:</strong> {{ $myPivot->judul_pilihan }}</li>
                                                <li><strong>Catatan:</strong> {{ $myPivot->catatan }}</li>
                                            </ul>
                                        </div>
                                    </div>

                                    @else
                                    {{-- TAMPILAN JIKA BELUM MENYETUJUI (FORM) --}}
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <form action="{{ route('dosen.bimbingan.process-initial-review', $pengajuan->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="judul_pilihan_dosen_saran" class="form-label">Pilih Judul yang Disetujui</label>
                                                    <select class="form-select @error('judul_pilihan_dosen_saran') is-invalid @enderror"
                                                        id="judul_pilihan_dosen_saran" name="judul_pilihan_dosen_saran" required>
                                                        <option value="">-- Pilih salah satu judul --</option>
                                                        <option value="{{ $pengajuan->judul1 }}"
                                                            {{ old('judul_pilihan_dosen_saran') == $pengajuan->judul1 ? 'selected' : '' }}>
                                                            {{ $pengajuan->judul1 }}
                                                        </option>
                                                        @if ($pengajuan->judul2)
                                                        <option value="{{ $pengajuan->judul2 }}"
                                                            {{ old('judul_pilihan_dosen_saran') == $pengajuan->judul2 ? 'selected' : '' }}>
                                                            {{ $pengajuan->judul2 }}
                                                        </option>
                                                        @endif
                                                        @if ($pengajuan->judul3)
                                                        <option value="{{ $pengajuan->judul3 }}"
                                                            {{ old('judul_pilihan_dosen_saran') == $pengajuan->judul3 ? 'selected' : '' }}>
                                                            {{ $pengajuan->judul3 }}
                                                        </option>
                                                        @endif
                                                    </select>
                                                    @error('judul_pilihan_dosen_saran')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="catatan_initial_revisi" class="form-label">Catatan/Revisi Awal</label>
                                                    <textarea class="form-control @error('catatan_initial_revisi') is-invalid @enderror"
                                                        id="catatan_initial_revisi" name="catatan_initial_revisi" rows="4" required>{{ old('catatan_initial_revisi') }}</textarea>
                                                    <div class="form-text">Berikan catatan atau revisi pertama Anda untuk judul yang dipilih.</div>
                                                    @error('catatan_initial_revisi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <button type="submit" class="btn btn-success">Setujui & Kirim Revisi Awal</button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                    @endif

                                    {{-- Riwayat Revisi --}}
                                    @if (isset($revisi) && $revisi->isNotEmpty())
                                    <h6 class="border-bottom pb-2 mb-3">Riwayat Revisi</h6>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
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
                                                            <td>{{ $item->created_at->format('d M Y H:i') }}
                                                            </td>
                                                            <td>
                                                                @if ($item->user && $item->user->role == 'mahasiswa')
                                                                <span
                                                                    class="badge bg-info">{{ $item->user->name }}
                                                                    (Mahasiswa)
                                                                </span>
                                                                @elseif($item->user)
                                                                <span
                                                                    class="badge bg-warning">{{ $item->user->name }}
                                                                    (Dosen)</span>
                                                                @else
                                                                <span class="badge bg-secondary">User tidak
                                                                    tersedia</span>
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
            const tindakanResubmitSelect = document.getElementById('tindakan_resubmit');
            const judulApprovedByDosenField = document.getElementById('judul_approved_by_dosen_field');

            function toggleJudulApprovedField() {
                if (tindakanResubmitSelect.value === 'approve_resubmission') {
                    judulApprovedByDosenField.style.display = 'block';
                } else {
                    judulApprovedByDosenField.style.display = 'none';
                }
            }

            tindakanResubmitSelect.addEventListener('change', toggleJudulApprovedField);
            toggleJudulApprovedField(); // Panggil saat halaman dimuat untuk setel status awal
        });
    </script>
</body>

</html>