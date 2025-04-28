<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            {{-- <div id="logo_simrs"></div> --}}
            <a href="javascript:void(0);" class="b-brand text-primary">
                <img src="{{ asset('images/logo/logoname.png') }}" alt="logo image" class="" style="height: 24px" />
                <span class="badge bg-brand-color-3 rounded-pill ms-1 theme-version">v1.0</span>
            </a>
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
                    <label>Pelayanan</label>
                    <i class="ph-duotone ph-chart-pie"></i>
                </li>
                <li class="pc-item">
                    <a href="{{ route('pelayanan.pasien') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-users-three"></i>
                        </span>
                        <span class="pc-mtext">Kunjungan Pasien</span>
                    </a>
                </li>

                {{-- <li class="pc-item pc-hasmenu">
                    <a href="javascript:void(0);" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-gauge"></i>
                        </span>
                        <span class="pc-mtext">Pendaftaran</span>
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
                <li class="pc-item pc-caption">
                    <label>Digital</label>
                    <i class="ph-duotone ph-chart-pie"></i>
                </li>
                {{-- <li class="pc-item">
                    <a href="{{ route('klaim.pasien') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-projector-screen-chart"></i>
                        </span>
                        <span class="pc-mtext">Smart Klaim</span>
                    </a>
                </li> --}}
                <li class="pc-item {{ request()->routeIs('monitoring.index') ? 'active' : '' }}">
                    <a href="{{ route('monitoring.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-projector-screen-chart"></i>
                        </span>
                        <span class="pc-mtext">Monitoring</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('klaim.index') || request()->routeIs('klaim.show') ? 'active' : '' }}">
                    <a href="{{ route('klaim.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-file-lock"></i>
                        </span>
                        <span class="pc-mtext">Smart Klaim</span>
                    </a>
                </li>
                {{-- <li class="pc-item pc-hasmenu">
                    <a href="javascript:void(0);" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-file-lock"></i>
                        </span>
                        <span class="pc-mtext">Smart Klaim</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        <span class="pc-badge">2</span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="javascript:void(0);">Rawat Jalan</a></li>
                        <li class="pc-item"><a class="pc-link" href="javascript:void(0);">Rawat Inap</a></li>
                        <li class="pc-item"><a class="pc-link" href="javascript:void(0);">Rawat Darurat</a></li>
                    </ul>
                </li> --}}
            </ul>
            <div class="card nav-action-card bg-brand-color-4">
                <div class="card-body" style="background-image: {{ url('images/layout/nav-card-bg.svg') }}">
                    <h5 class="text-dark">Tambahan Fitur?</h5>
                    <p class="text-dark text-opacity-75">Silakan melakukan permintaan fitur melalui tombol di bawah ini.</p>
                    <a href="javascript:void(0);" class="btn btn-primary" target="_blank">Form Pengajuan</a>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
    // if ($('body').attr('class') == 'layout-nested') {
    //     $('#logo_simrs').empty().append(`<a href="javascript:void(0);" class="b-brand text-primary"><img src="{{ asset('images/logo/logoname_w.png') }}" alt="logo image" class="" style="height: 24px" />
    //                             <span class="badge bg-brand-color-3 rounded-pill ms-1 theme-version">v1.0</span></a>`);
    // } else {
    //     $('#logo_simrs').empty().append(`<a href="javascript:void(0);" class="b-brand text-primary"><img src="{{ asset('images/logo/logoname.png') }}" alt="logo image" class="" style="height: 24px" />
    //                             <span class="badge bg-brand-color-3 rounded-pill ms-1 theme-version">v1.0</span></a>`);
    // }
</script>
<!-- [ Sidebar Menu ] end -->
