```blade
{{-- resources/views/kajur/judul-ta/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Pengajuan Judul</h5>
                        <a href="{{ route('kajur.judul-ta.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Status</th>
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
                            </table>
                        </div>
                    </div>

                    <h6 class="border-bottom pb-2 mb-3">Judul yang Diajukan</h6>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Judul Pilihan 1</th>
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
                                @if($pengajuan->alasan_penolakan)
                                <tr>
                                    <th>Alasan Penolakan</th>
                                    <td><strong class="text-danger">{{ $pengajuan->alasan_penolakan }}</strong></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($pengajuan->status == 'submitted')
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">Setujui Pengajuan</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('kajur.judul-ta.approve', $pengajuan->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="judul_approved" class="form-label">Judul yang Disetujui</label>
                                            <select class="form-select mb-2 @error('judul_approved') is-invalid @enderror" id="judul_approved_select">
                                                <option value="">-- Pilih dari judul yang diajukan --</option>
                                                <option value="{{ $pengajuan->judul1 }}">{{ $pengajuan->judul1 }}</option>
                                                <option value="{{ $pengajuan->judul2 }}">{{ $pengajuan->judul2 }}</option>
                                                <option value="{{ $pengajuan->judul3 }}">{{ $pengajuan->judul3 }}</option>
                                            </select>
                                            <input type="text" class="form-control @error('judul_approved') is-invalid @enderror" id="judul_approved" name="judul_approved" value="{{ old('judul_approved') }}" required>
                                            @error('judul_approved')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Pilih dari judul yang ada atau tuliskan judul baru</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="dosen_id" class="form-label">Dosen Pembimbing</label>
                                            <select class="form-select @error('dosen_id') is-invalid @enderror" id="dosen_id" name="dosen_id" required>
                                                <option value="">-- Pilih Dosen Pembimbing --</option>
                                                @foreach($dosen as $d)
                                                    <option value="{{ $d->id }}" {{ old('dosen_id') == $d->id ? 'selected' : '' }}>
                                                        {{ $d->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('dosen_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success">Setujui Pengajuan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">Tolak Pengajuan</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('kajur.judul-ta.reject', $pengajuan->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="alasan_penolakan" class="form-label">Alasan Penolakan</label>
                                            <textarea class="form-control @error('alasan_penolakan') is-invalid @enderror" id="alasan_penolakan" name="alasan_penolakan" rows="4" required>{{ old('alasan_penolakan') }}</textarea>
                                            @error('alasan_penolakan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($pengajuan->status == 'approved' || $pengajuan->status == 'finalized')
                    <h6 class="border-bottom pb-2 mb-3">Dosen Pembimbing</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form action="{{ route('kajur.judul-ta.assignPembimbing', $pengajuan->id) }}" method="POST">
                                @csrf
                                <div class="input-group mb-3">
                                    <select class="form-select" id="dosen_id" name="dosen_id" required>
                                        @foreach($dosen as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-primary" type="submit">Ubah Pembimbing</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const judulSelect = document.getElementById('judul_approved_select');
    const judulInput = document.getElementById('judul_approved');
    
    if (judulSelect && judulInput) {
        judulSelect.addEventListener('change', function() {
            if (this.value) {
                judulInput.value = this.value;
            }
        });
    }
});
</script>
@endsection
```