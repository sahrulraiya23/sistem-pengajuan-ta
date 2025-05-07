<main>
    <header class="py-10 mb-4 bg-gradient-primary-to-secondary">
        <div class="container-xl px-4">
            <div class="text-center">
                <h1 class="text-white">Selamat Datang Di Sistem Pengajuan Judul Tugas Akhir</h1>
                <p class="lead mb-0 text-white-50">Teknik Informatika - Universitas Halu Oleo</p>
            </div>
        </div>
    </header>



    <div class="container-xl px-4">
        <h2 class="mt-5 mb-0">Dashboards</h2>
        <p>Three dashboard examples to get you started!</p>
        <hr class="mt-0 mb-4" />

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card card-waves mb-4 mt-5">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Pengajuan Judul Tugas Akhir</h5>
                                <a href="{{ route('mahasiswa.judul-ta.create') }}" class="btn btn-primary">Ajukan Judul
                                    Baru</a>
                            </div>
                        </div>
                        <div class="card-body p-5">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($pengajuan->isEmpty())
                                <div class="alert alert-info">
                                    Anda belum mengajukan judul tugas akhir. Silakan klik tombol "Ajukan
                                    Judul Baru" untuk memulai.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul Pilihan 1</th>
                                                <th>Status</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuan as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $item->judul1 }}</td>
                                                    <td>
                                                        @if ($item->status == 'submitted')
                                                            <span class="badge bg-info">Diajukan</span>
                                                        @elseif($item->status == 'approved')
                                                            <span class="badge bg-success">Disetujui</span>
                                                        @elseif($item->status == 'rejected')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @elseif($item->status == 'finalized')
                                                            <span class="badge bg-primary">Difinalisasi</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('mahasiswa.judul-ta.show', $item->id) }}"
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
            </div>
        </div>
    </div>


</main>
