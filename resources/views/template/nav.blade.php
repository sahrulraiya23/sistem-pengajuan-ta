<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white"
    id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i
            data-feather="menu"></i></button>
    <!-- Navbar Brand-->
    <!-- * * Tip * * You can use text or an image for your navbar brand.-->
    <!-- * * * * * * When using an image, we recommend the SVG format.-->
    <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
    <a class="navbar-brand d-flex align-items-center pe-3 ps-4 ps-lg-2" href="{{ route('home') }}">
        <img src="{{ asset('assets/img/logo-uho.png') }}" alt="Logo UHO" style="height: 35px;" class="me-3">
        <span style="font-size: 12px;">Teknik Informatika</span>
    </a>
    <!-- Navbar Search Input-->
    <!-- * * Note: * * Visible only on and above the lg breakpoint-->
    <form class="form-inline me-auto d-none d-lg-block me-3">
        <div class="input-group input-group-joined input-group-solid">
            <input class="form-control pe-0" type="search" placeholder="Search" aria-label="Search" />
            <div class="input-group-text"><i data-feather="search"></i></div>
        </div>
    </form>
    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">
        <!-- Documentation Dropdown-->
        <li class="nav-item dropdown no-caret d-none d-md-block me-3">
            <a class="nav-link dropdown-toggle" id="navbarDropdownDocs" href="javascript:void(0);" role="button"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="fw-500">Documentation</div>
                <i class="fas fa-chevron-right dropdown-arrow"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-0 me-sm-n15 me-lg-0 o-hidden animated--fade-in-up"
                aria-labelledby="navbarDropdownDocs">
                <a class="dropdown-item py-3" href="https://docs.startbootstrap.com/sb-admin-pro" target="_blank">
                    <div class="icon-stack bg-primary-soft text-primary me-4"><i data-feather="book"></i></div>
                    <div>
                        <div class="small text-gray-500">Documentation</div>
                        Usage instructions and reference
                    </div>
                </a>
                <div class="dropdown-divider m-0"></div>
                <a class="dropdown-item py-3" href="https://docs.startbootstrap.com/sb-admin-pro/components"
                    target="_blank">
                    <div class="icon-stack bg-primary-soft text-primary me-4"><i data-feather="code"></i></div>
                    <div>
                        <div class="small text-gray-500">Components</div>
                        Code snippets and reference
                    </div>
                </a>
                <div class="dropdown-divider m-0"></div>
                <a class="dropdown-item py-3" href="https://docs.startbootstrap.com/sb-admin-pro/changelog"
                    target="_blank">
                    <div class="icon-stack bg-primary-soft text-primary me-4"><i data-feather="file-text"></i></div>
                    <div>
                        <div class="small text-gray-500">Changelog</div>
                        Updates and changes
                    </div>
                </a>
            </div>
        </li>
        <!-- Navbar Search Dropdown-->
        <!-- * * Note: * * Visible only below the lg breakpoint-->
        <li class="nav-item dropdown no-caret me-3 d-lg-none">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="searchDropdown" href="#"
                role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                    data-feather="search"></i></a>
            <!-- Dropdown - Search-->
            <div class="dropdown-menu dropdown-menu-end p-3 shadow animated--fade-in-up"
                aria-labelledby="searchDropdown">
                <form class="form-inline me-auto w-100">
                    <div class="input-group input-group-joined input-group-solid">
                        <input class="form-control pe-0" type="text" placeholder="Search for..." aria-label="Search"
                            aria-describedby="basic-addon2" />
                        <div class="input-group-text"><i data-feather="search"></i></div>
                    </div>
                </form>
            </div>
        </li>
        {{-- ... kode lainnya di dalam <ul class="navbar-nav align-items-center ms-auto"> ... --}}

        @if (auth()->check() && auth()->user()->role == 'kajur')
            <li class="nav-item dropdown no-caret dropdown-notifications me-2">
                <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts"
                    href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false" title="Notifikasi">
                    <i data-feather="bell"></i>
                    {{-- Tampilkan badge hanya jika ada notifikasi belum dibaca --}}
                    @if ($unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadNotifications->count() }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up"
                    aria-labelledby="navbarDropdownAlerts">
                    <h6 class="dropdown-header dropdown-notifications-header">
                        <i class="me-2" data-feather="bell"></i>
                        Pemberitahuan
                    </h6>

                    {{-- Loop untuk setiap notifikasi --}}
                    @forelse($unreadNotifications as $notification)
                        <a class="dropdown-item dropdown-notifications-item"
                            href="{{ $notification->data['url'] ?? '#' }}">
                            <div class="dropdown-notifications-item-content">
                                <div class="dropdown-notifications-item-content-text">
                                    {{ $notification->data['message'] }}</div>
                                <div class="dropdown-notifications-item-content-details">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                            </div>
                        </a>
                    @empty
                        <a class="dropdown-item dropdown-notifications-item" href="#!">
                            <div class="dropdown-notifications-item-content">
                                <div class="dropdown-notifications-item-content-text">Tidak ada notifikasi baru.</div>
                            </div>
                        </a>
                    @endforelse

                    <a class="dropdown-item dropdown-notifications-footer"
                        href="{{ route('kajur.judul-ta.index') }}">Lihat semua pengajuan</a>
                </div>
            </li>
        @endif
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            {{-- ... kode dropdown user yang sudah ada ... --}}
        </li>

        {{-- ... --}}
        <!-- Messages Dropdown-->
        <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownMessages"
                href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false"><i data-feather="mail"></i></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up"
                aria-labelledby="navbarDropdownMessages">
                <h6 class="dropdown-header dropdown-notifications-header">
                    <i class="me-2" data-feather="mail"></i>
                    Message Center
                </h6>
                <!-- Example Message 1  -->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <img class="dropdown-notifications-item-img"
                        src="{{ asset('assets/img/illustrations/profiles/profile-2.png') }}" />
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet,
                            consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                            aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                            aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                            velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                        <div class="dropdown-notifications-item-content-details">Thomas Wilcox · 58m</div>
                    </div>
                </a>
                <!-- Example Message 2-->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <img class="dropdown-notifications-item-img"
                        src="{{ asset('assets/img/illustrations/profiles/profile-3.png') }}" />
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet,
                            consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                            aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                            aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                            velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                        <div class="dropdown-notifications-item-content-details">Emily Fowler · 2d</div>
                    </div>
                </a>
                <!-- Example Message 3-->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <img class="dropdown-notifications-item-img"
                        src="{{ asset('assets/img/illustrations/profiles/profile-4.png') }}" />
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet,
                            consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                            aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                            aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                            velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                        <div class="dropdown-notifications-item-content-details">Marshall Rosencrantz · 3d</div>
                    </div>
                </a>
                <!-- Example Message 4-->
                <a class="dropdown-item dropdown-notifications-item" href="#!">
                    <img class="dropdown-notifications-item-img"
                        src="{{ asset('assets/img/illustrations/profiles/profile-5.png') }}" />
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet,
                            consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                            aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                            aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                            velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                        <div class="dropdown-notifications-item-content-details">Colby Newton · 3d</div>
                    </div>
                </a>
                <!-- Footer Link-->
                <a class="dropdown-item dropdown-notifications-footer" href="#!">Read All Messages</a>
            </div>
        </li>
        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage"
                href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img class="img-fluid" src="{{ asset('assets/img/illustrations/profiles/profile-1.png') }}" />
            </a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up"
                aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img"
                        src="{{ asset('assets/img/illustrations/profiles/profile-1.png') }}" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name">{{ Auth::user()->name }}</div>
                        <div class="dropdown-user-details-email">{{ Auth::user()->email }}</div>

                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <div class="dropdown-item-icon"><i data-feather="user"></i></div>
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                        Logout
                    </button>
                </form>

            </div>
        </li>

    </ul>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js" crossorigin="anonymous"></script>
    <script>
        feather.replace()
    </script>

</nav>
