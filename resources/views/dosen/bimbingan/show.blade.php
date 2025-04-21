```blade
{{-- resources/views/dosen/bimbingan/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Detail Bimbingan Tugas Akhir</h5>
                            <a href="{{ route('dosen.bimbingan.index') }}" class="btn btn-secondary">Kembali</a>
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

                        <h6 class="border-bottom pb-2 mb-3">Informasi Mahasiswa</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Nama Mahasiswa</th>
                                        <td>{{ $pengajuan->mahasiswa->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $pengajuan->mahasiswa->email }}</td>
                                    </tr>
                                    <tr>

                                                                            <th>NIM</th>
                                                                            <td>{{ $pengajuan->mahasiswa->nim ?? '-' }}</td>
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
                                                                            <td>
                                                                                @if($pengajuan->status == 'submitted')
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
                                                                        @if($pengajuan->judul_approved)
                                                                        <tr>
                                                                            <th>Judul yang Disetujui</th>
                                                                            <td><strong class="text-success">{{ $pengajuan->judul_approved }}</strong></td>
                                                                        </tr>
                                                                        @endif
                                                                        @if($pengajuan->judul_revisi)
                                                                        <tr>
                                                                            <th>Judul Revisi Terbaru</th>
                                                                            <td><strong class="text-primary">{{ $pengajuan->judul_revisi }}</strong></td>
                                                                        </tr>
                                                                        @endif
                                                                    </table>
                                                                </div>
                                                            </div>
                                        
                                                            @if($pengajuan->status == 'approved' && !$pengajuan->status == 'finalized')
                                                            <div class="row mb-4">
                                                                <div class="col-12">
                                                                    <form action="{{ route('dosen.bimbingan.finalize', $pengajuan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memfinalisasi judul tugas akhir ini?');">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-primary btn-lg">
                                                                            <i class="bi bi-check-circle"></i> Finalisasi Judul Tugas Akhir
                                                                        </button>
                                                                        <div class="form-text">
                                                                            Finalisasi akan mengunci judul dan menerbitkan surat tugas
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            @endif
                                        
                                                            <div class="row mb-4">
                                                                <div class="col-md-12">
                                                                    <div class="card">
                                                                        <div class="card-header bg-light">
                                                                            <h6 class="mb-0">Kirim Revisi</h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <form action="{{ route('dosen.revisi.store', $pengajuan->id) }}" method="POST">
                                                                                @csrf
                                                                                <div class="mb-3">
                                                                                    <label for="isi_revisi" class="form-label">Isi Revisi</label>
                                                                                    <textarea class="form-control @error('isi_revisi') is-invalid @enderror" id="isi_revisi" name="isi_revisi" rows="4" required>{{ old('isi_revisi') }}</textarea>
                                                                                    @error('isi_revisi')
                                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                        
                                                                                <div class="mb-3">
                                                                                    <label for="tanggal_revisi" class="form-label">Tanggal Revisi</label>
                                                                                    <input type="date" class="form-control @error('tanggal_revisi') is-invalid @enderror" id="tanggal_revisi" name="tanggal_revisi" value="{{ old('tanggal_revisi', date('Y-m-d')) }}" required>
                                                                                    @error('tanggal_revisi')
                                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                        
                                                                                <div class="mb-3">
                                                                                    <label for="status" class="form-label">Status</label>
                                                                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                                                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Menunggu Revisi</option>
                                                                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Revisi Selesai</option>
                                                                                    </select>
                                                                                    @error('status')
                                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                        
                                                                                <div class="d-grid">
                                                                                    <button type="submit" class="btn btn-success">Kirim Revisi</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                        
                                                            @if($revisi->isNotEmpty())
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
                                                                                @foreach($revisi as $key => $item)
                                                                                <tr>
                                                                                    <td>{{ $key + 1 }}</td>
                                                                                    <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                                                                    <td>
                                                                                        @if($item->user->role == 'mahasiswa')
                                                                                            <span class="badge bg-info">{{ $item->user->name }} (Mahasiswa)</span>
                                                                                        @else
                                                                                            <span class="badge bg-warning">{{ $item->user->name }} (Dosen)</span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>{{ $item->catatan ?? $item->isi_revisi }}</td>
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
