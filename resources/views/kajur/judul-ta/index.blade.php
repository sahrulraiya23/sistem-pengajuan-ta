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
                                        {{-- TAB NAVIGATION --}}
                                        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                            @foreach (['submitted' => 'Diajukan', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'finalized' => 'Difinalisasi'] as $tabId => $label)
                                                <li class="nav-item" role="presentation">
                                                    <button
                                                        class="nav-link @if ($loop->first) active @endif"
                                                        id="{{ $tabId }}-tab" data-bs-toggle="tab"
                                                        data-bs-target="#{{ $tabId }}" type="button"
                                                        role="tab" aria-controls="{{ $tabId }}"
                                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                        {{ $label }}
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>

                                        {{-- TAB CONTENT --}}
                                        <div class="tab-content" id="myTabContent">
                                            @php
                                                $tabs = [
                                                    'submitted' => ['label' => 'Diajukan'],
                                                    'approved' => ['label' => 'Disetujui'],
                                                    'rejected' => ['label' => 'Ditolak'],
                                                    'finalized' => ['label' => 'Difinalisasi'],
                                                ];
                                            @endphp

                                            @foreach ($tabs as $status => $info)
                                                <div class="tab-pane fade @if ($loop->first) show active @endif"
                                                    id="{{ $status }}" role="tabpanel"
                                                    aria-labelledby="{{ $status }}-tab">
                                                    @php
                                                        // Menggunakan $pengajuan dan $item->status
                                                        $filtered = $pengajuan->filter(function ($item) use ($status) {
                                                            return $item->status == $status;
                                                        });
                                                    @endphp

                                                    @if ($filtered->isEmpty())
                                                        <div class="alert alert-light text-center">
                                                            Tidak ada pengajuan dengan status
                                                            {{ strtolower($info['label']) }}.
                                                        </div>
                                                    @else
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Nama Mahasiswa</th>
                                                                        <th>Judul Pilihan 1</th>
                                                                        <th>Tanggal Pengajuan</th>
                                                                        <th>Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($filtered as $key => $item)
                                                                        <tr>
                                                                            <td>{{ $key + 1 }}</td>
                                                                            <td>{{ $item->mahasiswa->name ?? 'N/A' }}
                                                                            </td>
                                                                            <td>{{ $item->judul1 }}</td>
                                                                            <td>{{ $item->created_at->format('d M Y') }}
                                                                            </td>
                                                                            <td>
                                                                                <a href="{{ route('kajur.judul-ta.show', $item->id) }}"
                                                                                    class="btn btn-sm btn-info">Detail</a>
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
</body>

</html>
