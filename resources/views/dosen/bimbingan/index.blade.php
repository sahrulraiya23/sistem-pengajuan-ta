{{-- Ganti semua isi file Anda dengan kode ini --}}

@include('template.css')
@include('template.nav')

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('template.sidenav')
    </div>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 py-4">
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h3 class="text-center font-weight-light my-4">Proposal untuk Konsultasi</h3>
                    </div>
                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Judul Awal Diajukan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ====================================================== --}}
                                    {{-- BAGIAN INI TELAH DIUBAH TOTAL DAN DIPERBAIKI --}}
                                    {{-- ====================================================== --}}
                                    @forelse ($pengajuan as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            {{-- Data diakses langsung dari $item (Model JudulTA) --}}
                                            <td>{{ $item->mahasiswa->nomor_induk ?? 'N/A' }}</td>
                                            <td>{{ $item->mahasiswa->name ?? 'Mahasiswa tidak ditemukan' }}</td>
                                            <td>{{ $item->judul1 }}</td>
                                            <td>
                                                {{-- Status juga diakses langsung dan pasti ada --}}
                                                <span class="badge bg-primary">Disetujui untuk Konsultasi</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('dosen.bimbingan.show', $item->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i> Detail & Beri Catatan
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Pesan ini akan muncul jika tidak ada proposal sama sekali --}}
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                Saat ini tidak ada proposal yang ditugaskan kepada Anda untuk
                                                konsultasi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </main>
        @include('template.footer')
    </div>
</div>

@include('template.script')
