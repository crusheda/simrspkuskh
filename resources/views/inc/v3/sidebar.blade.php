<div class="sidebar-backdrop" data-sidebar-close></div>

<aside class="app-sidebar" id="appSidebar">
    <a class="brand" href="{{ route('v3.dashboard') }}" wire:navigate> <span class="brand-mark"> <img src="{{ asset('images/logo/logo.png') }}" alt="SIRMED Logo"> </span>

        <span class="brand-copy">
            <strong>SIR<span class="brand-accent">MED</span></strong>
            <small class="brand-tagline">Hospital Information System</small>
        </span>

        <span class="brand-version">V3</span>
    </a>

    <div class="sidebar-profile">
        <div class="profile-avatar">
            {{ mb_substr(Auth::user()->NAMA ?? (Auth::user()->name ?? 'U'), 0, 1) }}
        </div>

        <div class="profile-copy">
            <strong>
                {{ Auth::user()->NAMA ?? (Auth::user()->name ?? 'Pengguna') }}
            </strong>

            <small>
                {{ Auth::user()->NIP ?? 'Akun SIRMED' }}
            </small>
        </div>

        <span class="status-dot"></span>
    </div>

    <div class="nav-caption">MENU UTAMA</div>

    <nav class="app-nav">
        <a
            href="{{ route('v3.dashboard') }}"
            wire:navigate
            wire:current="active"
            class="app-nav-link"
        >
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>

        <a
            href="{{ route('v3.monitoring') }}"
            wire:navigate
            wire:current="active"
            class="app-nav-link"
        >
            <i class="bi bi-activity"></i>
            <span>Monitoring</span>
        </a>

        <a
            href="{{ route('v3.emr') }}"
            wire:navigate
            class="app-nav-link {{ request()->routeIs('v3.emr', 'v3.emr.detail') ? 'active' : '' }}"
        >
            <i class="bi bi-clipboard2-pulse"></i>
            <span>Medical Record</span>
        </a>

        <a
            href="{{ route('v3.smartclaim') }}"
            wire:navigate
            wire:current="active"
            class="app-nav-link"
        >
            <i class="bi bi-file-earmark-medical"></i>
            <span>Smart Claim</span>
        </a>
    </nav>

    <div class="nav-caption mt-4">PREFERENSI</div>

    <nav class="app-nav">
        <button
            type="button"
            class="app-nav-link app-nav-button"
            id="themeToggle"
        >
            <i class="bi bi-moon-stars"></i>
            <span>Mode gelap</span>
        </button>

        <button
            type="button"
            class="app-nav-link app-nav-button"
            data-bs-toggle="modal"
            data-bs-target="#aboutSirmed"
        >
            <i class="bi bi-info-circle"></i>
            <span>Tentang SIRMED</span>
        </button>
    </nav>

    <div class="sidebar-bottom">
        <div class="help-card">
            <div class="help-icon">
                <i class="bi bi-headset"></i>
            </div>

            <strong>Butuh bantuan?</strong>

            <p>Hubungi tim IT jika mengalami kendala aplikasi.</p>

            <a href="mailto:it@rspkusukoharjo.com">
                Hubungi IT
                <i class="bi bi-arrow-up-right"></i>
            </a>
        </div>

        {{-- <div class="sidebar-footer">
            © {{ date('Y') }} RS PKU Muhammadiyah Sukoharjo
        </div> --}}
    </div>
</aside>

<div class="modal fade" id="aboutSirmed" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-dialog-scrollable">
        <div class="modal-content modal-accent">
            <div class="modal-header border-0">
                <span class="modal-icon modal-icon-primary">
                    <i class="bi bi-heart-pulse-fill"></i>
                </span>

                <h5 class="modal-title">Tentang SIRMED</h5>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body pt-0">
                <p class="fw-semibold mb-2">Sistem Informasi Rekam Medis</p>
                <p class="modal-description mb-0">
                    RS PKU Muhammadiyah Sukoharjo · Laravel + Livewire.
                </p>
            </div>
        </div>
    </div>
</div>
