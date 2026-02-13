<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Arsip Tesis - Mahasiswa</title>
    @include('template.css')

    <style>
        /* CSS Tambahan untuk Kerapian */
        .page-header-dark .page-header-title {
            color: #fff;
        }
        .page-header-dark .page-header-subtitle {
            color: rgba(255, 255, 255, 0.8);
        }
        
        /* Efek Hover pada Card */
        .lift {
            transition: all 0.2s ease-in-out;
        }
        .lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(31, 45, 65, 0.15) !important;
        }

        /* Styling Badge Modern */
        .badge-pill-soft {
            border-radius: 50rem;
            padding-left: 1em;
            padding-right: 1em;
            font-weight: 600;
        }

        /* Memastikan Icon Stack Rapi */
        .icon-stack {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background-color: rgba(0, 97, 242, 0.1);
            color: #0061f2;
            margin-right: 0.75rem;
        }
    </style>
</head>

<body class="nav-fixed">
    @include('template.nav')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('template.sidenav')
        </div>

        <div id="layoutSidenav_content">
            <main>
                {{-- 1. HEADER HALAMAN --}}
                <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                    <div class="container-xl px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-4">
                                    <h1 class="page-header-title fw-bold">
                                        <div class="page-header-icon text-white-50"><i data-feather="book-open"></i></div>
                                        Arsip Tugas Akhir
                                    </h1>
                                    <div class="page-header-subtitle">
                                        Jelajahi koleksi Skripsi, Tesis, dan Disertasi mahasiswa secara lengkap.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- 2. KONTEN UTAMA --}}
                <div class="container-xl px-4 mt-n10">
                    
                    {{-- Card Filter --}}
                    <div class="card mb-4 shadow border-0 rounded-lg">
                        <div class="card-header bg-white fw-bold py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="icon-stack bg-primary-soft text-primary me-2">
                                    <i data-feather="filter" style="width:16px; height:16px;"></i>
                                </div>
                                Filter & Pencarian
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form method="GET" action="{{ route('mahasiswa.thesis.index') }}">
                                <div class="row gx-3">
                                    {{-- Kolom Pencarian --}}
                                    <div class="col-md-4 mb-3">
                                        <label for="search" class="small text-muted fw-bold mb-1">Kata Kunci</label>
                                        <div class="input-group input-group-joined">
                                            <span class="input-group-text text-muted"><i data-feather="search"></i></span>
                                            <input class="form-control ps-0" type="text" name="search" id="search"
                                                value="{{ request('search') }}" placeholder="Judul atau Penulis..." aria-label="Search">
                                        </div>
                                    </div>

                                    {{-- Kolom Jenis --}}
                                    <div class="col-md-3 mb-3">
                                        <label for="type" class="small text-muted fw-bold mb-1">Jenis Karya</label>
                                        <select class="form-select" name="type" id="type">
                                            <option value="">Semua Jenis</option>
                                            @foreach ($types as $key => $value)
                                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Kolom Tahun --}}
                                    <div class="col-md-3 mb-3">
                                        <label for="year" class="small text-muted fw-bold mb-1">Tahun Terbit</label>
                                        <select class="form-select" name="year" id="year">
                                            <option value="">Semua Tahun</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Tombol Filter --}}
                                    <div class="col-md-2 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                                            Terapkan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Info Hasil Pencarian --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h5 mb-0 text-gray-800 fw-bold">Daftar Dokumen</h2>
                        <span class="badge bg-white text-primary shadow-sm p-2 px-3 rounded-pill fw-normal border">
                            Menampilkan <b>{{ $theses->count() }}</b> dari <b>{{ $theses->total() }}</b> data
                        </span>
                    </div>

                    {{-- Grid Card Hasil --}}
                    <div class="row gx-4">
                        @forelse($theses as $thesis)
                            <div class="col-md-6 col-xl-4 mb-4">
                                {{-- Card Item --}}
                                <div class="card h-100 lift shadow-sm border-0 rounded-lg overflow-hidden">
                                    {{-- Garis Indikator Warna di Atas Card --}}
                                    @php
                                        $stripeColor = match ($thesis->type) {
                                            'skripsi' => 'bg-primary',
                                            'tesis' => 'bg-warning',
                                            'disertasi' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <div class="{{ $stripeColor }}" style="height: 4px;"></div>

                                    <div class="card-body p-4 d-flex flex-column">
                                        {{-- Header Card: Badge & Tahun --}}
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge {{ $stripeColor }} bg-opacity-10 text-dark badge-pill-soft">
                                                {{ ucfirst($thesis->type) }}
                                            </span>
                                            <div class="small text-muted fw-bold d-flex align-items-center">
                                                <i class="me-1 text-gray-400" data-feather="calendar" style="width: 14px; height: 14px;"></i>
                                                {{ $thesis->year }}
                                            </div>
                                        </div>

                                        {{-- Judul --}}
                                        <h5 class="card-title mb-2">
                                            <a href="{{ route('mahasiswa.thesis.show', $thesis->id) }}" class="text-dark text-decoration-none stretched-link fw-bold">
                                                {{ Str::limit($thesis->title, 60) }}
                                            </a>
                                        </h5>

                                        {{-- Meta Data: Penulis & Prodi --}}
                                        <div class="mt-auto pt-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="me-2 text-primary" data-feather="user" style="width: 16px; height: 16px;"></i>
                                                <span class="small fw-bold text-gray-600 text-truncate">{{ $thesis->author }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="me-2 text-gray-400" data-feather="book" style="width: 16px; height: 16px;"></i>
                                                <span class="small text-muted text-truncate">{{ $thesis->program_study }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Footer Card --}}
                                    <div class="card-footer bg-light border-top-0 py-3 px-4">
                                        <div class="d-flex align-items-center justify-content-between small">
                                            <span class="text-muted fst-italic">
                                                Diupload {{ $thesis->created_at->diffForHumans() }}
                                            </span>
                                            <div class="text-primary fw-bold d-flex align-items-center">
                                                Detail <i class="ms-1" data-feather="arrow-right" style="width: 14px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card card-body text-center py-5 border-0 shadow-sm rounded-lg">
                                    <div class="mb-3">
                                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle p-4">
                                            <i class="text-muted" data-feather="search" style="width: 36px; height: 36px;"></i>
                                        </div>
                                    </div>
                                    <h4 class="h5 text-gray-800 fw-bold">Data Tidak Ditemukan</h4>
                                    <p class="text-muted mb-3">Maaf, kami tidak dapat menemukan dokumen yang sesuai dengan kriteria pencarian Anda.</p>
                                    <a href="{{ route('mahasiswa.thesis.index') }}" class="btn btn-primary btn-sm shadow-sm px-4">
                                        Reset Filter
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if ($theses->hasPages())
                        <div class="d-flex justify-content-center mt-2 mb-5">
                            {{ $theses->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </main>

            @include('template.footer')
        </div>
    </div>

    @include('template.script')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
</body>

</html>