<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <!-- Sidenav Menu Heading (Account)-->
            <!-- * * Note: * * Visible only on and above the sm breakpoint-->
            <div class="sidenav-menu-heading d-sm-none">Account</div>
            <!-- Sidenav Link (Alerts)-->
            <!-- * * Note: * * Visible only on and above the sm breakpoint-->

            <!-- Sidenav Menu Heading (Core)-->
            <div class="sidenav-menu-heading">Core</div>
            <!-- Sidenav Accordion (Dashboard)-->
            <a class="nav-link" href="{{ route('mahasiswa.judul-ta.index') }}">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Dashboards
            </a>

            <a class="nav-link" href="{{ route('mahasiswa.judul-ta.create') }}">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Ajukan Judul
            </a>

            <a class="nav-link" href="{{ route('mahasiswa.judul-ta.index') }}">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                TA Sebelumnya
            </a>



            <!-- Sidenav Link (Tables)-->
            <a class="nav-link" href="tables.html">
                <div class="nav-link-icon"><i data-feather="filter"></i></div>
                Tables
            </a>
        </div>
    </div>
    <!-- Sidenav Footer-->
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title">{{ Auth::user()->name }}</div>
        </div>
    </div>
</nav>
