```blade
{{-- resources/views/dosen/bimbingan/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Bimbingan Tugas Akhir</h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($bimbingan->isEmpty())
                        <div class="alert alert-info">
                            Belum ada mahasiswa yang ditugaskan untuk dibimbing.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Judul Tugas Akhir</th>
                                        <th>Status</th>
                                        <th>Tanggal Ditugaskan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bimbingan as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->judulTA->mahasiswa->name }}</td>
                                            <td>
                                                @if($item->judulTA->judul_approved)
                                                    {{ $item->judulTA->judul_approved }}
                                                @else
                                                    {{ $item->judulTA->judul1 }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->judulTA->status == 'submitted')
                                                    <span class="badge bg-info">Diajukan</span>
                                                @elseif($item->judulTA->status == 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($item->judulTA->status == 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @elseif($item->judulTA->status == 'finalized')
                                                    <span class="badge bg-primary">Difinalisasi</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td>
                                                <a href="{{ route('dosen.bimbingan.show', $item->judul_ta_id) }}" class="btn btn-sm btn-info">Detail</a>
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
```