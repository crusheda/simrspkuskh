<header class="app-topbar">
    <div class="topbar-left">
        <button
            class="icon-button sidebar-toggle"
            id="sidebarToggle"
            aria-label="Buka navigasi"
        >
            <i class="bi bi-list"></i>
        </button>

        <div>
            <span class="breadcrumb-kicker">
                SISTEM INFORMASI RUMAH SAKIT
            </span>

            <div class="breadcrumb-title" id="pageTitleTopbar">
                @yield('page-title', 'Rekam Medis Elektronik')
            </div>
        </div>
    </div>

    <div class="topbar-actions">
        <span class="topbar-date d-none d-md-inline-flex">
            <i class="bi bi-calendar3"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </span>

        <div class="dropdown notification-dropdown"
            data-endpoint="{{ route('v3.notifikasi.klaim') }}"
            data-all-url="{{ route('v3.setting.notifikasi') }}"
        >
            <button
                type="button"
                class="icon-button notification-trigger"
                id="notificationDropdown"
                data-bs-toggle="dropdown"
                data-bs-auto-close="outside"
                aria-expanded="false"
                aria-label="Buka notifikasi"
            >
                <i class="bi bi-bell"></i>

                <span
                    id="notificationBadge"
                    class="notification-dot"
                    style="display: none;"
                ></span>
            </button>

            <div
                class="dropdown-menu dropdown-menu-end notification-menu"
                aria-labelledby="notificationDropdown"
            >
                <div class="notification-menu-header">
                    <div class="notification-heading-icon">
                        <i class="bi bi-bell"></i>
                    </div>

                    <div class="notification-heading-copy">
                        <h6>Notifikasi</h6>
                        <p>Informasi terbaru terkait klaim dan kunjungan</p>
                    </div>

                    <span
                        id="notificationHeaderBadge"
                        class="notification-count"
                        style="display: none;"
                    >0</span>
                </div>

                <div class="notification-menu-caption">
                    <span class="notification-live-indicator"></span>
                    Pembaruan otomatis setiap 30 detik
                </div>

                <ul
                    id="notificationList"
                    class="notification-list"
                    aria-live="polite"
                >
                    <li class="notification-state">
                        <span class="spinner-border spinner-border-sm"></span>
                        <span>Memuat notifikasi...</span>
                    </li>
                </ul>

                <div class="notification-menu-footer">
                    <a
                        href="{{ route('v3.setting.notifikasi') }}"
                        wire:navigate
                        class="notification-all-link"
                    >
                        <span>Buka Semua Notifikasi</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <button
            class="icon-button"
            id="themeToggleTop"
            aria-label="Ganti tema"
        >
            <i class="bi bi-moon-stars"></i>
        </button>

        <div class="dropdown">
            <button
                class="user-menu dropdown-toggle"
                data-bs-toggle="dropdown"
            >
                <span class="user-avatar">
                    {{ mb_substr(Auth::user()->NAMA ?? (Auth::user()->name ?? 'U'), 0, 1) }}
                </span>

                <span class="user-menu-copy d-none d-sm-flex">
                    <strong>
                        {{ Auth::user()->NAMA ?? (Auth::user()->name ?? 'Pengguna') }}
                    </strong>

                    <small>
                        {{ Auth::user()->NIP ?? 'Pengguna SIRMED' }}
                    </small>
                </span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                <li class="dropdown-header">Akun pengguna</li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
