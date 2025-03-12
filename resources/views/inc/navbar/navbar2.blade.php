<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="card pc-user-card">
            <div class="card-body">
                <div class="d-flex align-items-center btn btn-link-secondary" onclick="window.location='{{ route('pelayanan.pasien') }}'">
                    <div class="flex-shrink-0 me-3">
                        <i class="ph-duotone ph-caret-double-left"></i>
                        {{-- <div class="btn btn-icon btn-link-secondary avtar">
                        </div> --}}
                    </div>
                    <div class="flex-grow-1 align-items-left">
                        <h6 class="mb-0">Kembali ke Halaman</h6>
                        <small>My Pasien</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label data-i18n="Navigation">Publik</label>
                    <i class="ph-duotone ph-gauge"></i>
                </li>
                <li class="pc-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-gauge"></i>
                        </span>
                        <span class="pc-mtext" data-i18n="Dashboard">Dashboard</span>
                    </a>
                </li>
                <li class="pc-item pc-caption">
                    <label>ERM</label>
                    <i class="ph-duotone ph-chart-pie"></i>
                </li>
                <li class="pc-item">
                    <a href="{{ route('pelayanan.pasien') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-projector-screen-chart"></i>
                        </span>
                        <span class="pc-mtext">Resume Medis</span>
                    </a>
                </li>
                {{-- <li class="pc-item pc-caption">
                    <label>Billing</label>
                    <i class="ph-duotone ph-chart-pie"></i>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="javascript:void(0);" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-gauge"></i>
                        </span>
                        <span class="pc-mtext">Smart Klaim</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        <span class="pc-badge">2</span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="javascript:void(0);">a</a></li>
                        <li class="pc-item"><a class="pc-link" href="javascript:void(0);">b</a></li>
                        <li class="pc-item"><a class="pc-link" href="javascript:void(0);">c</a></li>
                        <li class="pc-item"><a class="pc-link" href="javascript:void(0);">d</a></li>
                        <li class="pc-item"><a class="pc-link" href="javascript:void(0);">e</a></li>
                    </ul>
                </li> --}}
            </ul>
        </div>
        <div class="card pc-user-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('/images/user.png') }}" alt="user-image"
                            class="user-avtar wid-45 rounded-circle" />
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="dropdown">
                            <a href="#" class="arrow-none dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" data-bs-offset="0,20">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-2">
                                        <h6 class="mb-0">Jonh Smith</h6>
                                        <small>Administrator</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="btn btn-icon btn-link-secondary avtar">
                                            <i class="ph-duotone ph-windows-logo"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu">
                                <ul>
                                    <li>
                                        <a class="pc-user-links">
                                            <i class="ph-duotone ph-user"></i>
                                            <span>My Account</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="pc-user-links">
                                            <i class="ph-duotone ph-gear"></i>
                                            <span>Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="pc-user-links">
                                            <i class="ph-duotone ph-lock-key"></i>
                                            <span>Lock Screen</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="pc-user-links">
                                            <i class="ph-duotone ph-power"></i>
                                            <span>Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- [ Sidebar Menu ] end -->
