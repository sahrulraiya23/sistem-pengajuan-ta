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
                                    @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                            role="alert">
                                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                            <div>{{ session('error') }}</div>
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
                                                                {{-- LOGIKA STATUS YANG BENAR --}}
                                                                @if ($pengajuan->status == 'submitted')
                                                                    <span
                                                                        class="badge bg-info text-dark rounded-pill px-3"><i
                                                                            class="bi bi-hourglass-split me-1"></i>Diajukan
                                                                        (Menunggu Aksi)</span>
                                                                @elseif ($pengajuan->status == 'konsultasi')
                                                                    <span class="badge bg-primary rounded-pill px-3"><i
                                                                            class="bi bi-chat-right-text me-1"></i>Tahap
                                                                        Konsultasi</span>
                                                                @elseif($pengajuan->status == 'approved')
                                                                    <span class="badge bg-success rounded-pill px-3"><i
                                                                            class="bi bi-check-circle me-1"></i>Disetujui</span>
                                                                @elseif($pengajuan->status == 'rejected')
                                                                    <span class="badge bg-danger rounded-pill px-3"><i
                                                                            class="bi bi-x-circle me-1"></i>Ditolak</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-secondary rounded-pill px-3">{{ ucfirst($pengajuan->status) }}</span>
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

                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-light py-3">
                                            <h6 class="mb-0 fw-bold text-primary">
                                                <i class="bi bi-book me-2"></i>Judul yang Diajukan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <ol class="list-group list-group-flush">
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start">
                                                    <div class="ms-2 me-auto">
                                                        <div class="fw-bold">Pilihan 1</div>
                                                        {{ $pengajuan->judul1 }}
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start">
                                                    <div class="ms-2 me-auto">
                                                        <div class="fw-bold">Pilihan 2</div>
                                                        {{ $pengajuan->judul2 }}
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start">
                                                    <div class="ms-2 me-auto">
                                                        <div class="fw-bold">Pilihan 3</div>
                                                        {{ $pengajuan->judul3 }}
                                                    </div>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>

                                    {{-- TAMPILKAN INFO JIKA SUDAH ADA TINDAKAN --}}
                                    @if ($pengajuan->status == 'konsultasi' && $pengajuan->dosenSaran)
                                        <div class="card border-info shadow-sm mb-4">
                                            <div class="card-header bg-info-subtle py-3">
                                                <h6 class="mb-0 fw-bold text-info-emphasis">
                                                    <i class="bi bi-person-check me-2"></i>Dosen Saran Telah Ditunjuk
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <p>Ketua Jurusan telah menunjuk dosen berikut untuk memberikan saran dan
                                                    konsultasi awal kepada mahasiswa:</p>
                                                <h5 class="text-center fw-bold">{{ $pengajuan->dosenSaran->name }}</h5>
                                                @if ($pengajuan->catatan_kajur)
                                                    <hr>
                                                    <p class="mb-1 fw-bold">Catatan dari Kajur:</p>
                                                    <blockquote class="blockquote mb-0">
                                                        <p class="fs-6"><em>"{{ $pengajuan->catatan_kajur }}"</em></p>
                                                    </blockquote>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif($pengajuan->status == 'rejected')
                                        <div class="alert alert-danger">
                                            <h5 class="alert-heading"><i
                                                    class="bi bi-exclamation-triangle-fill me-2"></i>Proposal Ditolak</h5>
                                            <p>Alasan Penolakan: <strong>{{ $pengajuan->alasan_penolakan }}</strong></p>
                                        </div>
                                    @endif
                                    @if ($pengajuan->status == 'submitted')
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-light py-3">
                                                <h6 class="mb-0 fw-bold text-primary"><i
                                                        class="bi bi-ui-checks-grid me-2"></i>Form Tindakan Ketua Jurusan
                                                </h6>
                                            </div>
                                            <div class="card-body p-4">
                                                <form action="{{ route('kajur.judul-ta.process', $pengajuan->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    {{-- Form Tunjuk Dosen Saran --}}
                                                    <div class="mb-3">
                                                        <label for="dosen_saran_id" class="form-label fw-medium">1. Tunjuk
                                                            Dosen Saran</label>
                                                        <select name="dosen_saran_id"
                                                            class="form-select @error('dosen_saran_id') is-invalid @enderror">
                                                            <option value="" disabled selected>-- Pilih dosen untuk
                                                                konsultasi awal --</option>
                                                            @foreach ($dosen as $d)
                                                                <option value="{{ $d->id }}">{{ $d->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('dosen_saran_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- Form Tolak Proposal --}}
                                                    <div class="mb-4">
                                                        <label for="alasan_penolakan" class="form-label fw-medium">2.
                                                            Tolak Proposal (Isi jika ingin menolak)</label>
                                                        <textarea class="form-control @error('alasan_penolakan') is-invalid @enderror" name="alasan_penolakan" rows="3"
                                                            placeholder="Tuliskan alasan penolakan di sini..."></textarea>
                                                        @error('alasan_penolakan')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- Catatan Opsional --}}
                                                    <div class="mb-4">
                                                        <label for="catatan" class="form-label fw-medium">Catatan
                                                            Tambahan (Opsional)</label>
                                                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan untuk mahasiswa atau dosen..."></textarea>
                                                    </div>

                                                    <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-4">
                                                        <button type="submit" name="tindakan" value="tolak"
                                                            class="btn btn-danger btn-lg"
                                                            onclick="return confirm('Apakah Anda yakin ingin MENOLAK proposal ini? Pastikan alasan penolakan sudah diisi.')">
                                                            <i class="bi bi-x-lg me-1"></i>Tolak
                                                        </button>
                                                        <button type="submit" name="tindakan" value="tunjuk_dosen"
                                                            class="btn btn-primary btn-lg">
                                                            <i class="bi bi-send-check me-1"></i>Tunjuk Dosen Saran
                                                        </button>
                                                    </div>
                                                </form>
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

    {{-- SCRIPT LAMA SUDAH TIDAK DIPERLUKAN LAGI --}}
