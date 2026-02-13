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
    @switch($pengajuan->status)
        @case('draft')
            <span class="badge bg-secondary rounded-pill px-3">
                <i class="bi bi-file-earmark me-1"></i>Draft
            </span>
            @break

        @case('submitted')
            <span class="badge bg-warning text-dark rounded-pill px-3">
                <i class="bi bi-send me-1"></i>Diajukan (Menunggu Kajur)
            </span>
            @break

        @case('approved_for_consultation')
            <span class="badge bg-info text-dark rounded-pill px-3">
                <i class="bi bi-people me-1"></i>Konsultasi Dosen
            </span>
            @break

        @case('revisi')
            <span class="badge bg-danger rounded-pill px-3">
                <i class="bi bi-exclamation-circle me-1"></i>Perlu Revisi
            </span>
            @break

        @case('submit_revisi')
        @case('re_submitted_after_consultation')
            <span class="badge bg-primary rounded-pill px-3">
                <i class="bi bi-arrow-repeat me-1"></i>Revisi Diajukan
            </span>
            @break

        @case('menunggu_persetujuan_dosen')
            <span class="badge bg-warning text-dark rounded-pill px-3">
                <i class="bi bi-hourglass-split me-1"></i>Menunggu Dosen
            </span>
            @break

        @case('approved')
            <span class="badge bg-success rounded-pill px-3">
                <i class="bi bi-check-circle me-1"></i>Disetujui Dosen
            </span>
            @break

        @case('finalized')
            <span class="badge bg-success rounded-pill px-3">
                <i class="bi bi-lock-fill me-1"></i>Final (Selesai)
            </span>
            @break

        @case('rejected')
            <span class="badge bg-dark rounded-pill px-3">
                <i class="bi bi-x-circle me-1"></i>Ditolak
            </span>
            @break

        @default
            <span class="badge bg-secondary rounded-pill px-3">
                {{ $pengajuan->status }}
            </span>
    @endswitch
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
                                                    @if ($pengajuan->dosenSarans->count() > 0)
                                                        <tr>
                                                            <th class="text-secondary">Dosen Saran</th>
                                                            <td>
                                                                @foreach ($pengajuan->dosenSarans as $dosenSaran)
                                                                    <span
                                                                        class="badge bg-info text-dark">{{ $dosenSaran->name }}</span>
                                                                @endforeach
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
                                    </div>
                                </div>
                            </div>

                            @if ($pengajuan->status == 'submitted')
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="card h-100 border-info shadow-sm">
                                            <div class="card-header bg-info text-white p-3">
                                                <h6 class="mb-0 fw-bold">
                                                    <i class="bi bi-person-plus me-2"></i>Tunjuk Dosen Saran
                                                </h6>
                                            </div>
                                            <div class="card-body p-4">
                                                <form
                                                    action="{{ route('kajur.judul-ta.processSubmission', $pengajuan->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="tindakan" value="tunjuk_dosen">

                                                    {{-- Dosen Saran 1 --}}
                                                    <div class="mb-3">
                                                        <label for="dosen_saran_id_1" class="form-label fw-medium">
                                                            Pilih Dosen Saran 1
                                                        </label>
                                                        <select
                                                            class="form-select @error('dosen_saran_ids.0') is-invalid @enderror"
                                                            id="dosen_saran_id_1" name="dosen_saran_ids[]" required>
                                                            <option value="">-- Pilih Dosen Saran 1 --
                                                            </option>
                                                            @foreach ($dosen as $d)
                                                                <option value="{{ $d->id }}"
                                                                    {{ old('dosen_saran_ids.0') == $d->id || ($pengajuan->dosenSarans->count() > 0 && $pengajuan->dosenSarans[0]->id == $d->id) ? 'selected' : '' }}>
                                                                    {{ $d->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('dosen_saran_ids.0')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- Dosen Saran 2 --}}
                                                    <div class="mb-3">
                                                        <label for="dosen_saran_id_2" class="form-label fw-medium">
                                                            Pilih Dosen Saran 2 (Opsional)
                                                        </label>
                                                        <select
                                                            class="form-select @error('dosen_saran_ids.1') is-invalid @enderror"
                                                            id="dosen_saran_id_2" name="dosen_saran_ids[]">
                                                            <option value="">-- Pilih Dosen Saran 2 --
                                                            </option>
                                                            @foreach ($dosen as $d)
                                                                <option value="{{ $d->id }}"
                                                                    {{ old('dosen_saran_ids.1') == $d->id || ($pengajuan->dosenSarans->count() > 1 && $pengajuan->dosenSarans[1]->id == $d->id) ? 'selected' : '' }}>
                                                                    {{ $d->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('dosen_saran_ids.1')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="catatan_kajur_tunjuk_dosen"
                                                            class="form-label fw-medium">
                                                            Catatan (Opsional)
                                                        </label>
                                                        <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan_kajur_tunjuk_dosen" name="catatan"
                                                            rows="3">{{ old('catatan', $pengajuan->catatan) }}</textarea>
                                                        @error('catatan')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="d-grid gap-2">
                                                        <button type="submit" class="btn btn-info btn-lg text-white">
                                                            <i class="bi bi-person-check me-2"></i>Tunjuk Dosen
                                                            Saran
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card h-100 border-danger shadow-sm">
                                            <div class="card-header bg-danger text-white p-3">
                                                <h6 class="mb-0 fw-bold">
                                                    <i class="bi bi-x-circle me-2"></i>Tolak Pengajuan
                                                </h6>
                                            </div>
                                            <div class="card-body p-4">
                                                <form
                                                    action="{{ route('kajur.judul-ta.processSubmission', $pengajuan->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="tindakan" value="tolak">
                                                    <div class="mb-4">
                                                        <label for="alasan_penolakan" class="form-label fw-medium">Alasan
                                                            Penolakan</label>
                                                        <textarea class="form-control @error('alasan_penolakan') is-invalid @enderror" id="alasan_penolakan"
                                                            name="alasan_penolakan" rows="3" required>{{ old('alasan_penolakan') }}</textarea>
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
                            @elseif ($pengajuan->status == \App\Models\JudulTA::STATUS_APPROVED)
                                <div class="row g-4 mb-4">
                                    <div class="col-md-12">
                                        <div class="card h-100 border-success shadow-sm">
                                            <div class="card-header bg-success text-white p-3">
                                                <h6 class="mb-0 fw-bold">
                                                    <i class="bi bi-check-circle me-2"></i>Finalisasi Persetujuan
                                                    Judul
                                                </h6>
                                            </div>
                                            <div class="card-body p-4">
                                                <form action="{{ route('kajur.judul-ta.finalize', $pengajuan->id) }}"
                                                    {{-- <<< UBAH RUTE ACTION DI SINI --}} method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    {{-- Hapus hidden input tindakan karena ini akan ditangani oleh route finalize --}}
                                                    {{-- <input type="hidden" name="tindakan" value="final_approve"> --}}
                                                    <div class="mb-3">
                                                        <label for="judul_approved_select" class="form-label fw-medium">
                                                            Pilih Judul yang Disetujui
                                                        </label>
                                                        <select
                                                            class="form-select form-select-lg mb-2 @error('judul_approved_by_kajur') is-invalid @enderror"
                                                            id="judul_approved_select"
                                                            name="judul_approved_by_kajur_from_select">
                                                            <option value="">-- Pilih dari judul yang
                                                                diajukan atau masukkan baru --</option>
                                                            <option value="{{ $pengajuan->judul1 }}"
                                                                {{ old('judul_approved_by_kajur_from_select', $pengajuan->judul_approved) == $pengajuan->judul1 ? 'selected' : '' }}>
                                                                {{ $pengajuan->judul1 }}</option>
                                                            <option value="{{ $pengajuan->judul2 }}"
                                                                {{ old('judul_approved_by_kajur_from_select', $pengajuan->judul_approved) == $pengajuan->judul2 ? 'selected' : '' }}>
                                                                {{ $pengajuan->judul2 }}</option>
                                                            <option value="{{ $pengajuan->judul3 }}"
                                                                {{ old('judul_approved_by_kajur_from_select', $pengajuan->judul_approved) == $pengajuan->judul3 ? 'selected' : '' }}>
                                                                {{ $pengajuan->judul3 }}</option>
                                                            @if ($pengajuan->judul_revisi)
                                                                <option value="{{ $pengajuan->judul_revisi }}"
                                                                    {{ old('judul_approved_by_kajur_from_select', $pengajuan->judul_approved) == $pengajuan->judul_revisi ? 'selected' : '' }}>
                                                                    {{ $pengajuan->judul_revisi }} (Revisi)
                                                                </option>
                                                            @endif
                                                        </select>
                                                        <input type="text"
                                                            class="form-control @error('judul_approved_by_kajur') is-invalid @enderror"
                                                            id="judul_approved_manual"
                                                            name="judul_approved_by_kajur_manual"
                                                            value="{{ old('judul_approved_by_kajur_manual', !in_array($pengajuan->judul_approved, [$pengajuan->judul1, $pengajuan->judul2, $pengajuan->judul3, $pengajuan->judul_revisi]) && $pengajuan->judul_approved ? $pengajuan->judul_approved : '') }}"
                                                            placeholder="Atau masukkan judul baru secara manual">
                                                        @error('judul_approved_by_kajur')
                                                            {{-- Error ini akan muncul jika kedua input kosong atau ada masalah validasi dari backend --}}
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                        <div class="form-text">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            Pilih dari judul yang ada, judul revisi, atau tuliskan
                                                            judul baru.
                                                        </div>
                                                    </div>



                                                    @php
                                                        // Ambil data pembimbing 1 yang sudah ada (jika ada) dengan aman
                                                        $pembimbing1 = optional(optional($pengajuan)->pembimbings)
                                                            ->where('tipe_pembimbing', 'pembimbing_1')
                                                            ->first();
                                                        $dosen1_id = optional($pembimbing1)->dosen_id;
                                                    @endphp
                                                    <div class="mb-4">
                                                        <label for="dosen_pembimbing_1_id" class="form-label fw-medium">
                                                            Dosen Pembimbing 1
                                                        </label>
                                                        <select
                                                            class="form-select @error('dosen_pembimbing_1_id') is-invalid @enderror"
                                                            id="dosen_pembimbing_1_id" name="dosen_pembimbing_1_id"
                                                            required>
                                                            <option value="">-- Pilih Dosen Pembimbing 1 --
                                                            </option>
                                                            @foreach ($dosen as $d)
                                                                <option value="{{ $d->id }}"
                                                                    {{ old('dosen_pembimbing_1_id', $dosen1_id) == $d->id ? 'selected' : '' }}>
                                                                    {{ $d->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('dosen_pembimbing_1_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- Dosen Pembimbing 2 --}}
                                                    @php
                                                        // Ambil data pembimbing 2 yang sudah ada (jika ada) dengan aman
                                                        $pembimbing2 = optional(optional($pengajuan)->pembimbings)
                                                            ->where('tipe_pembimbing', 'pembimbing_2')
                                                            ->first();
                                                        $dosen2_id = optional($pembimbing2)->dosen_id;
                                                    @endphp
                                                    <div class="mb-4">
                                                        <label for="dosen_pembimbing_2_id" class="form-label fw-medium">
                                                            Dosen Pembimbing 2
                                                        </label>
                                                        <select
                                                            class="form-select @error('dosen_pembimbing_2_id') is-invalid @enderror"
                                                            id="dosen_pembimbing_2_id" name="dosen_pembimbing_2_id"
                                                            required>
                                                            <option value="">-- Pilih Dosen Pembimbing 2 --
                                                            </option>
                                                            @foreach ($dosen as $d)
                                                                <option value="{{ $d->id }}"
                                                                    {{ old('dosen_pembimbing_2_id', $dosen2_id) == $d->id ? 'selected' : '' }}>
                                                                    {{ $d->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('dosen_pembimbing_2_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="d-grid gap-2">
                                                        <button type="submit" class="btn btn-success btn-lg">
                                                            <i class="bi bi-check-lg me-2"></i>Setujui dan Tetapkan
                                                            Pembimbing
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif {{-- Akhir dari @elseif ($pengajuan->status == \App\Models\JudulTA::STATUS_APPROVED) --}}
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
            const judulApprovedSelect = document.getElementById('judul_approved_select');
            const judulApprovedManual = document.getElementById('judul_approved_manual');

            function toggleManualInput() {
                // Jika tidak ada pilihan di select (nilai kosong), aktifkan input manual
                if (judulApprovedSelect.value === '') {
                    judulApprovedManual.removeAttribute('readonly');
                    // Atur required untuk input manual jika select kosong dan tidak ada nilai old() untuk manual
                    if (!judulApprovedManual.value) {
                        judulApprovedManual.setAttribute('required', 'required');
                    }
                } else {
                    // Jika ada pilihan di select, nonaktifkan dan kosongkan input manual
                    judulApprovedManual.setAttribute('readonly', 'readonly');
                    judulApprovedManual.value = '';
                    judulApprovedManual.removeAttribute('required');
                }
            }

            // Panggil saat halaman dimuat untuk mengatur kondisi awal
            toggleManualInput();

            // Panggil setiap kali pilihan select berubah
            judulApprovedSelect.addEventListener('change', toggleManualInput);

            // Jika ada nilai old('judul_approved_by_kajur_manual'), pastikan select tidak dipilih
            if (judulApprovedManual.value) {
                judulApprovedSelect.value = ''; // Kosongkan select jika input manual ada nilainya
                toggleManualInput(); // Pastikan input manual aktif
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
