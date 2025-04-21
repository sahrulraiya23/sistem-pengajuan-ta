```blade
{{-- resources/views/mahasiswa/revisi/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Revisi Judul Tugas Akhir</h5>
                            <a href="{{ route('judul-ta.show', $id) }}" class="btn btn-secondary">Kembali</a>
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
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Status</th>
                                        <td>
                                            @if ($pengajuan->status == 'submitted')
                                                <span class="badge bg-info">Diajukan</span>
                                            @elseif($pengajuan->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($pengajuan->status == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @elseif($pengajuan->status == 'finalized')
                                                <span class="badge bg-primary">Difinalisasi</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Judul yang Disetujui</th>
                                        <td>{{ $pengajuan->judul_approved }}</td>
                                    </tr>
                                    @if ($pengajuan->judul_revisi)
                                        <tr>
                                            <th>Judul Revisi Sebelumnya</th>
                                            <td>{{ $pengajuan->judul_revisi }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <h6 class="border-bottom pb-2 mb-3">Kirim Revisi</h6>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <form action="{{ route('mahasiswa.revisi.store', $id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="judul_revisi" class="form-label">Judul Revisi</label>
                                        <input type="text"
                                            class="form-control @error('judul_revisi') is-invalid @enderror"
                                            id="judul_revisi" name="judul_revisi"
                                            value="{{ old('judul_revisi', $pengajuan->judul_revisi) }}" required>
                                        @error('judul_revisi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="catatan" class="form-label">Catatan Revisi</label>
                                        <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="4"
                                            required>{{ old('catatan') }}</textarea>
                                        @error('catatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Jelaskan perubahan yang Anda lakukan dan alasannya</div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Kirim Revisi</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @if ($revisi->isNotEmpty())
                            <h6 class="border-bottom pb-2 mb-3">Riwayat Revisi</h6>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```
