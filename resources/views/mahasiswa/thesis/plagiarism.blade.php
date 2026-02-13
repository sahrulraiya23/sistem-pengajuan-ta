<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Cek Plagiarisme Judul</title>
    @include('template.css')
    <style>
        .page-header-dark .page-header-title {
            color: #fff;
        }

        .page-header-dark .page-header-subtitle {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Styling Progress Bar yang lebih tebal */
        .progress {
            height: 1.5rem;
            border-radius: 1rem;
            background-color: #e9ecef;
        }

        .progress-bar {
            border-radius: 1rem;
            font-weight: bold;
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
                                        <div class="page-header-icon text-white-50"><i data-feather="shield"></i></div>
                                        Cek Kemiripan Judul
                                    </h1>
                                    <div class="page-header-subtitle">
                                        Periksa tingkat kemiripan rencana judul Anda dengan arsip penelitian sebelumnya.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- 2. KONTEN UTAMA --}}
                <div class="container-xl px-4 mt-n10">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">

                            {{-- Card Form Input --}}
                            <div class="card mb-4 shadow border-0 rounded-lg">
                                <div class="card-header bg-white py-3 fw-bold">
                                    <i class="me-2 text-primary" data-feather="edit-3"></i> Masukkan Judul
                                </div>
                                <div class="card-body p-4">
                                    <form method="POST" action="{{ route('mahasiswa.plagiarism.submit') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="title" class="form-label small text-muted fw-bold">Judul Tugas Akhir</label>
                                            <textarea name="title" id="title" rows="4"
                                                class="form-control form-control-lg @error('title') is-invalid @enderror"
                                                placeholder="Ketikkan judul lengkap rencana penelitian Anda di sini..."
                                                required>{{ old('title', $inputTitle ?? '') }}</textarea>
                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                                <i class="me-2" data-feather="search"></i> Analisis Kemiripan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @if (isset($maxSimilarity))
                            {{-- LOGIKA WARNA STATUS --}}
                            @php
                            $colorClass = 'success';
                            $iconStatus = 'check-circle';
                            $statusText = 'Aman - Tingkat kemiripan rendah.';

                            if ($maxSimilarity > 75) {
                            $colorClass = 'danger';
                            $iconStatus = 'alert-octagon';
                            $statusText = 'Bahaya - Terlalu mirip dengan judul yang ada.';
                            } elseif ($maxSimilarity > 40) {
                            $colorClass = 'warning';
                            $iconStatus = 'alert-triangle';
                            $statusText = 'Perhatian - Ada kemiripan yang cukup signifikan.';
                            }
                            @endphp

                            {{-- Card Hasil Analisis --}}
                            <div class="card mb-4 shadow border-0 rounded-lg">
                                <div class="card-header bg-{{ $colorClass }} text-white fw-bold py-3">
                                    <i class="me-2" data-feather="bar-chart-2"></i> Hasil Analisis
                                </div>
                                <div class="card-body p-4 text-center">

                                    <div class="mb-4">
                                        <i class="text-{{ $colorClass }} mb-2" data-feather="{{ $iconStatus }}" style="width: 48px; height: 48px;"></i>
                                        <h3 class="fw-bold text-{{ $colorClass }}">{{ $statusText }}</h3>
                                    </div>

                                    <div class="row justify-content-center mb-4">
                                        <div class="col-md-10">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="fw-bold text-dark">Skor Kemiripan Tertinggi</span>
                                                <span class="fw-bold text-{{ $colorClass }}">{{ $maxSimilarity }}%</span>
                                            </div>
                                            <div class="progress shadow-sm">
                                                <div class="progress-bar bg-{{ $colorClass }} progress-bar-striped progress-bar-animated"
                                                    role="progressbar"
                                                    style="width: {{ $maxSimilarity }}%"
                                                    aria-valuenow="{{ $maxSimilarity }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ $maxSimilarity }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Daftar Detail Kemiripan --}}
                            @if (!empty($similarities))
                            <div class="card mb-4 shadow border-0 rounded-lg">
                                <div class="card-header bg-white fw-bold py-3">
                                    <i class="me-2 text-primary" data-feather="list"></i> Rincian Dokumen Mirip
                                </div>
                                <div class="list-group list-group-flush">
                                    @foreach ($similarities as $item)
                                    @php
                                    $percent = $item['percentage'];
                                    $badgeColor = $percent > 70 ? 'danger' : ($percent > 40 ? 'warning' : 'success');
                                    @endphp

                                    <a href="{{ route('mahasiswa.thesis.show', $item['thesis']->id) }}" class="list-group-item list-group-item-action p-4">
                                        <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 text-primary fw-bold">{{ $item['thesis']->title }}</h6>
                                            <span class="badge bg-{{ $badgeColor }} rounded-pill p-2 px-3">{{ $percent }}% Mirip</span>
                                        </div>
                                        <div class="small text-muted mb-2">
                                            <i class="me-1" data-feather="user" style="width: 14px;"></i> {{ $item['thesis']->author }} &middot;
                                            <i class="me-1 ms-2" data-feather="calendar" style="width: 14px;"></i> {{ $item['thesis']->year }} &middot;
                                            <span class="fw-bold text-dark ms-2">{{ $item['thesis']->program_study }}</span>
                                        </div>
                                        <div class="small text-muted fst-italic">
                                            Klik untuk melihat detail dokumen ini.
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="alert alert-success shadow-sm border-0">
                                <div class="d-flex align-items-center">
                                    <i class="me-3" data-feather="check-circle" style="width: 24px; height: 24px;"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">Selamat!</h5>
                                        <p class="mb-0">Tidak ditemukan judul yang memiliki kemiripan signifikan (>15%) dengan judul Anda.</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Disclaimer --}}
                            <div class="alert alert-light border shadow-sm">
                                <div class="d-flex">
                                    <i class="me-3 text-muted" data-feather="info"></i>
                                    <div>
                                        <h6 class="alert-heading fw-bold">Catatan Penting</h6>
                                        <p class="small text-muted mb-0">
                                            Alat ini hanya membandingkan kemiripan teks judul secara harfiah.
                                            Hasil skor rendah tidak menjamin bebas plagiarisme secara substansi/konten.
                                            Selalu konsultasikan judul Anda dengan Dosen Pembimbing.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif

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
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
</body>

</html>