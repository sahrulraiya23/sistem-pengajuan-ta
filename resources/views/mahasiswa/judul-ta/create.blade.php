```blade
{{-- resources/views/mahasiswa/judul-ta/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Ajukan Judul Tugas Akhir</h5>
                            <a href="{{ route('mahasiswa.judul-ta.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('mahasiswa.judul-ta.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="judul1" class="form-label">Judul Pilihan 1</label>
                                <input type="text" class="form-control @error('judul1') is-invalid @enderror"
                                    id="judul1" name="judul1" value="{{ old('judul1') }}" required>
                                @error('judul1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Masukkan judul pilihan pertama Anda</div>
                            </div>

                            <div class="mb-3">
                                <label for="judul2" class="form-label">Judul Pilihan 2</label>
                                <input type="text" class="form-control @error('judul2') is-invalid @enderror"
                                    id="judul2" name="judul2" value="{{ old('judul2') }}" required>
                                @error('judul2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Masukkan judul pilihan kedua Anda</div>
                            </div>

                            <div class="mb-3">
                                <label for="judul3" class="form-label">Judul Pilihan 3</label>
                                <input type="text" class="form-control @error('judul3') is-invalid @enderror"
                                    id="judul3" name="judul3" value="{{ old('judul3') }}" required>
                                @error('judul3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Masukkan judul pilihan ketiga Anda</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Ajukan Judul</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```
