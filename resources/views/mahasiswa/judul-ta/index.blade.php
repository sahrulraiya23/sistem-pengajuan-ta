{{-- resources/views/mahasiswa/judul-ta/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Pengajuan Judul Tugas Akhir</h5>
                            <a href="{{ route('mahasiswa.judul-ta.create') }}" class="btn btn-primary">Ajukan Judul Baru</a>
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

                        @if ($pengajuan->isEmpty())
                            <div class="alert alert-info">
                                Anda belum mengajukan judul tugas akhir. Silakan klik tombol "Ajukan Judul Baru" untuk
                                memulai.
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
@endsection
