<div class="sidebar-backdrop" data-sidebar-close></div>

<aside class="app-sidebar" id="appSidebar">
    <a class="brand" href="{{ route('v3.dashboard') }}" wire:navigate>
        <span class="brand-mark">
            <i class="bi bi-heart-pulse-fill"></i>
        </span>

        <span class="brand-copy">
            <strong>SIRMED</strong>
            <small>RS PKU Muhammadiyah</small>
        </span>

        <span class="brand-version">v3</span>
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
            wire:current="active"
            class="app-nav-link"
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

        <div class="sidebar-footer">
            © {{ date('Y') }} RS PKU Muhammadiyah Sukoharjo
        </div>
    </div>
</aside>
