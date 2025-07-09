{{-- resources/views/kajur/judul-ta/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Daftar Pengajuan Judul - Kajur</title>
    @include('template.css')
</head>

<body class="nav-fixed">
    @include('template.nav')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('template.sidenav')
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mt-4">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="mb-0">Daftar Pengajuan Judul Tugas Akhir</h5>
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
                                        <div class="alert alert-info text-center">
                                            Belum ada pengajuan judul tugas akhir dari mahasiswa.
                                        </div>
                                    @else
                                        {{-- Definisi Tabs (dibuat sekali saja di awal) --}}
                                        @php
                                            $tabs = [
                                                \App\Models\JudulTA::STATUS_SUBMITTED => [
                                                    'label' => 'Diajukan',
                                                    'icon' => 'bi-hourglass',
                                                ],
                                                \App\Models\JudulTA::STATUS_APPROVED_FOR_CONSULTATION => [
                                                    'label' => 'Menunggu Dosen Saran',
                                                    'icon' => 'bi-chat-dots',
                                                ],
                                                \App\Models\JudulTA::STATUS_APPROVED => [
                                                    'label' => 'Siap Difinalisasi',
                                                    'icon' => 'bi-check-circle',
                                                ],
                                                \App\Models\JudulTA::STATUS_REVISED => [
                                                    'label' => 'Direvisi Mahasiswa',
                                                    'icon' => 'bi-pencil-square',
                                                ],
                                                \App\Models\JudulTA::STATUS_SUB => [
                                                    'label' => 'Mahasiswa Mengajukan Revisi',
                                                    'icon' => 'bi-arrow-repeat',
                                                ],
                                                \App\Models\JudulTA::STATUS_REJECTED => [
                                                    'label' => 'Ditolak',
                                                    'icon' => 'bi-x-circle',
                                                ],
                                                \App\Models\JudulTA::STATUS_FINALIZED => [
                                                    'label' => 'Difinalisasi',
                                                    'icon' => 'bi-lock',
                                                ],
                                            ];
                                        @endphp

                                        {{-- TAB NAVIGATION --}}
                                        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                            @foreach ($tabs as $status_key => $info)
                                                <li class="nav-item" role="presentation">
                                                    <button
                                                        class="nav-link @if ($loop->first) active @endif"
                                                        id="{{ $status_key }}-tab" data-bs-toggle="tab"
                                                        data-bs-target="#{{ $status_key }}" type="button"
                                                        role="tab" aria-controls="{{ $status_key }}"
                                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                        <i class="{{ $info['icon'] ?? 'bi-info-circle' }} me-1"></i>
                                                        {{ $info['label'] }}
                                                        @php
                                                            // Menghitung jumlah item untuk badge di setiap tab
                                                            $count = $pengajuan
                                                                ->filter(function ($item) use ($status_key) {
                                                                    return $item->status == $status_key;
                                                                })
                                                                ->count();
                                                        @endphp
                                                        @if ($count > 0)
                                                            <span
                                                                class="badge rounded-pill bg-primary ms-1">{{ $count }}</span>
                                                        @endif
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>

                                        {{-- TAB CONTENT --}}
                                        <div class="tab-content" id="myTabContent">
                                            @foreach ($tabs as $status_key => $info)
                                                <div class="tab-pane fade @if ($loop->first) show active @endif"
                                                    id="{{ $status_key }}" role="tabpanel"
                                                    aria-labelledby="{{ $status_key }}-tab">
                                                    @php
                                                        // Filter koleksi $pengajuan berdasarkan status saat ini
                                                        $filteredPengajuan = $pengajuan->filter(function ($item) use (
                                                            $status_key,
                                                        ) {
                                                            return $item->status == $status_key;
                                                        });
                                                    @endphp

                                                    @if ($filteredPengajuan->isEmpty())
                                                        <div class="alert alert-light text-center">
                                                            Tidak ada pengajuan dengan status
                                                            **{{ strtolower($info['label']) }}**.
                                                        </div>
                                                    @else
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Nama Mahasiswa</th>
                                                                        <th>NIM</th>
                                                                        <th>Judul Pilihan 1</th>
                                                                        {{-- Kolom Dosen Saran/Pembimbing disesuaikan --}}
                                                                        <th>Dosen Terkait</th>
                                                                        <th>Tanggal Pengajuan</th>
                                                                        <th>Status Saat Ini</th>
                                                                        <th>Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($filteredPengajuan as $key => $item)
                                                                        <tr>
                                                                            <td>{{ $key + 1 }}</td>
                                                                            <td>{{ $item->mahasiswa->name ?? 'N/A' }}
                                                                            </td>
                                                                            <td>{{ $item->mahasiswa->nomor_induk ?? '-' }}
                                                                            </td>
                                                                            <td>{{ $item->judul1 }}</td>
                                                                            <td>
                                                                                @if ($item->status == \App\Models\JudulTA::STATUS_FINALIZED && $item->pembimbing && $item->pembimbing->dosen)
                                                                                    <span class="badge bg-success">
                                                                                        <i
                                                                                            class="bi bi-person-check me-1"></i>
                                                                                        {{ $item->pembimbing->dosen->name }}
                                                                                        (Pembimbing)
                                                                                    </span>
                                                                                @elseif($item->dosenSarans->count() > 0)
                                                                                    @foreach ($item->dosenSarans as $dosenSaran)
                                                                                        <span
                                                                                            class="badge bg-info text-dark">
                                                                                            <i
                                                                                                class="bi bi-person-lines-fill me-1"></i>
                                                                                            {{ $dosenSaran->name }}
                                                                                            (Saran)
                                                                                        </span><br>
                                                                                    @endforeach
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $item->created_at->format('d M Y H:i') }}
                                                                            </td>
                                                                            <td>
                                                                                {{-- Menampilkan badge status --}}
                                                                                @switch($item->status)
                                                                                    @case(\App\Models\JudulTA::STATUS_SUBMITTED)
                                                                                        <span
                                                                                            class="badge bg-info rounded-pill px-3"><i
                                                                                                class="bi bi-hourglass me-1"></i>
                                                                                            Diajukan</span>
                                                                                    @break

                                                                                    @case(\App\Models\JudulTA::STATUS_APPROVED_FOR_CONSULTATION)
                                                                                        <span
                                                                                            class="badge bg-warning text-dark rounded-pill px-3"><i
                                                                                                class="bi bi-chat-dots me-1"></i>
                                                                                            Menunggu Dosen Saran</span>
                                                                                    @break

                                                                                    @case(\App\Models\JudulTA::STATUS_REVISED)
                                                                                        <span
                                                                                            class="badge bg-secondary rounded-pill px-3"><i
                                                                                                class="bi bi-pencil-square me-1"></i>
                                                                                            Revisi</span>
                                                                                    @break

                                                                                    @case(\App\Models\JudulTA::STATUS_SUBMIT_REVISED)
                                                                                        <span
                                                                                            class="badge bg-primary rounded-pill px-3"><i
                                                                                                class="bi bi-arrow-repeat me-1"></i>
                                                                                            Mengajukan Revisi</span>
                                                                                    @break

                                                                                    @case(\App\Models\JudulTA::STATUS_APPROVED)
                                                                                        <span
                                                                                            class="badge bg-success rounded-pill px-3"><i
                                                                                                class="bi bi-check-circle me-1"></i>
                                                                                            Disetujui Dosen Saran</span>
                                                                                    @break

                                                                                    @case(\App\Models\JudulTA::STATUS_REJECTED)
                                                                                        <span
                                                                                            class="badge bg-danger rounded-pill px-3"><i
                                                                                                class="bi bi-x-circle me-1"></i>
                                                                                            Ditolak</span>
                                                                                    @break

                                                                                    @case(\App\Models\JudulTA::STATUS_FINALIZED)
                                                                                        <span
                                                                                            class="badge bg-dark rounded-pill px-3"><i
                                                                                                class="bi bi-lock me-1"></i>
                                                                                            Difinalisasi</span>
                                                                                    @break

                                                                                    @default
                                                                                        <span
                                                                                            class="badge bg-light text-secondary rounded-pill px-3">{{ $item->status }}</span>
                                                                                @endswitch
                                                                            </td>
                                                                            <td>
                                                                                <a href="{{ route('kajur.judul-ta.show', $item->id) }}"
                                                                                    class="btn btn-sm btn-info text-white">
                                                                                    <i class="bi bi-eye me-1"></i>
                                                                                    Detail
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
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
    {{-- Script untuk auto-dismiss alerts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000); // Alert akan hilang setelah 5 detik
            });
        });
    </script>
</body>

</html>
