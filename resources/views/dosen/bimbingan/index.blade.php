{{-- resources/views/dosen/bimbingan/index.blade.php --}}

@include('template.css')

@include('template.nav')

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('template.sidenav')
    </div>

    <div id="layoutSidenav_content">
        <div class="container mt-4">
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
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($bimbingan->isEmpty())
                                <div class="alert alert-info">
                                    Belum ada mahasiswa yang ditugaskan untuk dibimbing.
                                </div>
                            @else
                                {{-- TAB NAVIGATION --}}
                                <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                    @foreach (['submitted' => 'Diajukan', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'finalized' => 'Difinalisasi'] as $tabId => $label)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link @if ($loop->first) active @endif"
                                                id="{{ $tabId }}-tab" data-bs-toggle="tab"
                                                data-bs-target="#{{ $tabId }}" type="button" role="tab"
                                                aria-controls="{{ $tabId }}"
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
                                                $filtered = $bimbingan->filter(function ($item) use ($status) {
                                                    return $item->judulTA->status == $status;
                                                });
                                            @endphp

                                            @if ($filtered->isEmpty())
                                                <div class="alert alert-info">
                                                    Tidak ada bimbingan dengan status {{ strtolower($info['label']) }}.
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Mahasiswa</th>
                                                                <th>Judul Tugas Akhir</th>
                                                                <th>Tanggal Ditugaskan</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($filtered as $key => $item)
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td>{{ $item->judulTA->mahasiswa->name }}</td>
                                                                    <td>
                                                                        @if ($status == 'approved' || $status == 'finalized')
                                                                            {{ $item->judulTA->judul_approved }}
                                                                        @elseif($status == 'rejected')
                                                                            {{ $item->judulTA->judul1 }}
                                                                            <small class="text-danger d-block">
                                                                                Alasan:
                                                                                {{ Str::limit($item->judulTA->alasan_penolakan, 50) }}
                                                                            </small>
                                                                        @else
                                                                            {{ $item->judulTA->judul1 }}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                                                    <td>
                                                                        <a href="{{ route('dosen.bimbingan.show', $item->judul_ta_id) }}"
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

        @include('template.footer')
    </div>
</div>

@push('scripts')
    @include('template.script')
@endpush
