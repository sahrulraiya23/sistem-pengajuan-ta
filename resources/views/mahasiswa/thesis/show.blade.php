<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Detail Tugas Akhir - {{ $thesis->title }}</title>
    @include('template.css')
    <style>
        /* Custom CSS untuk mempercantik */
        .page-header-dark {
            color: rgba(255, 255, 255, 0.9);
        }
        .text-justify {
            text-align: justify;
        }
        .icon-stack-sm {
            height: 2rem;
            width: 2rem;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        /* Efek hover untuk list group */
        .list-group-item-action:hover {
            background-color: #f8f9fa;
            border-left: 4px solid #0061f2; /* Aksen biru saat hover */
            padding-left: 1.25rem; /* Sedikit geser ke kanan */
            transition: all 0.2s ease-in-out;
        }
        .list-group-item {
            transition: all 0.2s ease-in-out;
            border-left: 4px solid transparent; /* Border transparan default */
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
                {{-- 1. HEADER HALAMAN (GRADIENT & MODERN) --}}
                <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                    <div class="container-xl px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-4">
                                    <h1 class="page-header-title text-white fw-bold">
                                        <div class="page-header-icon text-white-50"><i data-feather="file-text"></i></div>
                                        Detail Tugas Akhir
                                    </h1>
                                    <div class="page-header-subtitle text-white-50">
                                        {{ ucfirst($thesis->type) }} &middot; {{ $thesis->year }} &middot;
                                        {{ $thesis->program_study }}
                                    </div>
                                </div>
                                <div class="col-12 col-xl-auto mt-4">
                                    <a href="{{ route('mahasiswa.thesis.index') }}"
                                        class="btn btn-light text-primary shadow-sm fw-bold">
                                        <i class="me-1" data-feather="arrow-left"></i> Kembali ke Daftar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- 2. KONTEN UTAMA --}}
                <div class="container-xl px-4 mt-n10">
                    <div class="row">
                        {{-- Kolom Kiri: Detail Utama --}}
                        <div class="col-lg-8">
                            <div class="card mb-4 shadow border-0 rounded-lg">
                                <div class="card-header p-4 bg-white border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            @php
                                                $badgeColor = match ($thesis->type) {
                                                    'skripsi' => 'bg-primary',
                                                    'tesis' => 'bg-warning',
                                                    'disertasi' => 'bg-success',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeColor }} bg-opacity-10 text-dark me-2 px-3 py-2 rounded-pill">
                                                {{ strtoupper($thesis->type) }}
                                            </span>
                                            <span class="text-muted small fw-bold">
                                                <i class="me-1" data-feather="calendar" style="width:12px"></i>
                                                {{ $thesis->year }}
                                            </span>
                                        </div>
                                        <span class="text-muted small">
                                            <i class="me-1" data-feather="clock" style="width:12px"></i>
                                            {{ $thesis->created_at->format('d M Y') }}
                                        </span>
                                    </div>

                                    <h2 class="card-title text-primary fw-bold mb-0" style="line-height: 1.4;">{{ $thesis->title }}</h2>
                                </div>

                                <div class="card-body p-4">
                                    {{-- Informasi Penulis & Prodi --}}
                                    <div class="row gx-4 mb-4">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="p-3 bg-light rounded-3 border">
                                                <div class="small text-muted text-uppercase fw-bold mb-1">Penulis</div>
                                                <div class="h6 mb-0 d-flex align-items-center text-dark">
                                                    <div class="icon-stack icon-stack-sm bg-primary text-white me-2">
                                                        <i data-feather="user"></i>
                                                    </div>
                                                    {{ $thesis->author }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded-3 border">
                                                <div class="small text-muted text-uppercase fw-bold mb-1">Program Studi</div>
                                                <div class="h6 mb-0 d-flex align-items-center text-dark">
                                                    <div class="icon-stack icon-stack-sm bg-success text-white me-2">
                                                        <i data-feather="book"></i>
                                                    </div>
                                                    {{ $thesis->program_study }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4 text-muted" />

                                    {{-- Abstrak --}}
                                    <h5 class="text-primary mb-3 fw-bold">
                                        <i class="me-2" data-feather="align-left"></i>Abstrak
                                    </h5>
                                    <div class="bg-light p-4 rounded-3 border border-light">
                                        <p class="card-text text-justify mb-0 text-dark" style="line-height: 1.8; font-size: 1rem;">
                                            {!! nl2br(e($thesis->abstract)) !!}
                                        </p>
                                    </div>
                                </div>

                              
                            </div>
                        </div>

                        {{-- Kolom Kanan: Tugas Akhir Sejenis --}}
                        <div class="col-lg-4">
                            <div class="card shadow border-0 mb-4 rounded-lg">
                                <div class="card-header bg-light border-bottom fw-bold text-dark">
                                    <i class="me-2 text-primary" data-feather="layers"></i>Tugas Akhir Sejenis
                                </div>
                                <div class="list-group list-group-flush">
                                    @php
                                        $similarTheses = \App\Models\Thesis::where('type', $thesis->type)
                                            ->where('id', '!=', $thesis->id)
                                            ->latest()
                                            ->limit(4)
                                            ->get();
                                    @endphp

                                    @forelse ($similarTheses as $similar)
                                        <a href="{{ route('mahasiswa.thesis.show', $similar->id) }}"
                                            class="list-group-item list-group-item-action py-3 border-bottom">
                                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                                <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill">{{ $similar->year }}</span>
                                                <small class="text-muted fst-italic" style="font-size: 0.75rem">
                                                    {{ $similar->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <h6 class="mb-1 text-primary fw-bold text-truncate">{{ $similar->title }}</h6>
                                            <p class="mb-0 small text-muted">
                                                <i class="me-1" data-feather="user" style="width:12px"></i>
                                                {{ $similar->author }}
                                            </p>
                                        </a>
                                    @empty
                                        <div class="list-group-item py-5 text-center text-muted">
                                            <i class="mb-2 text-gray-300" data-feather="slash" style="width: 32px; height: 32px;"></i> <br>
                                            Tidak ada tugas akhir sejenis.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Card Info Bantuan --}}
                            <div class="card shadow border-0 bg-primary text-white rounded-lg">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i data-feather="help-circle" style="width: 48px; height: 48px; opacity: 0.8;"></i>
                                    </div>
                                    <h5 class="text-white fw-bold">Butuh Bantuan?</h5>
                                    <p class="text-white-50 mb-3 small">
                                        Jika Anda menemukan kesalahan data atau indikasi plagiarisme pada dokumen ini, silakan lapor.
                                    </p>
                                    <button class="btn btn-light text-primary btn-sm fw-bold shadow-sm">
                                        Hubungi Admin
                                    </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Render Icon
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });

        function sharePage() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $thesis->title }}',
                    text: 'Lihat tugas akhir: {{ $thesis->title }} oleh {{ $thesis->author }}',
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    // Menggunakan Toast atau Alert yang lebih cantik (opsional, disini alert biasa)
                    alert('Tautan halaman ini telah disalin ke clipboard!');
                });
            }
        }
    </script>
</body>

</html>