<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">

            <div class="sidenav-menu-heading">Menu</div>

            {{-- Tampil untuk SEMUA role --}}
            <a class="nav-link" href="#">
                <div class="nav-link-icon"><i data-feather="layout"></i></div>
                Dashboards
            </a>

            {{-- Hanya tampil untuk role 'mahasiswa' --}}
            @if (Auth::user()->role == 'mahasiswa')
            <a class="nav-link" href="{{ route('mahasiswa.judul-ta.create') }}">
                <div class="nav-link-icon"><i data-feather="file-plus"></i></div>
                Ajukan Judul
            </a>
            <a class="nav-link" href="{{ route('mahasiswa.thesis.index') }}">
                <div class="nav-link-icon"><i class="fas fa-book"></i></div>
                Arsip Tesis
            </a>
            <a class="nav-link {{ Request::is('mahasiswa/plagiarism-check*') ? 'active' : '' }}"
                href="{{ route('mahasiswa.plagiarism.check') }}">
                <div class="nav-link-icon"><i class="fas fa-shield-alt"></i></div> {{-- Atau icon yang sesuai --}}
                Cek Plagiarisme
            </a>
            @endif


        </div>
    </div>
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title">{{ Auth::user()->name }}</div>
            <div class="sidenav-footer-subtitle" style="font-size: 0.8rem; text-transform: capitalize;">
                ({{ Auth::user()->role }})</div>
        </div>
    </div>
</nav>